<?php
/**
Handles admin notes on the main admin page.
PHP 5.4+
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

class AdminNote
{
    public $adminnote;

    public function __construct() {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from adminnotes";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $this->adminnote['htmlcode'] = $q->fetch();

    }

    public function setAdminNote($htmlcode) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update adminnotes set htmlcode=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($htmlcode));
        Database::disconnect();
        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Saved Your Admin Notes!</strong></div>";
    }

    public function getAdminNote()
    {
        return $this->adminnote['htmlcode'];
    }
}

