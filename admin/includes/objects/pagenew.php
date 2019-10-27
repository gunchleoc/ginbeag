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

require_once $projectroot."admin/includes/objects/forms.php";
require_once $projectroot."functions/pages.php";
require_once $projectroot."includes/objects/template.php";



//
//
//
class NewPageForm extends Template
{

    function __construct($parentpage,$title="",$navtitle="",$ispublishable=false,$isrootchecked=false)
    {
        parent::__construct();

        $this->stringvars['actionvars']= makelinkparameters(array("page" => $this->stringvars['page']));

        $this->stringvars['page']=$parentpage;
        $this->stringvars['parentname']=title2html(getpagetitle($parentpage)).' ('.getpagetype($parentpage).')';
        $this->stringvars['pagetitle']=$title;
        $this->stringvars['navtitle']=$navtitle;

        $this->vars['rootcheckedform']= new CheckboxForm("root", "root", "Create main page:", $isrootchecked);
        $this->vars['is_publishable_yes']= new RadioButtonForm("", "ispublishable", "public", "Public page", $ispublishable);
        $this->vars['is_publishable_no']= new RadioButtonForm("", "ispublishable", "internal", "Internal page", !$ispublishable);


        $pagetypes=getpagetypes();
        $keys=array_keys($pagetypes);

        for($i=0;$i<count($keys);$i++)
        {
            $short=$keys[$i];
            $values[]=$short;
            $descriptions[]=$short.': '.input2html($pagetypes[$short]);
        }
        $this->vars['typeselection']= new OptionForm($keys[0], $values, $descriptions, "type", "Page type: ", 1);
        $this->vars['submitrow'] = new SubmitRow("create", "Create", true, true, "admin.php".$this->stringvars['actionvars'], $this->stringvars["jsid"]);
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/newpageform.tpl");
    }
}
?>
