<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
include_once($projectroot."includes/includes.php");
include_once($projectroot."language/languages.php");

//
// Page template superclass
//
class Template {
	var $templates=array();

    // vars that are simple strings
    var $stringvars=array();

    // vars have to be of class Template
    var $vars=array();

    // arrays of vars of class Template
    var $listvars=array();
    
    // array of links to javascript files
    var $jspaths=array();
    
    // javascript to be loaded in header. contains jsids that need replacing
    var $jscripts=array();


    // overwrite this constructor
    // fill attributes, then call createTemplates
    //
    function Template($jsid="",$jspaths=array(),$jscripts=array()) {
    	global $sid, $page;
    	
    	$this->stringvars['sid']=$sid;
    	$this->stringvars['page']=$page;
    	$this->stringvars['jsid']=$jsid;
    	$this->jspaths=$jspaths;
    	$this->jscripts=$jscripts;
    	$this->createTemplates();
    }
    
    //
    // overwrite this function
    // assign templates using addTemplate
    //
    function createTemplates()
    {
    }
    
	// get array of links to javascript files
	// already prepared as string for includion in HTML header
	function getjspaths() {
		$result="";
	  
	  	for($i=0;$i<count($this->jspaths);$i++)
	  	{
	  		$result.='<script type="text/javascript" src="'.getprojectrootlinkpath().$this->jspaths[$i].'"></script>';
	  	}
	  
	  	return $result;
	}

	// get inline javascript as string to be placed in header
	// jsids have been replaced
	function getscripts() {
	  
		$result="";
	
		for($i=0;$i<count($this->jscripts);$i++)
		{
			$result.=prepareJavaScript($this->stringvars['jsid'], $this->jscripts[$i])." ";
		}
		return $result;
	}
	  
    //
    // adds a template to be parsed
    // templates have to be added in the sequence
    // that you want them concatenated
    //
    function addTemplate($filename)
    {
		$this->templates[]=$filename;
    }


    //
    // parses variables in the attribute arrays into all templates
    //
    function toHTML()
    {
		global $projectroot;
		$result="";

		// concatenate templates
		for($i=0;$i<count($this->templates);$i++)
		{
			$filename=$projectroot."templates/".getproperty("Default Template")."/".$this->templates[$i];
			if(file_exists($filename))
			{
				$result.= implode("", @file($filename));
			}
			else
			{
				$filename=$projectroot."templates/default/".$this->templates[$i];
				if(file_exists($filename))
				{
					$result.= implode("", @file($filename));
				}
				elseif(DEBUG) print('<p class="highlight">Missing template file! '.$filename.'</p>');
			}        
		}
      
		// handle switches
		$keys=array_keys($this->vars);
		$keys=array_merge($keys,array_keys($this->stringvars));
		$listkeys=array_keys($this->listvars);
		while($listkey=current($listkeys))
		{
			if(count($listkey)) $keys[]=$listkey;
			next($listkeys);
		}
      
		preg_match_all("/<!--\s*BEGIN\s*switch\s*(\w*)\s*-->/", $result, $matches);

		for($i=0;$i<count($matches[1]);$i++)
		{
			$found =array_search(strtolower($matches[1][$i]),$keys);
			$pattern="/<!--\s*BEGIN\s*switch\s*".$matches[1][$i]."\s*-->(.*)<!--\s*END\s*switch\s*".$matches[1][$i]."\s*-->/Us";
			if($found || $found === 0)
			{
				$result=preg_replace($pattern,"\\1",$result);
			}
			else
			{
				$result=preg_replace($pattern,"",$result);
			}
		}


		// parse vars
		$keys=array_keys($this->vars);
		if(count($keys))
		{
			while($key=current($keys))
			{
				// just a precaution
				if($this->vars[$key] instanceof Template)
				{
					$result=str_replace ("{".strtoupper($key)."}", $this->vars[$key]->toHTML(), $result);
				}
				next($keys);
			}
		}
		// parse listvars
		$listkeys=array_keys($this->listvars);
		for($i=0;$i<count($listkeys);$i++)
		{
			$temp="";
			$currentarray=$this->listvars[$listkeys[$i]];
			$keys=array_keys($currentarray);
			for($j=0;$j<count($keys);$j++)
			{
				// just a precaution
				if($currentarray[$keys[$j]] instanceof Template)
				{
					// concatenate from the object's own toHTML function
					$temp.=$currentarray[$keys[$j]]->toHTML();
				}
			}
			// replace with concatenated string
			$result=str_replace ("{".strtoupper($listkeys[$i])."}", $temp, $result);
		}
	
	
		// parse stringvars
		$keys=array_keys($this->stringvars);
		if(count($keys))
		{
			while($key=current($keys))
			{
				$result=str_replace ("{".strtoupper($key)."}", $this->stringvars[$key], $result);
				next($keys);
			}
		}
	      
		return $result;
	}
    
    
    //
    // $source="get", "post"
    // $excludes = array, list of variables to exclude, using the array keys
    // addvars = additional vars that are not in $_GET or $_POST
    // result = string to be added to template stringvars
    //
    function makehiddenvarsold($source="get",$excludes = array(),$addvars = array())
    {
		global $_GET, $_POST, $sid, $LEGALVARS; /// todo legalvars check fails
    	
		//print_r($LEGALVARS);
    	
		$result='<input type="hidden" name="sid" value="'.$sid.'" />';
		$excludes["sid"]= "sid";
    	
		$vars = array();
    	
		if($source=="get") $vars = $_GET;
		else $vars = $_POST;
    	
		// eliminate excluded vars    	
		$keys = array_keys($vars);
    	while($key=current($keys))
  		{
    		if(array_key_exists($key,$excludes))
    		{
    			unset($vars[$key]);
    		}
    		next($keys);
  		}    	
    	
    	// vars from get/post
    	$keys = array_keys($vars);
    	while($key=current($keys))
  		{
  			//if(!array_key_exists($key,$excludes) && array_key_exists($key,$LEGALVARS))
    		//if(!array_key_exists($key,$excludes))
    		{
      			$result.= '<input type="hidden" name="'.$key.'" value="'.$vars[$key].'" />';
    		}
    		next($keys);
  		}
  		
  		// add extra vars
    	$addkeys = array_keys($addvars);
    	while($key=current($addkeys))
  		{
  			//if(!array_key_exists($key,$vars) && array_key_exists($key,$LEGALVARS))
    		if(!array_key_exists($key,$vars))
    		{
      			$$result.= '<input type="hidden" name="'.$key.'" value="'.$addvars[$key].'" />';
    		}
    		next($addkeys);
  		}   		
  		return $result;
    }
    




    //
    // $vars must be an array. keys = varnames, values = varvalues
    // sid and page are added automatically
    // result = string to be added to template stringvars
    //
    function makehiddenvars($vars)
    {
		$result='<input type="hidden" name="sid" value="'.$this->stringvars["sid"].'" />';
		$result.='<input type="hidden" name="page" value="'.$this->stringvars["page"].'" />';
 		
  		// add extra vars
    	$keys = array_keys($vars);
    	while($key=current($keys))
  		{
    		if(!array_key_exists($key,$vars))
    		{
      			$$result.= '<input type="hidden" name="'.$key.'" value="'.$vars[$key].'" />';
    		}
    		next($keys);
  		}   		
  		return $result;
    }
    


    
    //
    // $vars must be an array. keys = varnames, values = varvalues
    // sid and page are added automatically
    // result = string to be added to template stringvars, starts with ?
    //
    function makeactionvars($vars)
    {
    	
    	$result='?sid='.$this->stringvars["sid"];
    	$result.='&page='.$this->stringvars["page"];
    	
    	$keys = array_keys($vars);
    	while($key=current($keys))
  		{
  			//if(!array_key_exists($key,$vars) && array_key_exists($key,$LEGALVARS))
    		if(!array_key_exists($key,$vars))
    		{
      			$result.= '&'.$key.'='.$vars[$key];
    		}
    		next($keys);
  		} 
  		return $result;
    }    
}

/************************* non-object functions ****************************/


//
// individualise javascripts with jsid
// needed when same javascript is inserted more than once into the same page
//
function prepareJavaScript($jsid, $scriptpath)
{
	global $projectroot;
    $result="";

    $filename=$projectroot.$scriptpath;
    if(file_exists($filename))
    {
    	$result.= implode("", @file($filename));
    }
    elseif(DEBUG) print('<p class="highlight">Missing javascript file! '.$filename.'</p>');


    // parse stringvars
    $result='<script language="JavaScript">'.str_replace ("{JSID}", $jsid, $result).'</script>';
    return $result;
}



//
// helper for testing
//
function print_vars($obj)
{
    foreach (get_object_vars($obj) as $prop => $val) {
        echo "<h1>$prop</h1>";
        print_r($val);
    }
    echo "<p>&nbsp;</p>";
}

//
// get CSS for the chosen template
//
function getCSSPath($stylesheet="")
{
	global $projectroot;
	$result=getprojectrootlinkpath()."templates/default/".$stylesheet;
    $filename=$projectroot."templates/".getproperty("Default Template")."/".$stylesheet;
    if(file_exists($filename))
    {
        $result= getprojectrootlinkpath()."templates/".getproperty("Default Template")."/".$stylesheet;
    }
    return $result;
}
?>
