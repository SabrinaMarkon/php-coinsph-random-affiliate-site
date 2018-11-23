<?php
/**
Gets the list of countries to populate dropdowns.
PHP 5.4++
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
// if (count(get_included_files()) === 1) { exit('Direct Access is not Permitted'); }
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

class Countries
{
	public $country;
	public $countryselectlist;

	function showCountries($country) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "select * from countries where country_name!=\"United States\" and country_name!=\"Canada\" and country_name!=\"Philippines\" order by country_id";
		$countryselectlist = "";
		foreach($pdo->query($sql) as $row)
		{
			$selected = "";
			if ($country === $row["country_name"])
			{
				$selected = " selected";
			}
			$countryselectlist .= "<option value=\"" . $row["country_name"] . "\"" . $selected . ">" . $row["country_name"] . "</option>";
		}
		Database::disconnect();
		return $countryselectlist;
	}

}