<?php
/**
Handles user interactions with the application.
PHP 5.4++
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
class User
{
	private $pdo;
	private $username;
	private $password;
	private $email;
	private $emailhash;
	private $gravatarimagelg;
	private $usernameoremail;

	public function newSignup($settings) {

		$username = $_POST['username'];
		$password = $_POST['password'];
		$walletid = $_POST['walletid'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$country = $_POST['country'];
		$signupip = $_SERVER['REMOTE_ADDR'];
		$referid = $_POST['referid'];
		
		# error checking.
		# make sure fields filled in. Make sure email is valid. Make sure passwords match.
		# make sure fields > x chars.
		

		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "select * from members where username=?";
		$q = $pdo->prepare($sql);
		$q->execute(array($username));
		$q->setFetchMode(PDO::FETCH_ASSOC);
		$data = $q->fetch();
		if ($data['username'] == $username)
		{
			Database::disconnect();
			return "<center><div class=\"alert alert-danger\" style=\"width:75%;\"><strong>The username you chose isn't available.</strong></div>";
		}
		else
		{
			$verificationcode = time() . mt_rand(10, 100);

			$sql = "insert into members (username,password,walletid,firstname,lastname,email,country,referid,signupdate,signupip,verificationcode) values (?,?,?,?,?,?,?,?,NOW(),?,?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($username,$password,$walletid,$firstname,$lastname,$email,$country,$referid,$signupip,$verificationcode));

			# create two unpaid transactions, one for the sponsor, and one for a random walletid in the randomizer. If none exist, add admin walletid.
			
			Database::disconnect();

			$subject = "Welcome to " . $settings['sitename'] . "!";
			$message = "Click to Verify your Email: " . $settings['domain'] . "/verify/" . $verificationcode . "\n\n";
			$message .= "Login URL: " . $settings['domain'] . "/login\nUsername: " . $username . "\nPassword: " . $password . "\n\n";
			$message .= "Your Referral URL: " . $settings['domain'] . "/r/" . $username . "\n\n";
			$message .= "Before receiving your ad and randomizer spot, you will need to send:\n";
			$message .= "1) " . $paysponsor . " to Bitcoin: " . $walletidsponsor . "\n";
			$message .= "2) " . $payrandom . " to Bitcoin: " . $walletidrandom . "\n\n";
			$sendsiteemail = new Email();
			$send = $sendsiteemail->sendEmail($email, $settings['adminemail'], $subject, $message, $settings['sitename'], $settings['domain'], $settings['adminemail'], '');

			return "<center><div class=\"alert alert-success\" style=\"width:75%;\"><strong>Success! Thanks for Joining!</strong><p>Please click the link in the email we sent to you to verify your email address.</p></div>";

			$username = null;
			$password = null;
			$walletid = null;
			$firstname = null;
			$lastname = null;
			$email = null;
			$country = null;
			$referid = null;
			$signupip = null;
		}

	}

	public function userLogin($username,$password) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "select * from members where username=? and password=? limit 1";
		$q = $pdo->prepare($sql);
		$q->execute(array($username,$password));
		$valid = $q->rowCount();
		if ($valid > 0) {
			# successful login.
			$q->setFetchMode(PDO::FETCH_ASSOC);
			$memberdetails = $q->fetch();
			return $memberdetails;
			}
		else {
			# incorrect login.
			return false;
			}
		Database::disconnect();

	}

	public function verifyUser($verificationcode) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "select from members where verificationcode=?";
		$q = $pdo->prepare($sql);
		$q->execute(array($verificationcode));
		$valid = $q->rowCount();
		if ($valid) {
			# successful email validation. Add time to verified field so we know when it happened.
			$sql = "update members set verified=" . $mktime() . " where verificationcode=?";
			$q = $pdo->prepare($sql);
			$q->execute(array($verificationcode));
			Database::disconnect();
			return "<center><div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your email address was verified!</strong></div>";
		} else {
			return "<center><div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your verification code was invalid. Please check the link in the welcome email.</strong></div>";
		}	
	}

	public function forgotLogin($sitename,$domain,$adminemail) {

		$usernameoremail = $_POST['usernameoremail'];
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "select * from members where username=? or email=? limit 1";
		$q = $pdo->prepare($sql);
		$q->execute(array($usernameoremail,$usernameoremail));
		$found = $q->rowCount();
		if ($found > 0)
			{
			$q->setFetchMode(PDO::FETCH_ASSOC);
			$data = $q->fetch();
			$email = $data['email'];
			$username = $data['username'];
			$password = $data['password'];
			$subject = "Your " . $sitename . " Login Details";
			$message = "Login URL: " . $domain . "\nUsername: " . $username . "\nPassword: " . $password . "\n\n";
			
			$sendsiteemail = new Email();
			$send = $sendsiteemail->sendEmail($email,$adminemail,$subject,$message,$sitename,$domain,$adminemail, '');
			
			Database::disconnect();
			return "<center><div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your login details were sent to your email address.</strong></div>";
			}
		else
			{
			Database::disconnect();
			return "<center><div class=\"alert alert-danger\" style=\"width:75%;\"><strong>The username or email address you entered was not found.</strong></div>";
			}

	}

	public function getGravatar($username,$email) {

		$emailhash = trim($email);
		$emailhash = md5($emailhash);
		$gravatarimagelg = "<img src=\"http://gravatar.com/avatar/" . $emailhash . "?s=130\" alt=\"" . $username . "\" class=\"avatar img-circle img-thumbnail gravatar-lg\">";
		return $gravatarimagelg;
		
	}

	public function saveProfile($username, $settings) {

		$password = $_POST['password'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$email = $_POST['email'];
		$oldemail = $_POST['oldemail'];
		$country = $_POST['country'];
		$signupip = $_SERVER['REMOTE_ADDR'];

		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "update members set password=?, firstname=?, lastname=?, email=?, country=?, signupip=? where username=?";
		$q = $pdo->prepare($sql);
		$q->execute(array($password, $firstname, $lastname, $email, $country, $signupip, $username));

		if ($email !== $oldemail) {
			
			$sql = "update members set verified='', verificationcode=? where username=?";
			$q = $pdo->prepare($sql);
			$q->execute(array($verificationcode, $username));

			$subject = "Welcome to " . $settings['sitename'] . "!";
			$message = "Click to Verify your Email: " . $settings['domain'] . "/verify/" . $verificationcode . "\n\n";
			$message .= "Login URL: " . $settings['domain'] . "/login\nUsername: " . $username . "\nPassword: " . $password . "\n\n";
			$message .= "Your Referral URL: " . $settings['domain'] . "/r/" . $username . "\n\n";
			$sendsiteemail = new Email();
			$send = $sendsiteemail->sendEmail($email, $settings['adminemail'], $subject, $message, $settings['sitename'], $settings['domain'], $settings['adminemail'], '');

		}

		Database::disconnect();
		$_SESSION['password'] = $password;
		$_SESSION['firstname'] = $firstname;
		$_SESSION['lastname'] = $lastname;
		$_SESSION['email'] = $email;
		$_SESSION['country'] = $country;
		$_SESSION['signupip'] = $signupip;

		return "<center><div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Account Details Were Saved!</strong><p>If you changed your email address, you will need to re-verify your account.</p></div>";

	}

	public function userLogout() {

		session_unset();
		return;

	}

	public function deleteUser($username) {
		
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "delete from members where username=?";
		$q = $pdo->prepare($sql);
		$q->execute(array($username));
		Database::disconnect();
		return "<center><div class=\"alert alert-success\" style=\"width:75%;\"><strong>Account " . $username . " Was Deleted</strong></div>";

	}

}
