<?php
/**
Ad rotator.
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

class Rotator {

    private $pdo;

    public function giveClick($id) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select url from ads where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$id]);
        $url = $q->fetchColumn();

        if ($url) {

            $sql = "update ads set clicks=clicks+1 where id=?";
            $q = $pdo->prepare($sql);
            $q->execute([$id]);
            
            Database::disconnect();

            return $url;

        } else {

            Database::disconnect();
            return;
        }

    }

    public function giveHit($id) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,ERRMODE_EXCEPTION);
        $sql = "update ads set hits=hits+1 where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$id]);

        Database::disconnect();
    }

    public function getAds() {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select id,title,url,shorturl,description,imageurl from ads where added=1 and approved=1 order by rand() limit 6";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $ads = $q->fetchAll();

        if ($ads) {

            return $ads;
        }

        Database::disconnect();
    }

}