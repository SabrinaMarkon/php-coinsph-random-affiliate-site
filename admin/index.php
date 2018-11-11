<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (!isset($_SESSION))
{
    session_start();
}
require_once "../config/Database.php";
require_once "../config/Settings.php";
require_once "../config/Layout.php";
require_once "../classes/Countries.php";
require_once "../classes/Email.php";

function sabrina_autoloader($class) {
    require 'classes/' . $class . ".php";
}
spl_autoload_register("sabrina_autoloader");

$sitesettings = new Settings();
$settings = $sitesettings->getSettings();
foreach ($settings as $key => $value)
{
    $$key = $value;
}

if (isset($_REQUEST['id']))
{
    $id = $_REQUEST['id'];
}
else
{
    $id = "";
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
if (isset($_POST['saveadminnotes']))
{
    $update = new AdminNote();
    $showupdate = $update->setAdminNote($_POST['htmlcode']);
}
if (isset($_POST['savesettings']))
{
    $update = new Setting();
    $showupdate = $update->saveSettings();
}

if (isset($_POST['editmail']))
{
    $editmail = new Mail();
    $showeditmail = $editmail->editMail($id);
}
if (isset($_POST['addmail']))
{
    $update = new Mail();
    $showupdate = $update->addMail();
}
if (isset($_POST['savemail']))
{
    $update = new Mail();
    $showupdate = $update->saveMail($id);
}
if (isset($_POST['deletemail']))
{
    $delete = new Mail();
    $showupdate = $delete->deleteMail($id);
}
if (isset($_POST['sendmail']))
{
    $send = new Mail();
    $showupdate = $send->sendMail($id);
}

if (isset($_POST['editpage']))
{
    $editpage = new Page();
    $showeditpage = $editpage->editPage($id);
}
if (isset($_POST['addpage']))
{
    $update = new Page();
    $showupdate = $update->addPage($domain);
}
if (isset($_POST['savepage']))
{
    $update = new Page();
    $showupdate = $update->savePage($id);
}
if (isset($_POST['deletepage']))
{
    $delete = new Page();
    $showupdate = $delete->deletePage($id);
}

if (isset($_POST['savemember']))
{
    $update = new Member();
    $showupdate = $update->saveMember($id);
}
if (isset($_POST['deletemember']))
{
    $delete = new Member();
    $showupdate = $delete->deleteMember($id);
}

if (isset($_POST['savetransaction']))
{
    $update = new Money();
    $showupdate = $update->saveTransaction($id);
}
if (isset($_POST['deletetransaction']))
{
    $delete = new Money();
    $showupdate = $delete->deleteTransaction($id);
}
// REFACTOR LATER to make better routes etc.
//if (isset($_POST['_method'])) {
//
//    $_method = $_POST['_method'];
//    if($_method === 'DELETE') {
//
//        $delete = new Money();
//        $showdelete = $delete->deleteTransaction($id);
//
//    }
//    elseif($_method === 'PATCH')
//    {
//
//        $update = new Money();
//        $showupdate = $update->saveTransaction($id);
//    }
//}

if (isset($_GET['page']) && ($_GET['page'] == "logout"))
{
//    $logout = new User();
//    $logout->userLogout();
//    $logoutpage = new PageContent();
//    $showlogout = $logoutpage->showPage('Logout Page');
}
######################################

$Layout = new Layout();
$Layout->showHeader();

//echo $_GET['page'] . "<br>";
if ((!empty($_GET['page'])) and ((file_exists($_GET['page'] . ".php") and ($_GET['page'] != "index"))))
{
    $page = $_REQUEST['page'];
    include $page . ".php";
}
else
{
    include "main.php";
}
$Layout->showFooter();