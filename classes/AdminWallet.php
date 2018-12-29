<?php
/**
Handles admin adding, updating, or deleting admin bitcoin wallet IDs.
PHP 5.4+
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

class AdminWallet
{
    private $pdo;

    public function getAllAdminWallets() {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from adminwallets order by name";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $adminwallets = $q->fetchAll();
        $adminwalletarray = array();
        foreach ($adminwallets as $adminwallet) {
            array_push($adminwalletarray, $adminwallet);
        }

        return $adminwalletarray;

    }

    public function addAdminWallet() {

        $name = $_POST['name'];
        $walletid = $_POST['walletid'];
        $coinsphpid = $_POST['coinsphpid'];

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "insert into adminwallets (name,walletid,coinsphpid) values (?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute([$name,$walletid,$coinsphpid]);
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>New Admin Wallet " . $name . " was Added!</strong></div>"; 
    }

    public function saveAdminWallet($id) {

        $name = $_POST['name'];
        $walletid = $_POST['walletid'];
        $coinsphpid = $_POST['coinsphpid'];
        
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update adminwallets set name=?,walletid=?,coinsphpid=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($name,$walletid,$coinsphpid,$id));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Admin Wallet " . $name . " was Saved!</strong></div>";
    }

    public function deleteAdminWallet($id) {

        $name = $_POST['name'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "delete from adminwallets where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Admin Wallet " . $name . " was Deleted</strong></div>";
    }
}