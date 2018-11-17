<?php
/**
Handles user interactions with the application.
PHP 5.4++
@author Sabrina Markon
@copyright 2018 Sabrina Markon, PHPSiteScripts.com
@license LICENSE.md
**/
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
        foreach ($ad as $ads) {
            array_push($adsarray, $ad);
        }
        Database::disconnect();
        
        return $adsarray;
    }

    public function createAd($username) {

        $name = $_POST['name'];
        $title = $_POST['title'];
        $url = $_POST['url'];
        $description = $_POST['description'];
        $imageurl = $_POST['imageurl'];

        # generate shorturl with GOOGLE!!?!?!?!?
        
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "insert into ads (username,name,title,url,shorturl,description,imageurl,added,approved,adddate) values (?,?,?,?,?,?,?,1,?,NOW())";
        $p = $pdo->prepare($sql);
        $p->execute(array($username,$name,$title,$url,$shorturl,$description,$imageurl,$adminautoapprove));
        Database::disconnect();
        
        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your New Ad: " . $name . " was Created!</strong></div>";
    }

    public function setAd($id) {

        $newname = $_POST['name'];
        $newtitle = $_POST['title'];
        $newurl = $_POST['url'];
        $newdescription = $_POST['description'];
        $newimageurl = $_POST['imageurl'];

        # generate shorturl with GOOGLE!!?!?!?!?

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update ads set name=?, title=?, url=?, description=?, imageurl=?, shorturl=?, added=1, approved=?, hits=0, clicks=0, adddate=NOW() where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($newname, $newtitle, $newurl, $newdescription, $newimageurl, $newshorturl, $adminautoapprove, $id));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Ad " . $newname . " was Saved!</strong></div>";
    }

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