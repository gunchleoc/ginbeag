<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."includes/templates/forms.php");
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
	$header = new HTMLHeader("Features","Webpage Building");  
	print($header->toHTML());
}

if($action=='savesite')
{
	$header = new HTMLHeader("Features","Webpage Building","Saving Site Features");
	print($header->toHTML());
  	savesitefeatures();
}


sitefeaturesform();

//
//
//
function sitefeaturesform()
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
          <th class="thHead" colspan="2">On Random: Picture of the Day</th>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Display Picture of the Day</span>
            <br><span class="gensmall">Will a Picture of the Day be shown in the navigator?</span>
          </td>
          <td class="table" valign="top"><span class="gen">
            <input type="radio" name="displaypotd" value="1"<?php
            if($properties["Display Picture of the Day"]) print(" checked");
            ?>>Yes
            <input type="radio" name="displaypotd" value="0"<?php
            if(!$properties["Display Picture of the Day"]) print(" checked");
            ?>>No</span>
          </td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Categories for Picture of the Day</span></td>
          <td class="table" valign="top"><span class="gen"><?php print($potdcatlistoutput);?></span></td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Select Categories for Picture of the Day</span>
            <br><span class="gensmall">The random Picture of the Day will be generated from the selected categories and their subcategories.</span>
          </td>
          <td class="table" valign="top">
            <?php 
            // todo language bug: all categories
            $categoryselection = new CategorySelectionForm(true);
             print($categoryselection->toHTML());
            ?>
            <input type="hidden" name="oldpotdcats" value="<?php print($properties["Picture of the Day Categories"]); ?>">
          </td>
        </tr>
        <tr><td class="spacer" colspan="2"></td></tr>
         <tr>
          <th class="thHead" colspan="2">On Random: Article of the Day</th>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Display Article of the Day</span>
            <br><span class="gensmall">Will an Article of the Day be shown in the navigator?</span>
          </td>
          <td class="table" valign="top"><span class="gen">
            <input type="radio" name="displayaotd" value="1"<?php
            if($properties["Display Article of the Day"]) print(" checked");
            ?>>Yes
            <input type="radio" name="displayaotd" value="0"<?php
            if(!$properties["Display Article of the Day"]) print(" checked");
            ?>>No</span>
          </td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Article of the Day Start Pages</span>
            <br><span class="gensmall">Get the Article of the Day from the folloing articlemenu pages and their subpages (separate with commas, e.g. '5,260,6'):</span>
          </td>
          <td class="table" valign="top">
            <input type="text" name="aotdpages" size="25" maxlength="255" value="<?php print($properties["Article of the Day Start Pages"]); ?>" /></td>
          </td>
        </tr>
        <tr><td class="spacer" colspan="2"></td></tr>

         <tr>
          <th class="thHead" colspan="2">Guestbook</th>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Enable Guestbook</span>
            <br><span class="gensmall">Adds a guestbook to the site</span>
          </td>
          <td class="table" valign="top"><span class="gen">
            <input type="radio" name="enableguestbook" value="1"<?php
            if($properties["Enable Guestbook"]) print(" checked");
            ?>>Yes
            <input type="radio" name="enableguestbook" value="0"<?php
            if(!$properties["Enable Guestbook"]) print(" checked");
            ?>>No</span>
          </td>
        </tr>
        <tr>
          <td class="table" valign="top"><span class="gen">Guestbook Entries Per Page</span></td>
          <td class="table" valign="top"><input type="text" name="guestbookperpage" size="5" maxlength="255" value="<?php print($properties["Guestbook Entries Per Page"]); ?>" /></td>
        </tr>

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

function savesitefeatures()
{
  global $sid, $_POST;

  $properties['Display Picture of the Day']=setinteger($_POST['displaypotd']);
  if(isset($_POST['selectedcat']))
  {
    $aotdcats=array_map("setinteger", $_POST['selectedcat']);
    $properties['Picture of the Day Categories']=implode(",",$aotdcats);
  }
  else $properties['Picture of the Day Categories']=setstring($_POST['oldpotdcats']);

  $properties['Display Article of the Day']=setinteger($_POST['displayaotd']);
  $aotdpages=explode(',',$_POST['aotdpages']);

  $aotdpages=array_map("setinteger", $aotdpages);
  $properties['Article of the Day Start Pages']=implode(",",$aotdpages);
  
  $properties['Enable Guestbook']=setinteger($_POST['enableguestbook']);
  $properties['Guestbook Entries Per Page']=setinteger($_POST['guestbookperpage']);

  $success=updateentries(SITEPROPERTIES_TABLE,$properties,"property_name","property_value");

  print('<span class="highlight">');
  if($success="1")
  {
    print("Site features saved");
  }
  else
  {
    print("Failed to save site features".$sql);
  }
  print('</span>');
}

?>
