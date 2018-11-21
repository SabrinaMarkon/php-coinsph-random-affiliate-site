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
foreach ($settings as $key => $value)
{
	$$key = $value;
}

# Get the sponsor if there is one.
if (isset($_GET['referid'])) {
		$_SESSION['referid'] = $_GET['referid'];
} elseif (!isset($_SESSION['referid'])) {
		$_SESSION['referid'] = 'admin';
}

######################################
if (isset($_POST['login']))
{
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
###################################### below is still ugly. Refactor.
if (isset($_POST['forgotlogin']))
{

$forgot = new User();
$show = $forgot->forgotLogin($sitename,$domain,$adminemail);
}
if (isset($_POST['contactus']))
{

$contact = new Contact();
$show = $contact->sendContact($settings);
}
if (isset($_POST['register']))
{

$register = new User();
$show = $register->newSignup($settings);
}
if (isset($_GET['page']) && ($_GET['page'] === "verify"))
{

$verify = new User();
# the last part of the url is code this time not referid like other urls. Don't fix cuz it ain't broke.
$verificationcode = $_SESSION['referid']; 
$show = $verify->verifyUser($verificationcode);
}
if (isset($_POST['saveprofile']))
{

$update = new User();
$show = $update->saveProfile($_SESSION['username'],$settings);
}
if (isset($_POST['resendverification'])) {

$resend = new User();
$show = $resend->resendVerify($_SESSION['username'],$_SESSION['password'],$_SESSION['email'],$settings);
}
if (isset($_POST['createad'])) {

$create = new Ad();
$show = $create->createAd($username);
}
if (isset($_POST['savead'])) {

$save = new Ad();
$show = $save->saveAd($id);
}
if (isset($_POST['deletead'])) {

$delete = new Ad();
$show = $delete->deleteAd($id);
}
if (isset($_POST['confirmpaid'])) {

$confirm = new ConfirmPayment();
$show = $confirm->confirmedPayment($id);	
}
if (isset($_GET['page']) && ($_GET['page'] === "logout"))
{

$logout = new User();
$logout->userLogout();
$logoutpage = new PageContent();
$show = $logoutpage->showPage('Logout Page');
}
######################################

$Layout = new Layout();
$Layout->showHeader();

if (!empty($show)) { echo $show; }

if ((!empty($_GET['page'])) and ((file_exists($_GET['page'] . ".php") and ($_GET['page'] !== "index"))))
{

    $page = $_REQUEST['page'];
    include $page . ".php";
} elseif ((!empty($_GET['page'])) and (!file_exists($_GET['page'] . ".php"))) {

	# show the admin create page.
	$page = $_GET['page'];
	include "dynamic.php";
} else {

    include "main.php";
}
$Layout->showFooter();
