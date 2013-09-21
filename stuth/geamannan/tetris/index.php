<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"stuth"));

include_once($projectroot."includes/objects/page.php");
?>
   

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
	<title>Fòram na Gàidhlig - Tetris</title>
	<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
		<link href="tetris.css"	rel="stylesheet" type="text/css">
	<script	type="text/javascript" src="jquery.js"></script>
	<script	type="text/javascript" src="tetris.js"></script>
	<style type="text/css">
	html, body { height: 100%; }
	#tetris	{ margin: 0	auto; }
	</style>
	<link rel="stylesheet" href="http://www.foramnagaidhlig.net/templates/fng/main.css" type="text/css">
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
                  Tetris
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
/*if(getproperty('Display Banners'))
{
  $banners=new BannerList();
  print($banners->toHTML());
}
*/
$db->closedb();
?>
<td>&nbsp;</td>

<td valign="top" align="center" width="*" class="table">
<table border="0" cellpadding="20" cellspacing="1" width="100%">
  <tr>
    <td align="left">


<!--  Game HTML starts here  -->  

<p>&nbsp;</p>
<table style="width: 100%; height: 100%;" cellspacing="0" cellpadding="0"><tr><td style="vertical-align: middle;">

	<div id="tetris">
		<div class="left">
			<h1><a href="http://code.google.com/p/js-tetris/">Js	Tetris 1.19</a></h1>
			<div class="menu">
				<div><a href="javascript:void(0)" id="tetris-menu-start">Geama ùr</a></div>
				<div id="tetris-pause">
					<a href="javascript:void(0)" id="tetris-menu-pause">Gabh anail</a>
				</div>
				<div style="display: none;" id="tetris-resume">
					<a href="javascript:void(0)" id="tetris-menu-resume">Lean air</a>
				</div>
				<div><a href="javascript:void(0)" id="tetris-menu-highscores">Sgòran àrda</a></div>
				<div><a href="javascript:void(0)" id="tetris-menu-help">Mu dheidhinn</a></div>
			</div>
			<div id="tetris-nextpuzzle"></div>
			<div id="tetris-gameover">Crìoch a’ gheama</div>
			<div id="tetris-keys">
				<div class="h5">Meur-chlàr:</div>
				<table cellspacing="0" cellpadding="0">
				<tr>
					<td>Cuairtich:</td>
					<td></td>
					<td><img src="key-up.gif" width="14" height="14" alt=""></td>
					<td></td>
				</tr>
				<tr>
					<td>Gluais:</td>
					<td><img src="key-left.gif" width="14" height="14" alt=""></td>
					<td><img src="key-down.gif" width="14" height="14" alt=""></td>
					<td><img src="key-right.gif" width="14" height="14" alt=""></td>
				</tr>
				<tr>
					<td>Tuit:</td>
					<td colspan="3">
						<img src="key-space.gif" width="44" height="13" alt="">
					</td>
				</tr>
				</table>
			</div>
			<div class="stats">
				<div class="h5">Staitistig:</div>
				<table cellspacing="0" cellpadding="0">
				<tr>
					<td	class="level">Rang:</td>
					<td><span id="tetris-stats-level">1</span></td>
				</tr>
				<tr>
					<td	class="score">Sgòr:</td>
					<td><span id="tetris-stats-score">0</span></td>
				</tr>
				<tr>
					<td	class="lines">Loidhnichean:</td>
					<td><span id="tetris-stats-lines">0</span></td>
				</tr>
				<tr>
					<td	class="apm">Gnìomh/mion:</td>
					<td><span id="tetris-stats-apm">0</span></td>
				</tr>
				<tr>
					<td	class="time">Ùine:</td>
					<td><span id="tetris-stats-time">0</span></td>
				</tr>

				</table>
			</div>
		</div>
		<div class="left-border"></div>
		<div id="tetris-area">
			<div class="grid1"></div>
			<div class="grid2"></div>
			<div class="grid3"></div>
			<div class="grid4"></div>
			<div class="grid5"></div>
			<div class="grid6"></div>
		</div>
		<div id="tetris-help" class="window">
			<div class="top">
				Mu dheidhinn an geama <span id="tetris-help-close" class="close">x</span>
			</div>
			<div class="content" style="margin-top:	1em;">
				<div style="margin-top:	1em;">
				<div>’S e geama tetris a th’ anns an JsTetris a ghabhas gnàthachadh sgìobhte le javascript.
				Tha bun-tùs a’ chòd ri fhaighinn, agus faodaidh tu atharrachadh.
				</div>
				<br>
				<div>Ùghdar: Cezary Tomczak</div>
				<div>Làrach-lìn: <a href="http://www.gosu.pl/tetris/">www.gosu.pl/tetris/</a></div>
				<br>
				<div>Ceadachas: BSD revised (saor do gach cleachdadh)</div>
				</div>
			</div>
		</div>
		<div id="tetris-highscores"	class="window">
			<div class="top">
				Na sgòran as àirde <span id="tetris-highscores-close" class="close">x</span>
			</div>
			<div class="content">
				<div id="tetris-highscores-content"></div>
				<br>
			</div>
		</div>
	</div>

</td></tr></table>
<p class="gen">Cleachdaidh an geama seo JavaScript agus briosgaidean.</p>
<p class="gen">Ma tha sgrìn beag agad, <a href="index.html">falaich na bannan-cinn is clàran-taice</a>.</p>
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