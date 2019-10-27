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

$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));

require_once $projectroot."language/languages.php";
require_once $projectroot."functions/categories.php";
require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."includes/includes.php";

//
// Templating for a ascending/descending selection form
//
class JumpToPageForm  extends Template
{

    function __construct($file="",$params=array(),$align="right", $target="")
    {
        parent::__construct();

        if(ismobile()) { $params["m"] = "on";
        }

        $attributes="";
        if($file) { $attributes.=' action="'.$file.'"';
        }
        if($target) { $attributes.=' target="'.$target.'"';
        }
        $this->stringvars['attributes']=$attributes;

        $this->stringvars['fields'] = $this->makehiddenvars($params);

        $this->stringvars['align']=$align;
        $this->stringvars['l_jumptopage']=getlang("pagemenu_jumptopage");
        $this->stringvars['l_go']=getlang("pagemenu_go");
    }

    function createTemplates()
    {
        $this->addTemplate("forms/jumptopageform.tpl");
    }
}


//
//
//
class PageMenu extends Template
{

    function __construct($offset, $number, $last, $params = array())
    {
        parent::__construct();
        if(ismobile()) { $params["m"] = "on";
        }
        $this->stringvars['pagemenu']=$this->makelinks($offset, $number, $last, $params, $this->stringvars['page']);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("forms/pagemenu.tpl");
    }

    function makelinks($offset, $number, $last, $params = array())
    {
        $result="";

        if($this->stringvars['page'] > 0) { $params["page"] = $this->stringvars['page'];
        }

        if(!$number>0) { $number=1;
        }

        $next=$offset+$number;
        $previous=$offset-$number;
        if($previous<0) { $previous=0;
        }
        $last=$number*(ceil($last/$number)-1);

        if($last>0) {
            $result.=getlang("pagemenu_goto");

            if($offset>0) {
                // "Previous"
                $params["offset"] = $previous;
                $result.='<a href="'.makelinkparameters($params).'" class="buttonlink">'.getlang("pagemenu_previous").'</a> ';
            }
            if($offset) {
                if($previous) {
                    // First page number
                    $params["offset"] = 0;
                    $result.='<a href="'.makelinkparameters($params).'" class="buttonlink">1</a> ';
                    if(($previous-$number)>0) { $result.='... ';
                    }
                }
                // previous number
                $params["offset"] = $previous;
                $result.='<a href="'.makelinkparameters($params).'" class="buttonlink">'.(1+$previous/$number).'</a> ';
            }

            // current number
            $params["offset"] = $offset;
            $result.='<a href="'.makelinkparameters($params).'" class="buttonlink"><span class="highlight"><strong>'.(1+$offset/$number).'</strong></span></a> ';

            // next number
            if($offset<$last) {
                $params["offset"] = $next;
                $result.='<a href="'.makelinkparameters($params).'" class="buttonlink">'.(1+$next/$number).'</a> ';
            }
            if(($next+$number)<$last && $last/$number>2) { $result.='... ';
            }
            if($next<$last) {
                // last number
                $params["offset"] = $last;
                $result.='<a href="'.makelinkparameters($params).'" class="buttonlink">'.(1+$last/$number).'</a> ';
            }
            // "Next"
            if($offset<$last) {
                $params["offset"] = $next;
                $result.=' <a href="'.makelinkparameters($params).'" class="buttonlink">'.getlang("pagemenu_next").'</a> ';
            }
        }
        return $result;
    }
}


//
// Templating for a categories selection form
//
class CategorySelectionForm  extends Template
{

    function __construct($multiple=false,$jsid ="", $cattype, $size=15,$selectedcat=array(),$jsfunction=false,$optionformname="selectedcat", $optionformlabel="")
    {
        $this->stringvars['jsid'] =$jsid;
        parent::__construct($jsid);

        if($multiple) { $this->stringvars['optionform_name'] =$optionformname."[]";
        } else { $this->stringvars['optionform_name'] =$optionformname;
        }

        $this->stringvars['optionform_label'] =$optionformlabel;
        $this->stringvars['optionform_id'] =$optionformname;
        $this->stringvars['optionform_size'] =$size;
        if ($size>1) { $this->stringvars['bigbox'] ='bigbox';
        }

        $attributes="";
        if($jsfunction) { $attributes.=' onChange="'.$jsfunction.'"';
        }
        if($multiple) { $attributes.=' multiple';
        }
        $this->stringvars['optionform_attributes'] =$attributes;

        $allcategories=getallcategorieswithname($cattype);

        $this->listvars['option'][]= new OptionFormOption(1, "", getlang("form_cat_allcats"));

        $this->makecategoryoption($allcategories, 1, $selectedcats = array_flip($selectedcat));
    }

    //
    // recursive collecting of categoryoptions to be put in option listvar
    //
    function makecategoryoption($categories, $parent,$selectedcat=array(),$level=0)
    {
        $remaining=array();
        $currentcats=array();

        foreach ($categories as $key => $category) {
            if($category['parent_id']==$parent) {
                $currentcats[$key] = $category;
            } else {
                $remaining[$key] = $category;
            }
        }

        foreach ($currentcats as $key => $category) {
            $category = $currentcats[$key];
            $optionisselected="";
            $optiontext="";

            if(array_key_exists($key, $selectedcat)) {
                $optionisselected=' selected';
            }
            for($i=0;$i<$level+1;$i++)
            {
                $optiontext.="&nbsp;&nbsp;&nbsp;&nbsp;";
            }

            $optiontext.=input2html($category["name"]);

            $this->listvars['option'][]= new OptionFormOption($key, $optionisselected, $optiontext);

            $this->makecategoryoption($remaining, $key, $selectedcat, $level+1);
        }
    }

    function createTemplates()
    {
        $this->addTemplate("forms/optionform.tpl");
    }
}


//
// Templating for a ascending/descending selection form
//
class AscDescSelectionForm  extends Template
{

    function __construct($isascselected=true)
    {
        parent::__construct();

        $this->stringvars['l_ascending']=getlang("form_ascdesc_ascending");
        $this->stringvars['l_descending']=getlang("form_ascdesc_descending");
        $this->stringvars['l_label']=getlang("form_ascdesc_label");

        if($isascselected) { $this->stringvars['asc_selected']=" selected";
        } else { $this->stringvars['desc_selected']=" selected";
        }
    }

    function createTemplates()
    {
        $this->addTemplate("forms/ascdescselection.tpl");
    }
}




//
// Templating for Options in Optionforms
//
class OptionFormOption  extends Template
{

    function __construct($optionvalue,$optionisselected,$optiontext)
    {
        parent::__construct();
        $this->stringvars['option_value'] =$optionvalue;
        if($optionisselected) { $this->stringvars['option_selected'] =" selected";
        } else { $this->stringvars['option_selected'] ="";
        }
        $this->stringvars['option_text'] =ucfirst($optiontext);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("forms/optionformoption.tpl");
    }
}


//
// Generic templating for option forms
// name may contain white spaces. They will be stripped for the form name,
// but left intact for display
//

class OptionForm extends Template
{

    function __construct($selected,$values=array(),$descriptions=array(),$name="option", $label="", $size=1, $attributes="")
    {
        parent::__construct();

        if($size>1) { $label=$label."<br />";
        }

        $this->stringvars['optionform_name'] =strtolower(str_replace(" ", "", $name));
        $this->stringvars['optionform_label']=$label;
        $this->stringvars['optionform_id'] = $this->stringvars['optionform_name'];
        $this->stringvars['optionform_size']=$size;
        $this->stringvars['optionform_attributes']=$attributes;

        for($i=0;$i<count($values);$i++)
        {
            $this->listvars['option'][]= new OptionFormOption($values[$i], $values[$i]==$selected, $descriptions[$i]);
        }
    }

    // assigns templates and list objects
    function createTemplates()
    {
        $this->addTemplate("forms/optionform.tpl");
    }
}


//
// Generic templating for option forms with multiple selections
// name may contain white spaces. They will be stripped for the form name,
// but left intact for display
//

class OptionFormMultiple extends Template
{

    function __construct($selected=array(),$values=array(),$descriptions=array(),$name="option", $label="", $size=1, $attributes="")
    {
        parent::__construct();

        $this->stringvars['optionform_name'] =strtolower(str_replace(" ", "", $name))."[]";
        $this->stringvars['optionform_label'] =$label;
        $this->stringvars['optionform_size']=$size;
        $this->stringvars['optionform_attributes']=$attributes." multiple";

        for($i=0;$i<count($values);$i++)
        {
            $this->listvars['option'][]= new OptionFormOption($values[$i], in_array($values[$i], $selected), $descriptions[$i]);
        }
    }

    // assigns templates and list objects
    function createTemplates()
    {
        $this->addTemplate("forms/optionform.tpl");
    }
}





//
// Generic templating for number option forms
// name may contain white spaces. They will be stripped for the form name,
// but left intact for display
//

class NumberOptionForm  extends Template
{

    function __construct($number,$from,$to,$showunknown=false,$jsid="",$name="number", $label="number")
    {
        parent::__construct();

        $this->stringvars['optionform_name'] =strtolower(str_replace(" ", "", $name));
        $this->stringvars['optionform_label'] =ucfirst($label).": ";
        $this->stringvars['optionform_id'] =$this->stringvars['optionform_name'];
        $this->stringvars['jsid'] =$jsid;
        $this->stringvars['optionform_size']=1;
        $this->stringvars['optionform_attributes']="";

        if($showunknown) { $this->listvars['option'][]= new OptionFormOption("0", $number==0, "- ".ucfirst($label)." -");
        }

        for($i=$from;$i<=$to;$i++)
        {
            $this->listvars['option'][]= new OptionFormOption($i, $number==$i, $i);
        }
    }

    // assigns templates and list objects
    function createTemplates()
    {
        $this->addTemplate("forms/optionform.tpl");
    }
}




//
// Templating for a days selection form
//
class DayOptionForm  extends NumberOptionForm
{

    function __construct($day,$showunknown=false,$jsid="",$name="day", $label="Day")
    {
        parent::__construct($day, 1, 31, $showunknown, $jsid, $name, $label);
    }
}

//
// Templating for a months selection form
//
class MonthOptionForm  extends NumberOptionForm
{

    function __construct($month,$showunknown=false,$jsid="",$name="month", $label="Month")
    {
        parent::__construct($month, 1, 12, $showunknown, $jsid, $name, $label);
    }
}


//
// Templating for a years selection form
//
class YearOptionForm  extends NumberOptionForm
{

    function __construct($year,$from,$to,$jsid="",$name="year", $label="Year")
    {
        parent::__construct($year, $from, $to, false, $jsid, $name, $label);
    }
}



//
//
//
class CheckboxForm extends Template
{

    function __construct($name,$value,$title,$ischecked,$labelpos="left")
    {
        parent::__construct();
        $this->stringvars['name']=$name;
        $this->stringvars['value']=$value;
        $this->stringvars['title']=$title;

        if($ischecked) {
            $this->stringvars['checked']='checked="checked"';
        } else {
            $this->stringvars['checked']="";
        }

        if($labelpos=="left") {
            $this->stringvars['label_left']="left";
        } else {
            $this->stringvars['label_right']="right";
        }
    }

    // assigns templates and list objects
    function createTemplates()
    {
        $this->addTemplate("forms/checkboxform.tpl");
    }
}

//
//
//
class RadioButtonForm extends Template
{

    function __construct($jsid,$name,$value,$title,$ischecked,$labelpos="left")
    {
        parent::__construct($jsid);
        $this->stringvars['name']=$name;
        $this->stringvars['value']=$value;
        $this->stringvars['title']=title2html($title);
        if($ischecked) {
            $this->stringvars['checked']='checked="checked"';
        } else {
            $this->stringvars['checked']="";
        }
        if($labelpos=="left") {
            $this->stringvars['label_left']="left";
        } else {
            $this->stringvars['label_right']="right";
        }
    }

    // assigns templates and list objects
    function createTemplates()
    {
        $this->addTemplate("forms/radiobuttonform.tpl");
    }
}


//
// Printview, Link to item, RSS
//
class LinkButton extends Template
{

    function __construct($link,$title,$image)
    {
        global $projectroot;
        parent::__construct();

        $this->stringvars['link']=$link;
        $this->stringvars['title']=$title;
        $this->stringvars['imgsrc']=getCSSPath($image);
        $imgfilepath = $projectroot."templates/".getproperty("Default Template")."/img/".basename($image);
        if(!file_exists($imgfilepath)) { $imgfilepath = $projectroot."templates/default/img/".basename($image);
        }
        $dimensions = getimagedimensions($imgfilepath);
        $this->stringvars['width'] = $dimensions["width"];
        $this->stringvars['height'] = $dimensions["height"];
    }
    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("forms/linkbutton.tpl");
    }
}
?>
