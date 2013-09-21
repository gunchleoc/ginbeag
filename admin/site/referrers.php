<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/referrersmod.php");
//include_once($projectroot."functions/users.php");
//include_once($projectroot."includes/includes.php");
//include_once($projectroot."includes/functions.php");
//include_once($projectroot."admin/includes/adminelements.php");

$sid=$_GET['sid'];
checksession($sid);

if(isset($_GET['order'])) $order=$_GET['order'];
elseif(isset($_POST['order'])) $order=$_POST['order'];
else $order="copyright_id";

if(isset($_GET['ascdesc'])) $ascdesc=$_GET['ascdesc'];
elseif(isset($_POST['ascdesc'])) $ascdesc=$_POST['ascdesc'];
else $ascdesc="asc";

if(isset($_GET['filterpermission'])) $filterpermission=$_GET['filterpermission'];
elseif(isset($_POST['filterpermission'])) $filterpermission=$_POST['filterpermission'];
else $filterpermission="10000";

$header = new HTMLHeader("Block Referrers","Webpage Building");
print($header->toHTML());

print('<p class="gen">Keeps the listed sites from linking to us. Do not add the protocol, e.g. <i>http://</i></p>');

// print_r($_POST);
// print_r($_GET);


if(isset($_POST['cancel']))
{
  referrerforms();
}
elseif(isset($_POST['block']))
{
  addblockedreferrer(trim($_POST['referrer']));
  referrerforms();
}
elseif(isset($_POST['unblock']))
{
  referrerunblockform($_POST['referrer']);
}
elseif(isset($_POST['confirmunblock']))
{
  print('<p class="highlight">Unblocked Referrer <i>'.$_POST['referrer'].'</i></p>');
  deleteblockedreferrer($_POST['referrer']);
  referrerforms();
}
else
{
  referrerforms();
}

$footer = new HTMLFooter();
print($footer->toHTML());


//
//
//
function referrerforms()
{
  global $sid;

  $blockedrefs=getblockedreferrers();

//  print_r($copyids);
?>
<table width="100%">
  <tr>
    <td>
      <form name="blockform" action="?sid=<?php print($sid);?>" method="post">
        <input type="text" name="referrer" value="" />
        <input type="submit" name="block" value="Block Referrer" class="mainoption" />
      </form>
    </td>
    <td align="right">
    </td>
  </tr>
</table>
      
<table width="100%"><tr><td class="bodyline">
<table cellpadding="5">
  <tr>
    <th class="thHead">Referrer</th>
    <th class="thHead">Unblock</th>
  </tr>
<?php
  for($i=0;$i<count($blockedrefs);$i++)
  {
?>
  <tr>
    <td class="table" align="right" valign="top">
      <span class="gen"><?php print($blockedrefs[$i]);?></span>
    </td>
    <td class="table" align="left" valign="top">
      <form name="referrerunblockform" action="?sid=<?php print($sid);?>" method="post">
        <input type="hidden" name="referrer" value="<?php print($blockedrefs[$i]);?>" />
        <input type="submit" name="unblock" value="&nbsp;Unblock&nbsp;" class="liteoption" />
      </form>
    </td>
  </tr>
<?php
  }
?>
</table>
</td></tr></table>

<?php
}


//
//
//
function referrerunblockform($referrer)
{
  global $sid;
?>
<p class="sectiontitle">Are you sure you want to unblock this referrer?</p>

<table width="100%"><tr><td class="bodyline">
<table cellpadding="5">

  <tr>
    <td class="table" align="right" valign="top">
      <span class="gen"><?php print($referrer);?></span>
    </td>
  </tr>
</table>
</td></tr></table>

<form name="confirmunblockform" action="?sid=<?php print($sid);?>" method="post">
  <input type="hidden" name="referrer" value="<?php print($referrer);?>" />
  <input type="submit" name="confirmunblock" value="Yes, please unblock" class="liteoption" />
  &nbsp;&nbsp;
  <input type="submit" name="cancel" value="No, please keep blocking" class="liteoption" />
</form>
<?php
}

?>
