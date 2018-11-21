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

    public function confirmedPayment($id) {

        $userwhopaid = $_POST['userwhopaid'];

        $pdo = DATABASE::connect();
        $pdo = setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);       

        # update the transaction record $id as paid.
        $sql = "update transactions set recipientapproved=1,datepaid=" . $time() . " where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$id]);

        $this->maybeGiveAdandRandomizer($pdo,$userwhopaid);
       
        $pdo = DATABASE::disconnect();

        return;
    }

    public function maybeGiveAdandRandomizer($pdo,$userwhopaid) {
        
        /* check to see if the person who paid ($userwhopaid) now has two transaction ids, one THEY paid to THEIR sponsor,
        and one for a random member THEY paid. */
        $totalverified = 0;
        $tranactionidforsponsor = 0;
        $tranactionidforrandom = 0;
        $adid = 0;
        $randomizerid = 0;

        # is there a verified sponsor payment unassigned to a randomizer position and ad?
        $sql = "select * from transactions where username=? and randomizerid='' and recipientapproved=1 and randomizerid='' and recipienttype='sponsor' order by id limit 1";
        $q = $pdo->prepare($sql);
        $q->execute([$userwhopaid]);
        $data = $q->fetch();
        if ($data) {
            $tranactionidforsponsor = $data['id'];
            $totalverified++;
        }

        # is there a verified random payment unassigned to a randomizer position and ad?
        $sql = "select * from transactions where username=? and randomizerid='' and recipientapproved=1 and randomizerid='' and recipienttype='random' order by id limit 1";
        $q = $pdo->prepare($sql);
        $q->execute([$userwhopaid]);
        $data = $q->fetch();
        if ($data) {
            $tranactionidforrandom = $data['id'];
            $totalverified++;
        }

        # if totalverified = 2, it means the userwhopaid should get an ad and a position in the randomizer.
        if ($totalverified === 2) {

            $addposition = new Randomizer();
            $randomizerid = $addposition->addRandomizer($userwhopaid,0);

            $addad = new Ad();
            $adid = $addad->createBlankAd($userwhopaid);

            # update the transactions with the correct adid (the id of the ad given to the user for making the two payments).
            $sql = "update transactions set adid=? where (id=? or id=?)";
            $q = $pdo->prepare($sql);
            $q->execute([$adid,$tranactionidforsponsor,$tranactionidforrandom]);

            # update the transactions with the correct randomizerid (the id of the randomizer position given to the user for making the two payments).
            $sql = "update transactions set randomizerid=? where (id=? or id=?)";
            $q = $pdo->prepare($sql);
            $q->execute([$randomizerid,$tranactionidforsponsor,$tranactionidforrandom]);

        }

        return;
    }

}