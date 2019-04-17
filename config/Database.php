<?php
/**
Set up the database connectivity. The site owner needs to complete 5 property values below.
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

class Database
{
	private static $dbhost = 'localhost';
	private static $dbname = 'DATABASE_NAME';
	private static $dbuser = 'DATABASE_USERNAME';
	private static $dbpass = 'DATABASE_PASSWORD';
	private static $dbconn = null;
	const BASE_URL = "http://randombtcads.phpsitescripts.com/";

	public function __construct() {
		die('Action not allowed'); 
	}

	public static function connect() {
		# one connection for whole program
		if (null == self::$dbconn) {

			try
			{
				self::$dbconn = new PDO("mysql:host=" . self::$dbhost . ";dbname=" . self::$dbname, self::$dbuser, self::$dbpass);
			}
			catch(PDOException $e)
			{
				echo 'Connection failed: ' . $e->getMessage();
				exit;
			}
		}
		return self::$dbconn;
	}

    public static function query($sqlquery, $attributearray) {
        # query the database
        $sqlqueryfields = '';
        $sqlvariables = '';
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        foreach ($attributearray as $attribute) {
            $sqlqueryfields .= $attribute . '=?, ';
            $sqlvariables .= $sqlvariables . ', ';
        }
        $sqlqueryfields = rtrim($sqlqueryfields, ',');
        $sqlvariables = rtrim($sqlvariables, ',');

        $sqlquery = "update members set " . $sqlqueryfields . " where id=?";
        $q = $pdo->prepare($sqlquery);
        $q->execute(array($id, $sqlvariables));
    }

	public static function disconnect() {
		self::$dbconn = null;
	}

}
