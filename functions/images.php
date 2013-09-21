<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################

//
//
//
function imageexists($filename)
{
  $fileindb=getdbelement("image_filename",IMAGES_TABLE, "image_filename", setstring($filename));
  return strlen($filename)>0 && strcasecmp($fileindb,$filename)==0;
}

//
//
//
function thumbnailexists($thumbnailfilename)
{
  $fileindb=getdbelement("thumbnail_filename",THUMBNAILS_TABLE, "thumbnail_filename", setstring($thumbnailfilename));
  return $fileindb==$thumbnailfilename;
}

//
//
//
function hasthumbnail($imagefilename)
{
  $fileindb=getdbelement("thumbnail_filename",THUMBNAILS_TABLE, "image_filename", setstring($imagefilename));
  return strlen($fileindb)>0;
}

//
//
//
function getthumbnail($imagefilename)
{
  return getdbelement("thumbnail_filename",THUMBNAILS_TABLE, "image_filename", setstring($imagefilename));
}

//
//
//
function getallfilenames($order="",$ascdesc="")
{
  if($order)
  {
    $order=setstring($order);
    if($order=="uploader") $order="editor_id";
    elseif($order=="filename") $order="image_filename";
    return getorderedcolumn("image_filename", IMAGES_TABLE, "1", $order,setstring($ascdesc));
  }
  else
  {
    return getorderedcolumn("image_filename", IMAGES_TABLE, "1", "image_filename","ASC");
  }
}


//
//
//
function getallcaptions()
{
  return getorderedcolumn("caption",IMAGES_TABLE, "1", "image_filename","ASC");
}

//
//
//
function getsomefilenames($offset,$number)
{
  return getorderedcolumnlimit("image_filename", IMAGES_TABLE, "1", "image_filename", setinteger($offset), setinteger($number),"ASC");
}

//
//
//
function countimages()
{
  return countelements("image_filename", IMAGES_TABLE);
}


//
//
//
function getimage($filename)
{
  return getrowbykey(IMAGES_TABLE, "image_filename", setstring($filename));
}

//
//
//
function getcaption($filename)
{
  return getdbelement("caption",IMAGES_TABLE, "image_filename",setstring($filename));
}


//
//
//
function getsource($filename)
{
  return getdbelement("source",IMAGES_TABLE, "image_filename",setstring($filename));
}

//
//
//
function getsourcelink($filename)
{
  return getdbelement("sourcelink",IMAGES_TABLE, "image_filename",setstring($filename));
}


//
//
//
function getuploaddate($filename)
{
  return getdbelement("uploaddate",IMAGES_TABLE, "image_filename",setstring($filename));
}


//
//
//
function getuploader($filename)
{
  return getdbelement("editor_id",IMAGES_TABLE, "image_filename",setstring($filename));
}

//
//
//
function getimagecopyright($filename)
{
  return getdbelement("copyright",IMAGES_TABLE, "image_filename",setstring($filename));
}

//
//
//
function getimagepermission($filename)
{
  return getdbelement("permission",IMAGES_TABLE, "image_filename",setstring($filename));
}

//
//
//
function imagepermissionrefused($filename)
{
  $refused= getimagepermission($filename);
  return $refused==PERMISSION_REFUSED;
}

//
//
//
function getallsources()
{
  return getdistinctorderedcolumn("source", IMAGES_TABLE,"1", "source","ASC");
}


//
//
//
function imageisused($filename)
{
  return count(pagesforimage($filename))>0 || count(newsitemsforimage($filename))>0;
}

//
// todo: modify for each new pagetype
//
function pagesforimage($filename)
{
  $filename=setstring($filename);

  $articlesynopses=getorderedcolumn("page_id",ARTICLES_TABLE, "synopsisimage = '".$filename."'", "page_id");
  $articlesections=getorderedcolumn("article_id",ARTICLESECTIONS_TABLE, "sectionimage = '".$filename."'", "article_id");
  $galleryitems=getorderedcolumn("page_id",GALLERYITEMS_TABLE, "image_filename = '".$filename."'", "page_id");
  $linklistimages=getorderedcolumn("page_id",LINKLISTS_TABLE, "image = '".$filename."'", "page_id");
  $linkimages=getorderedcolumn("page_id",LINKS_TABLE, "image = '".$filename."'", "page_id");
  return array_merge($articlesynopses,$articlesections,$galleryitems,$linklistimages,$linkimages);
}

//
//
//
function newsitemsforimage($filename)
{
  $filename=setstring($filename);
  $synopsisimages=getorderedcolumn("newsitem_id",NEWSITEMSYNIMG_TABLE, "image_filename = '".$filename."'", "newsitem_id");
  $sectionimages=getorderedcolumn("newsitem_id",NEWSITEMSECTIONS_TABLE, "sectionimage = '".$filename."'", "newsitem_id");
  return array_merge($synopsisimages,$sectionimages);
}


//
//
//
function getpictureoftheday()
{
  $date=date("Y-m-d",strtotime('now'));
//  print($date);
  
  $potd=getdbelement("potd_filename",PICTUREOFTHEDAY_TABLE, "potd_date", $date);
  if(!hasthumbnail($potd) || !imageisused($potd) || imagepermissionrefused($potd))
  {
    $query="DELETE FROM ".PICTUREOFTHEDAY_TABLE." where potd_date= '".$date."';";
    $sql=singlequery($query);
    $potd=0;
  }
  if(!$potd)
  {
    $cats=explode(",",getproperty('Picture of the Day Categories'));

    // get all category children
    $categories=array();
    for($i=0;$i<count($cats);$i++)
    {
      $pendingcategories=array(0 => $cats[$i]);
      while(count($pendingcategories))
      {
        $selectedcat=array_pop($pendingcategories);
        array_push($categories,$selectedcat);
        $pendingcategories=array_merge($pendingcategories,getcategorychildren($selectedcat));
      }
    }
    if(count($categories)==0) $categories=array(0 => 0);
    $cats=implode("','",$categories);
    if(count($categories)>1) $cats="'".$cats."'";

    $query="select thumbs.image_filename from ";
    $query.=THUMBNAILS_TABLE." as thumbs, ";
    $query.=IMAGES_TABLE." as images, ";
    $query.=IMAGECATS_TABLE." as cats WHERE ";
    $query.=" thumbs.image_filename = cats.image_filename";
    $query.=" AND thumbs.image_filename = images.image_filename";
    $query.=" AND images.permission <> '".PERMISSION_REFUSED."'";
    $query.=" AND cats.category in(".$cats.")";

//    print($query);
    $sql=singlequery($query);
    $images=array();
    if($sql)
    {
      // get column
      while($row=mysql_fetch_row($sql))
      {
        array_push($images,$row[0]);
      }
    }

    list($usec, $sec) = explode(' ', microtime());
    $random= ((float) $sec + ((float) $usec * 100000)) % count($images);

    $potd=$images[$random];
    if($potd)
    {
      $query="insert into ";
      $query.=(PICTUREOFTHEDAY_TABLE." values(");
      $query.="'".$date."',";
      $query.="'".$potd."'";
      $query.=");";
//    print($query);
      $sql=singlequery($query);
    }
  }
  return $potd;
}

?>
