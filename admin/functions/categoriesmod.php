<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/constants.php");
include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."functions/categories.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

//
//
//
function addcategory($parent,$name)
{
	global $db;
	$values[0]=0;
	$values[1]=$db->setinteger($parent);
	$values[2]=$db->setstring($name);
	return insertentry(CATEGORIES_TABLE,$values);
}

//
// returns false if isroot($catid)
//
function renamecategory($catid,$name)
{
	global $db;
	$result=false;
	
	if(!isroot($catid))
	{
		$result = updatefield(CATEGORIES_TABLE,"name",$db->setstring($name),"category_id = '".$db->setinteger($catid)."'");
	}
	return $result;
}

//
// returns false if isroot($catid)
//
function movecategory($catid,$newparent)
{
	global $db;
	$result=false;
	
	if(!isroot($catid) && !isdescendant($catid,$newparent))
	{
		$result=updatefield(CATEGORIES_TABLE,"parent_id",$db->setinteger($newparent),"category_id = '".$db->setinteger($catid)."'");
	}
	return $result;
}

//
//
//
function isdescendant($parent,$descendant)
{
	$result=false;
	$children=getcategorychildren($parent);
	while(!$result && count($children))
	{
		$currentchild=array_pop($children);
		if($currentchild==$descendant)
		{
			$result=true;
		}
		else
		{
			$children=array_merge($children,getcategorychildren($currentchild));
		}
	}
	return $result;
}

//
// removes category from elements
// if category is not a root category, replaces category with parent category
//
function deletecategory($catid)
{
	global $db;
	$imagefilenames=getcategoryimages($catid);
	$result= true;
	
	for($i=0;$i<count($imagefilenames);$i++)
	{
		removeimagecategories($imagefilenames[$i],array(0 => $catid));
		if(!isroot($catid))
		{
			$result= $result & addimagecategories($imagefilenames[$i],array(0 => getcategoryparent($catid)));
		}
	}
	
	$pageids=getcategorypages($catid);
	for($i=0;$i<count($pageids);$i++)
	{
		removepagecategories($pageids[$i],array(0 => $catid));
		if(!isroot($catid))
		{
			$result= $result & addpagecategories($pageids[$i],array(0 => getcategoryparent($catid)));
		}
	}
		
	$newsitemids=getcategorynewsitems($catid);
	for($i=0;$i<count($newsitemids);$i++)
	{
		removenewsitemcategories($newsitemids[$i],array(0 => $catid));
		if(!isroot($catid))
		{
			$result= $result & addnewsitemcategories($newsitemids[$i],array(0 => getcategoryparent($catid)));
		}
	}
	
	$result= $result & deleteentry(CATEGORIES_TABLE,"category_id='".$db->setinteger($catid)."'");
	return $result;
}


//
//
//
function addimagecategories($filename,$categories)
{
	global $db;
	$result=true;
  	$imagecategories=getcategoriesforimage($filename);
  	for($i=0;$i<count($categories);$i++)
  	{
    	if(!isroot($categories[$i]))
    	{
      		$addcategory=true;
      		for($j=0;$addcategory && $j<count($imagecategories);$j++)
      		{
        		if($imagecategories[$j]==$categories[$i])
        		{
          			$addcategory=false;
        		}
      		}
      		if($addcategory)
      		{
        		$values[0]=0;
        		$values[1]=$db->setstring($filename);
        		$values[2]=$db->setinteger($categories[$i]);
        		$test = insertentry(IMAGECATS_TABLE,$values);
        		$result = $result & ($test>0);
      		}
    	}
  	}
  	return $result;
}


//
//
//
function removeimagecategories($filename,$categories)
{
	global $db;
	$result=true;
	for($i=0;$i<count($categories);$i++)
	{
    	$condition="image_filename ='".$db->setstring($filename)."' and category ='".$db->setinteger($categories[$i])."'";
    	$result = $result & deleteentry(IMAGECATS_TABLE,$condition);
  	}
  	return $result;
}


//
//
//
function addpagecategories($page_id,$categories)
{
	global $db;
	$result = true;
	$pagecategories=getcategoriesforpage($page_id);
	for($i=0;$i<count($categories);$i++)
	{
		if(!isroot($categories[$i]))
		{
			$addcategory=true;
			for($j=0;$addcategory && $j<count($pagecategories);$j++)
			{
				if($pagecategories[$j]==$categories[$i])
				{
					$addcategory=false;
				}
			}
			if($addcategory)
			{
				$values[0]=0;
				$values[1]=$db->setinteger($page_id);
				$values[2]=$db->setinteger($categories[$i]);
				$result = $result & insertentry(PAGECATS_TABLE,$values);
			}
		}
	}
	return $result;
}


//
//
//
function removepagecategories($page_id,$categories)
{
	global $db;
	$result = true;
	for($i=0;$i<count($categories);$i++)
	{
		$condition="page_id ='".$db->setinteger($page_id)."' and category ='".$db->setinteger($categories[$i])."'";
		$result = $result & deleteentry(PAGECATS_TABLE,$condition);
	}
	return $result;
}


//
//
//
function addnewsitemcategories($newsitem_id,$categories)
{
	global $db;
	$result=true;
  	$newsitemcategories=getcategoriesfornewsitem($newsitem_id);
  	for($i=0;$i<count($categories);$i++)
  	{
    	if(!isroot($categories[$i]))
    	{
      		$addcategory=true;
      		for($j=0;$addcategory && $j<count($newsitemcategories);$j++)
      		{
        		if($newsitemcategories[$j]==$categories[$i])
        		{
          			$addcategory=false;
        		}
      		}
      		if($addcategory)
      		{
        		$values[0]=0;
        		$values[1]=$db->setinteger($newsitem_id);
        		$values[2]=$db->setinteger($categories[$i]);
        		$temp = insertentry(NEWSITEMCATS_TABLE,$values);
        		$result = $result & ($temp>0);
      		}
    	}
  	}
  	return $result;
}


//
//
//
function removenewsitemcategories($newsitem_id,$categories)
{
	global $db;
	$result=true;
  	for($i=0;$i<count($categories);$i++)
  	{
    	$condition="newsitem_id ='".$db->setinteger($newsitem_id)."' and category ='".$db->setinteger($categories[$i])."'";
    	$result = $result & deleteentry(NEWSITEMCATS_TABLE,$condition);
  	}
  	return $result;
}
?>
