<?php
// checks if an action variable of a HTTP request is a site action
function issiteaction($action)
{
	$result=false;
	$actions = array();
	$actions["site"]=1;
	$actions["sitestats"]=1;
	$actions["sitereferrers"]=1;
	$actions["sitepagetype"]=1;
	$actions["sitepagerestrict"]=1;
	$actions["sitelayout"]=1;
	$actions["siteiotd"]=1;
	$actions["sitespam"]=1;
	$actions["siteguest"]=1;
	$actions["sitepolicy"]=1;
	$actions["sitebanner"]=1;
	$actions["sitetech"]=1;
	$actions["sitedb"]=1;
	$actions["siteind"]=1;
	$actions["siteuserman"]=1;
	$actions["siteuserperm"]=1;
	$actions["siteuserlist"]=1;
	$actions["siteusercreate"]=1;
	$actions["siteipban"]=1;
	$actions["siteonline"]=1;

    if(array_key_exists($action,$actions)) $result=true;

	return $result;
}
?>
