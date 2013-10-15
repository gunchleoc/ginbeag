<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/functions/copyrightmod.php");
include_once($projectroot."includes/objects/forms.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."admin/includes/objects/site/copyrightpermissions.php");
include_once($projectroot."includes/functions.php");
include_once($projectroot."admin/includes/objects/adminmain.php");

checksession();

if(isset($_GET['page'])) $page=$_GET['page'];
else $page=0;

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

$postaction="";
if(isset($_GET['postaction'])) $postaction=$_GET['postaction'];
unset($_GET['postaction']);

// print_r($_POST);
// print_r($_GET);


if(isset($_POST['cancel']))
{
	$forms = new SiteCopyrightForms($offset);
}
elseif(isset($_POST['editcopyright']))
{
	$forms = new SiteCopyrightEditForm($_POST['copyrightid']);
}
elseif(isset($_POST['changecopyright']))
{
	$holder=fixquotes(trim($_POST['holder']));
	$oldholder=getcopyrightholder($_POST['copyrightid']);
	if(!$holder)
	{
		print('<p class="highlight">Please specify a copyright holder!</p>');
		$forms = new SiteCopyrightEditForm($_POST['copyrightid'],$oldholder,$_POST['contact'],$_POST['comments'],$_POST['credit'],$_POST['permission']);
	}
	elseif(holderexists($holder) && $holder !== $oldholder)
	{
		print('<p class="highlight">Copyright Holder <i>'.$holder.'</i> already exists!</p>');
		$forms = new SiteCopyrightEditForm($_POST['copyrightid'],$oldholder,$_POST['contact'],$_POST['comments'],$_POST['credit'],$_POST['permission']);
	}
	else
	{
		updatecopyrightholder($_POST['copyrightid'],$holder,
		                      fixquotes(trim($_POST['contact'])),fixquotes(trim($_POST['comments'])),
		                      $_POST['permission'],fixquotes($_POST['credit']));
		print('<p class="highlight">Updated copyright<p>');
		$forms = new SiteCopyrightForms($offset);
	}
}
elseif(isset($_POST['addcopyrightform']))
{
	$forms = new SiteCopyrightAddForm();
}
elseif(isset($_POST['addcopyright']))
{
	$holder=fixquotes(trim($_POST['holder']));
	if(!$holder)
	{
		print('<p class="highlight">Please specify a copyright holder!</p>');
		$forms = new SiteCopyrightAddForm('',$_POST['contact'],$_POST['comments'],$_POST['credit'],$_POST['permission']);
	}
	elseif(holderexists($holder))
	{
		print('<p class="highlight">Copyright Holder <i>'.$holder.'</i> already exists!</p>');
		$forms = new SiteCopyrightAddForm($holder,$_POST['contact'], $_POST['comments'],$_POST['credit'],$_POST['permission']);
	}
	else
	{
		addcopyrightholder($holder,fixquotes(trim($_POST['contact'])),
		                   fixquotes(trim($_POST['comments'])),
		                   $_POST['permission'],fixquotes($_POST['credit']));
		print('<p class="highlight">Added copyright holder<p>');
		$forms = new SiteCopyrightForms($offset);
	}
}
elseif(isset($_POST['deletecopyrightform']))
{
	$forms = new SiteCopyrightDeleteForm($_POST['copyrightid']);
}
elseif(isset($_POST['confirmdelete']))
{
	  print('<p class="highlight">Deleted Copyright Holder <i>'.$_POST['copyrightid'].': '.getcopyrightholder($_POST['copyrightid']).'</i></p>');
	  deletecopyrightholder($_POST['copyrightid']);
	  $forms = new SiteCopyrightForms($offset);
}
elseif(($postaction=="search") || isset($_GET['search']))
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
	$forms = new SiteCopyrightForms($offset,fixquotes($holder['holder']));
}
else
{
	$forms = new SiteCopyrightForms($offset);
}

$content = new AdminMain($page,"sitecopyperm","",$forms);
print($content->toHTML());
$db->closedb();


//
//
//
function permission2html($permission)
{
	$result="Unknown";
	
	if($permission==PERMISSION_GRANTED)
		$result="&radic;";
	elseif($permission==NO_PERMISSION)
		$result="&mdash;";
	elseif($permission==PERMISSION_REFUSED)
		$result="&dagger;";
	elseif($permission==PERMISSION_IMAGESONLY)
		$result="&diams;";
	elseif($permission==PERMISSION_LINKIMAGESONLY)
		$result="&diams;&nbsp;&rarr;";
	elseif($permission==PERMISSION_LINKONLY)
		$result="&rarr;";
	elseif($permission==PERMISSION_PENDING)
		$result="&infin;";
	elseif($permission==PERMISSION_NOREPLY)
		$result="X";
	return $result;
}
?>