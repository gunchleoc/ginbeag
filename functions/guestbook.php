<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));

include_once($projectroot."functions/db.php");

################################################################################
##                                                                            ##
##        Functions                                                           ##
##                                                                            ##
################################################################################


//
//
//
function addguestbookentry($postername,$addy,$subject,$messagetext)
{
  $values[0]=0;
  $values[1]=setstring($postername);
  $values[2]=setstring($addy);
  $values[3]=setstring($subject);
  $values[4]=setstring($messagetext);
  $values[5]=date(DATETIMEFORMAT, strtotime('now'));
  
  $query="insert into ";
  $query.=GUESTBOOK_TABLE." values(";
  for($i=0;$i<count($values)-1;$i++)
  {
    $query.="'".$values[$i]."', ";
  }
  $query.="'".$values[count($values)-1]."');";
//  print('<p>'.$query);

  $sql=singlequery($query);
  return $sql;
}

//
//
//
function getguestbookentries($number,$offset)
{
  if(!$offset) $offset=0;
  if(!$number>0) $number=1;
  return getorderedcolumnlimit("message_id",GUESTBOOK_TABLE,"1", "date", setinteger($offset), setinteger($number),"DESC");
}

//
//
//
function countguestbookentries()
{
  return countelements("message_id", GUESTBOOK_TABLE);
}

//
//
//
function getguestbookentrycontents($message_id)
{
  $result=array();
  $message_id=setinteger($message_id);
  $result['name']= getdbelement("name",GUESTBOOK_TABLE, "message_id", $message_id);
  $result['email']= getdbelement("email",GUESTBOOK_TABLE, "message_id", $message_id);
  $result['subject']= getdbelement("subject",GUESTBOOK_TABLE, "message_id", $message_id);
  $result['message']= getdbelement("message",GUESTBOOK_TABLE, "message_id", $message_id);
  $result['date']= getdbelement("date",GUESTBOOK_TABLE, "message_id", $message_id);

  return $result;
}
?>
