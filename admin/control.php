<?php
/**
Handles admin login sessions between admin pages.
PHP 5.4+
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/

# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
  header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
  exit;
}

if ((isset($_SESSION['adminusername'])) && (isset($_SESSION['adminpassword'])))
{
$logincheck = new Admin();
$newlogin = $logincheck->adminLogin($_SESSION['adminusername'],$_SESSION['adminpassword']);
 if ($newlogin === false)
	{

	$Layout = new Layout();
	$Layout->showHeader();
	$logincheck->adminLogout();
	$showcontent = new AdminLoginForm();
	echo $showcontent->showLoginForm(1);
	$Layout->showFooter();
	exit;
	}
else
	{

	$showgravatar = $logincheck->getGravatar($adminemail);
	}
}
else
{

	// $Layout = new Layout();
	// $Layout->showHeader();
	$showcontent = new AdminLoginForm();
	echo $showcontent->showLoginForm(1);
	$Layout->showFooter();
	exit;
}
?>