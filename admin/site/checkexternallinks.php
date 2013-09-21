<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/functions/linkcheckerfunctions.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/templates/elements.php");


$sid=$_GET['sid'];
checksession($sid);

$timeout = 30;
$hosts=array();
$projectrootlinkpath=getprojectrootlinkpath();

$header = new HTMLHeader("Check for external dead links","Webpage Building");
print($header->toHTML());


print('<p class="gen">This may take a while, so please be patient. As long as the status bar of your browser is moving, the script has not crashed.</p>');

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['selectpagetype']))
{
  // articles
  if($_POST['pagetype']==='article')
  {
    print('<p class="pagetitle">Checking Article pages</p>');
    $articlestocheck=getallarticlepagesandsourcelinks();

    while($article=current($articlestocheck))
    {
      print(' .');
      if($article['sourcelink'])
      {
        checklink($article['sourcelink'],$article['source'],$article['page_id']);
      }
      $articletext=getalltextfieldsforarticle($article['page_id']);
      $links=extractlinksfromtext($articletext);
      while($link=current($links))
      {
        checklink($link['link'],$link['title'],$article['page_id']);
        next($links);
      }
      next($articlestocheck);
    }
    extlinksdonebutton();
  }
  // externals
  elseif($_POST['pagetype']==='external')
  {
    print('<p class="pagetitle">Checking External pages</p>');
    $linkstocheck=getallexternallinks();

    while($link=current($linkstocheck))
    {
      print(' .');
      checklink($link['link'],getpagetitle($link['page_id']),$link['page_id']);
      next($linkstocheck);
    }
    extlinksdonebutton();
  }
  // galleries
  if($_POST['pagetype']==='gallery')
  {
    print('<p class="pagetitle">Checking Gallery pages</p>');
    $galleriestocheck=getallgalleryintrotexts();

    while($gallery=current($galleriestocheck))
    {
      print(' .');
      $links=extractlinksfromtext($gallery['introtext']);
      while($link=current($links))
      {
        checklink($link['link'],$link['title'],$article['page_id']);
        next($links);
      }
      next($galleriestocheck);
    }
    extlinksdonebutton();
  }
  // linklists
  elseif($_POST['pagetype']==='linklist')
  {
    print('<p class="pagetitle">Checking Linklist pages</p>');
    $linkstocheck=getalllinklistlinks();

    while($link=current($linkstocheck))
    {
      print(' .');
      checklink($link['link'],$link['title'],$link['page_id']);
      next($linkstocheck);
    }
    extlinksdonebutton();
  }
  // menus
  if($_POST['pagetype']==='menu'
     || $_POST['pagetype']==='articlemenu'
     || $_POST['pagetype']==='linklistmenu')
  {
    print('<p class="pagetitle">Checking Articlemenu, Linklistmenu and Menu pages</p>');
    $menustocheck=getallmenupageswithintro();

    while($menu=current($menustocheck))
    {
      print(' .');
      $links=extractlinksfromtext($menu['introtext']);
      while($link=current($links))
      {
        checklink($link['link'],$link['title'],$menu['page_id']);
        next($links);
      }
      next($menustocheck);
    }
    extlinksdonebutton();
  }
  // news
  if($_POST['pagetype']==='news')
  {
    print('<p class="pagetitle">Checking News pages</p>');
    $pagestocheck=getallnewspages();
    for($i=0;$i<count($pagestocheck);$i++)
    {
      $sources=getnewsitemandsourcelinks($pagestocheck[$i]);
      while($source=current($sources))
      {
        print(' .');
        if($source['sourcelink'])
        {
          checklink($source['sourcelink'],$source['source'],$pagestocheck[$i],'&action=editcontents&item='.$source['newsitem_id']);
        }
        $newsitemtext=getnewsitemsectiontexts($source['newsitem_id']);
        $links=extractlinksfromtext($newsitemtext);
        while($link=current($links))
        {
          checklink($link['link'],$link['title'],$pagestocheck[$i],'&action=editcontents&item='.$source['newsitem_id']);

          next($links);
        }
        next($sources);
      }
    }
    extlinksdonebutton();
  }
}
else
{
  pagetypeform();
}
$footer = new HTMLFooter();
print($footer->toHTML());





function pagetypeform()
{
  global $sid;

  $pagetypes=getpagetypes();
  $keys=array_keys($pagetypes);
?>
<form name="pagetypeform" action="?sid=<?php
print($sid);
?>" method="post">
<table>
<tr>
<td><span class="gen">Select pagetype for checking:</span></td>
<td>
<select name='pagetype' size='1'>
<?php
  for($i=0;$i<count($keys);$i++)
  {
    $short=$keys[$i];
    $long=$pagetypes[$short];
    print('<option value="'.$short.'"');
    print('>'.$short.': '.$long.'</option>');
  }
?>
</select>
</td>
</tr>
<tr><td></td>
<td>
&nbsp;<br /><input type="submit" name="selectpagetype" value="Select" class="mainoption">
&nbsp;&nbsp;<input type="reset" value="Reset" class="liteoption">

</td>
</tr>
</table>

</span>
</form>

<?php
}


//
// adapted from http://www.zend.com/zend/spotlight/php-link-validation1.php
//
function checklink($url,$title,$page,$params="")
{
  global $projectrootlinkpath, $sid;

  //do not check internal links
  if(isexternallink($url))
//  if(!(str_startswith($url, $projectrootlinkpath)||str_startswith($url, '?')))
  {
    //Disable warnings in PHP scripts
    $e = error_reporting();
    error_reporting($e & (E_ALL-E_WARNING));
  
    $urlparams=parse_url($url);

    //format validation (parameter syntax not checked)
//    $error=formaterror($urlparams);
    if ($error)
    {
      print('<br /><br /><a href="'.$url.'" target="_blank" class="gen">'.$title.'</a> ');
      print('<span class="highlight">This is not a valid link - '.$error.'!</span>');
      print('<span class="gen"> in </span><a href="../admin.php?sid='.$sid.'&page='.$page.$params.'" target="blank" class="gen">page '.$page.'</a>');
    }
    //server validation
    else
    {
      $error=servererror($url,$urlparams);
      if ($error)
      {
        print('<br /><br /><a href="'.$url.'" target="_blank" class="gen">'.$title.'</a> ');
        print('<span class="highlight">Could not validate server  </span><span class="gen"> - '.$error.'!</span>');
        print('<span class="gen"> in </span><a href="../admin.php?sid='.$sid.'&page='.$page.$params.'" target="blank" class="gen">page '.$page.'</a>');
      }
      else
      {
        $error=documenterror($url,$urlparams);
        if($error)
        {
          print('<br /><br /><a href="'.$url.'" target="_blank" class="gen">'.$title.'</a> ');
          print('<span class="highlight">Could not retrieve document </span><span class="gen"> - '.$error.'</span>');
          print('<span class="gen"> in </span><a href="../admin.php?sid='.$sid.'&page='.$page.$params.'" target="blank" class="gen">page '.$page.'</a>');
        }
      }
    }
    if(!$error)
    {
/*      print('<br /><br /><a href="'.$url.'" target="_blank">'.$title.'</a> ');
      print('<span class="gen">OK</span>');*/
    }
    error_reporting($e);
  }
}

//
//
//
function formaterror($urlparams)
{
  $result='';

//  print_r($urlparams);
  print('<br />xxx :'.$urlparams['path'].':');

  if(!isset($urlparams['scheme']) || !preg_match('/http|https|ftp/i',$urlparams['scheme']))
  {
    $result='Missing/Wrong scheme (http|https|ftp)';
  }
  elseif(!isset($urlparams['host']) || !preg_match('/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?/i',$urlparams['host']))
  {
    $result='Missing/Wrong host syntax '.$urlparams['host'];
  }
  elseif(isset($urlparams['path']) && !preg_match('/^\/([A-Z0-9][A-Z0-9_-]*)(\/[A-Z0-9][A-Z0-9_-]*)*([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)?/i',$urlparams['path']))
  {
    $result='Wrong path syntax '.$urlparams['path'];
  }
  return $result;
}

//
//
//
function servererror($url, $urlparams)
{
  global $hosts;
  $result='';
  
  if(!isset($hosts[$url]))
  {
//    print('checking server');

    if (preg_match('/^\d+\.\d+\.\d+\.\d+/', $urlparams['host']))
    {
      $result='numeric IP address';
    }
    else
    {
      $host=gethostbyname($urlparams['host']);
      if($host===$urlparams['host'])
      {
        $result='host not found';
      }
    }
//    print('host: '.$host);
    $hosts[$url]=$host;
//    print_r ($hosts);
  }
  return $result;
}

//
//
//
function documenterror($url,$urlparams)
{
  global $hosts, $timeout;
  $result='';

  if (!isset($urlparams['port']))
  {
    switch ($urlparams['scheme'])
    {
      case 'http':
        $urlparams['port'] = 80;
        break;
      case 'https':
        $urlparams['port'] = 443;
        break;
      case 'ftp':
        $urlparams['port'] = 21;
        break;
    }
  }
//  $key = $hosts[$url].":".$urlparams['port'];
  $fp = fsockopen($hosts[$url], $urlparams['port'],
    &$errno, &$errstr, $timeout);
  if($fp)
  {
    $parameters=$urlparams['path'];
    if($urlparams['query'])
    {
      $parameters.='?'.$urlparams['query'];
    }
    if($urlparams['fragment'])
    {
      $parameters.='#'.$urlparams['fragment'];
    }
//    print('<p>'.$parameters.'</p>');
    $request=sprintf( "HEAD %s HTTP/1.0\n\n",$parameters);
    $request.='Accept-Encoding: gzip, zip\n\n';
    $request.='Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/x-shockwave-flash, application/zip, application/gzip, application/pdf, audio/x-wav, */*\n\n';
//    $request.='Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-powerpoint, application/vnd.ms-excel, application/msword, application/zip, */*\n\n';
    $request.='Referer: '.getproperty("Domain Name").'\n\n';

    $success= fwrite ($fp,$request);
    $done=false;
    for ($try = 1; !$done && $try <= 3; $try++)
    {
      $got = fgets ($fp);
//      $got = fgets ($fp, 256);
      if (($got != NULL) && (eregi ("HTTP/1.(.) (.*) (.*)", $got, $parts)))
      {
        switch($parts[2])
        {
          case 200:
            $result = "";
            break;
/*          case 300:
            $result = "Page has moved (Code 300)";
            break;*/
            
          case 300:
          case 301:
          case 302:
          case 303:
          case 304:
          case 305:
            $result = "Page has moved (Code {$parts[2]})";
            break;
          case 403:
            $result = "Access restricted (Code 403)";
            break;
          case 404:
            $result = "Page <i>".$parameters."</i> not found (Code 404)";
            break;
          case 408:
          case 500:
          case 503:
          case 504:
            $result = "Time-out or server problem (Code {$parts[2]})";
            break;
          default:
            $result = "Error retrieving page <i>".$parameters."</i> (Code {$parts[2]})";
        }
        $done=true;
      }
      else
      {
        $result = "Could not communicate with server";
      }
    }
  }
  else
  {
    $result= 'fsockopen() Error #'.$errno.': '.$errstr;
  }

  return $result;
}


//
//
//
function extractlinksfromtext($text)
{
//  print('<p>'.$text.'</p>');
  $result=array();
  $matches=array();

  preg_match_all("/\[url\](.*?)\[\/url\]/i",$text,$matches);
  $nooflinks=count($matches[1]);
  for($i=0;$i<$nooflinks;$i++)
  {
    $link=array();
    $link['link']=$matches[1][$i];
    $link['title']=$matches[1][$i];
    array_push($result,$link);
  }

  preg_match_all("/\[url=(.*?)\](.*?)\[\/url\]/i",$text,$matches);
  $nooflinks=count($matches[1]);
  for($i=0;$i<$nooflinks;$i++)
  {
    $link=array();
    $link['link']=$matches[1][$i];
    $link['title']=$matches[2][$i];
    array_push($result,$link);
  }
  
  preg_match_all("/\[link\](.*?)\[\/link\]/i",$text,$matches);
  $nooflinks=count($matches[1]);
  for($i=0;$i<$nooflinks;$i++)
  {
    $link=array();
    $link['link']=$matches[1][$i];
    $link['title']=$matches[1][$i];
    array_push($result,$link);
  }
  
//  print_r($matches[0]);
//  print_r($matches[1]);
//  print_r($result);

//  print('<hr>');

  return $result;
}

//
//
//
function isexternallink($url)
{
  $result=true;
  $pattern="/".str_replace("/","\/",getprojectrootlinkpath())."/";
  if(preg_match($pattern,$url))
  {
    $result=false;
  }
  elseif(str_startswith($url,"/"))
  {
    $result=false;
  }
  elseif(str_startswith($url,"?"))
  {
    $result=false;
  }
  return $result;
}

//
//
//
function extlinksdonebutton()
{
  global $sid;
?>
<form action="?sid=<?php
print($sid);
?>" method="post">
<input type="submit" name="done" value="Done" class="mainoption">
</form>
<?php
}

// http://www.drweb.de/ressourcen/error_codes.shtml
/*
100 Continue
101 Switching Protocols
200 OK
201 Created
202 Accepted
203 Non-Authoritative Information
204 No Content
205 Reset Content
206 Partial Content
300 Multiple Choices
301 Moved Permanently
302 Moved Temporarily
303 See Other
304 Not Modified
305 Use Proxy
400 Bad Request
401 Unauthorized
402 Payment Required
403 Forbidden
404 Not Found
405 Method Not Allowed
406 Not Acceptable
407 Proxy Authentication Required
408 Request Time-Out
409 Conflict
410 Gone
411 Length Required
412 Precondition Failed
413 Request Entity Too Large
414 Request-URL Too Large
415 Unsupported Media Type
500 Server Error
501 Not Implemented
502 Bad Gateway
503 Out of Resources
504 Gateway Time-Out
505 HTTP Version not supported
*/
?>
