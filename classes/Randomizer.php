<?php
/**
Randomizer handling.
PHP 5.4++
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
class Randomizer {

    private $pdo;

    /* Get an array of all records in the randomizer table.*/
    private function getAllUsers() {

        return;
    }

    /* Get one username from the randomizer table.*/
    private function getOneUser() {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,ERRMODE_EXCEPTION);
        $sql = "select * from randomizer order by rand() limit 1";
        $randomuser = $pdo->query($sql)->fetch();
        DATABASE::disconnect();
        return $randomuser;
    }

    /* Add a username to the randomizer table.*/
    private function addUser($username) {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "insert into randomizer (username) values (?)";
        $q = $pdo->prepare($sql);
        $q->execute([$username]);
        DATABASE::disconnect();
        return;
    }

    /* Delete a single id, OR all ids for a deleted user from the randomizer table.*/
    private function deleteUser($username, $id) {

        # if $username is empty, just delete the $id

        # if $username is not empty, delete all randomizer positions for that username.

        return;
    }

    private function saveUser($username, $id) {

        # if $username is empty, just update the single $id

        # if $username is not empty, update all randomizer positions for that username.

        return;
    }

}