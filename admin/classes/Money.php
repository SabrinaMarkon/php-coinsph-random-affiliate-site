<?php
/**
Handles admin adding, updating, or deleting financial transactions.
PHP 5.4+
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license README-LICENSE.txt
 **/
class Money
{

    private $pdo;

    public function getAllTransactions() {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from transactions order by id desc";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $transactions = $q->fetchAll();
        $transarray = array();
        foreach ($transactions as $transaction) {
            array_push($transarray, $transaction);
        }
//        print_r($transarray);
//        exit;
        return $transarray;

    }

    public function saveTransaction($id) {

        $username = $_POST['username'];
        $recipient = $_POST['recipient'];
        $recipientapproved = $_POST['recipientapproved'];
        $recipienttype = $_POST['recipienttype'];
        $amount = $_POST['amount'];
        $datepaid = $_POST['datepaid'];
        $transaction = $_POST['transaction'];

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update transactions set username=?, recipient=?, recipientapproved=?, recipienttype=?, amount=?, datepaid=?, transaction=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($username, $recipient, $recipientapproved, $recipienttype, $amount, $datepaid, $transaction, $id));
        Database::disconnect();
        return "<center><div class=\"alert alert-success\" style=\"width:75%;\"><strong>Transaction ID #" . $id . " was Saved!</strong></div>";

    }

    public function deleteTransaction($id) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "delete from transactions where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        return "<center><div class=\"alert alert-success\" style=\"width:75%;\"><strong>Transaction ID " . $id . " was Deleted</strong></div>";

    }

}