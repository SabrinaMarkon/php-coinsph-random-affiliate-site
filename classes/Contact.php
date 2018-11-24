<?php
/**
Users can contact the admin.
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

class Contact
{
	private $username;
	private $email;
	private $subject;
	private $message;
	private $headers;

	public function sendContact($settings) {

	$username = $_POST['username'];
	$email = $_POST['email'];
	$subject = $_POST['subject'];
	$message = $_POST['message'];

	if (isset($username))
		{
		$message .= "\n\nSent by Username: " . $username . "\n\n";
		}
	
	$sendsiteemail = new Email();
	$send = $sendsiteemail->sendEmail($settings['adminemail'],$email,$subject,$message,$settings['sitename'],$settings['adminemail'],'');

	return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Message was Sent!</strong></div>";
	
	}
}