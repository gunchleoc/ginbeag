<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"pagecontent"));
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

//
//
//
function getmenucontents($page) {
  $sql = new SQLSelectStatement(MENUS_TABLE, '*', array('page_id'), array($page), 'i');
  return $sql->fetch_row();
}


//
//
//
function getmenunavigatordepth($page) {
  $sql = new SQLSelectStatement(MENUS_TABLE, 'navigatordepth', array('page_id'), array($page), 'i');
  return $sql->fetch_value();
}


//
//
//
function getarticlepageoverview($page) {
  $sql = new SQLSelectStatement(ARTICLES_TABLE, array('article_author', 'source', 'day', 'month', 'year'), array('page_id'), array($page), 'i');
  return $sql->fetch_row();
}


// ***************************  articlemenu ********************************* //

//
//
//
function getallarticleyears() {
  $sql = new SQLSelectStatement(ARTICLES_TABLE, 'year');
  $sql->set_order(array('year' => 'ASC'));
  $sql->set_distinct();
  return $sql->fetch_column();
}


//
//
//
function getfilteredarticles($page,$selectedcat,$from,$to,$order,$ascdesc,$showhidden=false) {
  $values = array();
  $datatypes = "";

  $query = "SELECT DISTINCTROW art.page_id FROM ";
  $query .= ARTICLES_TABLE." AS art, ";
  $query .= PAGES_TABLE." AS page";

  // Filter for categories
  if ($selectedcat != 1) {
    // get all category descendants
    $categories = getcategorydescendants($selectedcat, CATEGORY_ARTICLE);
    $datatypes = str_pad($datatypes, count($categories) + strlen($datatypes), 'i');
    $placeholders = array_fill(0, count($categories), '?');
    $values = array_merge($values, $categories);

    $query .= ", ".ARTICLECATS_TABLE." AS cat WHERE cat.page_id = art.page_id";
    $query .= " AND cat.category IN (". implode(',' , $placeholders) . ") AND";
  } else {
    $query .= " WHERE";
  }

  $query .= " page.page_id = art.page_id";

  if (!$showhidden) {
    $query .= " AND page.ispublished = ?";
    array_push($values, 1);
    $datatypes .= 'i';
  }

  // get pages to search
  $pages=getsubpagesforpagetype($page, "articlemenu");
  $datatypes = str_pad($datatypes, count($pages) + strlen($datatypes), 'i');
  $placeholders = array_fill(0, count($pages), '?');
  $values = array_merge($values, $pages);
  $query .= " AND page.parent_id IN (".implode(",", $placeholders).")";

  // Filter for years
  if ($from != "all" && $to != "all") {
    $query .= " AND art.year BETWEEN ? AND ?";
    array_push($values, $from);
    array_push($values, $to);
    $datatypes .= 'ss';
  }

  if ($order) {
    $query .= " ORDER BY ";
    if ($order === "title") $query .= "page.title_page ";
    elseif ($order === "author") $query .= "art.article_author ";
    elseif ($order === "date") $query .= "art.year, art.month, art.day ";
    elseif ($order === "source") $query .= "art.source ";
    elseif ($order === "editdate") $query .= "page.editdate ";
    $query.= strtolower($ascdesc) === "desc" ? "DESC" : "ASC";
  }

  $sql = new RawSQLStatement($query, $values, $datatypes);
  return $sql->fetch_column();
}
?>
