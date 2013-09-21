<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/publicusersmod.php");
include_once($projectroot."functions/publicsessions.php");
include_once($projectroot."admin/includes/adminelements.php");

$sid=$_GET['sid'];
checksession($sid);

$header = new HTMLHeader("Who's Online","Webpage Building");
print($header->toHTML());

if(!isadmin($sid))
{
  die('<p class="highlight">You have no permission for this area</p>');
}


// print_r($_POST);
// print_r($_GET);

//
//
//

  $sessions=getallpublicsessions();
?>
<table><tr><td class="bodyline">
<table>
<tr>
  <th class="thHead" colspan="5">Public Users Online</th>
</tr>
<tr>
  <th class="thHead">Username</th>
  <th class="thHead">Last Click</th>
  <th class="thHead">IP/Host</th>
  <th class="thHead">Login Successful?</th>
  <th class="thHead">Retries</th>
</tr>
<?php

  for($i=0; $i<count($sessions);$i++)
  {

    // get all user values from DB
    $userid=getpublicsiduser($sessions[$i]);
    $username=getpublicusername($userid);
    $ip = getpublicip($sessions[$i]);
    $lastlogin = getlastpubliclogin($username,$ip);
    $valid = ispublicsessionvalid($sessions[$i]);
    $retries = getpublicretries($username,$ip);
?>

<tr>
  <td class="gen">
<?php

    print($username);
?>
  </td>
  <td class="gen">
<?php

    print($lastlogin);
?>
  </td>
  <td class="gen">
<?php
    print(long2ip($ip).'<br />');
    print(gethostbyaddr(long2ip($ip)));
?>
  </td>
  <td class="gen" align="center">
<?php
  if($valid) print('Yes');
  else print('&nbsp;');
?>
  </td>
  <td class="gen" align="right">
<?php
    print($retries);
?>
  </td>
</tr>
<?php
  }
?>
</table>
</td></tr></table>
</form>

</table>
</td></tr></table>
</p>
<?php
$footer = new HTMLFooter();
print($footer->toHTML());
?>
