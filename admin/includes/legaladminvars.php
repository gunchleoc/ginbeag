<?php

// makelinkparameters($_GET,true);
// todo site
// todo separate list for admin?

$LEGALVARS = array();
$LEGALVARS["action"]=1;
$LEGALVARS["articlepage"]=1;
$LEGALVARS["articlesection"]=1;
$LEGALVARS["ascdesc"]=1;
$LEGALVARS["contents"]=1;
$LEGALVARS["copyright"]=1;
$LEGALVARS["elementtype"]=1;
$LEGALVARS["forgetful"]=1;
$LEGALVARS["image"]=1;
$LEGALVARS["imageid"]=1;
$LEGALVARS["item"]=1;
$LEGALVARS["jump"]=1;
$LEGALVARS["jumppage"]=1;
$LEGALVARS["key"]=1;
$LEGALVARS["link"]=1;
$LEGALVARS["logout"]=1;
$LEGALVARS["newsitem"]=1;
$LEGALVARS["newsitemsection"]=1;
$LEGALVARS["noofimages"]=1;
$LEGALVARS["offset"]=1;
$LEGALVARS["override"]=1;
$LEGALVARS["page"]=1;
$LEGALVARS["pageposition"]=1;
$LEGALVARS["params"]=1;
$LEGALVARS["permission"]=1;
$LEGALVARS["referrer"]=1;
$LEGALVARS["search"]=1;
$LEGALVARS["selectedcat"]=1;
$LEGALVARS["showall"]=1;
$LEGALVARS["sid"]=1;
$LEGALVARS["sitepolicy"]=1;
$LEGALVARS["source"]=1;
$LEGALVARS["sourcelink"]=1;
$LEGALVARS["subpages"]=1;
$LEGALVARS["superforgetful"]=1;
$LEGALVARS["text"]=1;
$LEGALVARS["unlock"]=1;
$LEGALVARS["user"]=1;


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
