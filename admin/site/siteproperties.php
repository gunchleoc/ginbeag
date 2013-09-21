<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."includes/templates/elements.php");

$sid=$_GET['sid'];
checksession($sid);

$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

unset($_GET['action']);
unset($_POST['action']);

//  print_r($_GET);

if($action=='site')
{
	$header = new HTMLHeader("Technical Setup","Webpage Building");  
	print($header->toHTML());
}

if($action=='savesite')
{
	$header = new HTMLHeader("Technical Setup","Webpage Building","Saving Technical Setup");
	print($header->toHTML());
  	savesiteproperties();
}

sitepropertiesform();

//
//
//
function sitepropertiesform()
{
  global $sid;
  
  $properties=getproperties();
  $potdcats=explode(",",$properties["Picture of the Day Categories"]);
  $potdcatnames=array();
  if(!count($potdcats))
  {
    $potdcatlistoutput="All Categories";
  }
  else
  {
    for($j=0;$j<count($potdcats);$j++)
    {
      array_push($potdcatnames,getcategoryname($potdcats[$j]));
    }
    sort($potdcatnames);
    $potdcatlistoutput=implode(", ",$potdcatnames);
  }

// todo: file upload
  
?>
<form name="site" action="?sid=<?php print($sid)?>&action=savesite" method="post">
<table>
  <tr>
    <td class="bodyline">
      <table cellpadding="5"
        <tr>
          <th class="thHead" colspan="2"  width="100%">Search Engines</th>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Google Keywords</span>
          <br><span class="gensmall">You can specify extra keywords you want search engines to pick up for each page. Separate with commas.</span></td>
          <td class="table" valign="top"><input type="text" name="keywords" size="50" maxlength="255" value="<?php print(title2html($properties["Google Keywords"])); ?>" /></td>
        </tr>
        <tr><td class="spacer" colspan="2"></td></tr>
        
        <tr>
          <th class="thHead" colspan="2">Site setup</th>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Domain Name</span></td>
          <td class="table" valign="top"><input type="text" name="domainname" size="50" maxlength="255" value="<?php print($properties["Domain Name"]); ?>" /></td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Local Path</span>
            <br><span class="gensmall">Leave this empty if you are installing to your site's root</span>
          </td>
          <td class="table" valign="top"><input type="text" name="localpath" size="50" maxlength="255" value="<?php print($properties["Local Path"]); ?>" /></td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Image Upload Path</span>
            <br><span class="gensmall">Relative to Local Path</span>
          </td>
          <td class="table" valign="top"><input type="text" name="imagepath" size="50" maxlength="255" value="<?php print($properties["Image Upload Path"]); ?>" /></td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Admin Email Address</span></td>
          <td class="table" valign="top"><input type="text" name="email" size="50" maxlength="255" value="<?php print($properties["Admin Email Address"]); ?>" /></td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Email Signature</span></td>
          <td class="table" valign="top"><textarea name="signature" rows="5" cols="30"><?php
          print(title2html($properties["Email Signature"])); ?></textarea></td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Date Time Format</span></td>
          <td class="table" valign="top"><input type="text" name="datetime" size="25" maxlength="255" value="<?php print($properties["Date Time Format"]); ?>" /></td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Date Format</span></td>
          <td class="table" valign="top"><input type="text" name="date" size="25" maxlength="255" value="<?php print($properties["Date Format"]); ?>" /></td>
        </tr>
        <tr><td class="spacer" colspan="2"></td></tr>

        <tr>
		      <td colspan="2" align="center">
            <input type="submit" name="submit" value="Submit" class="mainoption" />
            &nbsp;&nbsp;
            <input type="reset" value="Reset" class="liteoption" />
            &nbsp;&nbsp;
            <?php print(locationbutton("Cancel", 'pagedisplay.php?sid='.$sid, "liteoption"));?>
		      </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php
}

function savesiteproperties()
{
  global $sid, $_POST;

  $properties['Google Keywords']=setstring(trim($_POST['keywords']));

  $properties['Domain Name']=setstring(trim($_POST['domainname']));
  $properties['Local Path']=setstring(trim($_POST['localpath']));
  $properties['Image Upload Path']=setstring(trim($_POST['imagepath']));
  $properties['Admin Email Address']=setstring(trim($_POST['email']));
  $properties['Email Signature']= setstring(trim($_POST['signature']));
  $properties['Date Time Format']=setstring(trim($_POST['datetime']));
  $properties['Date Format']=setstring(trim($_POST['date']));


  $success=updateentries(SITEPROPERTIES_TABLE,$properties,"property_name","property_value");

  print('<span class="highlight">');
  if($success="1")
  {
    print("Technical setup saved");
  }
  else
  {
    print("Failed to save technical setup".$sql);
  }
  print('</span>');
}

?>
