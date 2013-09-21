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
  $values[0]=0;
  $values[1]=setinteger($parent);
  $values[2]=setstring($name);
  return insertentry(CATEGORIES_TABLE,$values);
}

//
// returns false if isroot($catid)
//
function renamecategory($catid,$name)
{
  $result=true;
  
  if(isroot($catid))
  {
    $result=false;
  }
  else
  {
    updatefield(CATEGORIES_TABLE,"name",setstring($name),"category_id = '".setinteger($catid)."'");
  }
  return $result;
}

//
// returns false if isroot($catid)
//
function movecategory($catid,$newparent)
{
  $result=true;

  if(isroot($catid))
  {
    $result=false;
  }
  elseif(!isdescendant($catid,$newparent))
  {
    $result=updatefield(CATEGORIES_TABLE,"parent_id",setinteger($newparent),"category_id = '".setinteger($catid)."'");
  }
  else
  {
    $result=false;
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
  $imagefilenames=getcategoryimages($catid);
  
  for($i=0;$i<count($imagefilenames);$i++)
  {
    removeimagecategories($imagefilenames[$i],array(0 => $catid));
    if(!isroot($catid))
    {
      addimagecategories($imagefilenames[$i],array(0 => getcategoryparent($catid)));
    }
  }
  
  $pageids=getcategorypages($catid);
  for($i=0;$i<count($pageids);$i++)
  {
    removepagecategories($pageids[$i],array(0 => $catid));
    if(!isroot($catid))
    {
      addpagecategories($pageids[$i],array(0 => getcategoryparent($catid)));
    }
  }
  
  $newsitemids=getcategorynewsitems($catid);
  for($i=0;$i<count($newsitemids);$i++)
  {
    removenewsitemcategories($newsitemids[$i],array(0 => $catid));
    if(!isroot($catid))
    {
      addnewsitemcategories($newsitemids[$i],array(0 => getcategoryparent($catid)));
    }
  }

  deleteentry(CATEGORIES_TABLE,"category_id='".setinteger($catid)."'");
}


//
//
//
function addimagecategories($filename,$categories)
{
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
        $values[1]=setstring($filename);
        $values[2]=setinteger($categories[$i]);
        insertentry(IMAGECATS_TABLE,$values);
      }
    }
  }
}


//
//
//
function removeimagecategories($filename,$categories)
{
  for($i=0;$i<count($categories);$i++)
  {
    $condition="image_filename ='".setstring($filename)."' and category ='".setinteger($categories[$i])."'";
    deleteentry(IMAGECATS_TABLE,$condition);
  }
}


//
//
//
function addpagecategories($page_id,$categories)
{
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
        $values[1]=setinteger($page_id);
        $values[2]=setinteger($categories[$i]);
        insertentry(PAGECATS_TABLE,$values);
      }
    }
  }
}


//
//
//
function removepagecategories($page_id,$categories)
{
  for($i=0;$i<count($categories);$i++)
  {
    $condition="page_id ='".setinteger($page_id)."' and category ='".setinteger($categories[$i])."'";
    deleteentry(PAGECATS_TABLE,$condition);
  }
}


//
//
//
function addnewsitemcategories($newsitem_id,$categories)
{
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
        $values[1]=setinteger($newsitem_id);
        $values[2]=setinteger($categories[$i]);
        insertentry(NEWSITEMCATS_TABLE,$values);
      }
    }
  }
}


//
//
//
function removenewsitemcategories($newsitem_id,$categories)
{
  for($i=0;$i<count($categories);$i++)
  {
    $condition="newsitem_id ='".setinteger($newsitem_id)."' and category ='".setinteger($categories[$i])."'";
    deleteentry(NEWSITEMCATS_TABLE,$condition);
  }
}
?>
