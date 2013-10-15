<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."admin/functions/sitestats.php");
include_once($projectroot."includes/objects/elements.php");
include_once($projectroot."includes/objects/forms.php");



//
//
//
class SiteStatsTable extends Template {

	function SiteStatsTable($count,$year, $month,$timespan="month")
	{
		parent::__construct();
		
		if($timespan=="month")
		{
			$this->stringvars['month']=$month;
			$stats=getmonthlypagestats($count,$year,$month);
		}
		else
		{
			$this->stringvars['month']="";
			$stats=getyearlypagestats($count,$year);
		}
		$this->stringvars['year']=$year;
		$this->stringvars['count']=$count;
		
		
		$this->vars["month_selection"]=new MonthOptionForm($month,true,"","month","Month");
		$this->vars["month_year_selection"]=new YearOptionForm($year,getstatsfirstyear(),date("Y",strtotime('now')),"","month_year","Year");
		$this->stringvars["count_selection"]= $count;
		
		$this->vars["year_year_selection"]=new YearOptionForm($year,getstatsfirstyear(),date("Y",strtotime('now')),"","year_year","Year");
	
		//print_r($stats);
		
		if(count($stats))
		{
		    for($i=0;$i<count($stats);$i++)
			{
		  		$this->listvars['stats'][]=new SiteStatsEntry($stats[$i], $i+1);
		  	}
		}
		else
		{
			$this->stringvars['nostats']="Stats not available";
		}
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/statstable.tpl");
	}
}


//
//
//
class SiteStatsEntry extends Template {

	function SiteStatsEntry($stats,$rank)
	{
		parent::__construct();
		
		$this->stringvars['rank']=$rank;
		$this->stringvars['views']=number_format($stats[1]);
		$this->stringvars['pagetype']=getpagetype($stats[0]);
		$this->stringvars['page']=$stats[0];
		$this->stringvars['pagetitle']=text2html(getpagetitle($stats[0]));
		$this->stringvars['url']=getprojectrootlinkpath()."admin/admin.php?page=".$stats[0];
	}
	
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("admin/site/statsentry.tpl");
	}
}

?>