<?php
/**
Handles admin adding, updating, or deleting members.
PHP 5.4+
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license README-LICENSE.txt
 **/
class Member
{
    private $pdo;

    public function getAllMembers() {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from members order by username";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $members = $q->fetchAll();
        $memberarray = array();
        foreach ($members as $member) {
            array_push($memberarray, $member);
        }
//        print_r($memberarray);
//        exit;
        return $memberarray;

    }

    public function saveMember($id) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $country = $_POST['country'];
        $email = $_POST['email'];
        $signupip = $_POST['signupip'];
        $verified = $_POST['verified'];
        $referid = $_POST['referid'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update `members` set username=?, password=?, firstname=?, lastname=?, country=?, email=?, signupip=?, verified=?, referid=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($username, $password, $firstname, $lastname, $country, $email, $signupip, $verified, $referid, $id));

//        if (!$q->execute(array($id, $username, $password, $firstname, $lastname, $country, $email, $signupip, $verified, $referid))) {
//            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
//        }
 //     echo $q->rowCount();

        Database::disconnect();
        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Member " . $username . " was Saved!</strong></div>";

    }

    public function deleteMember($id) {

        $username = $_POST['username'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "delete from members where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();
        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Member " . $username . " was Deleted</strong></div>";

    }
}