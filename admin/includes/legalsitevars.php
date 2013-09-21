<?php
$LEGALVARS = array();
$LEGALVARS["action"]=1;
$LEGALVARS["ascdesc"]=1;
$LEGALVARS["backup"]=1;
$LEGALVARS["bannerid"]=1;
$LEGALVARS["changeaccess"]=1;
$LEGALVARS["changelevel"]=1;
$LEGALVARS["clearpagecache"]=1;
$LEGALVARS["contact"]=1;
$LEGALVARS["display"]=1;
$LEGALVARS["filterpermission"]=1;
$LEGALVARS["generate"]=1;
$LEGALVARS["holder"]=1;
$LEGALVARS["offset"]=1;
$LEGALVARS["order"]=1;
$LEGALVARS["profile"]=1;
$LEGALVARS["ref"]=1;
$LEGALVARS["search"]=1;
$LEGALVARS["sid"]=1;
$LEGALVARS["structure"]=1;
$LEGALVARS["type"]=1;
$LEGALVARS["userid"]=1;


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
