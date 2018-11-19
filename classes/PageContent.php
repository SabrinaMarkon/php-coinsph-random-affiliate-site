<?php
/**
Shows the web content for the current page that the admin has saved in the database.
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

class PageContent
{
	public $content;
	public $pagename;

	public function showPage($pagename) {
		
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "select htmlcode from pages where name=? or slug=?";
		$q = $pdo->prepare($sql);
		$q->execute(array($pagename,$pagename));
		$q->setFetchMode(PDO::FETCH_ASSOC);
		$data = $q->fetch();
		$content = $data['htmlcode'];
$content = <<<HEREDOC
$content
HEREDOC;
		Database::disconnect();
		
		return $content;
	}

}