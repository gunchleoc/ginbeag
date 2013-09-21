<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/functions/linkcheckerfunctions.php");
include_once($projectroot."includes/functions.php");


$sid=$_GET['sid'];
checksession($sid);

$timeout = 30;
$hosts=array();
$projectrootlinkpath=getprojectrootlinkpath();

$header = new HTMLHeader("Check for internal dead links","Webpage Building");
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
      print('<span class="gen"> .</span>');
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
      print('<span class="gen"> .</span>');
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
      print('<span class="gen"> .</span>');
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
      print('<span class="gen"> .</span>');
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
      print('<span class="gen"> .</span>');
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
        print('<span class="gen"> .</span>');
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
  if(!isexternallink($url))
  {
    $pattern="/(^\?page=|^\/\?page=|";
    $pattern.="^".str_replace("/","\/",getproperty("Local Path"))."\/(.*?)page=";
    $pattern.="^\/".str_replace("/","\/",getproperty("Local Path"))."\/(.*?)page=";
    $pattern.="^".str_replace("/","\/",$projectrootlinkpath)."(.*?)page=";
    $pattern.=")(\d+)/i";
//    print($pattern);
    preg_match_all($pattern,$url,$matches);

    if($matches[5][0])
    {
      if(!getpagetype($matches[5][0]))
      {
        print('<br /><br /><a href="'.$projectrootlinkpath.'?page='.$matches[5][0].'" target="_blank" class="gen">'.$title.'</a> ');
        print('<span class="highlight">This page does not exist!</span>');
        print(' <span class="gen">Linked in <a href="../admin.php?sid='.$sid.'&page='.$page.$params.'" target="blank">page '.$page.'</a></span>');
      }
      else
      {
        if(ispublished($page) && !ispublished($matches[5][0]))
        {
          print('<br /><br /><a href="'.$projectrootlinkpath.'?page='.$matches[5][0].'" target="_blank" class="gen">'.$title.'</a> ');
          print('<span class="highlight">Published page points to unpublished page!</span>');
          print(' <span class="gen">Linked in <a href="../admin.php?sid='.$sid.'&page='.$page.$params.'" target="blank">page '.$page.'</a></span>');
        }
        if(ispagerestricted($page) && !ispagerestricted($matches[5][0]))
        {
          print('<br /><br /><a href="'.$projectrootlinkpath.'?page='.$matches[5][0].'" target="_blank" class="gen">'.$title.'</a> ');
          print('<span class="highlight">Restricted page points to unrestricted page!</span>');
          print(' <span class="gen">Linked in <a href="../admin.php?sid='.$sid.'&page='.$page.$params.'" target="blank">page '.$page.'</a></span>');
        }
      }
    }
  }
}


//
//
//
function extractlinksfromtext($text)
{
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

?>
