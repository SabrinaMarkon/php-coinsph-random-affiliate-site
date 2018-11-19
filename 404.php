<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once "config/Database.php";
require_once "config/Settings.php";
require_once "config/Layout.php";
require_once "classes/PageContent.php";
$showcontent = new PageContent();
echo $showcontent->showPage('404 Page');
?>