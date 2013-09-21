<?php

// makelinkparameters($_GET,true);
// todo site
// todo separate list for admin?

$LEGALVARS = array();
$LEGALVARS["action"]=1;
$LEGALVARS["ascdesc"]=1;
$LEGALVARS["caption"]=1;
$LEGALVARS["categoriesblank"]=1;
$LEGALVARS["clear"]=1;
$LEGALVARS["copyright"]=1;
$LEGALVARS["copyrightblank"]=1;
$LEGALVARS["filename"]=1;
$LEGALVARS["filter"]=1;
$LEGALVARS["image"]=1;
$LEGALVARS["missing"]=1;
$LEGALVARS["missingthumb"]=1;
$LEGALVARS["noofimages"]=1;
$LEGALVARS["nothumb"]=1;
$LEGALVARS["number"]=1;
$LEGALVARS["offset"]=1;
$LEGALVARS["order"]=1;
$LEGALVARS["page"]=1;
$LEGALVARS["permission"]=1;
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
		exit;
	}
	next($getkeys);
}


?>
