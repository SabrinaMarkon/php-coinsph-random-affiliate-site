<?php
/**
Get the global site settings.
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

class Settings
{
	private $setting = array();
	public function getSettings() {
		
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "select * from adminsettings";
		$q = $pdo->prepare($sql);
		$q->execute();
		$q->setFetchMode(PDO::FETCH_ASSOC);
		$setting = $q->fetch();
		return $setting;

	}

}
