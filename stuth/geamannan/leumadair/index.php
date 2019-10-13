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
    <title>Fòram na Gàidhlig - Leumadairean</title>
    <meta http-equiv="Content-Type"    content="text/html;    charset=utf-8">
    <link rel="stylesheet" href="../../../templates/fng/main.css" type="text/css">
    <link href="leumadair.css"    rel="stylesheet" type="text/css">
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="leumadair.js"></script>
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
        <h1 id="headerpagetitle" class="headerpagetitle newline">Leumadairean</h1>

        <div class="invisible"><a href="#contentarea" accesskey="n" class="invisible">Skip navigation</a></div>

        <div id="navigator" title="Clàr-taice">
<?php
$navigator = new Navigator(38, false, 1, false, false);
print($navigator->toHTML());
if(getproperty('Display Banners')) {
    $banners=new BannerList();
    print($banners->toHTML());
}
$db->closedb();
?>
        </div>
        <div id="contentarea" style="height: 950px;" title="Susbaint">
<!--  Game HTML starts here  -->
                <h2 class="pagetitle">Leumadairean</h2>

<table cellspacing="20"  align="center">
    <tr>
        <td id="sea" valign="top" class="sea">
            <table id="square" cellspacing="0" cellpadding="0" class="frame">
                <!-- game square goes here per JavaScript //-->
            </table>
        </td>
        <td valign="top">
            <h2 align="center" id="gametitle"></h2>
            <div id="messages"></div>
        </td>
    </tr>
</table>
<br />
<hr>
<h4>Briog air dealbh gus ìre airson geama ùr a thaghadh</h4>
<div id="games"></div>

<br /><hr>

<p class="gen">Cleachdaidh an geama seo JavaScript.</p>


<!-- End game HTML -->
</div>
    <div class="footer newline"></div>
    </div>
</body>
</html>
