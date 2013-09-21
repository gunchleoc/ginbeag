<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."admin/functions/pagesmod.php");

$sid=$_GET['sid'];
checksession($sid);

$header = new HTMLHeader("Rebuild Indices","Webpage Building");
print($header->toHTML());

$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

unset($_GET['action']);
unset($_POST['action']);

if($action==='restrictedpages')
{
  rebuildaccessrestrictionindex();
}


showrestrictedpages();

//
//
//
function showrestrictedpages()
{
  global $sid;
?>


<form name="restrictedpagesform" action="?sid=<?php print($sid)?>&action=restrictedpages" method="post">
<input type="submit" name="rebuild" value="Rebuild restricted page list" class="liteoption" />
<br /><span class="gensmall">Use this button if page restrictions are off</span>
</form>
<?php
$footer = new HTMLFooter();
print($footer->toHTML());
}

?>
