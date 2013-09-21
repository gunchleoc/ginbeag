<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/usersmod.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."admin/includes/adminelements.php");

$sid=$_GET['sid'];
checksession($sid);

$header = new HTMLHeader("User Permissions","Webpage Building");
print($header->toHTML());

if(!isadmin($sid))
{
  die('<p class="highlight">You have no permission for this area</p>');
}

if(isset($_GET['userid'])) $userid=$_GET['userid'];
elseif(isset($_POST['userid'])) $userid=$_POST['userid'];
else $userid=-1;

// print_r($_POST);
// print_r($_GET);

if(isset($_POST['searchuser']))
{
  $userid=getuserid($_POST['username']);
}
elseif(isset($_POST['searchpublicuser']))
{
  $userid=getpublicuserid($_POST['username']);
}
if((isset($_POST['searchuser']) || isset($_POST['searchpublicuser'])) && !$userid)
{
  print('<p class="highlight">User <i>'.$_POST['username'].'</i> not found.</p>');
}
// public users for restricted areas
elseif(isset($_GET['changeaccess']) && $_GET['changeaccess']==="removepage")
{
  removepageaccess(array(0 => $userid),$_POST["pageid"]);
  print('<p class="highlight">Removed Page</p>');
}
elseif(isset($_GET['changeaccess']) && $_GET['changeaccess']==="addpage")
{
  addpageaccess(array(0 => $userid),$_POST["pageid"]);
  print('<p class="highlight">Added Page</p>');
}
// webpage editors
elseif(isset($_POST['changelevel']) || isset($_GET['changelevel']))
{
  setuserlevel($userid,$_POST['userlevel']);
  if($_POST['userlevel']==USERLEVEL_USER)
  {
    print('<p class="highlight">Userlevel for <i>'.getusername($userid).'</i> set to <i>User</i></p>');
  }
  elseif($_POST['userlevel']==USERLEVEL_ADMIN)
  {
    print('<p class="highlight">Userlevel for <i>'.getusername($userid).'</i> set to <i>Administrator</i></p>');
  }
}
if($userid>0)
{
  if(isset($_GET['type']) && $_GET['type']==="public" || isset($_POST['searchpublicuser']))
  {
    publicuseraccessform($userid);
  }
  else
  {
    userlevelform($userid);
  }
}
else
{
  selectuserform();
}

$footer = new HTMLFooter();
print($footer->toHTML());


//
//
//
function selectuserform()
{
  global $sid;
?>
<form name="profile" action="?sid=<?php print($sid)?>" method="post">
<table><tr><td class="bodyline">
<table><tr>
<th class="thHead" colspan="2" width="100%">Search for Username</th>
</tr>
<tr>
<tr>
<td class="table"><span class="gen">Username:</span><br /><span class="gensmall">Please enter the full username</span></td>
<td class="table"><input type="text" name="username" size="20" maxlength="25" value="" /></td>
</tr>
<tr>
<td class="spacer" colspan="2">
</td>
</tr>
<tr>
<td class="table" colspan="2">
<p><input type="submit" name="searchuser" value="Webpage Editors" class="mainoption">
&nbsp;&nbsp;&nbsp;<input type="submit" name="searchpublicuser" value="Users for Restriced Areas" class="mainoption">
</td>
</tr>
</table>
</td></tr></table>
<br /><a href="userlist.php?sid=<?php print($sid);?>&ref=userpermissions" class="gen">Select user from list</a>
</form>
<?php
}

//
//
//
function userlevelform($userid)
{
  global $sid;

  // get all user values from DB
  $username=getusername($userid);
  $email=getuseremail($userid);
  $iscontact=getiscontact($userid);
  $contactfunction=getcontactfunction($userid);
?>
<p class="sectiontitle">Edit permissions for: <?php print($username)?></p>

<form name="profile" action="?sid=<?php print($sid)?>&changelevel=change" method="post">
<table><tr><td class="bodyline">
<table><tr>
<th class="thHead" colspan="2">Userlevel</th>
</tr>
<tr>
<td class="table"><span class="gen">Userlevel:</span></td>
<td class="table">

<select name="userlevel" size="1">
  <option value="<?php print(USERLEVEL_USER)?>"
<?php
  if(getuserlevel($userid)==USERLEVEL_USER) print('selected'); ?>>User</option>
  <option value="<?php print(USERLEVEL_ADMIN)?>"
  <?php
  if(getuserlevel($userid)==USERLEVEL_ADMIN) print('selected'); ?>>Administrator</option>
</select>
</td>
</tr>
<tr>
<td class="spacer" colspan="2">
</td>
</tr>
<tr>
<td class="table" align="center" colspan="2">
<input type="hidden" name="userid" value="<?php print($userid)?>" />
<input type="submit" name="changelevel" value="Change Userlevel" class="mainoption">
<input type="reset" value="Reset Profile" class="liteoption">
</td>
</tr>


</table>
</td></tr></table>

</table>
</td></tr></table>
</form>

<?php
}

//
//
//
function publicuseraccessform($userid)
{
  global $sid;

  // get all user values from DB
  $username=getpublicusername($userid);
  $userpages=getpageaccessforpublicuser($userid);
  $restrictedpages=getrestrictedpages();
  $restrictedpagesnoaccess=array();
  for($i=0;$i<count($restrictedpages);$i++)
  {

    if(!hasaccess($userid, $restrictedpages[$i]))
    {
      array_push($restrictedpagesnoaccess, $restrictedpages[$i]);
    }
  }
  
?>
<p class="sectiontitle">Edit page access permissions for: <?php print($username)?></p>

<table><tr><td class="bodyline">
<table>
<?php
  if(count($userpages)>0)
  {
?>
<tr>
<th class="thHead" colspan="2">Page Access</th>
</tr>
<?php
    for($i=0;$i<count($userpages);$i++)
    {
?>
<tr>
<td class="table">
<a href="<?php print(getprojectrootlinkpath()."admin/pagedisplay.php?page=".$userpages[$i].'&sid='.$sid);?>" target="_blank" class="gen"><?php print($userpages[$i].": ".getnavtitle($userpages[$i]));?></a>
</td>
<td class="table">
<form name="pageaccess" action="?sid=<?php print($sid)?>&changeaccess=removepage&type=public" method="post">
<input type="hidden" name="userid" value="<?php print($userid);?>">
<input type="hidden" name="pageid" value="<?php print($userpages[$i]);?>">
<input type="submit" name="removepage" value="Remove access to this page" class="liteoption">
</form>
</td>
</tr>
<?php
    }
?>
<tr>
<td class="spacer" colspan="2">
</td>
</tr>
<?php
  }
  if(count($restrictedpagesnoaccess)>0)
  {
?>
<tr>
<th class="thHead" colspan="2">No Page Access</th>
</tr>
<?php
    for($i=0;$i<count($restrictedpagesnoaccess);$i++)
    {
?>
<tr>
<td class="table">
<a href="<?php print(getprojectrootlinkpath()."admin/pagedisplay.php?page=".$restrictedpagesnoaccess[$i].'&sid='.$sid);?>" target="_blank" class="gen"><?php print($restrictedpagesnoaccess[$i].": ".getnavtitle($restrictedpagesnoaccess[$i]));?></a>
</td>
<td class="table">
<form name="pageaccess" action="?sid=<?php print($sid)?>&changeaccess=addpage&type=public" method="post">
<input type="hidden" name="userid" value="<?php print($userid);?>">
<input type="hidden" name="pageid" value="<?php print($restrictedpagesnoaccess[$i]);?>">
<input type="submit" name="addpage" value="Add access to this page" class="liteoption">
</form>
</td>
</tr>
<?php
    }
  }
?>
</table>
</td></tr></table>

</table>
</td></tr></table>

<?php
}


?>
