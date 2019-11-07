<?php
/**
 * An Gineadair Beag is a content management system to run websites with.
 *
 * PHP Version 7
 *
 * Copyright (C) 2005-2019 GunChleoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */

/**
 * This file contains the superclass for all objects that generate screen output
 */

$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
require_once $projectroot."includes/includes.php";
require_once $projectroot."language/languages.php";

/**
 * Page template superclass
 *
 * Inherit from this class fo all objects that output HTML to the screen.
 * Data ist stored in special arrays ($stringvars, $vars, $listvars).
 * The placeholder variables in the corresponding .tpl file are then replaced by the
 * contents of these arrays.
 *
 * $stringvars contains strings
 * $vars contains individual Template objects
 * $listvars contains arrays of Template objects
 *
 * To create a template object, call this constructor first (parent::__construct();)
 * in its constructor, then add your data into the special arrays.
 *
 * Make sure to overwrite createTemplates() as well.
 */
class Template
{
    /**
     * A list of .tpl files used to generate the HTML representation of the object
     */
    var $templates=array();


    /**
     * Content variables that are simple strings
     */
    var $stringvars=array();

    /**
     * Content variables of class Template
     */
    var $vars=array();

    /**
     * Arrays of content variables of class Template
     */
    var $listvars=array();

    /**
     * Links to javascript library files
     */
    var $jspaths=array();

    /**
     * Javascript to be loaded inline. Contains {JSID}s that need replacing
     */
    var $jscripts=array();


    /**
     * Constructor for the template superclass
     *
     * To create a template object, call this constructor first (parent::__construct();)
     * in its constructor, then add your data into the special arrays ($stringvars, $vars, $listvars).
     *
     * @param string $jsid     A string added to HTML elements' IDs in the .tpl file, for uniquely
     *                         referencing multiple elements with the same name. Will be inserted
     *                         for each {JSID} in the .tpl file.
     *
     * @param array  $jspaths  A list of javascript libraries to link in the HTML
     *
     * @param array  $jscripts A list of javascript functions to include inline in the HTML. These will be run through the parser.
     *
     * @return void
     */
    function __construct($jsid="",$jspaths=array(),$jscripts=array())
    {
        global $sid, $page;

        $this->stringvars['sid']=$sid;
        $this->stringvars['page']=$page;
        $this->stringvars['jsid']=$jsid;
        $this->jspaths=$jspaths;
        $this->jscripts=$jscripts;
        $this->createTemplates();
    }


    /**
     * Overwrite this function to add .tpl file to represent your object as HTML
     *
     * Call $this->addTemplate("<filename>.tpl"); in this function
     *
     * @return void
     */
    function createTemplates()
    {
    }


    /**
     * Get an array of links to javascript files already prepared as string for includion in HTML header
     *
     * @return string HTML with <script> tags to link to the $jspaths
     */
    function getjspaths()
    {
        $result="";

        for($i=0;$i<count($this->jspaths);$i++)
        {
            $result.='<script type="text/javascript" src="'.getprojectrootlinkpath().$this->jspaths[$i].'"></script>';
        }

        return $result;
    }


    /**
     * Get inline javascript as string to be placed in header. {JSID}s have been replaced.
     *
     * Calls prepareJavaScript($jsid, $scriptpath) for all $jscripts
     *
     * @return string HTML with <script> tags and inline javascript that has the {JSID} replaced
     */
    function getScripts()
    {
        $result = "&nbsp;".$this->prepareJavaScript("admin/includes/javascript/messageboxes.js");

        for($i=0;$i<count($this->jscripts);$i++)
        {
            $result .= $this->prepareJavaScript($this->jscripts[$i])." ";
        }
        return $result;
    }


    /**
     * Individualise javascripts with {JSID}s. Needed when the same javascript is inserted more than once into the same page.
     *
     * @return string HTML with <script> tags and inline javascript that has the {JSID} replaced
     */
    function prepareJavaScript($scriptpath)
    {
        global $projectroot;
        $result="";

        $filename=$projectroot.$scriptpath;
        if(file_exists($filename)) {
            $result.= implode("", @file($filename));
        }
        elseif(DEBUG) { print('<p class="highlight">Missing javascript file! '.$scriptpath.'</p>');
        }

        // replace JSIDs
        $result = '<script language="JavaScript">'.str_replace("{JSID}", $this->stringvars["jsid"], $result).'</script>';
        return $result;
    }


    /**
     * Adds a .tpl template to be parsed and added to the HTML output
     *
     * You can call this function multiple times.
     * Templates have to be added in the sequence that you want them concatenated.
     *
     * @param string $filename The filename of the .tpl file to be added
     *
     * @return void
     */
    function addTemplate($filename)
    {
        $this->templates[]=$filename;
    }


    /**
     * Parses variables in the attribute arrays into all .tpl templates
     *
     * Data ist stored in special arrays ($stringvars, $vars, $listvars).
     * The placeholder variables in the corresponding .tpl file are replaced by the
     * contents of these arrays by this function.
     *
     * @return string the HTML representation of this object
     */
    function toHTML()
    {
        global $projectroot;

        $result="";

        $default_template = getproperty("Default Template");

        $class = get_class($this);
        $is_main_template = in_array($class, array("RSSPage", "Page", "PageHeader"));

        if (DEBUG) {
            if (!$is_main_template) {
                $result .= "\n<!-- " . $class . " -->\n";
                $debug_vars = get_class_vars($class);
                foreach ($debug_vars as $name => $value) {
                    if (!empty($value)) {
                        if (is_array($value)) {
                            $result .= "\n\t\t<!-- Var $name: " . count($value) ." item(s) -->\n";
                            foreach ($value as $subname => $subvalue) {
                                if (is_array($subvalue)) {
                                    $result .= "\n\t\t<!-- $subname: " . count($subvalue) ." item(s) -->\n";
                                } else {
                                    $result .= "\n\t\t<!-- $subname: $subvalue -->\n";
                                }
                            }
                        } else {
                            $result .= "\n\t<!-- Var $name: $value -->\n";
                        }
                    }
                }
            }
        }

        // concatenate templates
        for ($i=0; $i<count($this->templates); $i++) {
            $filename = $projectroot . "templates/" . $default_template . "/" . $this->templates[$i];
            if (file_exists($filename)) {
                $result .= implode("", @file($filename));
            } else {
                $filename = $projectroot . "templates/default/" . $this->templates[$i];
                if (file_exists($filename)) {
                    $result .= implode("", @file($filename));
                } elseif (DEBUG) {
                    print('<p class="highlight">Missing template file! ' . $this->templates[$i] . '</p>');
                }
            }
        }

        // handle switches
        $keys=array_keys($this->vars);
        $keys=array_merge($keys, array_keys($this->stringvars));
        $listkeys=array_keys($this->listvars);
        while($listkey=current($listkeys))
        {
            if(!empty($listkey)) {
                $keys[]=$listkey;
            }
            next($listkeys);
        }

        preg_match_all("/<!--\s*BEGIN\s*switch\s*(\w*)\s*-->/", $result, $matches);

        for($i=0;$i<count($matches[1]);$i++)
        {
            $found =array_search(strtolower($matches[1][$i]), $keys);
            $pattern="/<!--\s*BEGIN\s*switch\s*".$matches[1][$i]."\s*-->(.*)<!--\s*END\s*switch\s*".$matches[1][$i]."\s*-->/Us";
            if($found || $found === 0) {
                $result=preg_replace($pattern, "\\1", $result);
            }
            else
            {
                $result=preg_replace($pattern, "", $result);
            }
        }

        // parse vars
        $keys=array_keys($this->vars);
        if(count($keys)) {
            while($key=current($keys))
            {
                // just a precaution
                if($this->vars[$key] instanceof Template) {
                    $result=str_replace("{".strtoupper($key)."}", $this->vars[$key]->toHTML(), $result);
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
                if($currentarray[$keys[$j]] instanceof Template) {
                    // concatenate from the object's own toHTML function
                    $temp.=$currentarray[$keys[$j]]->toHTML();
                }
            }
            // replace with concatenated string
            $result=str_replace("{".strtoupper($listkeys[$i])."}", $temp, $result);
        }


        // parse stringvars
        $keys=array_keys($this->stringvars);
        if(count($keys)) {
            while($key=current($keys))
            {
                $result=str_replace("{".strtoupper($key)."}", $this->stringvars[$key], $result);
                next($keys);
            }
        }

        // Trim superfluous whitespace
        if ($is_main_template) {
            $result=preg_replace("/\n\h*\n/Us", "\n", $result);
        }

        return $result;
    }



    //
    // $vars must be an array. keys = varnames, values = varvalues
    // sid and page are added automatically
    // result = string to be added to template stringvars
    //
    function makehiddenvars($vars = array())
    {
        $result= "";
        if(strlen($this->stringvars["sid"]) > 0) {
            $result .= '<input type="hidden" id="'.$this->stringvars["jsid"].'sid" name="sid" value="'.$this->stringvars["sid"].'" />';
        }
        if(strlen($this->stringvars["page"]) > 0 && $this->stringvars["page"] > 0) {
            $result .= '<input type="hidden" id="'.$this->stringvars["jsid"].'page" name="page" value="'.$this->stringvars["page"].'" />';
        }

        // add extra vars
        $keys = array_keys($vars);
        while($key=current($keys))
        {
            if(strlen($vars[$key]) > 0) {
                $result.= '<input type="hidden" id="'.$this->stringvars["jsid"].$key.'" name="'.$key.'" value="'.$vars[$key].'" />';
            }
            next($keys);
        }
        return $result;
    }
}

/*************************
 * non-object functions
 ****************************/

/**
 * Helper function for testing
 *
 * @return string with all vars of this object
 */
function print_vars($obj)
{
    foreach (get_object_vars($obj) as $prop => $val)
    {
        echo "<h1>$prop</h1>";
        print_r($val);
    }
    echo "<p>&nbsp;</p>";
}


/**
 * Gets the weblink for a stylesheet.
 *
 * Reverts to default style if file is not available for the template chosen by the user in the site layout.
 *
 * @param string $stylesheet A filename for the stylesheet. e.g. "main.css"
 *
 * @return string wih the weblink for the stylesheet
 */
function getCSSPath($stylesheet="")
{
    global $projectroot;
    $result=getprojectrootlinkpath()."templates/default/".$stylesheet;
    $filename=$projectroot."templates/".getproperty("Default Template")."/".$stylesheet;
    if(file_exists($filename)) {
        $result= getprojectrootlinkpath()."templates/".getproperty("Default Template")."/".$stylesheet;
    }
    return $result;
}
?>
