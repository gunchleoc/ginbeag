<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));

require_once $projectroot."includes/objects/template.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."functions/pages.php";
require_once $projectroot."functions/pagecontent/newspages.php";

//
//
//
class RSSItem extends Template
{

    function RSSItem($newsitem,$title,$description,$link,$source,$pubdate)
    {
        parent::__construct();
        $this->stringvars['title']=html2xml($title);
        $this->stringvars['description']=html2xml($description);

        if($link) {
            $this->stringvars['link']=urlencode($link);
        }
        if($source) {
            $this->stringvars['source']=html2xml($source);
        }

        $this->stringvars['pubdate']=html2xml($pubdate);
        $this->stringvars['guid']=getprojectrootlinkpath().makelinkparameters(array("newsitem" => $newsitem));
    }

    // assigns templates and list objects
    function createTemplates()
    {
        $this->addTemplate("pages/news/rssitem.tpl");
    }
}


//
//
//
class RSSPage extends Template
{

    function RSSPage($page)
    {
        global $defaultlanguage;

        parent::__construct();

        $rootlink=getprojectrootlinkpath();
        $sitename=getproperty("Site Name");
        $this->stringvars['title']=html2xml(title2html($sitename.' - '.getnavtitle($page)));
        $this->stringvars['link']=$rootlink.'index.php'.makelinkparameters(array("page" => $page));
        $this->stringvars['description']=html2xml(title2html(getpagetitle($page)));
        $this->stringvars['language']=$defaultlanguage;
        $this->stringvars['serverprotocol']=getproperty("Server Protocol");

        $permissions=getcopyright($page);
        $this->stringvars['copyright']=html2xml(title2html($permissions['copyright']));

        $imageurl=getproperty("Left Header Image");
        if(!$imageurl) { $imageurl=getproperty("Right Header Image");
        }
        $this->stringvars['imageurl']=$rootlink.'img/'.$imageurl;
        $this->stringvars['imagetitle']=html2xml(title2html($sitename));
        $this->stringvars['imagelink']=$rootlink;


        // get newsitems, needed here for pubdate
        $newsitemsperpage=getproperty("News Items Per Page");
        if(!($newsitemsperpage>0)) { $newsitemsperpage = 5;
        }
        $newsitems=getpublishednewsitems($page, $newsitemsperpage, 0);

        $contents=getnewsitemcontents($newsitems[0]);
        $this->stringvars['pubdate']=@date("r", strtotime($contents['date']));
        $this->stringvars['lastbuilddate']=@date("r", strtotime(geteditdate($page)));

        for($i=0;$i<count($newsitems);$i++)
        {
            $contents=getnewsitemcontents($newsitems[$i]);

            $description=html2xml(text2html($contents['synopsis'], true));
            if($contents['source']) {
                $source=html2xml(title2html($contents['source']));
                $description.='<p>Source: '.html2xml(title2html($contents['source'])).'</p>';
            }
            $link=$contents['sourcelink'];
            if(str_startswith($link, '?')) {
                $link=$rootlink.'index.php'.$link;
            }
            $pubdate=@date("r", strtotime($contents['date']));

            $this->listvars['item'][]= new RSSItem($newsitems[$i], html2xml(title2html($contents['title'])), $description, $link, $contents['source'], $pubdate);
        }

    }

    // assigns templates and list objects
    function createTemplates()
    {
        $this->addTemplate("pages/news/rsspage.tpl");
    }
}

//
//
//
function html2xml($text)
{

    return html_entity_decode(strip_tags($text, '<p> </p> <br> <br />'), ENT_QUOTES, "UTF-8");
}

//
//
//
function link2xml($link)
{
    $url=parse_url($link);
    $result="";
    if(isset($url['scheme']) && $url['scheme']) { $result.=$url['scheme'].'://';
    } elseif(isset($url['host']) && $url['host']) { $result.=getproperty("Server Protocol").$url['host'];
    }
    return $result;
}

?>
