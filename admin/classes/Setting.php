<?php
/**
Handles admin changing site settings.
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
        $newadmindefaultwalletid = $_POST['admindefaultwalletid'];

        # if either username or password changed, update session.
        if (($adminuser !== $newadminuser) or ($adminpass !== $newadminpass)) {
            $_SESSION['adminusername'] = $newadminuser;
            $_SESSION['adminassword'] = $newadminpass;
        }

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update adminsettings set adminuser=?, adminpass=?, adminname=?, adminemail=?, sitename=?, domain=?, adminratio=?, adminautoapprove=?, admindefaultwalletid=?";
        $q = $pdo->prepare($sql);
        $q-> execute(array($newadminuser, $newadminpass, $newadminname, $newadminemail, $newsitename, $newdomain, $newadminratio, $newadminautoapprove, $newadmindefaultwalletid));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Site Settings Were Saved!</strong></div>";

    }

}