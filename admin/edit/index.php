<?php
$keys=array_keys($_GET);
$values=array_values($_GET);

$params="";

if(count($keys>0))
{
  $params.="?".$keys[0]."=".$values[0];
  for($i=1;$i<count($keys);$i++)
  {
    $params.="&".$keys[$i]."=".$values[$i];
  }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta http-equiv="Content-Style-Type" content="text/css">
  <link rel="stylesheet" href="../page.css" type="text/css">
  <meta http-equiv="refresh" content="1;url=../../index.php<?php print($params); ?>">
	<title>Redirecting</title>
</head>
<body>
</body>
</html>
