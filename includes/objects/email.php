<?php
/**
 * An Gineadair Beag is a content management system to run websites with.
 *
 * PHP Version 7
 *
 * Copyright (C) 2005-2019 GunChleoc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category Ginbeag
 * @package  Ginbeag
 * @author   gunchleoc <fios@foramnagaidhlig.net>
 * @license  https://www.gnu.org/licenses/agpl-3.0.en.html GNU AGPL
 * @link     https://github.com/gunchleoc/ginbeag/
 */

$projectroot=dirname(__FILE__);
$projectroot=substr($projectroot, 0, strrpos($projectroot, "includes"));

require_once $projectroot."language/languages.php";
require_once $projectroot."includes/objects/template.php";

//
// Summary info when Email is (being) sent
//
class EmailInfo  extends Template
{

    function __construct($email, $subject, $messagetext)
    {
        parent::__construct();

        $this->stringvars['l_email_enteredmessage']=getlang("email_enteredmessage");
        $this->stringvars['l_email']=getlang("email_email");
        $this->stringvars['email']=$email;
        $this->stringvars['l_subject']=getlang("email_subject");
        $this->stringvars['subject']=stripslashes($subject);
        $this->stringvars['l_message']=getlang("email_message");
        $this->stringvars['message']=stripslashes(nl2br($messagetext));
    }

    function createTemplates()
    {
        $this->addTemplate("emailinfo.tpl");
    }
}

//
// Antispam - MathCAPCHA
//
class MathCAPTCHA  extends Template
{

    function __construct()
    {
        global $emailvariables;
        parent::__construct();

        $captcha=makemathcaptcha();
        $this->stringvars['captcha_question']=$captcha["question"];
        $this->stringvars['captchareplyvariable']=$emailvariables['Math CAPTCHA Reply Variable'];
        $this->stringvars['hiddenvars'] = $this->makehiddenvars(array($emailvariables['Math CAPTCHA Answer Variable'] => $captcha["answer"]));
    }

    function createTemplates()
    {
        $this->addTemplate("mathcaptcha.tpl");
    }
}

?>
