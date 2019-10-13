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

// zweimal, weil nur auf "a" geprft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/pagesmod.php";
require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/objects/page.php";
require_once $projectroot."admin/includes/objects/admintopframe.php";
require_once $projectroot."admin/includes/objects/messages.php";
require_once $projectroot."admin/includes/actions.php";

//
// last parameter needs to be a Template or string when used
//  $message needs to be ot type AdminMessage
//
class AdminMain extends Template
{

    function __construct($page, $action, $message, $contentobject=null)
    {
        global $_GET, $projectroot;
        parent::__construct();

        /****************************
 * header
***************************************/

        $this->stringvars['stylesheet']=getCSSPath("main.css");
        $this->stringvars['stylesheetcolors']= getCSSPath("colors.css");
        $this->stringvars['adminstylesheet']=getCSSPath("admin.css");
        $this->stringvars['headertitle']= title2html(getproperty("Site Name")).' - Webpage building';


        // load linked Javascript
        if($contentobject instanceof Template) {
            $jspaths = $contentobject->getjspaths();
            $this->stringvars['scriptlinks']='<script type="text/javascript" src="'.getprojectrootlinkpath().'includes/javascript/jquery.js"></script>';
            if(strlen($jspaths)>0) {
                $this->stringvars['scriptlinks'] .= $jspaths;
            }

            $jscripts = $contentobject->getScripts();
            if(strlen($jscripts)>0) {
                $this->stringvars['javascript']=$jscripts;
            }
        }

        if($contentobject instanceof DoneRedirect) {
            $this->stringvars['is_redirect']="redirect";
            $this->stringvars['url']=$contentobject->stringvars['url'];
        }

        $this->vars['header']= new AdminTopFrame($page, $action);


        /****************************
 * navigator
************************************/

        if(issiteaction($action)) {
            include_once $projectroot."admin/includes/objects/site/navigator.php";
            $this->vars['navigatorfixed']= new SiteAdminNavigatorHeader();
            $this->vars['navigatorscroll'] = new SiteAdminNavigator();
        }
        else
        {
              include_once $projectroot."admin/includes/objects/navigator.php";
              $this->vars['navigatorfixed'] = new AdminNavigatorHeader();
              $this->vars['navigatorscroll'] = new AdminNavigator($page);
        }

        /****************************
 * content
**************************************/

        $this->vars['message'] = $message;

        if(!is_null($contentobject)) {


            if($contentobject instanceof Template) {
                $this->vars['contents']= $contentobject;
            } elseif(is_string($contentobject)) {
                $this->stringvars['contents']= $contentobject;
            } else {
                $this->stringvars['contents']= "Error: illegal content in AdminMain!";
            }

        }
        else
        {
            // rerout to guide for webpage editors
            if(!isset($_GET["page"]) || strlen($_GET["page"]<1) || $_GET["page"]<1) {
                $contentstring='<table border="0" cellpadding="10" cellspacing="0" width="100%">';
                $contentstring.='<tr><td><p class="gen">Please check the <a href="http://www.noclockthing.de/minicms" target="_blank">';
                $contentstring.='Guide</a> to find your way around.</p>';
                $contentstring.='<p class="gen">This site needs JavaScript for some editing functions and cookies to keep the editing session.</p>';
                $contentstring.='<p class="highlight">Since login sessions can always be lost,';
                $contentstring.=' it can\'t hurt to copy the texts you\'re editing to your computer\'s clipboard';
                $contentstring.=' before pressing any buttons.</p>';
                $contentstring.='<p class="gen">Please stay away from the Technical Setup in the Administration section, unless you know what you\'re doing ;)</p>';
                $contentstring.='<p class="gen">Please log out when you leave</p></td></tr></table>';
                $this->stringvars['contents'] = $contentstring;
            }
            // create page content
            else
            {
                $pagetype=getpagetype($_GET["page"]);

                // init
                if(isset($_GET['articlepage'])) {
                    $articlepage=$_GET['articlepage'];
                } elseif(isset($_GET['offset'])) {
                    $articlepage=$_GET['offset']+1;
                } elseif(!isset($_GET['articlepage']) || strlen($_GET['articlepage'])<1) {
                    $articlepage=1;
                } else {
                    $articlepage=0;
                }


                if(isset($_GET['offset'])) { $offset=$_GET['offset'];
                } else { $offset=0;
                }

                $this->vars['message'] = new AdminPageDisplayMessage(true);

                if($pagetype==="article") {
                    include_once $projectroot."includes/objects/articlepage.php";
                    $this->vars['contents'] = new ArticlePage($articlepage, true);
                }
                elseif($pagetype==="articlemenu") {
                    include_once $projectroot."includes/objects/menupage.php";
                    $this->vars['contents'] = new ArticleMenuPage($_GET["page"], true);
                }
                elseif($pagetype==="menu" || $pagetype=="linklistmenu") {
                    include_once $projectroot."includes/objects/menupage.php";
                    $this->vars['contents'] = new MenuPage($_GET["page"], true);
                }
                elseif($pagetype==="external") {
                    $this->stringvars['contents'] =  '<div style="margin:2em"><a href="'.getexternallink($_GET["page"]).'" target="_blank">External page</a></div>';
                }
                elseif($pagetype==="gallery") {
                    include_once $projectroot."includes/objects/gallerypage.php";
                    $this->vars['contents'] = new GalleryPage($offset, true);
                }
                elseif($pagetype==="linklist") {
                    include_once $projectroot."includes/objects/linklistpage.php";
                    $this->vars['contents'] = new LinklistPage($offset, true);
                }
                elseif($pagetype==="news") {
                    include_once $projectroot."includes/objects/newspage.php";
                    $this->vars['contents'] = new NewsPage($_GET["page"], $offset, true);
                }
                else
                {
                    $contentstring=getlang("error_pagenotfound");
                }
            }
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/adminmain.tpl");
    }
}

?>
