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
			$this->vars['image'] = new LinkedImage($image,$link, $this->stringvars['title']);

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

    function LinkedImage($filename,$linkurl, $linkname)
    {
		global $projectroot;
		
		parent::__construct();
		
		$image="";
		$this->stringvars['halign']="float: left;";
		$alttext=title2html($linkname);

		$width=getproperty("Thumbnail Size");

		$filepath = getimagepath($filename);
		$thumbnail = getthumbnail($filename);
		$thumbnailpath = getthumbnailpath($filename, $thumbnail);

		if(thumbnailexists($thumbnail) && file_exists($thumbnailpath) && !is_dir($thumbnailpath))
		{
			$dimensions=getimagedimensions($thumbnailpath);
			$image='<a href="'.$linkurl.'"><img src="'.getimagelinkpath($thumbnail,getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" alt="'.$alttext.'" title="'.$alttext.'" class="linkedimage"></a>';
		}
		else if(imageexists($filename) && file_exists($filepath) && !is_dir($filepath))
		{
			$dimensions=calculateimagedimensions($filepath, true);
			$image='<a href="'.$linkurl.'"><img src="'.getimagelinkpath($filename,getimagesubpath($filename)).'" width="'.$dimensions["width"].'" height="'.$dimensions["height"].'" alt="'.$alttext.'" title="'.$alttext.'" class="linkedimage"></a>';
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

	function LinklistPage($offset=0,$showhidden)
	{
		parent::__construct();
		$linkids=getlinklistitems($this->stringvars['page']);
		$noofids=count($linkids);
		if(!$offset) $offset=0;
		
		$this->vars['printviewbutton']= new LinkButton('?sid='.$this->stringvars['sid'].'&printview=on&page='.$this->stringvars['page'],getlang("pagemenu_printview"),"img/printview.png");
		
		$pageintro = getpageintro($this->stringvars['page']);
		$this->vars['pageintro'] = new PageIntro(getpagetitle($this->stringvars['page']),$pageintro['introtext'],$pageintro['introimage'],$pageintro['imageautoshrink'], $pageintro['usethumbnail'],$pageintro['imagehalign'],$showhidden);
		
		// links
		for($i=$offset;$i<($noofids)&&$i<$noofids;$i++)
		{
			$contents=getlinkcontents($linkids[$i]);
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

	function LinklistPagePrintview($showhidden)
	{
		parent::__construct();
		$linkids=getlinklistitems($this->stringvars['page']);
		$noofids=count($linkids);
		
		$pageintro = getpageintro($this->stringvars['page']);
		$this->vars['pageintro'] = new PageIntro("",$pageintro['introtext'],$pageintro['introimage'],$pageintro['imageautoshrink'], $pageintro['usethumbnail'],$pageintro['imagehalign'],$showhidden);
		
		$this->stringvars['pagetitle']=title2html(getpagetitle($this->stringvars['page']));
		
		for($i=0;$i<$noofids;$i++)
		{
			$contents=getlinkcontents($linkids[$i]);
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
