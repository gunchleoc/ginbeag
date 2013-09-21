<?php
$projectroot=dirname(__FILE__)."/";

// check legal vars
include_once($projectroot."includes/legalvars.php");

include_once($projectroot."functions/email.php");
include_once($projectroot."includes/templates/page.php");

if(isset($_GET['language']))
{
  $language=$_GET['language'];
}
else $language="";

if(isset($_GET['user']))
{
  $recipient=getuseremail($_GET['user']);
  $userid=getuserid($_GET['user']);
}
elseif(isset($_POST['userid']))
{
  $userid=$_POST['userid'];
  if($userid==0)
  {
    $recipient=getproperty("Admin Email Address");
  }
  else
  {
    $recipient=getuseremail($userid);
  }
}
else
{
  $recipient=getproperty("Admin Email Address");
  $userid=0;
}

$header = new PageHeader(0,getlang("pagetitle_contact"));
print($header->toHTML());

?>
<tr><td valign="top" width="20%">

<?php
$navigator = new Navigator($page_id,$displaysisters,$navigatordepth-1,false,$showhidden);
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
<?php

//display new contact form
if(!isset($_POST[$emailvariables['E-Mail Address Variable']['property_value']]))
{
  print('<p class="pagetitle">'.getlang("pageintro_contact").'</p>');
 displayemailform("","","",true,$userid,$language);
}
// check data and send e-mail
else
{
  // get vars
  $addy=trim($_POST[$emailvariables['E-Mail Address Variable']['property_value']]);
  
//  print("test".$addy);
  $subject=trim($_POST[$emailvariables['Subject Line Variable']['property_value']]);
  $messagetext=trim(stripslashes($_POST[$emailvariables['Message Text Variable']['property_value']]));
  $sendcopy=$_POST['sendcopy'];

  $error=emailerror($addy,$subject,$messagetext,$sendcopy,$language);

  if($error!=="")
  {
    print('<p align="left">'.$error.'</p>');
    printemailinfo($addy,$subject,$messagetext,$sendcopy,$language);
    print('<p class="highlight">'.getlang("email_tryagain")."</p>");
    displayemailform($addy,$subject,$messagetext,$sendcopy,$userid,$language);
  }
  else
  {
    printemailinfo($addy,$subject,$messagetext,$sendcopy,$language);
    sendemail($addy,$subject,$messagetext,$sendcopy,$recipient,$language);
  }
}
?>
</td></tr></table>
</td></tr>
 <?php
$footer= new PageFooter();
print($footer->toHTML());

//
// display a contact form
//
function displayemailform($addy,$subject,$messagetext,$sendcopy,$userid,$language)
{
  global $emailvariables;

?>
<p>
<form name='email' action='?language=<?php print($language);?>' method='post'>
  <table>
    <tr>
      <td align='right'><span class="gen"><?php print(getlang("email_to"))?>:</span></td>
      <td>
        <select name="userid" size="1">
<?php
  $contacts=getallcontacts();
  for($i=0;$i<count($contacts);$i++)
  {
?>
          <option value="<?php print($contacts[$i]);?>"
            <?php if($contacts[$i]==$userid) print('selected'); ?>>
<?php
  print(stripslashes(getusername($contacts[$i])));
  $function=getcontactfunction($contacts[$i]);
  if(strlen($function)>0) print(" (".stripslashes($function).")");
?>
          </option>
<?php
  }
?>
          <option value="0"
            <?php if(0==$userid) print('selected'); ?>>
            Webmaster
          </option>
        </select>
      </td>
    </tr>
    <tr>
      <td align='right'><span class="gen"><?php print(getlang("email_address"))?>:</span></td>
      <td><input type='text' name='<?php print($emailvariables['E-Mail Address Variable']['property_value']);?>' size='60' value='<?php print($addy);?>' /></td>
    </tr>
    <tr>
      <td align='right'><span class="gen"><?php print(getlang("email_subject"))?>:</span></td>
      <td><input type='text' name='<?php print($emailvariables['Subject Line Variable']['property_value']);?>' size='60' value='<?php print($subject);?>' /></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td align='right' valign='top'><span class="gen"><?php print(getlang("email_message"))?>:</span></td>
      <td>
        <textarea name='<?php print($emailvariables['Message Text Variable']['property_value']);?>' cols='60' rows='20'><?php print($messagetext);?></textarea>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>
        <input type='checkbox' name='sendcopy' <?php if($sendcopy) print("checked");?>>
        <span class="gen"><?php print(getlang("email_sendcopy"))?></span>
      </td>
    </tr>
    
<?php
  if($emailvariables['Use Math CAPTCHA']['property_value'])
  {
    $captcha=makemathcaptcha();
?>

    <tr>
       <td align='right' valign='top'>&nbsp;</td>
       <td align='left'>&nbsp;<br><span class="gen"><?php print($captcha["question"]);?></span>
         <input type='text' name='<?php print($emailvariables['Math CAPTCHA Reply Variable']['property_value']);?>' size='2' value=''/>
         <input type='hidden' name='<?php print($emailvariables['Math CAPTCHA Answer Variable']['property_value']);?>' value='<?php print($captcha["answer"]);?>'/>
       </td>
    </tr>
<?php
  }
?>
    
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;<br><input type='submit' value='<?php print(getlang("email_sendemail"))?>'  class="mainoption"></td>
    </tr>
  </table>
</form>
<?php
}
// end function displayemailform


//
//
//
function makemathcaptcha()
{
    $result=array();

    list($usec, $sec) = explode(' ', microtime());
    $number1= ((float) $sec + ((float) $usec * 100000)) % 20;
    list($usec, $sec) = explode(' ', microtime());
    $number2= ((float) $sec + ((float) $usec * 100000)) % 10;

    $result["question"] = ($number1+1)."&nbsp;+ ".($number2+1)." = ";
    $result["answer"] = $number1+$number2+2;
    return $result;
}
?>
