<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"stuth"));

include_once($projectroot."includes/templates/page.php");
?>
   

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
	<title>Fòram na Gàidhlig - An Crochadair</title>
	<meta http-equiv="Content-Type"	content="text/html;	charset=utf-8">
	<link rel="stylesheet" href="http://www.foramnagaidhlig.net/page.css" type="text/css">
	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="crochadair.js"></script>

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
                  An Crochadair
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

<FORM NAME="game">
	<table>
		<tr>
			<td colspan="2"><H1>An Crochadair</H1></td>
		</tr>
		<tr>
			<td><img NAME="status" id="status" src="gungheama.gif" style="padding:2px; border:1px solid #021a40; background-color:sienna;"/></td>
			<td>
				<P>Am facal ri lorg:</p>
				<P><INPUT TYPE="TEXT" size="45" NAME="toGuess"  /></p>
				
				<P>Tagh litir.</P>
				<p>
					<INPUT id="a" TYPE="BUTTON" VALUE=" A ">
					<INPUT id="à" TYPE="BUTTON" VALUE=" À ">
					<INPUT id="e" TYPE="BUTTON" VALUE=" E ">
					<INPUT id="è" TYPE="BUTTON" VALUE=" È ">
					<INPUT id="i" TYPE="BUTTON" VALUE=" I ">
					<INPUT id="ì" TYPE="BUTTON" VALUE=" Ì ">
					<INPUT id="o" TYPE="BUTTON" VALUE=" O ">
					<INPUT id="ò" TYPE="BUTTON" VALUE=" Ò ">
					<INPUT id="u" TYPE="BUTTON" VALUE=" U ">
					<INPUT id="ù" TYPE="BUTTON" VALUE=" Ù ">
					&nbsp;&nbsp;&nbsp;&nbsp;
					<INPUT id="-" TYPE="BUTTON" VALUE=" - ">
					<INPUT id="asgair" TYPE="BUTTON" VALUE=" ' ">
				</p>
				<p>
					<INPUT id="b" TYPE="BUTTON" VALUE=" B ">
					<INPUT id="c" TYPE="BUTTON" VALUE=" C ">
					<INPUT id="d" TYPE="BUTTON" VALUE=" D ">
					<INPUT id="f" TYPE="BUTTON" VALUE=" F ">
					<INPUT id="g" TYPE="BUTTON" VALUE=" G ">
					<INPUT id="h" TYPE="BUTTON" VALUE=" H ">
					<INPUT id="l" TYPE="BUTTON" VALUE=" L ">
					<INPUT id="m" TYPE="BUTTON" VALUE=" M ">
					<INPUT id="n" TYPE="BUTTON" VALUE=" N ">
					<INPUT id="p" TYPE="BUTTON" VALUE=" P ">
					<INPUT id="r" TYPE="BUTTON" VALUE=" R ">
					<INPUT id="s" TYPE="BUTTON" VALUE=" S ">
					<INPUT id="t" TYPE="BUTTON" VALUE=" T ">
				</p>
				
				<p>
					Na litrichean a chleachd thu gu ruige seo:
					<br />&nbsp;<br /><span id="guessed" NAME="guessed" style="font-size:80%" /> </span>
				</p>
				<p>&nbsp;</p>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<p id="messages" name="messages"><span style="color: red; font-weight:bold;">An tòisich thu air geama ùr?</span></p>
			</td>
		</tr>
	</table>
	<P><INPUT TYPE="BUTTON" id="restart" NAME="restart" style="font-size:80%" VALUE="Tòisich air geama ùr"></p>
  <P>Tagh stòr-dàta:
<span style="font-size:80%">
  <br />

    <input type="radio" id="iomlan" name="stordata" value="iomlan" checked="checked" /> Liosta mòr de dh'fhaclan<br>
    <input type="radio" id="àiteachan" name="stordata" value="àiteachan" /> Ainmean-àite<br>
    </span>
  </p>
 
</FORM>

<hr>
<p style="font-family: Arial, Helvetica, Sans Serif; font-size: 65%;">
Tha an geama seo stèidhichte air JavaScript a fhuair mi air <a href="http://www.java2s.com/Code/JavaScript/Page-Components/AJavaScriptHangmanGame.htm">java2s.com</a>.
</p>
<p style="font-family: Arial, Helvetica, Sans Serif; font-size: 65%;">
Dealbhan le taic bho <a href="http://opengameart.org/">OpenGameArt.org</a>:
<br />An cnàimheach le <a href="http://gord-goodwin.blogspot.com/2010/03/manny-mannequin.html">Gord Goodwin</a> <a href='http://creativecommons.org/publicdomain/zero/1.0/'><br /><img src='http://opengameart.org/sites/default/files/license_images/cc0.png' alt='' title=''><br />CC0</a>
<br /> agus na h-eòin le <a href="https://github.com/leezh/flying-penguin">leezh</a>.<br /><a href='http://creativecommons.org/licenses/by/3.0/'><img src='http://opengameart.org/sites/default/files/license_images/cc-by.png' alt='' title=''><br />CC-BY 3.0</a>
</p>
<p style="font-family: Arial, Helvetica, Sans Serif; font-size: 65%;">
Cleachdaidh an geama seo JavaScript.</p>

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