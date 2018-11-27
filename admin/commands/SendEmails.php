<?php
/**
Handles admin or member submitted mail outs and should be called as a scheduled job.
PHP 5.4+
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
// if (count(get_included_files()) === 1) { exit('Direct Access is not Permitted'); }
# Prevent direct access to this file. Show browser's default 404 error instead.
// Commented out for CRON:
// if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
//     header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
//     exit;
// }

/*
 * SendEmails handles admin or member submitted mail outs and should be called as a scheduled job.
 *
 * @author Sabrina Markon
 * @copyright 2018 Sabrina Markon, PHPSiteScripts.com
 * @license LICENSE.md
 *
 * @param $domain The main url of the website.
 * @param $sitename The name of the website
 * @param $adminemail The website admin's support email address.
 * @param @adminname The name of the website admin.
 */
require_once('../../config/Database.php');
require_once('../../config/Settings.php');
require_once('../../classes/Email.php');

class SendEmails
{
    private $email;
    private $url;
    private $subject;
    private $message;
    private $headers;
    private $pdo;

    public function getMails($domain, $sitename, $adminemail, $adminname)
    {
        // get all mails that are marked as pending mailout.
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from mail where needtosend=1 order by id";
        $q = $pdo->query($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $sendmails = $q->fetchAll();

        if ($sendmails) {

            foreach ($sendmails as $sendmail) {

                $id = $sendmail['id'];
                $senderuserid = $sendmail['username'];
                $subject = $sendmail['subject'];
                $message = $sendmail['message'];
                $url = $sendmail['url'];
                $save = $sendmail['save'];

                $sent = date('Y-m-d');

                $sql = "select * from members where verified!='' order by id";
                $q = $pdo->prepare($sql);
                $q->execute();
                $q->setFetchMode(PDO::FETCH_ASSOC);
                $members = $q->fetchAll();

                if ($members) {

                    foreach ($members as $member) {

                        $username = $member['username'];
                        $firstname = $member['firstname'];
                        $lastname = $member['lastname'];
                        $email = $member['email'];
                        $fullname = $firstname . ' ' . $lastname;

                        // message disclaimer:
                        $disclaimer = "--------------------------------------------------------------<br><br>";
                        $disclaimer .= "This is a message from ".$sitename.". You are receiving this because you are a double opted-in member of " . $sitename . " with username " . $username . "<br><br>";
                        $disclaimer .= "You can opt out of receiving all emails from this website by logging in and deleting your account here:<br><br><a href=\"" . $domain . "/login\">" . $domain . "/login</a><br><br>";
                        $disclaimer .= "Kindly allow up to 24 hours to stop receiving mail once you delete your account.<br><br>";
                        $disclaimer .= "Thank you,<br>" . $adminname . "<br>" . $sitename . "<br><br><br>";
                        $disclaimer .= "Live Removal Assistance or Questions: <a href=\"mailto:" . $adminemail . "\">" . $adminemail ."</a><br><br>";
                        $disclaimer .= "This email is sent in strict compliance with International spam laws.<br><br>";

                        // full message and subject with disclaimer as well as this member's substitution:
                        $html = $message . "<br><br><br>" . $disclaimer;
                        $html = str_replace("~USERID~", $username, $html);
                        $html = str_replace("~FULLNAME~", $fullname, $html);
                        $html = str_replace("~FIRSTNAME~", $firstname, $html);
                        $html = str_replace("~LASTNAME~", $lastname, $html);
                        $html = str_replace("~EMAIL~", $email, $html);
                        $html = $html . "<br><br>Sent by: " . $senderuserid;
                        $subject = str_replace("~USERID~", $username, $subject);
                        $subject = str_replace("~FULLNAME~", $fullname, $subject);
                        $subject = str_replace("~FIRSTNAME~", $firstname, $subject);
                        $subject = str_replace("~LASTNAME~", $lastname, $subject);
                        $subject = str_replace("~EMAIL~", $email, $subject);

                        $htmlheader = "Content-Type: text/html; charset=windows-1252\n";

                        $sendsiteemail = new Email();
                        $sendsiteemail->sendEmail($email, $adminemail, $subject, $html, $sitename, $adminemail, $htmlheader);
                        
                        if ($senderuserid === 'admin') {

                            # if the admin sent it, send a copy to the default admin email account.
                            $sendsiteemail->sendEmail($adminemail, $adminemail, $subject, $html, $sitename, $adminemail, $htmlheader);
                        }
                    }
                }

                if ($save === '0') {

                    // delete the mail record if it is not one the user saved:
                    $sql = "delete from mail where id=?";
                    $q = $pdo->prepare($sql);
                    $q->execute([$id]);

                } else {

                    // update the mail record to show it has been sent:
                    $sql = "update mail set needtosend=0, sent=? where id=?";
                    $q = $pdo->prepare($sql);
                    $q->execute([$sent, $id]);

                }
            }
        }

        Database::disconnect();
    }




}

$sitesettings = new Settings();
$settings = $sitesettings->getSettings();
foreach ($settings as $key => $value)
{
    $$key = $value;
}

$mail = new SendEmails();
$mail->getMails($domain, $sitename, $adminemail, $adminname);

