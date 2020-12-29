<?php
/**
Set up the header and footer of the layout template.
PHP 5.4++
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

class Layout
{
	function showHeader($metatitle, $metadescription) {
		include "header.php";
	}
	function showFooter() {
		include "footer.php";
	}
}
