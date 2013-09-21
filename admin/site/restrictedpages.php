<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."admin/functions/pagesmod.php");

$sid=$_GET['sid'];
checksession($sid);

$header = new HTMLHeader("Pages with Restricted Access","Webpage Building");
print($header->toHTML());

showrestrictedpages();

//
//
//
function showrestrictedpages()
{
  global $sid;

  $pages=getrestrictedpages();
  if(count($pages))
  {
//  print_r($stats);
?>
<table><tr><td class="bodyline">
<table cellpadding="5"><tr>
<th class="thHead">Page</th>
<th class="thHead">Pagetype</th>
<th class="thHead">Users</th>
</tr>
<?php
    for($i=0;$i<count($pages);$i++)
    {
      $url=getprojectrootlinkpath()."admin/admin.php?page=".$pages[$i]."&sid=".$sid;
      $accessusers=getallpublicuserswithaccessforpage($pages[$i]);
?>
<tr>
  <td class="table" align="left"><?php print($pages[$i].': <a href="'.$url.'" target="_blank" class="gen">'.text2html(getpagetitle($pages[$i])).'</a>');?>
  </td>
  <td class="gen" align="left"><?php print(getpagetype($pages[$i]));?>
  </td>
  <td class="table" align="left">
<?php
    if(count($accessusers)==0)
    {
      print('<p align="center" class="gen">&mdash;</p>');
    }
    else
    {
      for($j=0;$j<count($accessusers);$j++)
      {
        if($j>0) print('<span class="gen"> - </span>');
        print('<a href="userpermissions.php?userid='.$accessusers[$j].'&type=public&sid='.$sid.'" class="gen">'.getpublicusername($accessusers[$j]).'</a>');
      }
    }
?>
  </td>
</tr>
<?php
    }
?>
</table>
<?php
  }
  else
  {
    print('<p class="pagetitle">No pages have been restricted</p>');
  }
?>
</td></tr>
</table>
<?php
$footer = new HTMLFooter();
print($footer->toHTML());
}

?>
