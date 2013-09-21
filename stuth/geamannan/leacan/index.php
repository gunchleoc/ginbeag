<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"stuth"));

include_once($projectroot."includes/templates/page.php");
?>
   

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
 	<meta name="keywords" content="leacan, tiles, game, geama, games, geamaichean, Gaelic, Scottish Gaelic, Scots Gaelic, Schottisch-Gälisch, Gàidhlig, Fòram, bòrd-brath, forum, learn, learn Gaelic, learn scottish gaelic, grammar, write gaelic, gaelic online community, learning gaelic, speak gaelic, sgrìobh sa Ghàidhlig">
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
	<title>Fòram na Gàidhlig - Leacan</title>
	<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
	<link rel="stylesheet" href="http://www.foramnagaidhlig.net/page.css" type="text/css">
	<link href="leacan.css"	rel="stylesheet" type="text/css">
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="leacan.js"></script>
</head>
<body>
 
<table align="center" border="0" cellpadding="10" cellspacing="0" width="100%">
  <tr>
    <td class="bodyline">
    <table border="0" width="100%" cellpadding="0">
      <tr>
        <td colspan="3">
          <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
              <td>
                <a href="http://www.foramnagaidhlig.net/index.php">
                  <img src="http://www.foramnagaidhlig.net/img/fnglogo_cearn.gif" border="0" alt="F&ograve;ram na G&agrave;idhlig" vspace="1" />
                </a>
              </td>
              <td align="center" width="100%" valign="middle">
                <span class="maintitle">F&ograve;ram na G&agrave;idhlig</span>
                <br />
                <span class="gen"><i>Coimhearsneachd airson ionnsachadh is leasachadh na G&agrave;idhlig</i><br />&nbsp;</span>
                <table cellspacing="0" cellpadding="2" border="0">
                  <tr>
                    <td align="center" valign="top" nowrap="nowrap">&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="25" align="center" valign="top" nowrap="nowrap">&nbsp;</td>
                  </tr>
                </table>
              </td>
              <td>
              </td>
            </tr>
          </table>
          <br />
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <table width="100%" cellpadding="10" cellspacing="0" border="0">
            <tr>
              <th class="thTop">
                <font size="+0">
                  Leacan
                </font>
                <br />
              </th>
            </tr>
          </table>
        </td>
      </tr>
      <tr><td>&nbsp;</td></tr>
<tr><td valign="top" width="20%">

<?php
$navigator = new Navigator(38,false,1,false,false);
print($navigator->toHTML());
if(getproperty('Display Banners'))
{
  $banners=new BannerList();
  print($banners->toHTML());
}

?>
<td>&nbsp;</td>

<td valign="top" align="center" width="*" class="table">
<table border="0" cellpadding="20" cellspacing="1" width="100%">
  <tr>
    <td align="left">


<!--  Game HTML starts here  -->  

<h1 align="center">Leacan</h1>

<table cellspacing="20"  align="center">
	<tr>
		<td valign="top">
			<table cellspacing="0" cellpadding="0" class="frame">
				<tr>
					<td class="frame">
						<img id="0_0" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="0_1" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="0_2" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="0_3" class="frame" src="" />
					</td>
				</tr>
				<tr>
					<td class="frame">
						<img id="1_0" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="1_1" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="1_2" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="1_3" class="frame" src="" />
					</td>
				</tr>
				<tr>
					<td class="frame">
						<img id="2_0" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="2_1" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="2_2" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="2_3" class="frame" src="" />
					</td>
				<tr>
					<td class="frame">
						<img id="3_0" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="3_1" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="3_2" class="frame" src="" />
					</td>
					<td class="frame">
						<img id="3_3" class="frame" src="" />
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">
			<img id="master" src ="" class="master" />
			<h2 align="center" id="gametitle" style="width:150px; font-size:110%;"></h2>
			<div id="messages" style="width:150px;"></div>
		</td>
	</tr>
</table>
<hr>
<h4>Briog air dealbh gus geama ùr a thòiseachadh</h4>
<div id="games"></div>


<br /><hr>

<p class="gen">Cleachdaidh an geama seo JavaScript.</p>

<!-- End game HTML -->
    </td>
  </tr>
</table>
    </td>
  </tr>
</table>
  </td>
</tr>
        </table>
      </td>
    </tr>
  </table>
  <table width="100%">
    <tr>
      <td align="left">
        <div align="left" class="footer">
        </div>
      </td>
      <td align="right">
        <div align="right" class="footer">
        </div>
      </td>
    </tr>
  </table>
</body>
</html>