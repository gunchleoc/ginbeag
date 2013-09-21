<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/copyrightmod.php");
include_once($projectroot."functions/users.php");
include_once($projectroot."includes/includes.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."includes/templates/forms.php");
//include_once($projectroot."admin/includes/adminelements.php");

$sid=$_GET['sid'];
checksession($sid);

$action="";
if(isset($_GET['action'])) $action=$_GET['action'];
elseif(isset($_POST['action'])) $action=$_POST['action'];

unset($_GET['action']);
unset($_POST['action']);


$entriesperpage=20;

if(isset($_GET['offset'])) $offset=$_GET['offset'];
else $offset=0;

if(isset($_GET['order'])) $order=$_GET['order'];
elseif(isset($_POST['order'])) $order=$_POST['order'];
else $order="copyright_id";

if(isset($_GET['ascdesc'])) $ascdesc=$_GET['ascdesc'];
elseif(isset($_POST['ascdesc'])) $ascdesc=$_POST['ascdesc'];
else $ascdesc="asc";

if(isset($_GET['filterpermission'])) $filterpermission=$_GET['filterpermission'];
elseif(isset($_POST['filterpermission'])) $filterpermission=$_POST['filterpermission'];
else $filterpermission="10000";

$header = new HTMLHeader("Blanket Copyright Permissions","Webpage Building");
print($header->toHTML());


// print_r($_POST);
// print_r($_GET);


if(isset($_POST['cancel']))
{
  copyrightforms($offset);
}
elseif(isset($_POST['editcopyright']))
{
  copyrighteditform($_POST['copyrightid']);
}
elseif(isset($_POST['changecopyright']))
{
  $holder=trim($_POST['holder']);
  $oldholder=getcopyrightholder($_POST['copyrightid']);
  if(!$holder)
  {
    print('<p class="highlight">Please specify a copyright holder!</p>');
    copyrighteditform($_POST['copyrightid'],$oldholder,$_POST['contact'],
                      $_POST['comments'],$_POST['credit'],$_POST['permission']);
  }
  elseif(holderexists($holder) && $holder !== $oldholder)
  {
    print('<p class="highlight">Copyright Holder <i>'.$holder.'</i> already exists!</p>');
    copyrighteditform($_POST['copyrightid'],$oldholder,$_POST['contact'],
                      $_POST['comments'],$_POST['credit'],$_POST['permission']);
  }
  else
  {
    updatecopyrightholder($_POST['copyrightid'],$holder,
                          trim($_POST['contact']),trim($_POST['comments']),
                          $_POST['permission'],$_POST['credit'],$sid);
    print('<p class="highlight">Updated copyright<p>');
    copyrightforms($offset);
  }
}
elseif(isset($_POST['addcopyrightform']))
{
  copyrightaddform();
}
elseif(isset($_POST['addcopyright']))
{
  $holder=trim($_POST['holder']);
  if(!$holder)
  {
    print('<p class="highlight">Please specify a copyright holder!</p>');
    copyrightaddform('',$_POST['contact'], $_POST['comments'],
                     $_POST['credit'],$_POST['permission']);
  }
  elseif(holderexists($holder))
  {
    print('<p class="highlight">Copyright Holder <i>'.$holder.'</i> already exists!</p>');
    copyrightaddform($holder,$_POST['contact'], $_POST['comments'],
                     $_POST['credit'],$_POST['permission']);
  }
  else
  {
    addcopyrightholder($holder,trim($_POST['contact']),
                       trim($_POST['comments']),
                       $_POST['permission'],$_POST['credit'],$sid);
    print('<p class="highlight">Added copyright holder<p>');
    copyrightforms($offset);
  }
}
elseif(isset($_POST['deletecopyrightform']))
{
  copyrightdeleteform($_POST['copyrightid']);
}
elseif(isset($_POST['confirmdelete']))
{
  print('<p class="highlight">Deleted Copyright Holder <i>'.$_POST['copyrightid'].': '.getcopyrightholder($_POST['copyrightid']).'</i></p>');
  deletecopyrightholder($_POST['copyrightid']);
  copyrightforms($offset);
}
elseif(($action=="search") || isset($_GET['search']))
{
  if(isset($_POST['holder']))
  {
    $holder=$_POST['holder'];
  }
  else
  {
    $holder=$_GET['holder'];
  }
  print('<p class="highlight">Search results for <i>'.$holder.'</i></p>');
  copyrightforms($offset,$holder['holder']);
}
else
{
  copyrightforms($offset);
}

$footer = new HTMLFooter();
print($footer->toHTML());


//
//
//
function copyrightforms($offset=0,$searchholder="")
{
  global $sid,$entriesperpage,$order,$ascdesc,$filterpermission;

  $params="order=".$order."&ascdesc=".$ascdesc."&filterpermission=".$filterpermission;
  if($searchholder)
  {
    $params.="&action=search&holder=".$searchholder;
  }
  
  if($searchholder)
  {
    $copyids=searchholder($searchholder,$order,$ascdesc,$filterpermission);
  }
  else
  {
    $copyids=getcopyrightids($order,$ascdesc,$filterpermission);
  }
  
//  print_r($copyids);
?>
<table width="100%">
  <tr>
    <td>
      <form name="addform" action="?sid=<?php print($sid);?>" method="post">
        <input type="submit" name="addcopyrightform" value="Add Copyright Holder" class="mainoption" />
      </form>
    </td>
    <td align="right">
      <form name="searchform" action="?sid=<?php
      print($sid);?>&order=<?php
      print($order);?>&ascdesc=<?php
      print($ascdesc);?>&filterpermission=<?php
      print($filterpermission);?>&action=search" method="post">
        <input type="text" name="holder" maxlength="255" value="" />
        <input type="submit" name="search" value="Search Holder" class="mainoption" />
        <input type="submit" name="cancel" value="Clear Search" class="liteoption" />
      </form>
    </td>
  </tr>
</table>

<form name="orderform" action="?sid=<?php
print($sid);
if($searchholder)
{
  print('&search=search&holder='.$searchholder);
}
?>&action=order" method="post">
<table width="100%">
  <tr>
    <td valign="middle">
      <table width="100%">
        <tr>
          <td align="left">
            <select name="filterpermission" size="1">
              <option value="10000" <?php if($filterpermission=="10000") print('selected');?>>-- Permissions --</option>
              <option value="<?php print(PERMISSION_GRANTED);?>"
                <?php if($filterpermission==PERMISSION_GRANTED) print('selected');?>>
                Granted
              </option>
              <option value="<?php print(NO_PERMISSION);?>"
                <?php if($filterpermission==NO_PERMISSION) print('selected');?>>
                No Permission
              </option>
              <option value="<?php print(PERMISSION_REFUSED);?>"
                <?php if($filterpermission==PERMISSION_REFUSED) print('selected');?>>
                Refused
              </option>
              <option value="<?php print(PERMISSION_IMAGESONLY);?>"
                <?php if($filterpermission==PERMISSION_IMAGESONLY) print('selected');?>>
                Images Only
              </option>
              <option value="<?php print(PERMISSION_LINKIMAGESONLY);?>"
                <?php if($filterpermission==PERMISSION_LINKIMAGESONLY) print('selected');?>>
                Links and Images Only
              </option>
              <option value="<?php print(PERMISSION_LINKONLY);?>"
                <?php if($filterpermission==PERMISSION_LINKONLY) print('selected');?>>
                Links Only
              </option>
              <option value="<?php print(PERMISSION_NOREPLY);?>"
                <?php if($filterpermission==PERMISSION_NOREPLY) print('selected');?>>
                No Reply
              </option>
              <option value="<?php print(PERMISSION_PENDING);?>"
                <?php if($filterpermission==PERMISSION_PENDING) print('selected');?>>
                Pending
              </option>
            </select>

            <select name="order" size="1">
              <option value="copyright_id" <?php if($order==="copyright_id") print('selected');?>>-- Order --</option>
              <option value="holder" <?php if($order==="holder") print('selected');?>>Copyright Holder</option>
              <option value="contact" <?php if($order==="contact") print('selected');?>>Contact Information</option>
              <option value="comments" <?php if($order==="comments") print('selected');?>>Comments/Restrictions</option>
              <option value="permission" <?php if($order==="permission") print('selected');?>>Permission</option>
              <option value="credit" <?php if($order==="credit") print('selected');?>>Preferred Credit</option>
              <option value="editor_id" <?php if($order==="editor_id") print('selected');?>>Responsible</option>
              <option value="added" <?php if($order==="added") print('selected');?>>Date Added</option>
              <option value="editdate" <?php if($order==="editdate") print('selected');?>>Last Update</option>
            </select>
            <select name="ascdesc" size="1">
              <option value="asc" <?php if($ascdesc==="asc") print('selected');?>>Ascending</option>
              <option value="desc" <?php if($ascdesc==="desc") print('selected');?>>Descending</option>
            </select>
            <input type="submit" name="search" value="Go" class="mainoption" />
          </td>
        </tr>
      </table>
    </td>
    <td align="right"  valign="middle">
<?php
	$pagemenu = new PageMenu($offset, $entriesperpage, count($copyids),$params);
  	print('<div align="right">'.$pagemenu->toHTML().'</div>');
?>
    </td>
  </tr>
</table>
</form>
      
<table width="100%"><tr><td class="bodyline">
<table cellpadding="5">
  <tr>
    <th class="thHead">ID</th>
    <th class="thHead">Copyright Holder</th>
    <th class="thHead">Contact Information</th>
    <th class="thHead">Comments/ Restrictions</th>
    <th class="thHead">Perm.</th>
    <th class="thHead">Preferred Credit</th>
    <th class="thHead"><font size="-2">Responsible/Added/ Last&nbsp;Update</font></th>
    <th class="thHead">Edit</th>
  </tr>
<?php
  for($i=$offset;$i<($offset+$entriesperpage) && $i<count($copyids);$i++)
  {
    $copyright=getcopyrightinfo($copyids[$i]);
?>
  <tr>
    <td class="table" align="right" valign="top">
      <span class="gen"><?php print($copyids[$i]);?></span>
    </td>
    <td class="table" align="left" valign="top">
      <b><?php
       print('<span class="gen">'.title2html($copyright['holder']).'</span>');
       print('<p><a href="../editimagelist.php?sid='.$sid.'&source=all&copyright='.$copyright['holder'].'&filter=Display+Selection" target="_blank" class="gensmall">Search&nbsp;Images</a></p>');
      ?></b>
    </td>
    <td class="table" align="left" valign="top">
      <span class="gen"><?php print(text2html($copyright['contact']));?></span>
    </td>
    <td class="table" align="left" valign="top">
      <span class="gen"><?php print(text2html($copyright['comments']));?></span>
    </td>
    <td class="table" align="center" valign="top">
      <span class="gen"><?php print(permission2html($copyright['permission']));?></span>
    </td>
    <td class="table" align="left" valign="top">
      <span class="gen"><?php print(title2html($copyright['credit']));?></span>
    </td>
    <td class="table" align="left" valign="top">
      <p class="gen"><?php print(title2html(getusername($copyright['editorid'])));?></p>
      <p class="footer"><?php print(formatdate($copyright['added']));?></p>
      <p class="footer"><?php print(formatdatetime($copyright['editdate'],true));?></p>
    </td>
    <td class="table" align="left" valign="top">
      <form name="editform" action="?sid=<?php print($sid);?>" method="post">
        <input type="hidden" name="copyrightid" value="<?php print($copyids[$i]);?>" />
        <input type="submit" name="editcopyright" value="&nbsp;Edit&nbsp;" class="liteoption" />
        <br />&nbsp;<br />
        <input type="submit" name="deletecopyrightform" value="Delete" class="liteoption" />
      </form>
    </td>
  </tr>
<?php
  }
?>
</table>
</td></tr></table>
<form name="addform" action="?sid=<?php print($sid);?>" method="post">
  <input type="submit" name="addcopyrightform" value="Add Copyright Holder" class="mainoption" />
</form>
<?php
printpermissionexplanation();
}

//
//
//
function copyrighteditform($copyid,$holder="",$contact="",$comments="",$credit="",$permission=1)
{
  global $sid;
  $copyright=getcopyrightinfo($copyid);
  if($holder || $contact || $comments || $credit)
  {
    $copyright['holder']=$holder;
    $copyright['contact']=$contact;
    $copyright['comments']=$comments;
    $copyright['permission']=$permission;
    $copyright['credit']=$credit;
  }
?>
<p class="sectiontitle">Editing Blanket Copyright: ID <?php print($copyid);?></p>
Last Person Responsible: <i><?php print(getusername($copyright['editorid']));?></i>
&nbsp;&nbsp;
Added: <i><?php print(formatdate($copyright['added']));?></i>
&nbsp;&nbsp;
Last Update: <i><?php print(formatdatetime($copyright['editdate']));?></i>

<form name="editform" action="?sid=<?php print($sid);?>" method="post">
<table><tr><td class="bodyline">
<table cellpadding="5">
<?php
copyrightinputformelements($copyright);
?>
  <tr>
    <td colspan="2" class="table" align="center">
      <input type="hidden" name="copyrightid" value="<?php print($copyid);?>" />
      <input type="submit" name="changecopyright" value="Submit Changes" class="mainoption" />
      &nbsp;&nbsp;
      <input type="reset" name="reset" value="Reset Forms" class="liteoption" />
      &nbsp;&nbsp;
      <input type="submit" name="cancel" value="Cancel Editing" class="liteoption" />
    </td>
  </tr>
</table>
</td></tr></table>


</form>
<?php
}

//
//
//
function copyrightaddform($holder="",$contact="",$comments="",$credit="",$permission=1)
{
  global $sid;

  $copyright=array();
  if($holder || $contact || $comments || $credit)
  {
    $copyright['holder']=$holder;
    $copyright['contact']=$contact;
    $copyright['comments']=$comments;
    $copyright['credit']=$credit;
    $copyright['permission']=$permission;
  }
  else
  {
    $copyright['holder']="";
    $copyright['contact']="";
    $copyright['comments']="";
    $copyright['credit']="";
    $copyright['permission']=NO_PERMISSION;
  }
?>
<p class="sectiontitle">Add New Copyright Holder</p>

<form name="addform" action="?sid=<?php print($sid);?>" method="post">
<table><tr><td class="bodyline">
<table cellpadding="5">
<?php
  copyrightinputformelements($copyright);
?>
  <tr>
    <td colspan="2" align="center">
      <input type="submit" name="addcopyright" value="Add Copyright Holder" class="mainoption" />
      &nbsp;&nbsp;
      <input type="submit" name="cancel" value="Cancel Adding" class="liteoption" />
    </td>
  </tr>
</table>
</td></tr></table>


</form>
<?php
}


//
//
//
function copyrightdeleteform($copyid)
{
  global $sid;
    $copyright=getcopyrightinfo($copyid);
?>
<p class="sectiontitle">Are you sure you want to delete this item?</p>

<table width="100%"><tr><td class="bodyline">
<table cellpadding="5">
  <tr>
    <th class="thHead">ID</th>
    <th class="thHead">Copyright Holder</th>
    <th class="thHead">Contact Information</th>
    <th class="thHead">Comments/ Restrictions</th>
    <th class="thHead">Perm.</th>
    <th class="thHead">Preferred Credit</th>
    <th class="thHead"><font size="-2">Responsible/Added/ Last&nbsp;Update</font></th>
  </tr>

  <tr>
    <td class="table" align="right" valign="top">
      <span class="gen"><?php print($copyid);?></span>
    </td>
    <td class="table" align="left" valign="top">
      <span class="gen"><b><?php print(title2html($copyright['holder']));?></b></span>
    </td>
    <td class="table" align="left" valign="top">
      <span class="gen"><?php print(text2html($copyright['contact']));?></span>
    </td>
    <td class="table" align="left" valign="top">
      <span class="gen"><?php print(text2html($copyright['comments']));?></span>
    </td>
    <td class="table" align="left" valign="top">
      <span class="gen"><?php print(permission2html($copyright['permission']));?></span>
    </td>
    <td class="table" align="left" valign="top">
      <span class="gen"><?php print(title2html($copyright['credit']));?></span>
    </td>
    <td class="table" align="left" valign="top">
      <p class="gen"><?php print(title2html(getusername($copyright['editorid'])));?></p>
      <p class="footer"><?php print(formatdate($copyright['added']));?></p>
      <p class="footer"><?php print(formatdatetime($copyright['editdate'],true));?></p>
    </td>
  </tr>


</table>
</td></tr></table>

<form name="confirmdeleteform" action="?sid=<?php print($sid);?>" method="post">
  <input type="hidden" name="copyrightid" value="<?php print($copyid);?>" />
  <input type="submit" name="confirmdelete" value="Yes, please delete" class="liteoption" />
  &nbsp;&nbsp;
  <input type="submit" name="cancel" value="No, please cancel" class="liteoption" />
</form>
<?php
printpermissionexplanation();
}


//
//
//
function copyrightinputformelements($copyright=array('permission' => NO_PERMISSION))
{
?>
  <tr>
    <th class="thHead">Description</th>
    <th class="thHead">Value</th>
  </tr>

  <tr>
    <td class="gen">Copyright Holder</td>
    <td class="table" align="left">
      <input type="text" name="holder" size="91" maxlength="255"
        value="<?php print(input2html($copyright['holder']));?>" />
    </td>
  </tr>

  <tr>
    <td class="gen">Contact Information</td>
    <td class="table" align="left">
      <textarea name="contact" rows="6" cols="90"><?php
        print(input2html($copyright['contact']));
        ?></textarea>
    </td>
  </tr>

  <tr>
    <td class="gen">Comments/Restrictions</td>
    <td class="table" align="left">
      <textarea name="comments" rows="6" cols="90"><?php
        print(input2html($copyright['comments']));
        ?></textarea>
    </td>
  </tr>
  
  <tr>
    <td class="gen">Preferred Credit</td>
    <td class="table" align="left">
      <input type="text" name="credit" size="91" maxlength="255"
        value="<?php print(input2html($copyright['credit']));?>" />
    </td>
  </tr>

  <tr>
    <td class="gen">Permission</td>
    <td class="gen" align="left">
<br />
<input type="radio" name="permission" value="<?php print(PERMISSION_GRANTED);?>" class="gen" <?php
if($copyright['permission']==PERMISSION_GRANTED)
{
  print('checked');
}
?>>
Permission granted

<br />
<input type="radio" name="permission" value="<?php print(PERMISSION_IMAGESONLY);?>" class="gen" <?php
if($copyright['permission']==PERMISSION_IMAGESONLY)
{
  print('checked');
}
?>>
Permission for images only

<br />
<input type="radio" name="permission" value="<?php print(PERMISSION_LINKIMAGESONLY);?>" class="gen" <?php
if($copyright['permission']==PERMISSION_LINKIMAGESONLY)
{
  print('checked');
}
?>>
Permission for images and links only

<br />
<input type="radio" name="permission" value="<?php print(PERMISSION_LINKONLY);?>" class="gen" <?php
if($copyright['permission']==PERMISSION_LINKONLY)
{
  print('checked');
}
?>>
Permission for links only

<br />
<input type="radio" name="permission" value="<?php print(PERMISSION_REFUSED);?>" class="gen" <?php
if($copyright['permission']==PERMISSION_REFUSED)
{
  print('checked');
}
?>>
Permission Refused

<br />
<input type="radio" name="permission" value="<?php print(PERMISSION_NOREPLY);?>" class="gen" <?php
if($copyright['permission']==PERMISSION_NOREPLY)
{
  print('checked');
}
?>>
No Reply

<br />
<input type="radio" name="permission" value="<?php print(PERMISSION_PENDING);?>" class="gen" <?php
if($copyright['permission']==PERMISSION_PENDING)
{
  print('checked');
}
?>>
Permission Pending

<br />
<input type="radio" name="permission" value="<?php print(NO_PERMISSION);?>" class="gen" <?php
if($copyright['permission']==NO_PERMISSION)
{
  print('checked');
}
?>>
No Permission

<br />&nbsp;
    </td>
  </tr>
  <tr><td colspan="2" class="spacer"></td></tr>
<?php
}


//
//
//
function permission2html($permission)
{
  $result="Unknown";

  if($permission==PERMISSION_GRANTED)
  {
    $result="&radic;";
  }
  elseif($permission==NO_PERMISSION)
  {
    $result="&mdash;";
  }
  elseif($permission==PERMISSION_REFUSED)
  {
    $result="&dagger;";
  }
  elseif($permission==PERMISSION_IMAGESONLY)
  {
    $result="&diams;";
  }
  elseif($permission==PERMISSION_LINKIMAGESONLY)
  {
    $result="&diams;&nbsp;&rarr;";
  }
  elseif($permission==PERMISSION_LINKONLY)
  {
    $result="&rarr;";
  }
  elseif($permission==PERMISSION_PENDING)
  {
    $result="&infin;";
  }
  elseif($permission==PERMISSION_NOREPLY)
  {
    $result="X";
  }
  return $result;
}

//
//
//
function printpermissionexplanation()
{
?>
<p class="highlight">&nbsp;<br />&nbsp;<br />Permission Symbols</p>
<p class="footer">&radic; -  Permission Granted
<br />&diams; -  Permission Granted, but for images only
<br />&diams;&nbsp;&rarr; -  Permission Granted, but for images and links only
<br />&rarr; -  Permission Granted, but for links only (including a short quote)
<br />&dagger; - Permission Refused: We may not use anything by this source
<br />&mdash; - No Permission: We have not asked
<br />&infin; - Permission Pending: We are waiting for an answer
<br />X - No Reply
</p>



<?php
}
?>
