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
// zweimal, weil nur auf "a" geprüft wird
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."admin/functions/sitestats.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/forms.php";



//
//
//
class SiteStatsTable extends Template
{

    function __construct($count,$year, $month,$timespan="month")
    {
        parent::__construct();

        if($timespan=="month") {
            $this->stringvars['month']=$month;
            $stats=getmonthlypagestats($count, $year, $month);
        }
        else
        {
            $this->stringvars['month']="";
            $stats=getyearlypagestats($count, $year);
        }
        $this->stringvars['year']=$year;
        $this->stringvars['count']=$count;

        $this->vars["month_selection"]=new MonthOptionForm($month, true, "", "month", "Month");
        $this->vars["month_year_selection"]=new YearOptionForm($year, getstatsfirstyear(), date("Y", strtotime('now')), "", "month_year", "Year");
        $this->stringvars["count_selection"]= $count;

        $this->vars["year_year_selection"]=new YearOptionForm($year, getstatsfirstyear(), date("Y", strtotime('now')), "", "year_year", "Year");

        $rank = 0;
        foreach ($stats as $page => $views) {
            $this->listvars['stats'][]=new SiteStatsEntry($views, $page, ++$rank);
        }

        if ($rank == 0) {
            $this->stringvars['nostats']="Stats not available";
        }
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/statstable.tpl");
    }
}


//
//
//
class SiteStatsEntry extends Template
{

    function __construct($views, $page, $rank)
    {
        parent::__construct();

        $this->stringvars['rank']=$rank;
        $this->stringvars['views']=number_format($views);
        $this->stringvars['pagetype']=getpagetype($page);
        $this->stringvars['page']=$page;
        $this->stringvars['pagetitle']=text2html(getpagetitle($page));
        $this->stringvars['url']=getprojectrootlinkpath()."admin/admin.php".makelinkparameters(array("page" => $page));
    }

    // assigns templates
    function createTemplates()
    {
        $this->addTemplate("admin/site/statsentry.tpl");
    }
}

?>
