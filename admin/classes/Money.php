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
        $transarray = array();
        foreach ($transactions as $transaction) {
            array_push($transarray, $transaction);
        }

        return $transarray;
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

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update transactions set username=?, recipient=?, recipientapproved=?, recipienttype=?, amount=?, datepaid=?, transaction=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($username, $recipient, $recipientapproved, $recipienttype, $amount, $datepaid, $transaction, $id));

        /* if the admin is verifying a transaction, check to see if that user now has 2 (sponsor and random) verified transactions that have no randomizerid yet.
        If this is the case, then reward the user with a randomizer position and an ad. */
        $returnshow = '';
        if ($recipientapproved === "1" and $oldrecipientapproved === "0") {

            # see if the user now has a verifed paid sponsor transaction and also a verified paid random transaction.
            $totalverified = 0;
            $tranactionidforsponsor = 0;
            $tranactionidforrandom = 0;
            $adid = 0;
            $randomizerid = 0;

            # is there a verified sponsor payment unassigned to a randomizer position and ad?
            $sql = "select * from transactions where username=? and randomizerid='' and recipientapproved=1 and randomizerid='' and recipienttype='sponsor' order by id limit 1";
            $q = $pdo->prepare($sql);
            $q->execute([$username]);
            $data = $q->fetch();
            if ($data) {
                $tranactionidforsponsor = $data['id'];
                $totalverified++;
            }

            # is there a verified random payment unassigned to a randomizer position and ad?
            $sql = "select * from transactions where username=? and randomizerid='' and recipientapproved=1 and randomizerid='' and recipienttype='random' order by id limit 1";
            $q = $pdo->prepare($sql);
            $q->execute([$username]);
            $data = $q->fetch();
            if ($data) {
                $tranactionidforrandom = $data['id'];
                $totalverified++;
            }

            # if totalverified = 2, it means the user should get an ad and a position!
            if ($totalverified === 2) {

                $addposition = new Randomizer();
                $randomizerid = $addposition->addUser($username,0);

                $addad = new Ad();
                $adid = $addad->createBlankAd($username);

                # update the transactions with the correct adid (the id of the ad given to the user for making the two payments).
                $sql = "update transactions set adid=? where (id=? or id=?)";
                $q = $pdo->prepare($sql);
                $q->execute([$adid,$tranactionidforsponsor,$tranactionidforrandom]);

                # update the transactions with the correct randomizerid (the id of the randomizer position given to the user for making the two payments).
                $sql = "update transactions set randomizerid=? where (id=? or id=?)";
                $q = $pdo->prepare($sql);
                $q->execute([$randomizerid,$tranactionidforsponsor,$tranactionidforrandom]);
                
                # Add the below message to the return output.
                $returnshow = "<div class=\"ja-bottompadding ja-topadding\">Username " . $username . " now has 2 verified payments, with one to their sponsor
                 and the other to a random user, so has been credited with an ad and a randomizer position.</div>";
            }
        }

        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Transaction ID #" . $id . " was Saved!</strong></div>" . $returnshow;
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