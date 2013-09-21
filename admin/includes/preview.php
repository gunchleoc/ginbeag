<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"admin"));

include_once($projectroot."includes/includes.php");
include_once($projectroot."admin/includes/adminelements.php");

print('<table width="100%"><tr><td>');

if(isset($_GET['image']))
{
  $header = new HTMLHeader("Previewing Image","Webpage Building");
  print('<img src="'.trim($_GET['image']).'">');
}
elseif(isset($_GET['newsitem']))
{
  $header = new HTMLHeader("Previewing Newsitem","Webpage Building");
  include_once($projectroot."includes/templates/newspage.php");

  $newsitem = new Newsitem($_GET['newsitem'],$_GET['page'],0,true,true,false);
  print($newsitem->toHTML());
}
elseif(isset($_GET['page']))
{
  $keys=array_keys($_GET);
  if(count($keys))
  {
    $params='?'.$keys[0].'='.$_GET[$keys[0]];
    for($i=1;$i<count($keys);$i++)
    {
      $params.='&'.$keys[$i].'='.$_GET[$keys[$i]];
    }
  }
  $header = new HTMLHeader("Redirecting from preview","Webpage Building","",'../../'.$params,"Redirecting from preview",true);
}
else
{
  $text=text2html($_GET['text']);
  $header = new HTMLHeader("Previewing Text","Webpage Building");
  print($text);
}
print($header->toHTML());
print('</td></tr></table>');
?>
<p><hr><p>
<form>
    <input type="button" name="close" value="Close" onClick="window.close()" class="mainoption">
</form>
<?php
$footer = new HTMLFooter();
print($footer->toHTML());
?>

