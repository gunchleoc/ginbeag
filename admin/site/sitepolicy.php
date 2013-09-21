<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."admin/includes/templates/adminforms.php");
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
	$header = new HTMLHeader("Site Policy","Webpage Building");  
	print($header->toHTML());
}

if($action=='savesite')
{
	$header = new HTMLHeader("Site Policy","Webpage Building","Saving Site Policy");
	print($header->toHTML());
  	savesitefeatures();
}


sitepolicyform();

//
//
//
function sitepolicyform()
{
  global $sid;
  
  $usepolicy=getproperty("Display Site Policy");
  $policytitle=getproperty("Site Policy Title");
  $policytext=getdbelement("sitepolicytext",SITEPOLICY_TABLE,"policy_id",0);
  
?>
<form name="site" action="?sid=<?php print($sid)?>&action=savesite" method="post">
<table>
  <tr>
    <td class="bodyline">
      <table cellpadding="5"
         <tr>
          <th class="thHead" colspan="2">General</th>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Display Site Policy</span>
            <br><span class="gensmall">Will a Site Policy be shown in all pages?</span>
          </td>
          <td class="table" valign="top"><span class="gen">
            <input type="radio" name="displaypolicy" value="1"<?php
            if($usepolicy) print(" checked");
            ?>>Yes
            <input type="radio" name="displaypolicy" value="0"<?php
            if(!$usepolicy) print(" checked");
            ?>>No</span>
          </td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Title for the Site Policy</span>
            <br><span class="gensmall">Used to display a link to the Site Policy, and as the title of the Site Policy page.</span>
          </td>
          <td class="table" valign="top">
            <input type="text" name="policytitle" size="50" maxlength="255" value="<?php print($policytitle); ?>" />
          </td>
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
        <tr><td class="spacer" colspan="2"></td></tr>
         <tr>
          <th class="thHead" colspan="2">Site Policy Text</th>
        </tr>
        <tr>
          <td class="table" valign="top" colspan="2">
<?php
  $edittextbuttons = new EditTextButtons(0,$policytext,"Edit Site Policy","sitepolicy");
  print($edittextbuttons->toHTML());
?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<?php
}

function savesitefeatures()
{
  global $sid, $_POST;

  $properties['Display Site Policy']=setinteger($_POST['displaypolicy']);
  $properties['Site Policy Title']=setstring($_POST['policytitle']);

  $success=updateentries(SITEPROPERTIES_TABLE,$properties,"property_name","property_value");

  print('<span class="highlight">');
  if($success="1")
  {
    print("Site policy saved");
  }
  else
  {
    print("Failed to save site policy".$sql);
  }
  print('</span>');
}

?>
