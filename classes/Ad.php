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

    public function showAds($username) {
        
        $pdo = DATABASE::connect($username);
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

    public function createAd() {

        return;
    }

    public function getAd($id) {

        return;
    }


//     id integer unsigned not null primary key auto_increment,
// username varchar(255) not null default 'admin',
// name varchar(255) not null,
// title varchar(255) not null,
// url varchar(500) not null,
// shorturl varchar(255) not null,
// description varchar(255) not null,
// imageurl varchar(500) not null,
// added tinyint(4) not null default '0',
// approved tinyint(4) not null default '0',
// hits integer unsigned not null default '0',
// clicks integer unsigned not null default '0',
// adddate datetime not null,



    public function setAd($id) {

        $newname = $_POST['name'];
        $newtitle = $_POST['title'];
        $newurl = $_POST['url'];
        $newdescription = $_POST['description'];
        $newimageurl = $_POST['imageurl'];

        # generate shorturl with GOOGLE!!?!?!?!?

        $pdo = DATABASE::connect($username);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "update ads set name=?, title=?, url=?, description=?, imageurl=?, shorturl=?, added=1, approved=?, hits=0, clicks=0, adddate=NOW() where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($newname, $newtitle, $newurl, $newdescription, $newimageurl, $newshorturl, $adminautoapprove, $id));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Ad " . $newname . " was Saved!</strong></div>";
    }

    public function deleteAd($id, $name) {

        $pdo = DATABASE::connect($username);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "delete from ads where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Your Ad " . $name . " was Deleted</strong></div>";
    }

}