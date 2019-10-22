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
    <title>Fòram na Gàidhlig - Leacan</title>
    <meta http-equiv="Content-Type"    content="text/html;    charset=utf-8">
    <link rel="stylesheet" href="../../../templates/fng/main.css" type="text/css">
    <link href="leacan.css"    rel="stylesheet" type="text/css">
    <script type="text/javascript" src="../../../includes/javascript/jquery.js"></script>
    <script type="text/javascript" src="leacan.js"></script>
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
        <h1 id="headerpagetitle" class="headerpagetitle newline">Leacan</h1>

        <div class="invisible"><a href="#contentarea" accesskey="n" class="invisible">Skip navigation</a></div>

        <div id="navigator" title="Clàr-taice">
<?php
$navigator = new Navigator(38, false, 1, false, false);
print($navigator->toHTML());
if(getproperty('Display Banners')) {
    $banners=new BannerList();
    print($banners->toHTML());
}
?>
        </div>
        <div id="contentarea" style="height: 950px;" title="Susbaint">
<!--  Game HTML starts here  -->
                <h2 class="pagetitle">Leacan</h2>

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
            <h2 align="center" id="gametitle"></h2>
            <div id="messages"></div>
        </td>
    </tr>
</table>
<hr>
<h4>Briog air dealbh gus geama ùr a thòiseachadh</h4>
<div id="games"></div>


<br /><hr>

<p class="gen">Cleachdaidh an geama seo JavaScript.</p>

<!-- End game HTML -->
</div>
    <div class="footer newline"></div>
    </div>
</body>
</html>
