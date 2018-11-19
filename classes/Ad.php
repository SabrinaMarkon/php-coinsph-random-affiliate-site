<?php
/**
Handles user interactions with the application.
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

class Ad {

    private $pdo;

    public function getAds($username) {
        
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from ads where username=? order by id desc";
        $q = $pdo->prepare($sql);
        $q->execute(array($username));
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $ads = $q->fetchAll();
        $adsarray = array();
        foreach ($ads as $ad) {
            array_push($adsarray, $ad);
        }
        Database::disconnect();
        
        return $adsarray;
    }

    /* Call this when we need to get the member a blank ad to create a new ad in the form. */
    public function getBlankAd($username) {
        
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from ads where username=? and added=0";
        $q = $pdo->prepare($sql);
        $q->execute([$adid]);
        $ad = $q->fetch();
        Database::disconnect();

        return $ad['id'];
    }

    /* Call this when the user submits their ad. */
    public function createAd($username) {

        $newname = $_POST['name'];
        $newtitle = $_POST['title'];
        $newurl = $_POST['url'];
        $newdescription = $_POST['description'];
        $newimageurl = $_POST['imageurl'];

        # generate shorturl - FIREBASE
        $newshorturl = '';
        
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update ads set name=?, title=?, url=?, description=?, imageurl=?, shorturl=?, added=1, approved=?, hits=0, clicks=0, adddate=NOW() where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($newname, $newtitle, $newurl, $newdescription, $newimageurl, $newshorturl, $adminautoapprove, $id));
        Database::disconnect();
        
        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your New Ad: " . $name . " was Created!</strong></div>";
    }

    /* When the second recipient (either the sponsor or the random member) confirms that they have received payment from the user, we
    call this method to create the blank ad for the user. */
    public function createBlankAd($username) {
       
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "insert into ads (username,adddate) values (?,NOW())";
        $q = $pdo->prepare($sql);
        $q->execute([$username]);

        # get the adid of the newly inserted blank ad.
        $adid = $pdo->lastInsertId();

        Database::disconnect();

        return $adid;
    }

    /* Call this when the user edits their existing ad. */
    public function saveAd($id) {

        $newname = $_POST['name'];
        $newtitle = $_POST['title'];
        $newurl = $_POST['url'];
        $newdescription = $_POST['description'];
        $newimageurl = $_POST['imageurl'];

        # generate shorturl - FIREBASE
        $newshorturl = '';

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update ads set name=?, title=?, url=?, description=?, imageurl=?, shorturl=?, added=1, approved=?, hits=0, clicks=0, adddate=NOW() where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($newname, $newtitle, $newurl, $newdescription, $newimageurl, $newshorturl, $adminautoapprove, $id));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Ad " . $newname . " was Saved!</strong></div>";
    }

    /* Call this to delete an ad. */
    public function deleteAd($id, $name) {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "delete from ads where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Ad " . $name . " was Deleted</strong></div>";
    }

}