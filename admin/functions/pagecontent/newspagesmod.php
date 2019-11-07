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
$projectroot=substr($projectroot, 0, strrpos($projectroot, "pagecontent"));
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."functions/db.php";
require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."functions/pagecontent/newspages.php";

//
//
//
function setdisplaynewestnewsitemfirst($page, $shownewestfirst)
{
    $sql = new SQLUpdateStatement(
        NEWS_TABLE,
        array('shownewestfirst'), array('page_id'),
        array($shownewestfirst ? 1 : 0, $page), 'ii'
    );
    return $sql->run();
}


//
//
//
function getnewsitems($page,$number,$offset)
{
    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'newsitem_id', array('page_id'), array($page), 'i');
    $sql->set_order(array('date' => 'DESC'));
    $sql->set_limit($number, $offset);
    return $sql->fetch_column();
}

//
//
//
function countnewsitems($page)
{
    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'newsitem_id', array('page_id'), array($page), 'i');
    $sql->set_operator('count');
    return $sql->fetch_value();
}

//
//
//
function getpagefornewsitem($newsitem)
{
    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'page_id', array('newsitem_id'), array($newsitem), 'i');
    return $sql->fetch_value();
}


//
//
//
function getnewsitemsectionimagealign($newsitemsection)
{
    $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'imagealign', array('newsitemsection_id'), array($newsitemsection), 'i');
    return $sql->fetch_value();
}

//
//
//
function getnewsitemsectiontext($newsitemsection)
{
    $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'text', array('newsitemsection_id'), array($newsitemsection), 'i');
    return $sql->fetch_value();
}

//
//
//
function getnewsitemsectionimage($newsitemsection)
{
    $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'sectionimage', array('newsitemsection_id'), array($newsitemsection), 'i');
    return $sql->fetch_value();
}

//
//
//
function getnewsitemsectionnumber($newsitemsection)
{
    $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'sectionnumber', array('newsitemsection_id'), array($newsitemsection), 'i');
    return $sql->fetch_value();
}

//
//
//
function getlastnewsitemsection($newsitem)
{
    $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'sectionnumber', array('newsitem_id'), array($newsitem), 'i');
    $sql->set_operator('max');
    return $sql->fetch_value();
}

//
//
//
function getnewsitemoffset($page,$number,$newsitem,$showhidden=false)
{
    if (!$newsitem > 0) {
        return 0;
    }
    if(!$number>0) { $number=1;
    }
    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'date', array('newsitem_id'), array($newsitem), 'i');
    $date = $sql->fetch_value();

    $sql = $showhidden ?
    new SQLSelectStatement(NEWSITEMS_TABLE, 'newsitem_id', array('page_id'), array($page, $date), 'is', "date > ?") :
    new SQLSelectStatement(NEWSITEMS_TABLE, 'newsitem_id', array('page_id', 'ispublished'), array($page, 1, $date), 'iis', "date > ?");
    $sql->set_operator('count');
    $noofelements = $sql->fetch_value();
    return floor($noofelements/$number);
}

//
//
//
function getnewsitemsectioncontents($newsitemsection)
{
    $sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, '*', array('newsitemsection_id'), array($newsitemsection), 'i');
    return $sql->fetch_row();
}

//
//
//
function updatenewsitemtitle($newsitem, $title)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMS_TABLE,
        array('title'), array('newsitem_id'),
        array($title, $newsitem), 'si'
    );
    $sql->run();
}

//
//
//
function addnewsitemsynopsisimage($newsitem, $filename)
{
    $sql = new SQLSelectStatement(NEWSITEMSYNIMG_TABLE, 'position', array('newsitem_id'), array($newsitem), 'i');
    $sql->set_operator('max');

    $sql = new SQLInsertStatement(
        NEWSITEMSYNIMG_TABLE,
        array('newsitem_id', 'image_filename', 'position'),
        array($newsitem, $filename, $sql->fetch_value() + 1),
        'isi'
    );
    return $sql->insert();
}

//
//
//
function editnewsitemsynopsisimage($newsitemimage, $filename)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMSYNIMG_TABLE,
        array('image_filename'), array('newsitemimage_id'),
        array($filename, $newsitemimage), 'si'
    );
    $sql->run();
}

//
//
//
function removenewsitemsynopsisimage($newsitemimage)
{
    $sql = new SQLDeleteStatement(NEWSITEMSYNIMG_TABLE, array('newsitemimage_id'), array($newsitemimage), 's');
    $sql->run();
}

//
//
//
function updatenewsitemsource($newsitem, $source, $sourcelink, $location, $contributor)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMS_TABLE,
        array('source', 'sourcelink', 'location', 'contributor'), array('newsitem_id'),
        array($source, $sourcelink, $location, $contributor, $newsitem), 'ssssi'
    );
    $sql->run();
}

//
//
//
function fakethedate($newsitem, $day, $month, $year, $hours, $minutes, $seconds)
{
    if (strlen($day) == 1) { $day = "0" . $day;
    }
    if (strlen($month) == 1) { $month = "0" . $month;
    }
    if (strlen($hours) == 1) { $hours = "0" . $hours;
    }
    if (strlen($minutes) == 1) { $minutes = "0" . $minutes;
    }
    if (strlen($seconds) == 1) { $seconds = "0" . $seconds;
    }
    if (strlen($year) == 4) {
        $date = SQLStatement::setinteger($year) . "-"
        . SQLStatement::setinteger($month) . "-"
        . SQLStatement::setinteger($day) . " "
        . SQLStatement::setinteger($hours) . ":"
        . SQLStatement::setinteger($minutes) . ":"
        . SQLStatement::setinteger($seconds);
        $sql = new SQLUpdateStatement(
            NEWSITEMS_TABLE,
            array('date'), array('newsitem_id'),
            array($date, $newsitem), 'si'
        );
        return $sql->run();
    }
    return false;
}

//
//
//
function publishnewsitem($newsitem)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMS_TABLE,
        array('ispublished'), array('newsitem_id'),
        array(1, $newsitem), 'ii'
    );
    return $sql->run();
}


//
//
//
function unpublishnewsitem($newsitem)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMS_TABLE,
        array('ispublished'), array('newsitem_id'),
        array(0, $newsitem), 'ii'
    );
    return $sql->run();
}

//
//
//
function updatenewsitemcopyright($newsitem,$copyright,$imagecopyright,$permission)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMS_TABLE,
        array('copyright', 'image_copyright', 'permission'), array('newsitem_id'),
        array($copyright, $imagecopyright, $permission, $newsitem), 'ssii'
    );
    return $sql->run();
}


//
//
//
function addnewsitem($page)
{
    $sql = new SQLInsertStatement(
        NEWSITEMS_TABLE,
        array('page_id', 'date', 'editor_id', 'permission', 'ispublished', 'imageautoshrink', 'usethumbnail'),
        array($page, date(DATETIMEFORMAT, strtotime('now')), getsiduser(), NO_PERMISSION, 0, 1, 1),
        'isiiiii'
    );
    return $sql->insert();
}

//
//
//
function deletenewsitem($newsitem)
{
    $sql = new SQLDeleteStatement(NEWSITEMS_TABLE, array('newsitem_id'), array($newsitem), 'i');
    $sql->run();
    $sql = new SQLDeleteStatement(NEWSITEMSYNIMG_TABLE, array('newsitem_id'), array($newsitem), 'i');
    $sql->run();
    $sql = new SQLDeleteStatement(NEWSITEMCATS_TABLE, array('newsitem_id'), array($newsitem), 'i');
    $sql->run();
    $sql = new SQLDeleteStatement(NEWSITEMSECTIONS_TABLE, array('newsitem_id'), array($newsitem), 'i');
    $sql->run();
}

//
// moves all newsitems in $page that are not newer than $day, $month, $year
// to a new page below $page
// returns number of archived newsitems
//
function archivenewsitems($page,$day,$month,$year)
{
    $maxpagetitlelength=200;
    $maxnavtitlelength=30;
    $months[1]='January';
    $months[2]='February';
    $months[3]='March';
    $months[4]='April';
    $months[5]='May';
    $months[6]='June';
    $months[7]='July';
    $months[8]='August';
    $months[9]='September';
    $months[10]='October';
    $months[11]='November';
    $months[12]='December';

    $date=$day." ".$months[$month]." ".$year." 23:59:59";
    $comparedate=date(DATETIMEFORMAT, strtotime($date));

    $sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'newsitem_id', array('page_id'), array($page, $comparedate), 'is', "date <= ?");
    $moveids = $sql->fetch_column();
    $noofitems=count($moveids);

    if($noofitems>0) {

        $oldestdate=getoldestnewsitemdate($page);

        $from=makearticledate($oldestdate['mday'], $oldestdate['mon'], $oldestdate['year']);
        $from2=$oldestdate['mday']." ".substr($months[$oldestdate['mon']], 0, 3)." ".$oldestdate['year'];

        $to=makearticledate($day, $month, $year);
        $to2=$day." ".substr($months[$month], 0, 3)." ".$year;

        if($from!=$to) {
            $interval=" (".$from." - ".$to.")";
        }
        else
        {
            $interval=" (".$from.")";
        }
        $pagetitle=getpagetitle($page);

        if(strlen($pagetitle)+strlen($interval)>$maxpagetitlelength) {
            $pagetitle=substr($pagetitle, 0, $maxpagetitlelength-strlen($interval));
            $pagetitle=substr($pagetitle, 0, strrpos($pagetitle, " "));
        }
        $pagetitle.=$interval;

        if($from2!=$to2) {
            $interval2=" (".$from2." - ".$to2.")";
        }
        else
        {
            $interval2=" (".$from.")";
        }
        $navtitle=getnavtitle($page);
        if(strlen($navtitle)+strlen($interval)>$maxnavtitlelength) {
            $navtitle=substr($navtitle, 0, $maxnavtitlelength-strlen($interval2));
            $navtitle=substr($navtitle, 0, strrpos($navtitle, " "));
        }
        $navtitle.=$interval2;

        $newpage=createpage($page, $pagetitle, $navtitle, "news", getsiduser(), ispublishable($page));

        // Move items to the new page
        $values = array($newpage);
        foreach ($moveids as $value) {
            $values[] = $value;
        }
        $placeholders = array_fill(0, $noofitems, '?');
        $sql = new RawSQLStatement(
            "UPDATE " . NEWSITEMS_TABLE .
                " SET page_id = ? WHERE newsitem_id IN (" . implode(",", $placeholders) . ")",
            $values, $datatypes = str_pad("", $noofitems + 1, 'i')
        );
        $sql->run();
    }
    return $noofitems;
}

//
//
//
function updatenewsitemsynopsistext($newsitem,$text)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMS_TABLE,
        array('synopsis'), array('newsitem_id'),
        array($text, $newsitem), 'si'
    );
    return $sql->run();
}



//
//
//
function addnewsitemsection($newsitem, $newsitemsection,$isquote=false)
{
    $sections = getnewsitemsections($newsitem);
    if (!$newsitemsection) {
        if(count($sections)>0) {
            $newsitemsection=$sections[count($sections)-1];
        }
        else {
            $newsitemsection=0;
        }
    }
    $sectionnumber = $newsitemsection == 0 ? 0 : getnewsitemsectionnumber($newsitemsection);

    if($isquote) {
        $offset=3;
    }
    else
    {
        $offset=1;
    }

    //make room

    if(getlastnewsitemsection($newsitem)!=$sectionnumber) {
        $finished=false;

        // Bring into shape for the database call
        $values = array();

        for($i = count($sections) - 1; $i > 0; $i--) {
            $currentsectionnumber=getnewsitemsectionnumber($sections[$i]);
            if ($currentsectionnumber > $sectionnumber) {
                array_push($values, array($currentsectionnumber + $offset, $sections[$i]));
            } else {
                break;
            }
        }

        // Write
        $sql = new SQLUpdateStatement(
            NEWSITEMSECTIONS_TABLE,
            array('sectionnumber'), array('newsitemsection_id'),
            array(), 'ii'
        );
        $sql->set_values($values);
        $sql->run();
    }

    $sectionnumber++;

    if ($isquote) {
        $sql = new SQLInsertStatement(
            NEWSITEMSECTIONS_TABLE,
            array('newsitem_id', 'sectionnumber', 'text', 'imagealign', 'imageautoshrink', 'usethumbnail'),
            array($newsitem, $sectionnumber, '[quote]', 'left', 1, 1),
            'iissii'
        );
        $sql->insert();

        $sectionnumber++;
    }

    $sql = new SQLInsertStatement(
        NEWSITEMSECTIONS_TABLE,
        array('newsitem_id', 'sectionnumber', 'imagealign', 'imageautoshrink', 'usethumbnail'),
        array($newsitem, $sectionnumber, 'left', 1, 1),
        'iisii'
    );
    $result = $sql->insert();

    if ($isquote) {
        $sectionnumber++;

        $sql = new SQLInsertStatement(
            NEWSITEMSECTIONS_TABLE,
            array('newsitem_id', 'sectionnumber', 'text', 'imagealign', 'imageautoshrink', 'usethumbnail'),
            array($newsitem, $sectionnumber, '[unquote]', 'left', 1, 1),
            'iissii'
        );
        $sql->insert();
    }
    return $result;
}


//
//
//
function updatenewsitemsectionimagealign($newsitemsection, $imagealign)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMSECTIONS_TABLE,
        array('imagealign'), array('newsitemsection_id'),
        array($imagealign, $newsitemsection), 'si'
    );
    return $sql->run();
}

//
//
//
function updatenewsitemsectionimagesize($newsitemsection, $autoshrink, $usethumbnail)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMSECTIONS_TABLE,
        array('imageautoshrink', 'usethumbnail'), array('newsitemsection_id'),
        array($autoshrink, $usethumbnail, $newsitemsection), 'iii'
    );
    return $sql->run();
}


//
//
//
function updatenewsitemsectionimagefilename($newsitemsection, $imagefilename)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMSECTIONS_TABLE,
        array('sectionimage'), array('newsitemsection_id'),
        array($imagefilename, $newsitemsection), 'si'
    );
    return $sql->run();
}

//
//
//
function updatenewsitemsectionttitle($newsitemsection, $title)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMSECTIONS_TABLE,
        array('sectiontitle'), array('newsitemsection_id'),
        array($title, $newsitemsection), 'si'
    );
    return $sql->run();
}


//
//
//
function updatenewsitemsectiontext($newsitemsection, $text)
{
    $sql = new SQLUpdateStatement(
        NEWSITEMSECTIONS_TABLE,
        array('text'), array('newsitemsection_id'),
        array($text, $newsitemsection), 'si'
    );
    return $sql->run();
}


//
//
//
function deletenewsitemsection($newsitem, $newsitemsection)
{
    // remove quotes if necessary
    $sections=getnewsitemsections($newsitem);
    $found=false;
    for($i=1;$i<count($sections)-1&&!$found;$i++)
    {
        if($sections[$i]==$newsitemsection) {
            $found=true;
            $text1=getnewsitemsectiontext($sections[$i-1]);
            $text2=getnewsitemsectiontext($sections[$i+1]);
            //      print("<p>text".$text1.$text2);
            if($text1==="[quote]" && $text2==="[unquote]") {
                $sql = new SQLDeleteStatement(NEWSITEMSECTIONS_TABLE, array('newsitemsection_id'), array($sections[$i - 1]), 'i');
                $sql->run();
                $sql = new SQLDeleteStatement(NEWSITEMSECTIONS_TABLE, array('newsitemsection_id'), array($sections[$i + 1]), 'i');
                $sql->run();
            }
        }
    }
    // delete
    $sql = new SQLDeleteStatement(NEWSITEMSECTIONS_TABLE, array('newsitemsection_id'), array($newsitemsection), 'i');
    $sql->run();
}

//
// // RSS feeds are empty
//
function addrssfeed($page)
{
    $sql = new SQLInsertStatement(RSS_TABLE, array('page_id'), array($page), 'i');
    return $sql->insert();
}

//
//
//
function removerssfeed($page)
{
    $sql = new SQLDeleteStatement(RSS_TABLE, array('page_id'), array($page), 'i');
    $sql->run();
}
?>
