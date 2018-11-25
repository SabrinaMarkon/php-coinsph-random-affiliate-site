<?php
/**
Handles admin promotional banners and emails for the site.
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

class Promotional {

    public $pdo;

    public function getAllPromotionals() {

        # create db connection.
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from promotional order by type,id";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $promotionals = $q->fetchAll();

        Database::disconnect();

        return $promotionals;
    }

    public function editPromotional($id) {

        # create db connection.
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from promotional where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$id]);
        $promotional = $q->fetch(PDO::FETCH_ASSOC);

        Database::disconnect();

        if ($promotional) {
            
            return $promotional;
        }

    }

    public function addPromotional($post) {

        $name = $post['name'];
        $type = $post['type'];
        
        if (isset($post['promotionalimage'])) {

            $promotionalimage = $post['promotionalimage'];
        } else {
            $promotionalimage = '';
        }
        if (isset($post['promotionalsubject'])) {
           
            $promotionalsubject = $post['promotionalsubject'];  
        } else {

            $promotionalsubject = '';
        }
        if (isset($post['promotionaladbody'])) {

            $promotionaladbody = $post['promotionaladbody'];
        } else {

            $promotionaladbody = '';
        }

        # create db connection.
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "insert into promotional (name,type,promotionalimage,promotionalsubject,promotionaladbody) values (?,?,?,?,?)";
        $q = $pdo->prepare($sql);
        $q->execute([$name,$type,$promotionalimage,$promotionalsubject,$promotionaladbody]);

        Database::disconnect();

        if ($type === 'banner') {

            $prettytype = 'Banner';
        } else {

            $prettytype = 'Email';
        }

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>New Promotional " . $prettytype . " was Added!</strong></div>";

    }

    public function savePromotional($id,$post) {

        $name = $post['name'];

        # adbody variable has the id at the end because each tinymce instance on the same page needs its own id.
        $promotionaladbodyvarname = "promotionaladbody" . $id;

        if (isset($post['promotionalimage'])) {

            $promotionalimage = $post['promotionalimage'];
        } else {
            $promotionalimage = '';
        }
        if (isset($post['promotionalsubject'])) {
           
            $promotionalsubject = $post['promotionalsubject'];  
        } else {

            $promotionalsubject = '';
        }
        if (isset($post[$promotionaladbodyvarname])) {

            $promotionaladbody = $post[$promotionaladbodyvarname];
        } else {

            $promotionaladbody = '';
        }

        # create db connection.
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update promotional set name=?,promotionalimage=?,promotionalsubject=?,promotionaladbody=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$name,$promotionalimage,$promotionalsubject,$promotionaladbody,$id]);

        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Promotional Ad ID#" . $id . " was Saved!</strong></div>";
    }

    public function deletePromotional($id) {

        # create db connection.
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "delete from promotional where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$id]);

        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Promotional Ad ID#" . $id . " Was Deleted</strong></div>";
    }
}

