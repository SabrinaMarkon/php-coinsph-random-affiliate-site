<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (!isset($_SESSION))
{
session_start();
}
require_once "config/Database.php";
require_once "config/Settings.php";
require_once "config/Layout.php";

function sabrina_autoloader($class) {
	require "classes/" . $class . ".php";
}
spl_autoload_register("sabrina_autoloader");

$sitesettings = new Settings();
$settings = $sitesettings->getSettings();
foreach ($settings as $key => $value) {
	$$key = $value;
}

# Get the sponsor if there is one.
if (isset($_GET['referid'])) {
		$_SESSION['referid'] = $_GET['referid'];
} elseif (!isset($_SESSION['referid'])) {
		$_SESSION['referid'] = 'admin';
}

# id variable is for the id of a single member, mail, etc. to update in the database.
if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
} else {
    $id = "";
}

$show = '';
$errors = '';

# get the form validation class instance to use for all the pages that post.
if (isset($_POST)) {

    $formvalidation = new FormValidation($_POST);
}

######################################
if (isset($_POST['login'])) {

	$_SESSION['username'] = $_REQUEST['username'];
	$_SESSION['password'] = $_REQUEST['password'];
	$logincheck = new User();
	$newlogin = $logincheck->userLogin($_SESSION['username'],$_SESSION['password']);
	if ($newlogin === false)
		{
		$logout = new User();
		$logout->userLogout();
		}
	else
		{
		# returned member details.
		foreach ($newlogin as $key => $value)
			{
			$$key = $value;
			$_SESSION[$key] = $value;
			}
		$showgravatar = $logincheck->getGravatar($_SESSION['username'],$_SESSION['email']);
		}
}
###################################### Want to refactor below.

if (isset($_POST['forgotlogin'])) {

	// $errors = $formvalidation->validateAll($_POST);
	$errors = '';
	if (!empty($errors)) {

		$showforgot = $errors;
	} else {

		# member clicked button to recover login details.
		$forgot = new User();
		$showforgot = $forgot->forgotLogin($sitename,$domain,$adminemail,$_POST); 
	}
}

if (isset($_POST['contactus'])) {

	$errors = $formvalidation->validateAll($_POST);
	if (!empty($errors)) {

		$showcontact = $errors;
	} else {

		# someone clicked to send a contact email to the admin.
		$contact = new Contact();
		$showcontact = $contact->sendContact($settings);
	}
}

if (isset($_POST['register'])) {

	$errors = $formvalidation->validateAll($_POST);
	if (!empty($errors)) {

		$showregister = $errors;
	} else {

		# new signup clicked to submit registration.
		$register = new User();
		$showregister = $register->newSignup($settings,$_POST);
	}
}

if (isset($_GET['page']) && ($_GET['page'] === "verify")) {

	# user clicked their email verification link.
	$verify = new User();
	# the last part of the url is code this time not referid like other urls. Don't fix cuz it ain't broke.
	$verificationcode = $_SESSION['referid']; 
	$showverify = $verify->verifyUser($verificationcode);

}

if (isset($_POST['saveprofile'])) {
	
	$errors = $formvalidation->validateAll($_POST);
	if (!empty($errors)) {

		$showsaveprofile = $errors;
	} else {

		# user clicked to submit profile updates.
		$update = new User();
		$showsaveprofile = $update->saveProfile($_SESSION['username'],$settings,$_POST);
	}
}

if (isset($_POST['resendverification'])) {

	$resend = new User();
	$showresend = $resend->resendVerify($_SESSION['username'],$_SESSION['password'],$_SESSION['email'],$settings);
}

if (isset($_POST['createad'])) {

	$errors = $formvalidation->validateAll($_POST);
	if (!empty($errors)) {

		$showad = $errors;
	} else {

		# user submitted a new ad.
		$create = new Ad();
		$showad = $create->createAd($id,$_SESSION['username'],$adminautoapprove,0,$_POST);
	}
}

if (isset($_POST['savead'])) {

	$id = $_SESSION['referid']; // the var name referid is what is in the url, but it has the id of the ad in this case.

	$errors = $formvalidation->validateAll($_POST);
	if (!empty($errors)) {

		$showad = $errors;
	} else {

		# user saved changes made to their ad.
		$save = new Ad();
		$showad = $save->saveAd($id,$adminautoapprove,0,$_POST);
	}
}

if (isset($_POST['deletead'])) {

	$id = $_SESSION['referid']; // the var name referid is what is in the url, but it has the id of the ad in this case.
	
	$delete = new Ad();
	$showad = $delete->deleteAd($id,$_POST['name']);
}

if (isset($_POST['confirmpaid'])) {

	$confirm = new ConfirmPayment();
	$show = $confirm->confirmedPayment($id);	
}

if (isset($_GET['page']) && ($_GET['page'] === "logout")) {

	$logout = new User();
	$logout->userLogout();
	$logoutpage = new PageContent();
	$show = $logoutpage->showPage('Logout Page');
}
######################################

# if an ad is clicked (we don't want a header.php)
if (isset($_GET['page']) && ($_GET['page'] === 'click')) {

	$page = $_GET['page'];
	include "click.php";
	exit;	
}

$Layout = new Layout();
$Layout->showHeader();

if ((!empty($_GET['page'])) && ((file_exists($_GET['page'] . ".php") && ($_GET['page'] !== "index")))) {

	$page = $_REQUEST['page'];
	include $page . ".php";
	
} elseif ((!empty($_GET['page'])) && (!file_exists($_GET['page'] . ".php"))) {

	# show the admin create page.
	$page = $_GET['page'];
	include "dynamic.php";

} else {

	include "main.php";
	
}

$Layout->showFooter();
