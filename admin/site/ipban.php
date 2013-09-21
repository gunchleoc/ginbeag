<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."admin/functions/publicusersmod.php");
//include_once($projectroot."admin/functions/pagesmod.php");

// print_r($_POST);
// print_r($_GET);

$sid=$_GET['sid'];
checksession($sid);

$header = new HTMLHeader("IP Ban","Webpage Building");
print($header->toHTML());

if(!isadmin($sid))
{
  die('<p class="highlight">You have no permission for this area</p>');
}

if(isset($_POST['banipallrestricted']))
{
  $ip= trim($_POST['ip']);
  if($ip === long2ip(ip2long($ip)))
  {
    addbannedipforrestrictedpages($ip);
  }
  else
  {
    print('<p class="highlight"><i>'.$ip.'</i> is not a valid IP address.</p>');
  }
}
elseif(isset($_POST['unbanipallrestricted']))
{
  removebannedipforrestrictedpageas($_POST['ip']);
}

showips();

//
//
//
function showips()
{
  global $sid;
  

?>
<table><tr><td class="bodyline">
<table cellpadding="5"><tr>
<th class="thHead">Ban IP from all pages with Restricted Access</th>
</tr>
  <form name="banipallrestricted" action="?sid=<?php print($sid)?>" method="post">
<tr>
  <td class="table" align="left">
  <input type="text" name="ip" value="" width="15" maxlength="15" class="post" />
  <input type="submit" name="banipallrestricted" value="Ban this IP address" class="post" />
  </td>
</tr>
  </form>
</table>
</td></tr>
</table>

<?php

  $ips=getalladdbannedipforrestrictedpages();
  if(count($ips))
  {
?>
<br />
<br />
<table><tr><td class="bodyline">
<table cellpadding="5"><tr>
<th class="thHead" colspan ="2">IPs banned from all pages with Restricted Access</th>
</tr>
<?php
    for($i=0;$i<count($ips);$i++)
    {
?>
  <form name="unbanipallrestricted" action="?sid=<?php print($sid)?>" method="post">
<tr>
  <td class="gen" align="left"><?php print($ips[$i]);?>
  </td>
  <td class="table" align="left">
  <input type="hidden" name="ip" value="<?php print($ips[$i]);?>" />
  <input type="submit" name="unbanipallrestricted" value="Unban this IP" class="post">
  </td>
</tr>
  </form>
<?php
    }
?>
</table>
<?php
  }
  else
  {
    print('<p class="pagetitle">No IPs have been banned</p>');
  }
?>
</td></tr>
</table>
<br />
<br />
<table><tr><td class="bodyline">
<table cellpadding="5"><tr>
<th class="thHead">Ban IP from all pages with Restricted Access</th>
</tr>
  <form name="banipallrestricted" action="?sid=<?php print($sid)?>" method="post">
<tr>
  <td class="table" align="left">
  <input type="text" name="ip" value="" width="15" maxlength="15" class="post" />
  <input type="submit" name="banipallrestricted" value="Ban this IP address" class="post" />
  </td>
</tr>
  </form>
</table>
</td></tr>
</table>

<?php
$footer = new HTMLFooter();
print($footer->toHTML());
}

?>
