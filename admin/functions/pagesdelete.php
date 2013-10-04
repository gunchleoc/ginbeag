<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/pagecontent/newspagesmod.php");
//include_once($projectroot."admin/functions/sessions.php");
//include_once($projectroot."functions/users.php");
//include_once($projectroot."functions/pages.php");

//
// todo: reorganize position_navigator with page locking
// todo: return error state when needed
//
function deletepage($page_id, $sid)
{
	global $db;
	$page_id=$db->setinteger($page_id);
  	$sid=$db->setstring($sid);
  

//  print("hallo delete");
    $pagestosearch[0]=$page_id;
    $deleteids=array();

    while(count($pagestosearch))
    {
		$currentpage=array_pop($pagestosearch);
		array_push($deleteids,$currentpage);
		$pageids=getorderedcolumn("page_id",PAGES_TABLE, "parent_id='".$currentpage."'", "position_navigator", "ASC");
		for($i=0;$i<count($pageids);$i++)
		{
			array_push($pagestosearch,$pageids[$i]);
		}
    }
    for($i=0;$i<count($deleteids);$i++)
    {
		$pagetype=getpagetype($deleteids[$i]);
		deleteentry(PAGES_TABLE,"page_id='".$deleteids[$i]."'");
		deleteentry(RESTRICTEDPAGES_TABLE,"page_id ='".$db->setinteger($deleteids[$i])."'");
		deleteentry(RESTRICTEDPAGESACCESS_TABLE,"page_id ='".$db->setinteger($deleteids[$i])."'");
		removerssfeed($deleteids[$i]);
		deleteentry(PAGECACHE_TABLE,"page_id='".$deleteids[$i]."'");
		
		if($pagetype==="article")
		{
			deleteentry(PAGECATS_TABLE,"page_id ='".$deleteids[$i]."'");
			deleteentry(ARTICLESECTIONS_TABLE,"article_id='".$deleteids[$i]."'");
			deleteentry(ARTICLES_TABLE,"page_id='".$deleteids[$i]."'");
		}
		elseif($pagetype==="external")
		{
			deleteentry(EXTERNALS_TABLE,"page_id='".$deleteids[$i]."'");
		}
		elseif($pagetype==="gallery")
		{
			deleteentry(GALLERYITEMS_TABLE,"page_id='".$deleteids[$i]."'");
		}
		elseif($pagetype==="linklist")
		{
			deleteentry(LINKS_TABLE,"page_id='".$deleteids[$i]."'");
		}
		elseif($pagetype==="menu" ||$pagetype==="articlemenu" || $pagetype==="linklistmenu" )
		{
			deleteentry(MENUS_TABLE,"page_id='".$deleteids[$i]."'");
		}
		elseif($pagetype==="news")
		{
			deleteentry(NEWS_TABLE,"page_id='".$deleteids[$i]."'");
			$newsitems=getcolumn("newsitem_id",NEWSITEMS_TABLE,"page_id='".$deleteids[$i]."'");
			for($j=0;$j<count($newsitems);$j++)
			{
				deleteentry(NEWSITEMSECTIONS_TABLE,"newsitem_id='".$newsitems[$j]."'");
				deleteentry(NEWSITEMSYNIMG_TABLE,"newsitem_id='".$newsitems[$j]."'");
				deleteentry(NEWSITEMCATS_TABLE,"newsitem_id='".$newsitems[$j]."'");
			}
			deleteentry(NEWSITEMS_TABLE,"page_id='".$deleteids[$i]."'");
		}
	}
	rebuildaccessrestrictionindex();
	return count($deleteids);
}
?>