<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));

include_once($projectroot."functions/pagecontent/linklistpages.php");
include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/images.php");
include_once($projectroot."includes/includes.php");


//
// a link in a linklist
//
class LinklistLink extends Template {

	function LinklistLink($linkid,$title,$image,$description,$link)
	{
		parent::__construct();
		// image permissions checked in LinkList
		$hasimage=strlen($image)>0;
		$this->stringvars['title'] = title2html($title);
		$this->stringvars['link'] = $link;
		$this->stringvars['linkid'] = $linkid;
		
		if($hasimage)
			$this->vars['image'] = new LinkedImage($image,$link, $this->stringvars['title'], 2);

		$this->stringvars['text'] = text2html($description);
	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("linklistlink.tpl");
    }
}


//
// click on thumbnail goes to link instead of showimage.php
//
class LinkedImage extends Template {

    function LinkedImage($filename,$linkurl, $linkname, $factor=1)
    {
		global $projectroot;
		
		parent::__construct();
		
		$image="";
		$this->stringvars['halign']="float: left;";
		$alttext=title2html($linkname);
		$thumbnail=getthumbnail($filename);
		$imagedir=$projectroot.getproperty("Image Upload Path");
		$filename=$imagedir.getimagesubpath(basename($filename)).'/'.$filename;
		if(file_exists($filename))
		{
			if(!is_dir($filename))
			{
				$dimensions=calculateimagedimensions($filename,$factor);
				$width=$dimensions["width"];
				$height=$dimensions["height"];
				
				if($thumbnail && file_exists($imagedir.getimagesubpath(basename($filename)).'/'.$thumbnail))
				{
					//$thumbnail=$imagedir.getimagesubpath(basename($filename)).'/'.$thumbnail;
					$image='<a href="'.$linkurl.'"><img src="'.getimagelinkpath($thumbnail,getimagesubpath(basename($filename))).'" alt="'.$alttext.'" title="'.$alttext.'" class="linkedimage"></a>';
				}
				else
				{
					$image='<a href="'.$linkurl.'"><img src="'.getimagelinkpath($filename,getimagesubpath(basename($filename))).'" width="'.$width.'" height="'.$height.'" alt="'.$alttext.'" title="'.$alttext.'" class="linkedimage"></a>';
				}
			}
		}
		else
		{
			$image='<a href="'.$linkurl.'">'.$alttext.'</a>';
		}
		$this->stringvars['image']=$image;
    }
    
    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("image.tpl");
    }
}





//
// main class for linklistpages
//
class LinklistPage extends Template {

	function LinklistPage($offset=0,$showrefused,$showhidden)
	{
		parent::__construct();
		$linkids=getlinklistitems($this->stringvars['page']);
		$noofids=count($linkids);
		if(!$offset) $offset=0;
		
		$this->vars['printviewbutton']= new LinkButton('?sid='.$this->stringvars['sid'].'&printview=on&page='.$this->stringvars['page'],getlang("pagemenu_printview"),"img/printview.png");
		
		$pageintro = getpageintro($this->stringvars['page']);
		$this->vars['pageintro'] = new PageIntro(getpagetitle($this->stringvars['page']),$pageintro['introtext'],$pageintro['introimage'],$pageintro['imagehalign'],$showrefused,$showhidden);
		
		// links
		for($i=$offset;$i<($noofids)&&$i<$noofids;$i++)
		{
			$contents=getlinkcontents($linkids[$i]);
			if(!mayshowimage($contents['image'],$this->stringvars['page'],$showhidden))
				$contents['image']="";
			$this->listvars['link'][]= new LinkListLink($linkids[$i],$contents['title'],$contents['image'],$contents['description'],$contents['link']);
		}
		
		$this->vars['editdata']= new Editdata($showhidden);
	}
  
    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("linklistpage.tpl");
    }
}




//
// main class for linklistpages
//
class LinklistPagePrintview extends Template {

	function LinklistPagePrintview($showrefused,$showhidden)
	{
		parent::__construct();
		$linkids=getlinklistitems($this->stringvars['page']);
		$noofids=count($linkids);
		
		$pageintro = getpageintro($this->stringvars['page']);
		$this->vars['pageintro'] = new PageIntro("",$pageintro['introtext'],$pageintro['introimage'],$pageintro['imagehalign'],$showrefused,$showhidden);
		
		$this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars['page']));
		
		for($i=0;$i<$noofids;$i++)
		{
			$contents=getlinkcontents($linkids[$i]);
			if(!mayshowimage($contents['image'],$this->stringvars['page'],$showhidden))
				$contents['image']="";
			$this->listvars['link'][]= new LinkListLink($linkids[$i],$contents['title'],$contents['image'],$contents['description'],$contents['link'],false,false);
		}
		
		$this->vars['editdata']= new Editdata($showhidden);
	}

    // assigns templates
    function createTemplates()
    {
		$this->addTemplate("linklistpage.tpl");
    }
}

?>
