<?php
/**
Sends an email.
PHP 5.4++
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
// if (count(get_included_files()) === 1) { exit('Direct Access is not Permitted'); }
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

class Email
{
	private $headers;
	private $subject;
	private $message;
	private $toemail;
	private $fromemail;

	public function sendEmail($toemail, $fromemail, $subject, $message, $sitename, $adminemail, $htmlheader) {
		
	$headers = "From: " . $sitename . "<" . $fromemail . ">\n";
	$headers .= "Reply-To: <" . $adminemail . ">\n";
	$headers .= "X-Sender: <" . $adminemail . ">\n";
	$headers .= "X-Mailer: PHP5\n";
	$headers .= "X-Priority: 3\n";
	$headers .= "Return-Path: <" . $adminemail . ">\n";
    $headers .= $htmlheader;

	@mail($toemail, $subject, wordwrap(stripslashes($message)), $headers, "-f$fromemail");

	return;

	}

}