<?php
/**
Handles admin changing site settings.
PHP 5.4+
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
 **/
class Setting
{

    public function saveSettings($adminuser, $adminpass) {

        $newadminuser = $_POST['adminuser'];
        $newadminpass = $_POST['adminpass'];
        $newadminname = $_POST['adminname'];
        $newadminemail = $_POST['adminemail'];
        $newsitename = $_POST['sitename'];
        $newdomain = $_POST['domain'];
        $newadminratio = $_POST['adminratio'];
        $newadminautoapprove = $_POST['adminautoapprove'];
        $admindefaultwalletid = $_POST['admindefaultwalletid'];

        # if either username or password changed, update session.
        if (($adminuser !== $newadminuser) or ($adminpass !== $newadminpass)) {
            $_SESSION['username'] = $newadminuser;
            $_SESSION['password'] = $newadminpass;
        }

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update adminsettings set adminuser=?, adminpass=?, adminname=?, adminemail=?, sitename=?, domain=?, adminratio=?, adminautoapprove=?, admindefaultwalletid=?";
        $q = $pdo->prepare($sql);
        $q-> execute(array($newadminuser, $newadminpass, $newadminname, $newadminemail, $newsitename, $newdomain, $newadminratio, $newadminautoapprove, $admindefaultwalletid));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Site Settings Were Saved!</strong></div>";

    }

}