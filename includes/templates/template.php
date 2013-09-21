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

    // arrays of String vars - do we need these?
//    var $liststringvars=array();
    
    // arrays of vars of class Template
    var $listvars=array();

    //
    // overwrite this constructor
    // fill attributes, then call createTemplates
    //
    function Template() {
    }
    
    //
    // overwrite this function
    // assign templates using addTemplate
    //
    function createTemplates()
    {
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
        $filename=$projectroot."templates/".$this->templates[$i];
        if(file_exists($filename))
        {
//        print('<br />'.$projectroot."templates/".$this->templates[$i]);
          $result.= implode("", @file($filename));
        }
        elseif(DEBUG) print('<p class="highlight">Missing template file! '.$filename.'</p>');
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
//          print('<br />key '.$key);
//          print(" ".get_class($this->vars[$key]));
          // just a precaution
          if(is_subclass_of ($this->vars[$key],"Template"))
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
          if(is_subclass_of ($currentarray[$keys[$j]],"Template"))
          {
//            print('<br />listkey '.$listkeys[$i]);
//            print(" ".get_class($currentarray[$keys[$j]]));
            // concatenate from the object's own toHTML function
            $temp.=$currentarray[$keys[$j]]->toHTML();
          }
        }
//        print("<br />concatenated listvars".$temp);
//        print("<br />result was".$result);
        // replace with concatenated string
        $result=str_replace ("{".strtoupper($listkeys[$i])."}", $temp, $result);
//        print("<br />result is".$result);
      }


      // parse stringvars
      $keys=array_keys($this->stringvars);
      if(count($keys))
      {
        while($key=current($keys))
        {
//          print('<br />stringkey '.$key);
//          print(" ".$this->stringvars[$key]);
          $result=str_replace ("{".strtoupper($key)."}", $this->stringvars[$key], $result);
          next($keys);
        }
      }
      
      // parse liststringvars
/*      $listkeys=array_keys($this->liststringvars);
      for($i=0;$i<count($listkeys);$i++)
      {
        $temp="";
        $currentarray=$this->liststringvars[$listkeys[$i]];
        $keys=array_keys($currentarray);
        for($j=0;$j<count($keys);$j++)
        {
//            print('<br />liststringvars '.$liststringvars[$i]);
//            print(" ".get_class($currentarray[$keys[$j]]));
            // concatenate from the object's own toHTML function
          $temp.=$currentarray[$keys[$j]];
        }
//        print("<br />concatenated listvars".$temp);
//        print("<br />result was".$result);
        // replace with concatenated string
        $result=str_replace ("{".strtoupper($listkeys[$i])."}", $temp, $result);
//        print("<br />result is".$result);
      }*/

      return $result;
    }
    
    
    //
    // $source="get", "post"
    // $excludes = array, list of variables to exclude, using the array keys
    // addvars = additional vars that are not in $_GET or $_POST
    // result = string to be added to template stringvars
    //
    function makehiddenvars($source="get",$excludes = array(),$addvars = array())
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
    // $source="get", "post"
    // $excludes = array, list of variables to exclude, using the array keys
    // addvars = additional vars that are not in $_GET or $_POST
    // result = string to be added to template stringvars, starts with ?
    //
    function makeactionvars($source="get",$excludes = array(),$addvars = array())
    {
    	global $_GET, $_POST, $sid, $LEGALVARS;  /// todo legalvars check fails
    	
    	//print_r($LEGALVARS);
    	
    	$result='?sid='.$sid;
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
      			$result.= '&'.$key.'='.$vars[$key];
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
      			$result.= '&'.$key.'='.$addvars[$key];
    		}
    		next($addkeys);
  		} 
  		 		
  		return $result;
    }    
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
?>
