<?php
$projectroot=dirname(__FILE__);
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot,0,strrpos($projectroot,"includes"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/objects/template.php");
include_once($projectroot."includes/objects/elements.php");

//
//
//
class SiteCopyrightPermissionExplanation extends Template {

	function SiteCopyrightPermissionExplanation()
  	{
  		parent::__construct();
  	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/copyrightpermissionexplanation.tpl");
  	}
}


//
//
//
class SiteCopyrightInputformElements extends Template {

	function SiteCopyrightInputformElements($copyright)
  	{
  		parent::__construct();
  	
		$this->stringvars['holder']=input2html($copyright['holder']);
		$this->stringvars['contact']=input2html($copyright['contact']);
		$this->stringvars['comments']=input2html($copyright['comments']);
		$this->stringvars['credit']=input2html($copyright['credit']);
		$this->stringvars['permission_granted']=PERMISSION_GRANTED;
		if($copyright['permission']==PERMISSION_GRANTED) $this->stringvars['granted']="true";
		$this->stringvars['permission_imagesonly']=PERMISSION_IMAGESONLY;
		if($copyright['permission']==PERMISSION_IMAGESONLY) $this->stringvars['imagesonly']="true";
		$this->stringvars['permission_linkimagesonly']=PERMISSION_LINKIMAGESONLY;
		if($copyright['permission']==PERMISSION_LINKIMAGESONLY)$this->stringvars['linkimagesonly']="true";
		$this->stringvars['permission_linkonly']=PERMISSION_LINKONLY;
		if($copyright['permission']==PERMISSION_LINKONLY) $this->stringvars['linkonly']="true";
		$this->stringvars['permission_refused']=PERMISSION_REFUSED;
		if($copyright['permission']==PERMISSION_REFUSED)$this->stringvars['refused']="true";
		$this->stringvars['permission_noreply']=PERMISSION_NOREPLY;
		if($copyright['permission']==PERMISSION_NOREPLY) $this->stringvars['noreply']="true";
		$this->stringvars['permission_pending']=PERMISSION_PENDING;
		if($copyright['permission']==PERMISSION_PENDING) $this->stringvars['pending']="true";
		$this->stringvars['no_permission']=NO_PERMISSION;
		if($copyright['permission']==NO_PERMISSION) $this->stringvars['nopermission']="true";
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/copyrightinputformelements.tpl");
  	}
}


//
//
//
class SiteCopyrightDeleteForm extends Template {

	function SiteCopyrightDeleteForm($copyid)
  	{
  		parent::__construct();
  		
  		$this->stringvars['actionvars']='?page='.$this->stringvars['page'].'&action=sitecopyperm';
  		$this->stringvars['hiddenvars']='<input type="hidden" name="copyrightid" value="'.$copyid.'" />';
  		
  		$copyright=getcopyrightinfo($copyid);
  	
		$this->stringvars['holder']=title2html($copyright['holder']);
		$this->stringvars['contact']=text2html($copyright['contact']);
		$this->stringvars['comments']=text2html($copyright['comments']);
		$this->stringvars['credit']=title2html($copyright['credit']);
		$this->stringvars['id']=$copyid;
		$this->stringvars['permission']=permission2html($copyright['permission']);
		$this->stringvars['editor']=title2html(getusername($copyright['editorid']));
		$this->stringvars['dateadded']=formatdate($copyright['added']);
		$this->stringvars['editdate']=formatdatetime($copyright['editdate']);
		$this->vars['permissionexplanation']=new SiteCopyrightPermissionExplanation();
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/copyrightdeleteform.tpl");
  	}
}


//
//
//
class SiteCopyrightAddForm extends Template {

	function SiteCopyrightAddForm($holder="",$contact="",$comments="",$credit="",$permission=1)
  	{
  		parent::__construct();
  		
  		$this->stringvars['actionvars']='?page='.$this->stringvars['page'].'&action=sitecopyperm';
  		
		$copyright=array();
  		if($holder || $contact || $comments || $credit)
  		{
    		$copyright['holder']=$holder;
    		$copyright['contact']=$contact;
    		$copyright['comments']=$comments;
    		$copyright['credit']=$credit;
    		$copyright['permission']=$permission;
  		}
  		else
  		{
    		$copyright['holder']="";
    		$copyright['contact']="";
    		$copyright['comments']="";
    		$copyright['credit']="";
    		$copyright['permission']=NO_PERMISSION;
  		}

  		$this->vars['formelements']=new SiteCopyrightInputformElements($copyright);
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/copyrightaddform.tpl");
  	}
}


//
//
//
class SiteCopyrightEditForm extends Template {

	function SiteCopyrightEditForm($copyid,$holder="",$contact="",$comments="",$credit="",$permission=1)
  	{
  		parent::__construct();
  		
  		$this->stringvars['actionvars']='?page='.$this->stringvars['page'].'&action=sitecopyperm';
  		$this->stringvars['hiddenvars']='<input type="hidden" name="copyrightid" value="'.$copyid.'" />';
  		
  		$copyright=getcopyrightinfo($copyid);
  		if($holder || $contact || $comments || $credit)
  		{
    		$copyright['holder']=$holder;
    		$copyright['contact']=$contact;
    		$copyright['comments']=$comments;
    		$copyright['permission']=$permission;
    		$copyright['credit']=$credit;
  		}
  		
  		$this->stringvars['id']=$copyid;
  		$this->stringvars['editor']=getusername($copyright['editorid']);
  		$this->stringvars['dateadded']=formatdate($copyright['added']);
  		$this->stringvars['editdate']=formatdatetime($copyright['editdate']);
  		
  		$this->vars['formelements']=new SiteCopyrightInputformElements($copyright);
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/copyrighteditform.tpl");
  	}
}


//
//
//
class SiteCopyrightInfo extends Template {

	function SiteCopyrightInfo($copyid)
  	{
  		parent::__construct();
  		
  		$this->stringvars['actionvars']='?page='.$this->stringvars['page'].'&action=sitecopyperm';
  		$this->stringvars['hiddenvars']='<input type="hidden" name="copyrightid" value="'.$copyid.'" />';
  		
  		$copyright=getcopyrightinfo($copyid);
  		
  		$this->stringvars['holder']=title2html($copyright['holder']);
		$this->stringvars['contact']=text2html($copyright['contact']);
		$this->stringvars['comments']=text2html($copyright['comments']);
		$this->stringvars['credit']=title2html($copyright['credit']);
		$this->stringvars['id']=$copyid;
		$this->stringvars['permission']=permission2html($copyright['permission']);
		$this->stringvars['editor']=title2html(getusername($copyright['editorid']));
		$this->stringvars['dateadded']=formatdate($copyright['added']);
		$this->stringvars['editdate']=formatdatetime($copyright['editdate']);
		
		$this->stringvars['imagesearchlink']='../editimagelist.php?source=all&copyright='.$copyright['holder'].'&filter=Display+Selection';
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/copyrightinfo.tpl");
  	}
}


//
//
//
class SiteCopyrightForms extends Template {

	function SiteCopyrightForms($offset=0,$searchholder="")
  	{
  		global $entriesperpage,$order,$ascdesc,$filterpermission;
  		parent::__construct();
  		
  		$this->stringvars['actionvars']='?page='.$this->stringvars['page'].'&action=sitecopyperm';

  		$params="order=".$order."&ascdesc=".$ascdesc."&filterpermission=".$filterpermission;
  		if($searchholder)
  		{
    		$params.="&action=search&holder=".$searchholder;
  		}
 
  		if($searchholder)
  		{
    		$copyids=searchholder($searchholder,$order,$ascdesc,$filterpermission);
  		}
  		else
  		{
    		$copyids=getcopyrightids($order,$ascdesc,$filterpermission);
  		}

  		$this->stringvars['searchformparams']='?page='.$this->stringvars['page'].'&order='.$order.'&ascdesc='.$ascdesc.'&filterpermission='.$filterpermission.'&postaction=search&action=sitecopyperm"';
  		$this->stringvars['searchtext']=$searchholder;
  		$this->stringvars['orderformparams']='?page='.$this->stringvars['page'].'&postaction=order&action=sitecopyperm"';
		if($searchholder) $this->stringvars['orderformparams'].='&search=search&holder='.$searchholder;
		
		$this->stringvars['permission_granted']=PERMISSION_GRANTED;
		$this->stringvars['permission_imagesonly']=PERMISSION_IMAGESONLY;
		$this->stringvars['permission_linkimagesonly']=PERMISSION_LINKIMAGESONLY;
		$this->stringvars['permission_linkonly']=PERMISSION_LINKONLY;
		$this->stringvars['permission_refused']=PERMISSION_REFUSED;
		$this->stringvars['permission_noreply']=PERMISSION_NOREPLY;
		$this->stringvars['permission_pending']=PERMISSION_PENDING;
		$this->stringvars['no_permission']=NO_PERMISSION;
		
		
		if($filterpermission=="10000") $this->stringvars['permissions']="true";
		elseif($filterpermission==PERMISSION_GRANTED) $this->stringvars['granted']="true";
		elseif($filterpermission==PERMISSION_IMAGESONLY) $this->stringvars['imagesonly']="true";
		elseif($filterpermission==PERMISSION_LINKIMAGESONLY)$this->stringvars['linkimagesonly']="true";
		elseif($filterpermission==PERMISSION_LINKONLY) $this->stringvars['linkonly']="true";
		elseif($filterpermission==PERMISSION_REFUSED)$this->stringvars['refused']="true";
		elseif($filterpermission==PERMISSION_NOREPLY) $this->stringvars['noreply']="true";
		elseif($filterpermission==PERMISSION_PENDING) $this->stringvars['pending']="true";
		elseif($filterpermission==NO_PERMISSION) $this->stringvars['nopermission']="true";
		
		
		if($order==="copyright_id") $this->stringvars['id']="true";
        elseif($order==="holder") $this->stringvars['holder']="true";
        elseif($order==="contact") $this->stringvars['contact']="true";
        elseif($order==="comments") $this->stringvars['comments']="true";
        elseif($order==="permission") $this->stringvars['permission']="true";
        elseif($order==="credit") $this->stringvars['credit']="true";
        elseif($order==="editor_id") $this->stringvars['editor']="true";
        elseif($order==="added") $this->stringvars['dateadded']="true";
        elseif($order==="editdate") $this->stringvars['editdate']="true";
		
		if($ascdesc==="asc") $this->stringvars['asc']="true";
        elseif($ascdesc==="desc") $this->stringvars['desc']="true";
        
        $this->vars['pagemenu']= new PageMenu($offset, $entriesperpage, count($copyids),$params);
        
        for($i=$offset;$i<($offset+$entriesperpage) && $i<count($copyids);$i++)
  		{
  			$this->listvars['entries'][]= new SiteCopyrightInfo($copyids[$i]);
		}
		
		if(!count($copyids)>0) $this->stringvars['entries']="";
		
		$this->vars['explanation']= new SiteCopyrightPermissionExplanation();
	}

  	// assigns templates
  	function createTemplates()
  	{
    	$this->addTemplate("admin/site/copyrightforms.tpl");
  	}
}

?>