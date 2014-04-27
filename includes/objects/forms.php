<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."language/languages.php");
include_once($projectroot."functions/categories.php");
include_once($projectroot."functions/pages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/includes.php");

//
// Templating for a ascending/descending selection form
//
class JumpToPageForm  extends Template {

	function JumpToPageForm($file="",$params=array(),$align="right", $target="")
	{
		parent::__construct();
		
		$attributes="";
		if($file) $attributes.=' action="'.$file.'"';
		if($target) $attributes.=' target="'.$target.'"';
		$this->stringvars['attributes']=$attributes;
		
		$fields="";
		if($this->stringvars['sid']) $fields.='<input type="hidden" name="sid" value="'.$this->stringvars['sid'].'" />';

		if(count($params)>0)
		{
			$keys=array_keys($params);
			$values=array_values($params);
			for($i=0;$i<count($keys);$i++)
			{
				$fields.='<input type="hidden" name="'.$keys[$i].'" value="'.$values[$i].'" />';
			}
		}
		$this->stringvars['fields']=$fields;
		$this->stringvars['align']=$align;
		$this->stringvars['l_jumptopage']=getlang("pagemenu_jumptopage");
		$this->stringvars['l_go']=getlang("pagemenu_go");
	}
	
	function createTemplates()
	{
		$this->addTemplate("jumptopageform.tpl");
	}
}


//
// todo: more templating for rtl?
//
class PageMenu extends Template {

    function PageMenu($offset, $number, $last, $params="")
    {
    	parent::__construct();
		$this->stringvars['pagemenu']=$this->makelinks($offset, $number, $last, $params,$this->stringvars['page']);
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("pagemenu.tpl");
    }
    
    function makelinks($offset, $number, $last, $params="")
    {
		$result="";
		$sidparam="";
		
		if($this->stringvars['sid']) $sidparam.='sid='.$this->stringvars['sid'].'&';
		if($this->stringvars['page']>0) $sidparam.='page='.$this->stringvars['page'].'&';
		if($params) $params.="&";

		if(!$number>0) $number=1;

		$next=$offset+$number;
		$previous=$offset-$number;
		if($previous<0) $previous=0;
		$last=$number*(ceil($last/$number)-1);
		
		if($last>0)
		{
			$result.=getlang("pagemenu_goto");
			
			if($offset>0)
			{
				// "Previous"
				$result.='<a href="?';
				$result.=$sidparam;
				$result.=$params;
				$result.='offset='.$previous;
				$result.='" method="post">'.getlang("pagemenu_previous").'</a> ';
			}
			if($offset)
			{
				if($previous)
				{
					// First page number
					$result.='<a href="?';
					$result.=$sidparam;
					$result.=$params;
					$result.='offset=0';
					$result.='" method="post">1</a>, ';
					if(($previous-$number)>0) $result.='... ';
				}
				// previous number
				$result.='<a href="?';
				$result.=$sidparam;
				$result.=$params;
				$result.='offset='.$previous;
				$result.='" method="post">';
				$result.=1+($previous/$number);
				$result.='</a>, ';
			}
		
			// current number
			$result.='<b>'.(1+$offset/$number).'</b>';
			if($offset<$last)
			{
				$result.=', ';
			}
		
			// next number
			if($offset<$last)
			{
				$result.='<a href="?';
				$result.=$sidparam;
				$result.=$params;
				$result.='offset='.$next;
				$result.='" method="post">';
				$result.=(1+$next/$number).'</a>';
				if($next<$last) $result.=', ';
			}
			if(($next+$number)<$last && $last/$number>2) $result.='... ';
			if($next<$last)
			{
				// last number
				$result.='<a href="?';
				$result.=$sidparam;
				$result.=$params;
				$result.='offset='.$last;
				$result.='" method="post">';
				$result.=(1+$last/$number).'</a>';
			}
			// "Next"
			if($offset<$last)
			{
				$result.=' <a href="?';
				$result.=$sidparam;
				$result.=$params;
				$result.='offset='.$next;
				$result.='" method="post">'.getlang("pagemenu_next").'</a>';
			}
		}
		return $result;
    }
}


//
// Templating for a categories selection form
//
class CategorySelectionForm  extends Template {

    function CategorySelectionForm($multiple=false,$jsid ="", $cattype, $size=15,$selectedcat=array(),$jsfunction=false,$optionformname="selectedcat", $optionformlabel="")
    {
		$this->stringvars['jsid'] =$jsid;
		parent::__construct($jsid);
      
		if($multiple) $this->stringvars['optionform_name'] =$optionformname."[]";
		else $this->stringvars['optionform_name'] =$optionformname;

		$this->stringvars['optionform_label'] =$optionformlabel;
		$this->stringvars['optionform_id'] =$optionformname;
		$this->stringvars['optionform_size'] =$size;
		if ($size>1) $this->stringvars['bigbox'] ='bigbox';
		
		$attributes="";
		if($jsfunction) $attributes.=' onChange="'.$jsfunction.'"';
		if($multiple) $attributes.=' multiple';
		$this->stringvars['optionform_attributes'] =$attributes;
		
		$allcategories=getallcategorieswithname($cattype);
		
		$this->listvars['option'][]= new OptionFormOption(1,"",getlang("form_cat_allcats"));
		
		$this->makecategoryoption($allcategories,1,$selectedcats=array_flip($selectedcat));
    }
    
    //
    // recursive collecting of categoryoptions to be put in option listvar
    //
    function makecategoryoption($categories, $parent,$selectedcat=array(),$level=0)
    {
		$remaining=array();
		$currentcats=array();
		
		while($category=current($categories))
		{
			if($category['parent_id']==$parent) array_push($currentcats,$category);
			else array_push($remaining,$category);
			next($categories);
		}

		while($category=current($currentcats))
		{
			$optionvalue=$category["category_id"];
			$optionisselected="";
			$optiontext="";
			
			if(array_key_exists($category["category_id"],$selectedcat)) $optionisselected=' selected';
			for($i=0;$i<$level+1;$i++)
			{
				$optiontext.="&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			
			$optiontext.=input2html($category["name"]);
			
			$this->listvars['option'][]= new OptionFormOption($optionvalue,$optionisselected,$optiontext);
			
			$this->makecategoryoption($remaining, $category["category_id"],$selectedcat,$level+1);
			next($currentcats);
		}
    }

    function createTemplates()
    {
		$this->addTemplate("optionform.tpl");
    }
}


//
// Templating for a ascending/descending selection form
//
class AscDescSelectionForm  extends Template {
    
    function AscDescSelectionForm($isascselected=true)
    {
    	parent::__construct();
    
    	$this->stringvars['l_ascending']=getlang("form_ascdesc_ascending");
    	$this->stringvars['l_descending']=getlang("form_ascdesc_descending");
    	$this->stringvars['l_label']=getlang("form_ascdesc_label");
    	
      	if($isascselected) $this->stringvars['asc_selected']=" selected";
      	else $this->stringvars['desc_selected']=" selected";
    }
    
    function createTemplates()
    {
      	$this->addTemplate("ascdescselection.tpl");
    }
}




//
// Templating for Options in Optionforms
//
class OptionFormOption  extends Template {

    function OptionFormOption($optionvalue,$optionisselected,$optiontext)
    {
    	parent::__construct();
        $this->stringvars['option_value'] =$optionvalue;
        if($optionisselected) $this->stringvars['option_selected'] =" selected";
        else $this->stringvars['option_selected'] ="";
        $this->stringvars['option_text'] =ucfirst($optiontext);
    }

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("optionformoption.tpl");
    }
}


//
// Generic templating for option forms
// name may contain white spaces. They will be stripped for the form name,
// but left intact for display
//

class OptionForm extends Template {

    function OptionForm($selected,$values=array(),$descriptions=array(),$name="option", $label="", $size=1, $attributes="")
    {
		parent::__construct();
		
		if($size>1) $label=$label."<br />";

        $this->stringvars['optionform_name'] =strtolower(str_replace(" ","",$name));
        $this->stringvars['optionform_label']=$label;
        $this->stringvars['optionform_id'] = $this->stringvars['optionform_name'];
        $this->stringvars['optionform_size']=$size;
        $this->stringvars['optionform_attributes']=$attributes;

        for($i=0;$i<count($values);$i++)
        {
			$this->listvars['option'][]= new OptionFormOption($values[$i],$values[$i]==$selected,$descriptions[$i]);
        }
    }

    // assigns templates and list objects
    function createTemplates()
    {
		$this->addTemplate("optionform.tpl");
    }
}


//
// Generic templating for option forms with multiple selections
// name may contain white spaces. They will be stripped for the form name,
// but left intact for display
//

class OptionFormMultiple extends Template {

    function OptionFormMultiple($selected=array(),$values=array(),$descriptions=array(),$name="option", $label="", $size=1, $attributes="")
    {
    	parent::__construct();

        $this->stringvars['optionform_name'] =strtolower(str_replace(" ","",$name))."[]";
        $this->stringvars['optionform_label'] =$label;
        $this->stringvars['optionform_size']=$size;
        $this->stringvars['optionform_attributes']=$attributes." multiple";

        for($i=0;$i<count($values);$i++)
        {
			$this->listvars['option'][]= new OptionFormOption($values[$i],in_array($values[$i],$selected),$descriptions[$i]);
        }
    }

    // assigns templates and list objects
    function createTemplates()
    {
		$this->addTemplate("optionform.tpl");
    }
}





//
// Generic templating for number option forms
// name may contain white spaces. They will be stripped for the form name,
// but left intact for display
//

class NumberOptionForm  extends Template {

    function NumberOptionForm($number,$from,$to,$showunknown=false,$jsid="",$name="number", $label="number")
    {
    	parent::__construct();

        $this->stringvars['optionform_name'] =strtolower(str_replace(" ","",$name));
        $this->stringvars['optionform_label'] =ucfirst($label).": ";
        $this->stringvars['optionform_id'] =$this->stringvars['optionform_name'];
        $this->stringvars['jsid'] =$jsid;
        $this->stringvars['optionform_size']=1;
        $this->stringvars['optionform_attributes']="";

        if($showunknown) $this->listvars['option'][]= new OptionFormOption("0",$number==0,"- ".ucfirst($label)." -");

        for($i=$from;$i<=$to;$i++)
        {
			$this->listvars['option'][]= new OptionFormOption($i,$number==$i,$i);
        }
    }

    // assigns templates and list objects
    function createTemplates()
    {
		$this->addTemplate("optionform.tpl");
    }
}




//
// Templating for a days selection form
//
class DayOptionForm  extends NumberOptionForm {

	function __construct($day,$showunknown=false,$jsid="",$name="day", $label="Day")
  	{
		parent::__construct($day,1,31,$showunknown,$jsid,$name,$label);
  	}
}

//
// Templating for a months selection form
//
class MonthOptionForm  extends NumberOptionForm {

	function __construct($month,$showunknown=false,$jsid="",$name="month", $label="Month")
  	{
		parent::__construct($month,1,12,$showunknown,$jsid,$name,$label);
  	}
}


//
// Templating for a years selection form
//
class YearOptionForm  extends NumberOptionForm {

	function __construct($year,$from,$to,$jsid="",$name="year", $label="Year")
  	{
		parent::__construct($year,$from,$to,false,$jsid,$name,$label);
  	}
}



//
//
//
class CheckboxForm extends Template {

    function CheckboxForm($name,$value,$title,$ischecked,$labelpos="left")
    {
    	parent::__construct();
		$this->stringvars['name']=$name;
		$this->stringvars['value']=$value;
		$this->stringvars['title']=$title;
		
		if($ischecked)
			$this->stringvars['checked']='checked="checked"';
		else
			$this->stringvars['checked']="";
		
		if($labelpos=="left")
			$this->stringvars['label_left']="left";
		else
			$this->stringvars['label_right']="right";
    }
    
    // assigns templates and list objects
    function createTemplates()
    {
		$this->addTemplate("checkboxform.tpl");
    }
}

//
//
//
class RadioButtonForm extends Template {

    function RadioButtonForm($jsid,$name,$value,$title,$ischecked,$labelpos="left")
    {
    	parent::__construct($jsid);
		$this->stringvars['name']=$name;
		$this->stringvars['value']=$value;
		$this->stringvars['title']=title2html($title);
		if($ischecked)
			$this->stringvars['checked']='checked="checked"';
		else
			$this->stringvars['checked']="";
		if($labelpos=="left")
			$this->stringvars['label_left']="left";
		else
			$this->stringvars['label_right']="right";
	}
	
	// assigns templates and list objects
	function createTemplates()
	{
		$this->addTemplate("radiobuttonform.tpl");
	}
}


//
// Printview, Link to item, RSS
//
class LinkButton extends Template {

	function LinkButton($link,$title,$image)
	{
		parent::__construct();
		
		$this->stringvars['link']=$link;
		$this->stringvars['title']=$title;
		$this->stringvars['imgsrc']=getCSSPath($image);
	}
	// assigns templates
	function createTemplates()
	{
		$this->addTemplate("linkbutton.tpl");
	}
}
?>