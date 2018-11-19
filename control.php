<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

if ((isset($_SESSION['username'])) && (isset($_SESSION['password'])))
{
$logincheck = new User();
$newlogin = $logincheck->userLogin($_SESSION['username'],$_SESSION['password']);
 if ($newlogin === false)
	{
	$showcontent = new LoginForm();
	echo $showcontent->showLoginForm(1);
	$Layout = new Layout();
	$Layout->showFooter();
	exit;
	}
else
	{
	# returned member details.
	foreach ($newlogin as $key => $value)
		{
		$$key = $value;
		$_SESSION[$key] = $value;
		}
		$showgravatar = $logincheck->getGravatar($username,$email);
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