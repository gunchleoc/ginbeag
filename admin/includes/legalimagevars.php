<?php
$LEGALVARS = array();
$LEGALVARS["addthumb"]=1;
$LEGALVARS["addunknownfile"]=1;
$LEGALVARS["action"]=1;
$LEGALVARS["ascdesc"]=1;
$LEGALVARS["caption"]=1;
$LEGALVARS["categoriesblank"]=1;
$LEGALVARS["clear"]=1;
$LEGALVARS["copyright"]=1;
$LEGALVARS["copyrightblank"]=1;
$LEGALVARS["createthumbnail"]=1;
$LEGALVARS["delete"]=1;
$LEGALVARS["deletefile"]=1;
$LEGALVARS["deletethumb"]=1;
$LEGALVARS["dontcreatethumbnail"]=1;
$LEGALVARS["executethumbnaildelete"]=1;
$LEGALVARS["filename"]=1;
$LEGALVARS["filter"]=1;
$LEGALVARS["image"]=1;
$LEGALVARS["missing"]=1;
$LEGALVARS["missingthumb"]=1;
$LEGALVARS["nodelete"]=1;
$LEGALVARS["noofimages"]=1;
$LEGALVARS["nothumb"]=1;
$LEGALVARS["number"]=1;
$LEGALVARS["offset"]=1;
$LEGALVARS["order"]=1;
$LEGALVARS["page"]=1;
$LEGALVARS["permission"]=1;
$LEGALVARS["replaceimage"]=1;
$LEGALVARS["replacethumb"]=1;
$LEGALVARS["resizeimage"]=1;
$LEGALVARS["s_caption"]=1;
$LEGALVARS["s_categoriesblank"]=1;
$LEGALVARS["s_copyright"]=1;
$LEGALVARS["s_copyrightblank"]=1;
$LEGALVARS["s_filename"]=1;
$LEGALVARS["s_missing"]=1;
$LEGALVARS["s_missingthumb"]=1;
$LEGALVARS["s_nothumb"]=1;
$LEGALVARS["s_selectedcat"]=1;
$LEGALVARS["s_source"]=1;
$LEGALVARS["s_sourceblank"]=1;
$LEGALVARS["s_unknown"]=1;
$LEGALVARS["s_unused"]=1;
$LEGALVARS["s_uploader"]=1;
$LEGALVARS["selectedcat"]=1;
$LEGALVARS["sid"]=1;
$LEGALVARS["source"]=1;
$LEGALVARS["sourceblank"]=1;
$LEGALVARS["sourcelink"]=1;
$LEGALVARS["unknown"]=1;
$LEGALVARS["unused"]=1;
$LEGALVARS["uploader"]=1;
$LEGALVARS["doorder"]=1;

$getkeys=array_keys($_GET);

while($key=current($getkeys))
{
    if(!array_key_exists($key,$LEGALVARS))
    {
	    header("HTTP/1.0 404 Not Found");
		print("HTTP 404: Sorry, but this page does not exist.");
		if(DEBUG) print("<br />'".$key."' not registered with legalimagevars.");
		exit;
	}
	next($getkeys);
}

?>
