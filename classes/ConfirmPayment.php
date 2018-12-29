<?php
/**
Confirms that a user has received a payment that they were owed, and
checks to see if the person who paid them has paid both their sponsor
and a random member, so that they can be awarded their ad and position.
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

class ConfirmPayment {

    private $pdo;

    public function confirmedPayment($id) {

        $userwhopaid = $_POST['userwhopaid'];
        $userwhopaidwalletid = $_POST['userwhopaidwalletid'];
        $userwhopaidcoinsphpid = $_POST['userwhopaidcoinsphpid'];

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);       

        # update the transaction record $id as paid.
        $now = (string) date('Y-m-d');
        $sql = "update transactions set recipientapproved=1,datepaid=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$now,$id]);

        $this->maybeGiveAdandRandomizer($pdo,$userwhopaid,$userwhopaidwalletid,$userwhopaidcoinsphpid);
       
        DATABASE::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>You confirmed the payment!</strong></div>"; 
    }

    public function unConfirmedPayment($id) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        # update the transaction record $id as unpaid (reset to how it was when first created).
        $sql = "update transactions set adid=0,randomizerid=0,recipientapproved=0,datepaid='' where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$id]);

        DATABASE::disconnect();
        
        return;
    }

    public function maybeGiveAdandRandomizer($pdo,$userwhopaid,$userwhopaidwalletid,$userwhopaidcoinsphpid) {

        /* check to see if the person who paid ($userwhopaid) now has two transaction ids, one THEY paid to THEIR sponsor,
        and one for a random member THEY paid. */
        $totalverified = 0;
        $transactionidforsponsor = 0;
        $transactionidforrandom = 0;
        $adid = 0;
        $randomizerid = 0;
        $returnshow = '';

        # is there a verified sponsor payment unassigned to a randomizer position and ad?
        $sql = "select id from transactions where username=? and randomizerid='' and recipientapproved=1 and randomizerid=0 and recipienttype='sponsor' order by id limit 1";
        $q = $pdo->prepare($sql);
        $q->execute([$userwhopaid]);
        $transactionidforsponsor = $q->fetchColumn();
        if ($transactionidforsponsor) {
            $totalverified++;
        }

        # is there a verified random payment unassigned to a randomizer position and ad?
        $sql = "select id from transactions where username=? and randomizerid='' and recipientapproved=1 and randomizerid=0 and recipienttype='random' order by id limit 1";
        $q = $pdo->prepare($sql);
        $q->execute([$userwhopaid]);
        $transactionidforrandom = $q->fetchColumn();
        if ($transactionidforrandom) {
            $totalverified++;
        }

        # if totalverified = 2, it means the userwhopaid should get an ad and a position in the randomizer.
        if ($totalverified === 2) {

            $addposition = new Randomizer();
            $randomizerid = $addposition->addRandomizer($userwhopaid,$userwhopaidwalletid,$userwhopaidcoinsphpid,0);

            $addad = new Ad();
            $adid = $addad->createBlankAd($userwhopaid);

            # update the transactions with the correct adid (the id of the ad given to the user for making the two payments).
            $sql = "update transactions set adid=? where (id=? or id=?)";
            $q = $pdo->prepare($sql);
            $q->execute([$adid,$transactionidforsponsor,$transactionidforrandom]);

            # update the transactions with the correct randomizerid (the id of the randomizer position given to the user for making the two payments).
            $sql = "update transactions set randomizerid=? where (id=? or id=?)";
            $q = $pdo->prepare($sql);
            $q->execute([$randomizerid,$transactionidforsponsor,$transactionidforrandom]);

            # Add the below message to the return output.
            $returnshow = "<br/>Username " . $userwhopaid . " now has 2 verified payments, with one to their sponsor 
            and the other to a random user, so has been credited with an ad and a randomizer position.";

        }

        Database::disconnect();

        return $returnshow;
    }

}