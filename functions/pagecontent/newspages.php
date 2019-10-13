<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getpublishednewsitems($page,$number,$offset) {
	if(!$offset) $offset=0;
	if(!$number>0) $number=1;

	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'newsitem_id', array('page_id', 'ispublished'), array($page, 1), 'ii');
	$sql->set_order(array('date' => (displaynewestnewsitemfirst($page) ? 'DESC' : 'ASC')));
	$sql->set_limit($number, $offset);
	return $sql->fetch_column();
}

//
//
//
function displaynewestnewsitemfirst($page) {
	$sql = new SQLSelectStatement(NEWS_TABLE, 'shownewestfirst', array('page_id'), array($page), 'i');
	return $sql->fetch_value();
}

//
//
//
function getnewsitemoffset($page,$number,$newsitem,$showhidden=false) {
	if (!$newsitem > 0) {
		return 0;
	}
	if(!$number>0) $number=1;
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
function countpublishednewsitems($page) {
	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'newsitem_id', array('page_id', 'ispublished'), array($page, 1), 'ii');
	$sql->set_operator('count');
	return $sql->fetch_value();
}


//
//
//
function getnewsitemcontents($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, '*', array('newsitem_id'), array($newsitem), 'i');
	return $sql->fetch_row();
}

//
// returns a date array
//
function getnewsitemdate($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'date', array('newsitem_id'), array($newsitem), 'i');
	return @getdate(strtotime($sql->fetch_value()));
}

//
//
//
function getoldestnewsitemdate($page) {
	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'date', array('page_id'), array($page), 'i');
	$sql->set_operator('min');
	return @getdate(strtotime($sql->fetch_value()));
}

//
//
//
function getnewestnewsitemdate($page) {
	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'date', array('page_id'), array($page), 'i');
	$sql->set_operator('max');
	return @getdate(strtotime($sql->fetch_value()));
}

//
//
//
function getnewsitempage($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'page_id', array('newsitem_id'), array($newsitem), 'i');
	return $sql->fetch_value();
}


//
//
//
function getnewsitemsynopsistext($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'synopsis', array('newsitem_id'), array($newsitem), 'i');
	return $sql->fetch_value();
}

//
//
//
function getnewsitemsynopsisimageids($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMSYNIMG_TABLE, 'newsitemimage_id', array('newsitem_id'), array($newsitem), 'i');
	$sql->set_order(array('position' => 'ASC'));
	return $sql->fetch_column();
}

//
//
//
function getnewsitemsynopsisimage($newsitemimage) {
	$sql = new SQLSelectStatement(NEWSITEMSYNIMG_TABLE, 'image_filename', array('newsitemimage_id'), array($newsitemimage), 'i');
	return $sql->fetch_value();
}


//
//
//
function getnewsitemsynopsisimages($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMSYNIMG_TABLE, 'image_filename', array('newsitem_id'), array($newsitem), 'i');
	$sql->set_order(array('position' => 'ASC'));
	return $sql->fetch_column();
}

//
//
//
function getnewsitemsections($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'newsitemsection_id', array('newsitem_id'), array($newsitem), 'i');
	$sql->set_order(array('sectionnumber' => 'ASC'));
	return $sql->fetch_column();
}

//
//
//
function getnewsitemsectioncontents($newsitemsection) {
	$sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, '*', array('newsitemsection_id'), array($newsitemsection), 'i');
	return $sql->fetch_row();
}

//
//
//
function getnewsitemsectiontext($newsitemsection) {
	$sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'text', array('newsitemsection_id'), array($newsitemsection), 'i');
	return $sql->fetch_value();
}


//
//
//
function getnewsitemsectionimage($newsitemsection) {
	$sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'sectionimage', array('newsitemsection_id'), array($newsitemsection), 'i');
	return $sql->fetch_value();
}


//
//
//
function getnewsitemsectionimagealign($newsitemsection) {
	$sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'imagealign', array('newsitemsection_id'), array($newsitemsection), 'i');
	return $sql->fetch_value();
}


//
//
//
function getnewsitemsectionnumber($newsitemsection) {
	$sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'sectionnumber', array('newsitemsection_id'), array($newsitemsection), 'i');
	return $sql->fetch_value();
}

//
//
//
function isnewsitempublished($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'ispublished', array('newsitem_id'), array($newsitem), 'i');
	return $sql->fetch_value();
}


//
// returns array of copyright, imagecopyright, permission
// permission is one of the constants PERMISSION_GRANTED, NO_PERMISSION
//
function getnewsitemcopyright($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, array('copyright', 'image_copyright', 'permission'), array('newsitem_id'), array($newsitem), 'i');
	return $sql->fetch_row();
}

//
//
//
function getnewsitempermission($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMS_TABLE, 'permission', array('newsitem_id'), array($newsitem), 'i');
	return $sql->fetch_value();
}


//
//
//
function getlastnewsitemsection($newsitem) {
	$sql = new SQLSelectStatement(NEWSITEMSECTIONS_TABLE, 'sectionnumber', array('newsitem_id'), array($newsitem), 'i');
	$sql->set_operator('max');
	return $sql->fetch_value();
}




//
//
//
function getfilterednewsitems($page,$selectedcat,$from,$to,$order,$ascdesc,$newsitemsperpage,$offset) {
	$values = array();
	$datatypes = "";

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

	$date=$from["day"]." ".$months[$from["month"]]." ".$from["year"];
	$fromdate=date(DATETIMEFORMAT, strtotime($date));

	$date=$to["day"]." ".$months[$to["month"]]." ".$to["year"]." 23:59:59";
	$todate=date(DATETIMEFORMAT, strtotime($date));

	$query="SELECT DISTINCTROW items.newsitem_id FROM ";
	$query.=NEWSITEMS_TABLE." AS items";

	// Filter for categories
	if ($selectedcat != 1) {
		// get all category descendants
		$categories = getcategorydescendants($selectedcat, CATEGORY_NEWS);
		$datatypes = str_pad($datatypes, count($categories) + strlen($datatypes), 'i');
		$placeholders = array_fill(0, count($categories), '?');
		$values = array_merge($values, $categories);

		$query .= ", ".NEWSITEMCATS_TABLE." AS cat";
		$query .= " WHERE cat.newsitem_id = items.newsitem_id";
		$query .= " AND cat.category IN (" . implode(',' , $placeholders) . ") AND";
	} else {
		$query .= " WHERE";
	}
	// years
	$query .= " items.date BETWEEN ? AND ?";
	array_push($values, $fromdate);
	array_push($values, $todate);
	$datatypes .= 'ss';

	// get pages to search
	$query .= " AND items.page_id = ?";
	$query .= " AND items.ispublished = ?";
	array_push($values, $page);
	array_push($values, 1);
	$datatypes .= 'si';

	if($order) {
		$query .= " ORDER BY ";
		if ($order === "title") $query .= "items.title ";
		elseif ($order === "date") $query .= "date ";
		elseif ($order === "source") $query .= "items.source ";
		$query .= strtolower($ascdesc) === "desc" ? "DESC" : "ASC";
	}

	$sql = new RawSQLStatement($query, $values, $datatypes);
	if($newsitemsperpage > 0) {
		$sql->set_limit($newsitemsperpage, $offset);
	}
	return $sql->fetch_column();
}

//
//
//
function searchnewsitemtitles($search,$page,$showhidden=false) {
	$query = "SELECT DISTINCTROW newsitem_id FROM " . NEWSITEMS_TABLE;
	$query .= " WHERE page_id = ? AND title like ?";

	$sql = new RawSQLStatement($query, array($page, '%' . trim($search) . '%'), 'is');
	return $sql->fetch_column();
}

?>
