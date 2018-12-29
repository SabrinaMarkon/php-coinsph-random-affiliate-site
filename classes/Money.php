<?php
 /**
Handles admin adding, updating, or deleting financial transactions.
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

        Database::connect();

        if ($transactions) {

            return $transactions;
        }
    }

    /* Find out how much a user owes or is owed by others. */
    public function getUserTransactions($username,$owesorgets) {

        
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($owesorgets === 'owes') {

            $sql = "select * from transactions where username=? and recipientapproved=0 order by id";
        } elseif ($owesorgets === 'gets') {

            $sql = "select * from transactions where recipient=? order by recipientapproved,id desc";
        } else {

            $sql = "select * from transactions where username=? and recipientapproved=1 order by id";
        }

        $q = $pdo->prepare($sql);
        $q->execute(array($username));
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $transactions = $q->fetchAll();

        Database::disconnect();
        
        if ($transactions) {

            return $transactions;
        }
    }

    public function addTransaction() {

        $username = $_POST['username'];
        $recipient = $_POST['recipient'];
        $recipientwalletid = $_POST['recipientwalletid'];
        $recipientcoinsphpid = $_POST['recipientcoinsphpid'];
        $recipienttype = $_POST['recipienttype'];
        $amount = $_POST['amount'];
        $transaction = $_POST['transaction'];

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        # create a transactions.
        $sql = "insert into transactions (username,amount,recipient,recipientwalletid,recipientcoinsphpid,recipienttype,transaction) values (?,?,?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute([$username,$amount,$recipient,$recipientwalletid,$recipientcoinsphpid,$recipienttype,$transaction]);
        
        DATABASE::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Transaction Created where " . $username . " owes " . $recipient . " " . $amount . "</strong></div>";
    }

    public function saveTransaction($id) {

        $username = $_POST['username'];
        $recipient = $_POST['recipient'];
        $oldrecipientapproved = $_POST['oldrecipientapproved'];
        $recipientapproved = $_POST['recipientapproved'];
        $recipienttype = $_POST['recipienttype'];
        $amount = $_POST['amount'];
        $datepaid = $_POST['datepaid'];
        if (($datepaid === '') or ($datepaid === 'Not Yet')) { 
            $datepaid = ''; 
        }
        $transaction = $_POST['transaction'];

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update transactions set username=?, recipient=?, recipientapproved=?, recipienttype=?, amount=?, datepaid=?, transaction=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($username, $recipient, $recipientapproved, $recipienttype, $amount, $datepaid, $transaction, $id));

        /* if the admin is verifying a transaction, check to see if that user now has 2 (sponsor and random) verified transactions that have no randomizerid yet.
        If this is the case, then reward the user with a randomizer position and an ad. */
        $returnshow = '';
        if ($recipientapproved === "1" and $oldrecipientapproved === "0") {
   
            # get the walletid & coinsphpid of the user who paid this one.
            $bitcoin = new Bitcoin();
            $walletidandcoinsphpid = $bitcoin->getUsersWalletIDs($username);
            if ($walletidandcoinsphpid) {

                $walletid = $walletidandcoinsphpid['walletid'];
                $coinsphpid = $walletidandcoinsphpid['coinsphpid'];
            }

            $checkifuserpaidtwo = new ConfirmPayment();
            $returnshow = $checkifuserpaidtwo->maybeGiveAdandRandomizer($pdo,$username,$walletid,$coinsphpid);
                
            }

        /* if the admin has un-verified a transaction, we need to reset it back to the way it was when the user first joined. */
        if ($recipientapproved === "0" and $oldrecipientapproved === "1") {
   
            $unconfirmpayment = new ConfirmPayment();
            $unconfirmpayment->unConfirmedPayment($id);

            }

        DATABASE::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Transaction ID #" . $id . " was Saved!</strong>" . $returnshow . "</div>";

    }        


    public function deleteTransaction($id) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "delete from transactions where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));

        Database::disconnect();
        
        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Transaction ID " . $id . " was Deleted</strong></div>";
    }

}