<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/constants.php");
include_once($projectroot."functions/db.php");
include_once($projectroot."functions/categories.php");

//
//
//
function addcategory($parent,$name, $cattype)
{
	global $db;
	if($cattype==CATEGORY_NEWS) $table = CATEGORIES_NEWS_TABLE;
	elseif($cattype==CATEGORY_ARTICLE) $table = CATEGORIES_ARTICLES_TABLE;
	else  $table = CATEGORIES_IMAGES_TABLE;
	$values[0]=0;
	$values[1]=$db->setinteger($parent);
	$values[2]=$db->setstring($name);
	return insertentry($table,$values);
}

//
// returns false if isroot($catid)
//
function renamecategory($catid, $name, $cattype)
{
	global $db;
	if($cattype==CATEGORY_NEWS) $table = CATEGORIES_NEWS_TABLE;
	elseif($cattype==CATEGORY_ARTICLE) $table = CATEGORIES_ARTICLES_TABLE;
	else  $table = CATEGORIES_IMAGES_TABLE;
	$result=false;

	if(!isroot($catid, $cattype))
	{
		$result = updatefield($table,"name",$db->setstring($name),"category_id = '".$db->setinteger($catid)."'");
	}
	return $result;
}

//
// returns false if isroot($catid)
//
function movecategory($catid,$newparent, $cattype)
{
	global $db;
	if($cattype==CATEGORY_NEWS) $table = CATEGORIES_NEWS_TABLE;
	elseif($cattype==CATEGORY_ARTICLE) $table = CATEGORIES_ARTICLES_TABLE;
	else  $table = CATEGORIES_IMAGES_TABLE;
	$result=false;

	if(!isroot($catid, $cattype) && !isdescendant($catid,$newparent, $cattype))
	{
		$result=updatefield($table,"parent_id",$db->setinteger($newparent),"category_id = '".$db->setinteger($catid)."'");
	}
	return $result;
}

//
//
//
function isdescendant($parent,$descendant, $cattype)
{
	$result=false;
	$children=getcategorychildren($parent, $cattype);
	while(!$result && count($children))
	{
		$currentchild=array_pop($children);
		if($currentchild==$descendant)
		{
			$result=true;
		}
		else
		{
			$children=array_merge($children,getcategorychildren($currentchild, $cattype));
		}
	}
	return $result;
}

//
// removes category from elements
// if category is not a root category, replaces category with parent category
//
function deletecategory($catid, $cattype)
{
	global $db;
	$result=true;

	if($cattype==CATEGORY_NEWS)
	{
		$table = CATEGORIES_NEWS_TABLE;
		$newsitemids=getcategorynewsitems($catid);
		for($i=0;$i<count($newsitemids);$i++)
		{
			removenewsitemcategories($newsitemids[$i],array(0 => $catid));
			if(!isroot($catid, $cattype))
			{
				$result= $result & addnewsitemcategories($newsitemids[$i],array(0 => getcategoryparent($catid, $cattype)));
			}
		}
	}
	elseif($cattype==CATEGORY_ARTICLE)
	{
		$table = CATEGORIES_ARTICLES_TABLE;
		$pageids=getcategorypages($catid);
		for($i=0;$i<count($pageids);$i++)
		{
			removearticlecategories($pageids[$i],array(0 => $catid));
			if(!isroot($catid, $cattype))
			{
				$result= $result & addpagecategories($pageids[$i],array(0 => getcategoryparent($catid, $cattype)));
			}
		}
	}
	else
	{
		$table = CATEGORIES_IMAGES_TABLE;
		$imagefilenames=getcategoryimages($catid);
		$result= true;

		for($i=0;$i<count($imagefilenames);$i++)
		{
			removeimagecategories($imagefilenames[$i],array(0 => $catid));
			if(!isroot($catid, $cattype))
			{
				$result= $result & addimagecategories($imagefilenames[$i],array(0 => getcategoryparent($catid, $cattype)));
			}
		}
	}

	$result= $result & deleteentry($table,"category_id='".$db->setinteger($catid)."'");
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
		if(!isroot($categories[$i], CATEGORY_IMAGE))
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
function addpagecategories($page,$categories)
{
	global $db;
	$result = true;
	$pagecategories=getcategoriesforpage($page);
	for($i=0;$i<count($categories);$i++)
	{
		if(!isroot($categories[$i], CATEGORY_ARTICLE))
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
				$values[1]=$db->setinteger($page);
				$values[2]=$db->setinteger($categories[$i]);
				$result = $result & insertentry(ARTICLECATS_TABLE,$values);
			}
		}
	}
	return $result;
}


//
//
//
function removearticlecategories($page,$categories)
{
	global $db;
	$result = true;
	for($i=0;$i<count($categories);$i++)
	{
		$condition="page_id ='".$db->setinteger($page)."' and category ='".$db->setinteger($categories[$i])."'";
		$result = $result & deleteentry(ARTICLECATS_TABLE,$condition);
	}
	return $result;
}



//
//
//
function addnewsitemcategories($newsitem,$categories)
{
	global $db;
	$result = true;

  	$newsitemcategories=getcategoriesfornewsitem($newsitem);
  	for($i=0;$i<count($categories);$i++)
  	{
		if(!isroot($categories[$i], CATEGORY_NEWS))
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
        		$values[1]=$db->setinteger($newsitem);
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
function removenewsitemcategories($newsitem,$categories)
{
	global $db;
	$result=true;
  	for($i=0;$i<count($categories);$i++)
  	{
    	$condition="newsitem_id ='".$db->setinteger($newsitem)."' and category ='".$db->setinteger($categories[$i])."'";
    	$result = $result & deleteentry(NEWSITEMCATS_TABLE,$condition);
  	}
  	return $result;
}
?>
