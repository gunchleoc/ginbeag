<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");


//
//
//
class SiteDBUtilsBackupForm extends Template {

	function SiteDBUtilsBackupForm()
	{
		parent::__construct();
		$this->stringvars['backupactionvars']='?page='.$this->stringvars['page'].'&postaction=backup&action=sitedb';
		$this->stringvars['cacheactionvars']='?page='.$this->stringvars['page'].'&postaction=cache&action=sitedb';
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/dbutilsbackupform.tpl");
	}
}


//
//
//
class SiteDBUtilsDBDump extends Template {

	function SiteDBUtilsDBDump($dump)
	{
		parent::__construct();
		$this->stringvars['dump']=$dump;
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/dbutilsdbdump.tpl");
	}
}


//
//
//
class SiteDBUtilsTableHeader extends Template {

	function SiteDBUtilsTableHeader($header)
	{
		parent::__construct();
		$this->stringvars['header']=$header;
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/dbutilsshowtableheader.tpl");
	}
}
?>