<?php
/**
Handles user interactions with the application.
PHP 5.4++
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
class Bitcoin {

    private $pdo;

    private function makeBitcoinButton($username) {


        # create a button for a user to pay another user with Bitcoin.

    }

    private function getBitcoinButtons($username) {

        
        # THREE CASES:
        
        # check if the username has TWO paid and recipient-approved transactions with adid=0.
        # If adid=0 for both the sponsor and the random types of payment, then these have not
        # yet been assigned to a new ad, so we can create one for the member. 

        # If only ONE transaction is paid and approved with adid=0 (out of the ones for sponsor and random),
        # then show the button ONLY for the missing one.

        # If NO transactions are paid and have adid=0, then we need to show BOTH buttons (one for sponsor and one for random)


    }



}