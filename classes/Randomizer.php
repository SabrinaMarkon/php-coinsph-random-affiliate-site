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
    private function getAllUsers() {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from randomizer order by username desc";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $randomusers = $q->fetchAll();
        $randomusersarray = array();
        foreach ($randomusers as $randomuser) {
            array_push($randomusersarray, $randomuser);
        }
        Database::disconnect();
        
        return $randomusersarray;
    }

    /* Get all positions for ONE USERNAME from the randomizer table.*/
    private function getAllForOneUser($username) {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from randomizer where username=? order by id desc";
        $q = $pdo->prepare($sql);
        $q->execute([$username]);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $positions = $q->fetchAll();
        $allpositionsforuser = array();
        foreach ($positions as $position) {
            array_push($allpositionsforuser, $position);
        }
        Database::disconnect();
        
        return $allpositionsforuser;   

    }

    /* Get one POSITION ONLY from the randomizer table.*/
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

        $walletid = $_POST['walletid'];
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "insert into randomizer (username,walletid) values (?,?)";
        $q = $pdo->prepare($sql);
        $q->execute([$username,$walletid]);
        DATABASE::disconnect();
        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Member " . $username . " was added to the Randomizer!</strong></div>";
    }

    /* Delete a single id, OR all ids for a deleted user from the randomizer table.*/
    private function deleteUser($username, $id) {

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

    private function saveUser($username, $id) {

        $updateusername = $_POST['updateusername'];
        $updatewalletid = $_POST['updatewalletid'];
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        # if $username is empty, just update the single $id
        if(empty($username)) {
            $sql = "update randomizer set walletid=?,username=? where id=?";
            $q = $pdo->prepare($sql);
            $q->execute([$updatewalletid,$updateusername,$id]);
            DATABASE::disconnect();
            return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Randomizer Position #" . $id . " was Saved!</strong></div>";
        }
        # if $username is not empty, update all randomizer positions for that username.
        else {
            $sql = "update randomizer set walletid=?,username=? where username=?";
            $q = $pdo->prepare($sql);
            $q->execute([$updatewalletid,$updateusername,$username]);
            DATABASE::disconnect();
            return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Randomizer Positions for " . $username . " were Saved!</strong></div>";
        }
    }

}