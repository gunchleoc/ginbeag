<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/adminelements.php");
include_once($projectroot."admin/functions/guestbookmod.php");
include_once($projectroot."includes/templates/forms.php");
include_once($projectroot."includes/templates/elements.php");

$sid=$_GET['sid'];
checksession($sid);

$itemsperpage=getproperty('Guestbook Entries Per Page');

$header = new HTMLHeader("Guestbook Entries","Webpage Building");
print($header->toHTML());


if(isset($_POST["deleteentry"]))
{
  $contents=getguestbookentrycontents($_POST['messageid']);
  print('<p class="highlight">Are you sure you want to delete this entry?</p>');
?>
<table width="100%"><tr><td class="table">

<?php
  printguestbookentry(getguestbookentrycontents($_POST['messageid']));
?>
</td></tr></table>
</body>
</html>

<form name="deleteconfirmform" method="post">
<input type="hidden" name="messageid" value="<?php print($_POST['messageid']);?>" />
<input type="submit" name="deleteconfirm" value="Yes, please delete this entry!" class="mainoption" />
<input type="submit" name="deleteabort" value="Oops, no!" class="liteoption" />
</form>
<?php
}
elseif(isset($_POST["deleteconfirm"]))
{
  print('<p class="highlight">Entry deleted.</p>');
  deleteguestbookentry($_POST['messageid']);
}
if(!isset($_POST["deleteentry"]))
{
  if(isset($_GET['offset'])) $offset=$_GET['offset'];
  else $offset=0;
  
  displayentriesadmin($itemsperpage, $offset);
}


//
//
//
function displayentriesadmin($number, $offset)
{
	$pagemenu = new PageMenu($offset, $number, countguestbookentries());
  	print('<div align="right">'.$pagemenu->toHTML().'</div>');
?>
<table width="100%"><tr><td class="table">

<?php
  $entries=getguestbookentries($number,$offset);

  if(count($entries)==0)
  {
    print('<p class="highlight">No messages</p>');
  }
  else
  {
    for($i=0;$i<count($entries);$i++)
    {
      if($i>0)
      {
        print('<hr>');
        print('<div align="right">');
        print('<a href="#top" class="genmed">Top of this page</a>');
        print('</div>');
      }

      printguestbookentry(getguestbookentrycontents($entries[$i]));
?>
<form name="deleteform" method="post">
<input type="hidden" name="messageid" value="<?php print($entries[$i]);?>" />
<input type="submit" name="deleteentry" value="Delete this entry" class="liteoption" />
</form>
<?php
    }
  }
?>
</td></tr></table>
</body>
</html>
<?php
	print('<div align="right">'.$pagemenu->toHTML().'</div>');
}

//
//
//
function printguestbookentry($contents)
{
  print('<span class="highlight">Name: </span><span class="gen"><b>'.title2html($contents["name"]).'</b></span>');
  print('<br /><span class="highlight">E-Mail: </span><span class="gen"><a href="mailto:'.$contents["email"].'">'.$contents["email"].'</a></span>');
  print('<br /><span class="highlight">Date: </span><span class="gen">'.formatdatetime($contents["date"]).'</span>');
  print('<br /><span class="highlight">Subject: </span><span class="gen"><b>'.title2html($contents["subject"]).'</b></span>');
  print('<br /><span class="gen">'.text2html($contents["message"]).'</span>');
}
?>
