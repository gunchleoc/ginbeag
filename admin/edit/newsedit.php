<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "admin"));

require_once $projectroot."admin/functions/sessions.php";
require_once $projectroot."admin/functions/pagesmod.php";
require_once $projectroot."admin/functions/categoriesmod.php";
require_once $projectroot."admin/includes/objects/edit/newspage.php";
require_once $projectroot."includes/objects/elements.php";
require_once $projectroot."includes/objects/images.php";
require_once $projectroot."includes/functions.php";
require_once $projectroot."admin/includes/objects/adminmain.php";

checksession();

if(isset($_GET['page'])) { $page=$_GET['page'];
} else { $page=0;
}

if(isset($_POST['item'])) { $offset=getnewsitemoffset($page, 1, $_POST['item'], true);
} elseif(isset($_GET['newsitem'])) { $offset=getnewsitemoffset($page, 1, $_GET['newsitem'], true);
} elseif(isset($_GET['offset'])) { $offset=$_GET['offset'];
} else { $offset=0;
}

if(isset($_GET['articlepage'])) { $articlepage=$_GET['articlepage'];
} else { $articlepage=0;
}

if(isset($_GET['articlesection'])) { $articlesection=$_GET['articlesection'];
} else { $articlesection=0;
}

//print("Post: ");
//print_r($_POST);
//print("<br/Get: ");
//print_r($_GET);

// *************************** actions ************************************** //

// page content actions
if(!$page) {
    $editpage = noPageSelectedNotice();
    $message = "Please select a page first";
    $error = true;
}
else
{
    $message = getpagelock($page);
    $error = false;
    if(!$message) {
        // update news
        // add a newsitem
        if(isset($_POST['addnewsitem'])) {
            addnewsitem($page);
            updateeditdata($page);
            $offset=0;
            $editpage = new EditNewsItemForms($page, $offset);
            $message = "Added news item";
        }
        // synopsis
        elseif(isset($_POST['addnewsitemsynopsisimage'])) {
            $filename=trim($_POST['filename']);
            if(imageexists($filename)) {
                addnewsitemsynopsisimage($_GET['newsitem'], $filename);
                updateeditdata($page);
                $message = "Added synopsis image";
            }
            else
            {
                $message = 'Failed to add synopsis image. The image <i>'.$_POST['filename'].'</i> does not exist!';
            }
            $editpage = new EditNewsItemForms($page, $offset);
        }
        elseif(isset($_POST['editnewsitemsynopsisimage'])) {
            if(imageexists($_POST['imagefilename'])) {
                $message = "Edited synopsis image";
                editnewsitemsynopsisimage($_GET['imageid'], $_POST['imagefilename']);
                updateeditdata($page);
            }
            else
            {
                $message = "Failed to edit synopsis image. The image <i>".text2html($_POST['imagefilename'])."</i> does not exist!";
            }
            $editpage = new EditNewsItemForms($page, $offset);
        }
        elseif(isset($_POST['removenewsitemsynopsisimage'])) {
            if(isset($_POST['removeconfirm'])) {
                $message = "Removed a synopsis image";
                removenewsitemsynopsisimage($_GET['imageid']);
            }
            else
            {
                $message = "Failed to remove image. Please confirm when removing an image.";
                $error = true;
            }
            updateeditdata($page);
            $editpage = new EditNewsItemForms($page, $offset);
        }
        // sections
        elseif(isset($_POST['addsection'])) {
            addnewsitemsection($_GET['newsitem'], $_GET['newsitemsection']);
            updateeditdata($page);
            $editpage = new EditNewsItemForms($page, $offset);
            $message = "Added section to newsitem";
        }
        elseif(isset($_POST['addquotedsection'])) {
            addnewsitemsection($_GET['newsitem'], $_GET['newsitemsection'], true);
            updateeditdata($page);
            $editpage = new EditNewsItemForms($page, $offset);
            $message = "Added quoted section to newsitem on page";
        }
        elseif(isset($_POST['deletesection'])) {
            $editpage = new DeleteNewsItemSectionConfirm($_GET['newsitem'], $_GET['newsitemsection']);
        }
        elseif(isset($_POST['confirmdeletesection'])) {
            deletenewsitemsection($_GET['newsitem'], $_GET['newsitemsection']);
            updateeditdata($page);
            $editpage = new EditNewsItemForms($page, $offset);
            $message = "Section deleted";
        }
        elseif(isset($_POST['nodeletesection'])) {
            $editpage = new EditNewsItemForms($page, $offset);
            $message="Deleting of section aborted";
        }
        // searching
        elseif(isset($_POST['search']) && isset($_POST['title']) && strlen($_POST['title']) > 0) {
            $editpage = new NewsItemSearchResults(fixquotes($_POST['title']));
        }
        // deleting
        elseif(isset($_POST['deleteitem'])) {
            $editpage = new DeleteNewsItemConfirm($_GET['newsitem']);
        }
        elseif(isset($_POST['confirmdeleteitem'])) {
            deletenewsitem($_GET['newsitem']);
            updateeditdata($page);
            $editpage = new EditNewsItemForms($page, $offset);
            $message = "Newsitem deleted";
        }
        else
        {
            $editpage = new EditNewsItemForms($page, $offset);
        }
    }
    // locked page
    else
    {
        $editpage = new pageBeingEditedNotice($message);
    }
}
$content = new AdminMain($page, "editcontents", new AdminMessage($message, $error), $editpage);
print($content->toHTML());
?>
