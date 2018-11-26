<?php
/**
Handles Bitcoin payment buttons and ad assignment to users.
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

class Bitcoin {

    private $pdo;

    public function showBitCoinWalletIds($username, $settings) {

        $showbitcoin = '';

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        
        # Does the user still have to pay their sponsor (or have them confirm the payment)?
        $sql = "select * from transactions where username=? and recipientapproved=0 and recipienttype='sponsor' order by id limit 1";
        $q = $pdo->prepare($sql);
        $q->execute([$username]);
        $data = $q->fetch();
        if ($data) {

            $recipientwalletid = $data['recipientwalletid'];
            $showbitcoin .= "<div class=\"text-center\"><strong>Please send " . $settings['paysponsor'] . " to Bitcoin Wallet ID: " . $recipientwalletid . "</strong></div>";
        }

        # Does the user still have to pay their sponsor (or have them confirm the payment)?
        $sql = "select * from transactions where username=? and recipientapproved=0 and recipienttype='random' order by id limit 1";
        $q = $pdo->prepare($sql);
        $q->execute([$username]);
        $data = $q->fetch();
        if ($data) {

            $recipientwalletid = $data['recipientwalletid'];
            $showbitcoin .= "<div class=\"text-center\"><strong>Please send " . $settings['payrandom']. " to Bitcoin Wallet ID: " . $recipientwalletid . "</strong></div>";
        }

        DATABASE::disconnect();

        return $showbitcoin;
    }

    /* Call this to get both the owed and paid payments for this randomizer position. */
    public function getPaymentsReceived($username,$walletid) {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from transactions where recipient=? and recipientwalletid=?";
        $q = $pdo->prepare($sql);
        $q->execute([$username,$walletid]);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $transactions = $q->fetchAll();
        
        Database::disconnect();
        
        if ($transactions) {

            return $transactions;
        }      
    }

    /* Call this to just get a user's walletid. */
    public function getUsersWalletID($username) {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select walletid from members where username=?";
        $q = $pdo->prepare($sql);
        $q->execute([$username]);
        $walletid = $q->fetchColumn();
        if (!$walletid) {
            $walletid = '';
        }

        return $walletid;
    }

}