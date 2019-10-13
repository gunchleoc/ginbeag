<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "stuth"));

require_once $projectroot."includes/objects/page.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
    <meta name="keywords" content="Gaelic, Scottish-Gaelic, Scots Gaelic, Schottisch-Gälisch, Gàidhlig, Fòram, bòrd-brath, forum">
    <title>Fòram na Gàidhlig - Tetris</title>
    <meta http-equiv="Content-Type"    content="text/html;    charset=utf-8">
    <link href="tetris.css"    rel="stylesheet" type="text/css">
    <script    type="text/javascript" src="jquery.js"></script>
    <script    type="text/javascript" src="tetris.js"></script>
    <style type="text/css">
    html, body { height: 100%; }
    #tetris    { margin: 0    auto; }
    </style>
    <link rel="stylesheet" href="main.css" type="text/css">

    <script language="JavaScript">

// special treatment for IE
if(navigator.appName =="Microsoft Internet Explorer")
{
    document.write('<link rel="stylesheet" type="text/css" href="templates/ie.css">');
}

$(document).ready(function() {
    // height for pages where content is shorter than navigator
    var navheight = $("#navigator").outerHeight(true);
    var bannerheight = $("#banners").outerHeight(true);
    var highestheader = $("#headerleft");

    if ($("#headerright").outerHeight(true) > highestheader.outerHeight(true))
    {
        highestheader = $("#headerright");
    }
    if ($("#headercenter").outerHeight(true) > highestheader.outerHeight(true))
    {
        highestheader = $("#headercenter");
    }
    var headerheight = highestheader.outerHeight(true);
    var titleheight = $("#headerpagetitle").outerHeight(true);
    var wrapperheight = Math.ceil(navheight+bannerheight+headerheight+titleheight);
    if ($("#wrapper").height() < wrapperheight)
    {
        $("#wrapper").height(wrapperheight);
        var difference = $("#wrapper").outerHeight() - $("#wrapper").height();
        //var difference = $("#wrapper").css("margin-top")+ $("#wrapper").css("margin-bottom")+ $("#wrapper").css("padding-top")+$("#wrapper").css("padding-bottom");
        var margin = $("#contentarea").css("margin-bottom").replace("px","") + $("#contentarea").css("margin-top").replace("px","");
        $("#contentarea").height(Math.ceil(navheight+bannerheight-difference-margin));
    }

});
    </script>

</head>
<body>
    <div id="wrapper">
        <div id="headerleft">
            <a href="http://www.foramnagaidhlig.net/">
                <img src="../../../img/fnglogo_cearn.gif" border="0" alt="Fòram na Gàidhlig" vspace="1" />
            </a>
        </div>
        <div id="headercenter">
            <h1 class="maintitle">Fòram na Gàidhlig</h1>

            <div id="sitedescription">Coimhearsneachd airson ionnsachadh is leasachadh na Gàidhlig</div>

        </div>
        <div id="headerright">


        </div>
        <h1 id="headerpagetitle" class="headerpagetitle newline">Tetris</h1>

        <div class="invisible"><a href="#contentarea" accesskey="n" class="invisible">Skip navigation</a></div>

        <div id="navigator" title="Clàr-taice">
<?php
$navigator = new Navigator(38, false, 1, false, false);
print($navigator->toHTML());
$db->closedb();
?>
        </div>
        <div id="contentarea" style="height: 500px;" title="Susbaint">
            <div style="height:40px"></div>
<!--  Game HTML starts here  -->
    <div id="tetris">
        <div class="left">
            <h1><a href="http://code.google.com/p/js-tetris/">Js    Tetris 1.19</a></h1>
            <div class="menu">
                <div><a href="javascript:void(0)" id="tetris-menu-start">Geama ùr</a></div>
                <div id="tetris-pause">
                    <a href="javascript:void(0)" id="tetris-menu-pause">Gabh anail</a>
                </div>
                <div style="display: none;" id="tetris-resume">
                    <a href="javascript:void(0)" id="tetris-menu-resume">Lean air</a>
                </div>
                <div><a href="javascript:void(0)" id="tetris-menu-highscores">Sgòran àrda</a></div>
                <div><a href="javascript:void(0)" id="tetris-menu-help">Mu dhèidhinn</a></div>
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
                <div class="h5">Stadastaig:</div>
                <table cellspacing="0" cellpadding="0">
                <tr>
                    <td    class="level">Rang:</td>
                    <td><span id="tetris-stats-level">1</span></td>
                </tr>
                <tr>
                    <td    class="score">Sgòr:</td>
                    <td><span id="tetris-stats-score">0</span></td>
                </tr>
                <tr>
                    <td    class="lines">Loidhnichean:</td>
                    <td><span id="tetris-stats-lines">0</span></td>
                </tr>
                <tr>
                    <td    class="apm">Gnìomh/mion:</td>
                    <td><span id="tetris-stats-apm">0</span></td>
                </tr>
                <tr>
                    <td    class="time">Ùine:</td>
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
                Mu dhèidhinn a' gheama <span id="tetris-help-close" class="close">x</span>
            </div>
            <div class="content" style="margin-top:    1em;">
                <div style="margin-top:    1em;">
                <div>’S e geama tetris a th’ anns an JsTetris a ghabhas gnàthachadh sgìobhte le javascript.
                Tha bun-tùs a’ chòd ri fhaighinn, agus faodaidh tu atharrachadh.
                </div>
                <br>
                <div>Ùghdar: Cezary Tomczak</div>
                <div>Làrach-lìn: B' àbhaist dha a bhith air <em>www.gosu.pl/tetris/</em> ach gheibh thu a' phròiseact JSTetris air <a href="http://code.google.com/p/js-tetris/">Google Code</a> a-nis.</div>
                <br>
                <div>Ceadachas: BSD revised (saor do gach cleachdadh)</div>
                </div>
            </div>
        </div>
        <div id="tetris-highscores"    class="window">
            <div class="top">
                Na sgòran as àirde <span id="tetris-highscores-close" class="close">x</span>
            </div>
            <div class="content">
                <div id="tetris-highscores-content" style="font-size: 11px; color: #002373;"></div>
                <br>
            </div>
        </div>
    </div>

<p class="gen">Cleachdaidh an geama seo JavaScript agus briosgaidean.</p>
<p class="gen">Ma tha sgrìn beag agad, <a href="index.html">falaich na bannan-cinn is clàran-taice</a>.</p>
<!-- End game HTML -->
        </div>
    <div class="footer newline"></div>
    </div>
</body>
</html>
