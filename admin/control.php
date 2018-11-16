<?php
if ((isset($_SESSION['username'])) && (isset($_SESSION['password'])))
{
$logincheck = new Admin();
$newlogin = $logincheck->adminLogin($_SESSION['username'],$_SESSION['password']);
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