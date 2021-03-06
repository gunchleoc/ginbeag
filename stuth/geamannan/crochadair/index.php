<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "stuth"));

require_once $projectroot."includes/objects/page.php";
$meta_content = '<meta name="keywords" content="Gaelic, Scottish-Gaelic, Scots Gaelic, Schottisch-Gälisch, Gàidhlig, Fòram, bòrd-brath, forum">';
$meta_content .= '<script type="text/javascript" src="../../../includes/javascript/jquery.js"></script>';
$meta_content .= '<script type="text/javascript" src="crochadair.js"></script>';
$meta_content .= '<link rel="stylesheet" href="crochadair.css" type="text/css">';

$header = new PageHeader(0, "An Crochadair", "An Crochadair", $meta_content);
print($header->toHTML());
?>
        <div id="navigator" title="Clàr-taice">
<?php
$navigator = new Navigator(38, false, 1, false, false);
print($navigator->toHTML());
if (getproperty('Display Banners')) {
    $banners=new BannerList();
    print($banners->toHTML());
}
?>
        </div>
        <div id="contentarea" style="height: 950px;" title="Susbaint">
<!--  Game HTML starts here  -->
                <h2 class="pagetitle">An Crochadair</h2>

<FORM NAME="game">
    <table>
        <tr>
            <td valign="top">
                <img NAME="status" id="status" src="gungheama.gif" style="padding:2px; border:1px solid #021a40; background-color:sienna;"/>

                <P><INPUT TYPE="BUTTON" id="restart" NAME="restart" VALUE="Tòisich air geama ùr"></p>
                  <P>Tagh stòr-dàta:
                          <br />
                          <input type="radio" id="beag" name="stordata" value="beag" checked="checked" /> Na faclan beaga<br>
                        <input type="radio" id="iomlan" name="stordata" value="iomlan" /> Liosta mòr de dh'fhaclan<br>
                        <input type="radio" id="àiteachan" name="stordata" value="àiteachan" /> Ainmean-àite<br>
                  </p>
            </td>
            <td>&nbsp;</td>
            <td valign="top">
                <P>Am facal ri lorg:</p>
                <P style="border:4px double sienna;background-color:#F7F7F7;" >
                    <INPUT TYPE="TEXT" size="45" NAME="toGuess"  style="background-color:#F7F7F7;font-size:120%; font-weight=bold; border:0px; padding:0.3em; " />
                </p>

                <P>Tagh litir.</P>
                <p>
                    <INPUT id="a" TYPE="BUTTON" VALUE="A">
                    <INPUT id="à" TYPE="BUTTON" VALUE="À">
                    <INPUT id="e" TYPE="BUTTON" VALUE="E">
                    <INPUT id="è" TYPE="BUTTON" VALUE="È">
                    <INPUT id="i" TYPE="BUTTON" VALUE="I">
                    <INPUT id="ì" TYPE="BUTTON" VALUE="Ì">
                    <INPUT id="o" TYPE="BUTTON" VALUE="O">
                    <INPUT id="ò" TYPE="BUTTON" VALUE="Ò">
                    <INPUT id="u" TYPE="BUTTON" VALUE="U">
                    <INPUT id="ù" TYPE="BUTTON" VALUE="Ù">
                    <INPUT TYPE="BUTTON" VALUE="" style="font-size:100%; color:white; background-color:white; width:2em; border:4px transparent;" disabled="true">
                    <INPUT id="-" TYPE="BUTTON" VALUE="-">
                    <INPUT id="asgair" TYPE="BUTTON" VALUE="'">
                </p>
                <p>
                    <INPUT id="b" TYPE="BUTTON" VALUE="B">
                    <INPUT id="c" TYPE="BUTTON" VALUE="C">
                    <INPUT id="d" TYPE="BUTTON" VALUE="D">
                    <INPUT id="f" TYPE="BUTTON" VALUE="F">
                    <INPUT id="g" TYPE="BUTTON" VALUE="G">
                    <INPUT id="h" TYPE="BUTTON" VALUE="H">
                    <INPUT id="l" TYPE="BUTTON" VALUE="L">
                    <INPUT id="m" TYPE="BUTTON" VALUE="M">
                    <INPUT id="n" TYPE="BUTTON" VALUE="N">
                    <INPUT id="p" TYPE="BUTTON" VALUE="P">
                    <INPUT id="r" TYPE="BUTTON" VALUE="R">
                    <INPUT id="s" TYPE="BUTTON" VALUE="S">
                    <INPUT id="t" TYPE="BUTTON" VALUE="T">
                </p>

                <p>
                    Na litrichean a chleachd thu gu ruige seo:
                    <br />&nbsp;<br /><span id="guessed" NAME="guessed" style="font-size:110%; font-weight=bold; border:4px groove sienna; background-color:#F7F7F7; padding:0.3em; font-family:monospace;"/>&nbsp;</span><span style="font-size:80%">&nbsp;</span>
                </p>
                <p id="messages" name="messages" style="border:2px groove sienna; padding:0.3em; background-color:#F7F7F7;"><span style="font-weight:bold;">An tòisich thu air geama ùr?</span></p>
            </td>
        </tr>
    </table>

</FORM>

<hr>
<p class="medtext">
Tha an geama seo stèidhichte air JavaScript a fhuair mi air <a href="https://www.java2s.com/Code/JavaScript/Page-Components/AJavaScriptHangmanGame.htm">java2s.com</a>.
</p>
<p class="medtext">
Dealbhan le taic bho <a href="https://opengameart.org/">OpenGameArt.org</a>:
<br />An cnàimheach le <a href="https://gord-goodwin.blogspot.com/2010/03/manny-mannequin.html">Gord Goodwin</a> <a href='https://creativecommons.org/publicdomain/zero/1.0/'><br /><img src='https://opengameart.org/sites/default/files/license_images/cc0.png' alt='' title=''><br />CC0</a>
<br /> agus na h-eòin le <a href="https://github.com/leezh/flying-penguin">leezh</a>.<br /><a href='https://creativecommons.org/licenses/by/3.0/'><img src='https://opengameart.org/sites/default/files/license_images/cc-by.png' alt='' title=''><br />CC-BY 3.0</a>
</p>
<p class="medtext">
Cleachdaidh an geama seo JavaScript.</p>

<!-- End game HTML -->
<?php
$footer = new PageFooter();
print($footer->toHTML());
?>
