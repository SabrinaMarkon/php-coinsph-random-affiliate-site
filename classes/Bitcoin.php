<?php
/**
Handles Bitcoin payment buttons and ad assignment to users.
PHP 5.4++
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
class Bitcoin {

    private $pdo;

    private function makeBitcoinButton($username,$whotopay) {

        # create a button for a user to pay another user with Bitcoin.
        # 'sponsor', 'admin', or 'random'.
        $bitcoinbutton;

        if ($whotopay === 'sponsor') {

            $bitcoinbutton = "I'm a REFERID BC button!";
            
        } elseif ($whotopay === 'admin') {
            

            $bitcoinbutton = "I'm an ADMIN BC button!";

        } else {

            # get a random user from the randomizer.
            $randomizer = new Randomizer();
            $payee = $randomizer->getUser();

            $bitcoinbutton = "I'm a RANDOM BC button!";
        }

        return $bitcoinbutton;
    }

    private function getBitcoinButtons($username) {

        $pdo = DATABASE::connect();
        $pdo->setAttributes(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $totalcount = 0;

        # at least one transaction must have been made to and approved by their referid sponsor.
        $sqlsponsor = "select * from transactions where adid=0 and recipienttype='sponsor' and recipientapproved=1 order by id limit 1";
        $sponsortransaction = $pdo->query($sqlsponsor)->fetch();
        if ($sponsortransaction) { $totalcount++; }

        # at least one transaction must have been made to and approved by a random member recipient.
        $sqlrandom = "select * from transactions where adid=0 and recipienttype='random' and recipientapproved=1 order by id limit 1";
        $randomtransaction = $pdo->query($sqlrandom)->fetch();
        if ($randomtransaction) { $totalcount++; }

        # THREE CASES:
        
        /* 1) If the user has TWO paid and recipient-approved transactions with adid=0.
         If adid=0 for both the sponsor and the random types of payment, then these have not 
         yet been assigned to a new ad, so we can create a blank ad for the member */
         if ($totalcount === 2) {
            # create blank ad for the user.
            $blankad = new Ad();
            $adid = $blankad->createBlankAd($username);
            
            # assign the new adid to a sponsor transaction and a random transaction.
            $sponsortransid = $sponsortransaction['id'];
            $randomtransid = $randomtransaction['id'];
            $sqladid = "update transactions set adid=? where id=? or id=?";
            $q = $pdo->prepare($sqladid);
            $q->execute([$adid,$sponsortransid,$randomtransid]);

            # add the user to the randomizer (it is possible to have multiple entries!).
            $randomizer = new Randomizer();
            $payee = $randomizer->addUser($username);

            ###### ALSO NEED TO DELETE FROM RANDOMIZER WHENEVER A MEMBER IS DELETED!!!!! ##########
            $randomizer = new Randomizer();
            $randomizer->addUser($username);

            Database::disconnect();        

            return;
         }

        /* 2) If only ONE transaction is paid and approved with adid=0 out of the ones for sponsor and random,
        then show the button ONLY for the MISSING one. */
        elseif ($totalcount === 1) {
            # we can't create a blank ad yet. Show the payment button ONLY for the one that hasn't been paid yet, either sponsor or random user.
            if ($sponsortransaction) {
                $whotopay = 'sponsor';
            } elseif ($ratiocounter === $adminratio) { 
                $whotopay = 'admin';                
            } else {
                $whotopay = 'random';
            }
            $onepaybutton = $this->makeBitcoinButton($username, $whotopay);
            return $onepaybutton;
        }

        /* 3) If NO transactions are paid and have adid=0, then we need to show BOTH buttons, one for sponsor and one for random */
        else {
            # totalcount === 0, so show a payment button for EACH of the sponsor and a random user (with the adminratio taken under consideration).
            if ($ratiocounter === $adminratio) {
                $whotopay = 'admin';
            } else {
                $whotopay = 'random';
            }
            $twopaybuttons = $this->makeBitcoinButton($username, 'sponsor') . makeBitcoinButton($username, $whotopay);
            return $twopaybuttons;
        }

    }



}