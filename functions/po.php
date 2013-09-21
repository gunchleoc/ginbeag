<?php
$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot,0,strrpos($projectroot,"functions"));
include_once($projectroot ."config.php");
include_once($projectroot ."includes/constants.php");

//http://riteshsblog.blogspot.co.uk/2010/02/php-reading-po-file-file-generated-from.html

//PHP Reading PO file (the file generated from php source)
//Hi Guy's,

//Here is the code for parsing PO file for editing in PHP. I have little modified the Drupal's code.


/**
* Parses Gettext Portable Object file into an array
* @param Object $file PO file path with file name
* @param string $search_key search word to find msgid in po file
* File object corresponding to the PO file to read
*/
function readPo($file,$search_key) {

	$fd = fopen($file->filepath, "rb"); // File will get closed by PHP on return
	$file_name = $file->filename;
	if (!$fd) {
		$msg = sprintf(__('The translation import failed, because the file %s could not be read'),$file_name);
		//do whatever you want with this error msg
		return FALSE;
	}

	$context = "COMMENT"; // Parser context: COMMENT, MSGID, MSGID_PLURAL, MSGSTR and MSGSTR_ARR
	$current = array(); // Current entry being read
	$plural = 0; // Current plural form
	$lineno = 0; // Current line
	$lang_arr = Array(); // total message in the file

	while (!feof($fd)) {

		$line = fgets($fd, 10*1024); // A line should not be this long
		if ($lineno == 0) {
			// The first line might come with a UTF-8 BOM, which should be removed.
			$line = str_replace("\xEF\xBB\xBF", '', $line);
		}
		$lineno++;
		$line = trim(strtr($line, array("\\\n" => "")));
		if (!strncmp("#", $line, 1)) { // A comment
			if ($context == "COMMENT") { // Already in comment context: add
				$current["#"][] = substr($line, 2);
			}
			elseif (($context == "MSGSTR") || ($context == "MSGSTR_ARR")) { // End current entry, start a new one

				if(!empty($search_key)){
					$pattern = "/\b".$search_key."\b/i";
					if(preg_match($pattern ,trim($current['msgid']))) {
						$lang_arr[] = $current;
					}
				}
				else
				$lang_arr[] = $current;

				$current = array();
				$current["#"][] = substr($line, 2);
				$context = "COMMENT";
			}
			else { // Parse error
				$msg = sprintf(__('The translation file %s contains an error: "msgstr" was expected but not found on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
		}
		elseif (!strncmp("msgid_plural", $line, 12)) {
			if ($context != "MSGID") { // Must be plural form for current entry
				$msg = sprintf(__('The translation file %s contains an error: "msgid_plural" was expected but not found on %d line'),$file_name,$lineno);
				$this->ErrSucc->addError($msg);
				return FALSE;
			}
			$line = trim(substr($line, 12));
			$quoted = $this->_parse_quoted($line);
			if ($quoted === FALSE) {
				$msg = sprintf(__('The translation file %s contains a syntax error on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
			$current["msgid"] = $current["msgid"] ."\0". $quoted;
			$context = "MSGID_PLURAL";
		}
		elseif (!strncmp("msgid", $line, 5)) {
			if ($context == "MSGSTR") { // End current entry, start a new one
				//$lang_arr[] = $current;
				$current = array();
			}
			elseif ($context == "MSGID") { // Already in this context? Parse error
				$msg = sprintf(__('The translation file %s contains an error: "msgid" is unexpected on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
			$line = trim(substr($line, 5));
			$quoted = $this->_parse_quoted($line);
			if ($quoted === FALSE) {
				$msg = sprintf(__('The translation file %s contains a syntax error on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
			$current["msgid"] = $quoted;
			$context = "MSGID";
		}
		elseif (!strncmp("msgstr[", $line, 7)) {
			if (($context != "MSGID") && ($context != "MSGID_PLURAL") && ($context != "MSGSTR_ARR")) { // Must come after msgid, msgid_plural, or msgstr[]
				$msg = sprintf(__('The translation file %s contains an error: "msgstr[]" is unexpected on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
			if (strpos($line, "]") === FALSE) {
				$msg = sprintf(__('The translation file %s contains a syntax error on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
			$frombracket = strstr($line, "[");
			$plural = substr($frombracket, 1, strpos($frombracket, "]") - 1);
			$line = trim(strstr($line, " "));
			$quoted = $this->_parse_quoted($line);
			if ($quoted === FALSE) {
				$msg = sprintf(__('The translation file %s contains a syntax error on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
			$current["msgstr"][$plural] = $quoted;
			$context = "MSGSTR_ARR";
		}
		elseif (!strncmp("msgstr", $line, 6)) {
			if ($context != "MSGID") { // Should come just after a msgid block
				$msg = sprintf(__('The translation file %s contains an error: "msgstr" is unexpected on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
			$line = trim(substr($line, 6));
			$quoted = $this->_parse_quoted($line);
			if ($quoted === FALSE) {
				$msg = sprintf(__('The translation file %s contains a syntax error on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
			$current["msgstr"] = $quoted;
			$context = "MSGSTR";
		}
		elseif ($line != "") {
			$quoted = $this->_parse_quoted($line);
			if ($quoted === FALSE) {
				$msg = sprintf(__('The translation file %s contains a syntax error on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
			if (($context == "MSGID") || ($context == "MSGID_PLURAL")) {
				$current["msgid"] .= $quoted;
			}
			elseif ($context == "MSGSTR") {
				$current["msgstr"] .= $quoted;
			}
			elseif ($context == "MSGSTR_ARR") {
				$current["msgstr"][$plural] .= $quoted;
			}
			else {
				$msg = sprintf(__('The translation file %s contains an error: there is an unexpected string on %d line'),$file_name,$lineno);
				//do whatever you want with this error msg
				return FALSE;
			}
		}
	}

	// End of PO file, flush last entry
	if (($context == "MSGSTR") || ($context == "MSGSTR_ARR")) {

		if(!empty($search_key)){
			$pattern = "/\b".$search_key."\b/i";
			if(preg_match($pattern ,trim($current['msgid'])))
			$lang_arr[] = $current;
		}
		else {
			$lang_arr[] = $current;
		}
	}
	elseif ($context != "COMMENT") {
		$msg = sprintf(__('The translation file %s ended unexpectedly at %d line'),$file_name,$lineno);
		//do whatever you want with this error msg
		return FALSE;
	}
	fclose($fd);

	return $lang_arr;
}

/**
* Parses a string in quotes
*
* @param $string
* A string specified with enclosing quotes
* @return
* The string parsed from inside the quotes
*/
function _parse_quoted($string) {
	if (substr($string, 0, 1) != substr($string, -1, 1)) {
		return FALSE; // Start and end quotes must be the same
	}
	$quote = substr($string, 0, 1);
	$string = substr($string, 1, -1);
	if ($quote == '"') { // Double quotes: strip slashes
		return stripcslashes($string);
	}
	elseif ($quote == "'") { // Simple quote: return as-is
		return $string;
	}
	else {
		return FALSE; // Unrecognized quote
	}
}

?>
