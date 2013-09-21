<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
include_once($projectroot."functions/categories.php");
include_once($projectroot."functions/images.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."language/languages.php");



//
// todo: create search engine
//
function searchform($page,$all=true,$search="")
{
  $result="";
  $pages=array();
  if(getpagetype($page)==="articlemenu")
  {
    $parent=$page;
    array_push($pages,$parent);
    while(!isrootpage($parent))
    {
      $parent=getparent($parent);
      array_push($pages,$parent);
    }
  }
  elseif(getpagetype($page)==="news")
  {
    $pages=getsubpagesforpagetype($page,"news");
  }

  $result.='<form name="searchform" method="get">';
  $result.='<input type="hidden" name="page" value="'.$page.'" />';
  $result.='<tr>';
  $result.='<td>';
  $result.='<p class="highlight">Search:</p>';

  $result.='<table border="0" cellspacing="0" cellpadding="10">';
  $result.='  <tr>';
  $result.='    <td  valign="top" align="left">';
  $result.='    Search';
  $result.='<select name="searchpage" size="1">';

  for($i=count($pages)-1;$i>=0;$i--)
  {
    $result.='<option value="'.$pages[$i].'"';
    if($pages[$i]==$page) $result.=' selected';
    $result.='>';
    $result.=input2html(getnavtitle($pages[$i]));
    $result.='</option>';
  }
  $result.='</select>';

  $result.='    for:';
  $result.='  	</td>';
  $result.='   <td  valign="top" align="left">';
  $result.='    <input type="text" name="search" value="'.input2html($search).'" size="30" />';
  $result.='    <br><input type="checkbox" name="all" value="1"';
  if($all) $result.=' checked';
  $result.='/>Match all words';
  $result.='  	</td>';
  $result.='    <td valign="top">';
  $result.='      <input type="submit" name="submit" value="Search" class="mainoption" />';
  $result.='    </td>';
  $result.='  </tr>';

  $result.='</table>';
  $result.='<hr>';
  $result.='</td>';
  $result.='</tr>';
  $result.='</form>';

 return $result;
}




//
// get adjusted offset for page jumped to
//
function getoffsetforjumppage($noofitems,$itemsperpage,$offset)
{
  global $_GET;

  if(isset($_GET['jumppage']) && $_GET['jumppage']>0
     && $noofitems && $_GET['jumppage']<=ceil($noofitems/$itemsperpage))
  {
    $offset=($_GET['jumppage']-1)*$itemsperpage;
    unset($_GET['jumppage']);
  }
  return $offset;
}




//
// makes copyright information.
//
function makecopyright($permissions)
{
  	$textcopyright="";
  	$imagecopyright="";
  	$bypermission="";
  	if(strlen($permissions['copyright'])>0)
  	{
    	$textcopyright= sprintf(getlang("footer_textcopyright"),title2html($permissions['copyright']));
  	}
  	if(strlen($permissions['image_copyright'])>0)
  	{
  		$imagecopyright= sprintf(getlang("footer_imagecopyright"),title2html($permissions['image_copyright']));
  	}
  	if(($permissions['permission'])==PERMISSION_GRANTED)
  	{
    	$bypermission=getlang("footer_bypermission");
  	}
  	return sprintf(getlang("footer_copyright"),$textcopyright, $imagecopyright, $bypermission);
}



//
// todo handle short month
//
function formatdatetime($date,$short=false)
{
	$result ="";
  	if(!$short)
  	{
    	$format=getproperty("Date Time Format");
	  	$result= @date($format,strtotime($date));
	  	$result= str_replace("January",getlangarray("date_month",1),$result);
	  	$result= str_replace("February",getlangarray("date_month",2),$result);
	  	$result= str_replace("March",getlangarray("date_month",3),$result);
	  	$result= str_replace("April",getlangarray("date_month",4),$result);
	  	$result= str_replace("May",getlangarray("date_month",5),$result);
	  	$result= str_replace("June",getlangarray("date_month",6),$result);
	  	$result= str_replace("July",getlangarray("date_month",7),$result);
	  	$result= str_replace("August",getlangarray("date_month",8),$result);
	  	$result= str_replace("September",getlangarray("date_month",9),$result);
	  	$result= str_replace("October",getlangarray("date_month",10),$result);
	  	$result= str_replace("November",getlangarray("date_month",11),$result);
	  	$result= str_replace("December",getlangarray("date_month",12),$result);
  	}
  	else
  	{
    	$format=SHORTDATETIMEFORMAT;
    	$result= @date($format,strtotime($date));
  	}
  	return str_replace(" ","&nbsp;",$result);;
}

//
// todo handle short month
//
function formatdate($date)
{
	$result="";
  	$format=getproperty("Date Format");
  	$result=  @date($format,strtotime($date));
  	$result= str_replace("January",getlangarray("date_month",1),$result);
  	$result= str_replace("February",getlangarray("date_month",2),$result);
  	$result= str_replace("March",getlangarray("date_month",3),$result);
  	$result= str_replace("April",getlangarray("date_month",4),$result);
  	$result= str_replace("May",getlangarray("date_month",5),$result);
  	$result= str_replace("June",getlangarray("date_month",6),$result);
  	$result= str_replace("July",getlangarray("date_month",7),$result);
  	$result= str_replace("August",getlangarray("date_month",8),$result);
  	$result= str_replace("September",getlangarray("date_month",9),$result);
  	$result= str_replace("October",getlangarray("date_month",10),$result);
  	$result= str_replace("November",getlangarray("date_month",11),$result);
  	$result= str_replace("December",getlangarray("date_month",12),$result);
  	
  	return str_replace(" ","&nbsp;",$result);
}
?>
