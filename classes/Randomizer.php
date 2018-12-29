<?php
/**
Randomizer handling.
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

class Randomizer {

    private $pdo;

    /* Get an array of all records in the randomizer table.*/
    public function getAllRandomizers() {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from randomizer order by id desc";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $randomusers = $q->fetchAll();

        # add the totals for each randomuser and add those items onto each randomuser array (one record).
        # Note randomuser needs to be passed by reference (add & to the start i.e. &$randomuser).

        /* "foreach iterates over iterables and sets the iteration variable by value. 
        This means that the $randomuser array which you are dealing with in the foreach is not the same value of the $randomusers array.
        To rememdy this (and avoid introducing an $index variable to mutate an item in the array), you will need to tell foreach to pass the value by reference."
        */
        foreach ($randomusers as &$randomuser) {

            $username = $randomuser['username'];
            $walletid = $randomuser['walletid'];
            $coinsphpid = $randomuser['coinsphpid'];
                       
            # get the total sums for this user of both their paid and owed earnings as a sponsor and as a random recipient.

            # Sponsor was paid:
            $sql= "select sum(amount) from transactions where recipienttype='sponsor' and recipientapproved=1 and recipientwalletid=? and recipientcoinsphpid=?"; 
            $q = $pdo->prepare($sql);
            $q->execute([$walletid,$coinsphpid]);
            $sponsorpaid = $q->fetchColumn();
            if (!$sponsorpaid) {
                $sponsorpaid = '0.00';
            }
            # Sponsor is owed:
            $sql = "select sum(amount) from transactions where recipienttype='sponsor' and recipientapproved=0 and recipientwalletid=? and recipientcoinsphpid=?";
            $q = $pdo->prepare($sql);
            $q->execute([$walletid,$coinsphpid]);
            $sponsorowed = $q->fetchColumn();
            if (!$sponsorowed) {
                $sponsorowed = '0.00';
            }
            # Random Payee was paid:
            $sql = "select sum(amount) from transactions where recipienttype='random' and recipientapproved=1 and recipientwalletid=? and recipientcoinsphpid=?";
            $q = $pdo->prepare($sql);
            $q->execute([$walletid,$coinsphpid]);
            $randompaid = $q->fetchColumn();
            if (!$randompaid) {
                $randompaid = '0.00';
            }
            # Random Payee is owed:
            $sql = "select sum(amount) from transactions where recipienttype='random' and recipientapproved=0 and recipientwalletid=? and recipientcoinsphpid=?";
            $q = $pdo->prepare($sql);
            $q->execute([$walletid,$coinsphpid]);
            $randomowed = $q->fetchColumn();
            if (!$randomowed) {
                $randomowed = '0.00';
            }

            $randomuser['sponsorpaid'] = $sponsorpaid;
            $randomuser['sponsorowed'] = $sponsorowed;
            $randomuser['randompaid'] = $randompaid;
            $randomuser['randomowed'] = $randomowed;

        }
        Database::disconnect();

        return $randomusers;
    }

    /* Get all positions for ONE USERNAME from the randomizer table.*/
    public function getAllRandomizersForOneUser($username) {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from randomizer where username=? order by id desc";
        $q = $pdo->prepare($sql);
        $q->execute([$username]);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $positions = $q->fetchAll();

        Database::disconnect();
        
        return $positions;   

    }

    /* Get one POSITION ONLY from the randomizer table. Not sure if I need this? */
    public function getOneRandomizer() {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,ERRMODE_EXCEPTION);
        $sql = "select * from randomizer order by rand() limit 1";
        $randomuser = $pdo->query($sql)->fetch();
        
        DATABASE::disconnect();

        return $randomuser;
    }

    /* Add a username to the randomizer table.This is called when the second payee (either the sponsor or the
    random user) confirms that they have received payment.*/
    public function addRandomizer($username,$walletid,$coinsphpid,$returnmessage) {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        # Give a randomizer position.
        $sql = "insert into randomizer (username,walletid,coinsphpid) values (?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute([$username,$walletid,$coinsphpid]);

        # get the randomizerid of the newly inserted blank ad.
        $randomizerid = $pdo->lastInsertId();

        DATABASE::disconnect();

        if ($returnmessage) {

            return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Member " . $username . " was added to the Randomizer!</strong></div>";
        } else {

            return $randomizerid;
        }
    }

    /* Admin can change randomizer positions to whichever wallet IDs they need to. */
    public function saveRandomizer($username,$walletid,$coinsphpid,$id) {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update randomizer set walletid=?,coinsphpid=?,username=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$walletid,$coinsphpid,$username,$id]);
        DATABASE::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Randomizer Position for " . $username . " were Saved!</strong></div>";
    }
    

    /* Delete a single id, OR all ids for a deleted user from the randomizer table.*/
    public function deleteRandomizer($username,$id) {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        # if $username is empty, just delete the $id
        if(empty($username)) {

            $sql = "delete from randomizer where id=?";
            $q = $pdo->prepare($sql);
            $q->execute([$id]);
            DATABASE::disconnect();

            return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Randomizer Position #" . $id . " was Deleted!</strong></div>";
        }
        # if $username is not empty, delete all randomizer positions for that username.
        else {

            $sql = "delete from randomizer where username=?";
            $q = $pdo->prepare($sql);
            $q->execute([$username]);
            DATABASE::disconnect();

            return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>All Randomizer Positions for " . $username . " were Deleted!</strong></div>";
        }  
    }

}