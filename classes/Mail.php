<?php
/**
Admin mailer.
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

class Mail
{
    private $email;
    private $url;
    private $subject;
    private $message;
    private $headers;
    private $pdo;

    public function getAllSavedMails() {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from mail where save=1 order by id desc";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $savedmails = $q->fetchAll();
        $savedmailarray = array();
        foreach ($savedmails as $savedmail) {
            array_push($savedmailarray, $savedmail);
        }

        Database::disconnect();

        return $savedmailarray;
    }

    public function editMail($id) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from mail where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $editmail = $q->fetch();

        Database::disconnect();
        
        if ($editmail) {
            return $editmail;
        }

    }

    public function saveMail($id) {

        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $url = $_POST['url'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update mail set subject=?, message=?, url=?, save=1 where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($subject, $message, $url, $id));

        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Mail was Saved!</strong></div>";
    }

    public function addMail() {

        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $url = $_POST['url'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "insert into mail set subject=?, message=?, url=?, save=1";
        $q = $pdo->prepare($sql);
        $q->execute(array($subject, $message, $url));

        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>New Mail was Added!</strong></div>";
    }

    public function sendMail($id) {

        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $url = $_POST['url'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        if ($id !== '') {
            $sql = "update mail set subject=?, message=?, url=?, needtosend=1 where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($subject, $message, $url, $id));
        } else {
            $sql = "insert into mail set subject=?, message=?, url=?, needtosend=1";
            $q = $pdo->prepare($sql);
            $q->execute(array($subject, $message, $url));
        }

        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Mail was Sent!</strong></div>";
    }

    public function sendVerifications($settings) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        
        $sent = new Datetime();
        $sent = $sent->format('Y-m-d');

        $getsql = "select * from members where verified='' order by id";
        $getq = $pdo->prepare($getsql);
        $getq->execute();
        $getq->setFetchMode(PDO::FETCH_ASSOC);
        $members = $getq->fetchAll();
        if ($members) {
            foreach ($members as $member) {
                $username = $member['username'];
                $password = $member['password'];
                $email = $member['email'];
                $verificationcode = $member['verificationcode'];
                if ($verificationcode === '') {
                    $verificationcode = time() . mt_rand(10, 100);
                    $sql = "update members set verificationcode=? where username=?";
                    $pdo->prepare($sql);
                    $pdo->execute(array($verificationcode,$username));
                }

                $subject = "Please verify your " . $settings['sitename'] . " membership";
                $message = "Click to Verify your Email: " . $settings['domain'] . "/verify/" . $verificationcode . "\n\n";
                $message .= "Login URL: " . $settings['domain'] . "/login\nUsername: " . $username . "\nPassword: " . $password . "\n\n";
                $message .= "Your Referral URL: " . $settings['domain'] . "/r/" . $username . "\n\n";
                $sendsiteemail = new Email();
                $send = $sendsiteemail->sendEmail($email, $settings['adminemail'], $subject, $message, $settings['sitename'], $settings['adminemail'], '');
            }
        }

        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Verification Emails were Resent!</strong></div>";
    }

    public function deleteMail($id) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "delete from mail where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));

        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Saved Mail Was Deleted</strong></div>";
    }



}