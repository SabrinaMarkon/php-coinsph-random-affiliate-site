<?php
if ((isset($_SESSION['adminusername'])) && (isset($_SESSION['adminpassword'])))
{
$logincheck = new Admin();
$newlogin = $logincheck->adminLogin($_SESSION['adminusername'],$_SESSION['adminpassword']);
 if ($newlogin === false)
	{
	$logincheck->adminLogout();
	$showcontent = new LoginForm();
	echo $showcontent->showLoginForm(1);
	$Layout = new Layout();
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
$showcontent = new LoginForm();
echo $showcontent->showLoginForm(1);
$Layout = new Layout();
$Layout->showFooter();
exit;
}
?>