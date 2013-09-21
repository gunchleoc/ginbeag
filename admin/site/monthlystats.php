<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

// check legal vars
include($projectroot."admin/includes/legalsitevars.php");

include_once($projectroot."admin/functions/dbmod.php");
include_once($projectroot."admin/functions/sessions.php");
include_once($projectroot."admin/includes/adminelements.php");

$sid=$_GET['sid'];
checksession($sid);

$year=date("Y",strtotime('now'));
$month=date("m",strtotime('now'));

$header = new HTMLHeader("Monthly Stats","Webpage Building");
print($header->toHTML());

$count=20;

printstats($count,$year, $month);
if($month>1)
{
  $month--;
  if (strlen($month)<2) $month="0".$month;
}
else
{
  $year --;
}
printstats($count,$year, $month);

//
//
//
function printstats($count,$year, $month)
{
  global $sid;

  $stats=getmonthlypagestats($count,$year,$month);
  if(count($stats))
  {
//  print_r($stats);
?>
<p class="pagetitle">Stats for <?php print($month." ".$year);?> - Top <?php print($count);?> pages</p>
<table><tr><td class="bodyline">
<table cellpadding="5"><tr>
<th class="thHead">Rank</th>
<th class="thHead">Views</th>
<th class="thHead">Pagetype</th>
<th class="thHead">Page</th>
</tr>
<?php
    for($i=0;$i<count($stats);$i++)
    {
      $url=getprojectrootlinkpath()."admin/pagedisplay.php?page=".$stats[$i][0]."&sid=".$sid;
?>
<tr>
  <td class="gen" align="right"><?php print($i+1);?>.
  </td>
  <td class="gen" align="right"><?php print($stats[$i][1]);?>
  </td>
  <td class="gen" align="left"><?php print(getpagetype($stats[$i][0]));?>
  </td>
  <td class="table" align="left"><?php print('<span class="gen">'.$stats[$i][0].':</span> <a href="'.$url.'" target="_blank" class="gen">'.text2html(getpagetitle($stats[$i][0])).'</a>');?>
  </td>
</tr>
<?php
    }
?>
</table>
<?php
  }
  else
  {
    print('<p class="pagetitle">Stats for '.$month.' '.$year.' not available');
  }
?>
</td></tr>
</table>

<?php
$footer = new HTMLFooter();
print($footer->toHTML());
}

?>
