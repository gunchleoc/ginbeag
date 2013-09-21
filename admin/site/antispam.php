<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."includes/templates/elements.php");
include_once($projectroot."admin/includes/templates/site.php");

$sid = $_GET['sid'];
checksession($sid);

$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

unset($_GET['action']);
unset($_POST['action']);

//  print_r($_GET);


if($action=='site')
{
	$siteantispam = new SiteAntispam("");
}

if($action=='savesite')
{
  	$message = savesitefeatures();
  	
   	$siteantispam = new SiteAntispam($message);
}

print($siteantispam->toHTML());


function savesitefeatures()
{
  global $sid, $_POST;

  if(isset($_POST['renamevariables']))
  {
    $properties['Math CAPTCHA Reply Variable']=makerandomvariablename();
    $properties['Math CAPTCHA Answer Variable']=makerandomvariablename();
    $properties['Message Text Variable']=makerandomvariablename();
    $properties['Subject Line Variable']=makerandomvariablename();
    $properties['E-Mail Address Variable']=makerandomvariablename();
  }
  else
  {
    $properties['Use Math CAPTCHA']=setinteger($_POST['usemathcaptcha']);
  }

  $success=updateentries(ANTISPAM_TABLE,$properties,"property_name","property_value");
  
  $result = "";

  if($success="1")
  {
    if(isset($_POST['renamevariables']))
    {
      $result = "Renamed Variables";
    }
    else
    {
      $result = "Anti-Spam settings saved";
    }
  }
  else
  {
    if(isset($_POST['renamevariables']))
    {
      $result = "Failed to rename variables ".$sql;
    }
    else
    {
      $result = "Failed to save Anti-Spam settings ".$sql;
    }
  }
  return $result;
}


function makerandomvariablename()
{
  $result="";
  $letters="aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ";
  
  list($usec, $sec) = explode(' ', microtime());
  $randomlength= (((float) $sec + ((float) $usec * 100000)) % 25)+6;

  for($i=0;$i<$randomlength;$i++)
  {
    list($usec, $sec) = explode(' ', microtime());
    $position= ((float) $sec + ((float) $usec * 100000)) % strlen($letters);
    $result.= substr($letters,$position,1);
  }
  return $result;
}

?>
