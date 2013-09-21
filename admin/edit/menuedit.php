<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/pagesmod.php");
include_once($projectroot."admin/edit/edittext.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/templates/adminforms.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."admin/includes/templates/adminelements.php");
include_once($projectroot."admin/includes/templates/adminmenupage.php");

$sid=$_GET['sid'];
checksession($sid);

$page=$_GET['page'];

//print_r($_POST);
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions

$message="";
  
$pagelockmessage = getpagelock($page);
if(!$pagelockmessage)
{
  // update menu
  if(isset($_POST['editmenulevels']))
  {
    $sisters=$_POST['sisters'];
    if($sisters): $sistersinnavigator=1;
    else:$sistersinnavigator=0;
    endif;
    $message = 'Edited page levels';
    updatemenunavigation($page,$_POST['navlevels'],$_POST['pagelevels'],$sistersinnavigator);
    updateeditdata($page, $sid);
  }
  elseif(isset($_POST['moveid']))
  {
    $message = 'Moved subpage "'.title2html(getpagetitle($_POST['moveid'])).'"';
    if(isset($_POST['moveup']))
    {
      $message .= " up";
      movepage($_POST['moveid'], "up", $_POST['positions']);
      updateeditdata($page, $sid);
    }
    elseif(isset($_POST['movedown']))
    {
      $message .= " down";
      movepage($_POST['moveid'], "down", $_POST['positions']);
      updateeditdata($page, $sid);
    }
    elseif(isset($_POST['movetop']))
    {
      $message .= " to the top";
      movepage($_POST['moveid'], "top");
      updateeditdata($page, $sid);
    }
    else
    {
      $message .= " to the bottom";
      movepage($_POST['moveid'], "bottom");
      updateeditdata($page, $sid);
    }
  }
/*  editmenuforms($page);
  print(generalsettingsbuttons($page));*/
  $editpage = new EditMenu($page,$message);
}
else
{
  $editpage = new DonePage($page,"This page is already being edited",$pagelockmessage,"&action=editcontents&override=on","menuedit.php","Override lock and edit");
}
print($editpage->toHTML());

// *************************** menu ***************************************** //

//
//
//
function editmenuforms($page)
{
  global $sid;
  
  $contents=getmenucontents($page);
  $edittextbuttons = new EditTextButtons($page,$contents['introtext'],"Edit Page Intro","menu");
  print($edittextbuttons->toHTML());

?>
<hr><p>
<?php
  $menulevelsform = new EditMenuLevelsForm($page,$contents['sistersinnavigator'],$contents['displaydepth'],$contents['navigatordepth']);
  print($menulevelsform->toHTML());


  $subpageids=getallsubpageids($page);
  $titles_navigator=getallsubpagenavtitles($page);
  if(count($subpageids)>1)
  {
?>

<table>
  <tr>
    <td class="bodyline">
      <table cellpadding="5"
        <tr>
          <th class="thHead" colspan="2">Ordering of subpages</th>
        </tr>
<?php
    for($i=0;$i<count($subpageids);$i++)
    {
?>

        <tr>
          <td class="gen" valign="top"><?php print(title2html($titles_navigator[$i])); ?></td>
          <td class="table" valign="top"><?php
             $moveform = new movepageform($page,$subpageids[$i]);
             print($moveform->toHTML());
          ?>
          </td>
        </tr>

<?php
    }
  }
?>
      </table>
    </td>
  </tr>
</table>
<?php
}
?>
