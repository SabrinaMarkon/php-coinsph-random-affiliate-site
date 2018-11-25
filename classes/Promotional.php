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

    public function __construct() {

        $this->pdo = new Database();
        $this->pdo->connect();
        $this->pdo->setAttributes(ATTR_ERRMODE,ERRMODE_EXCEPTION);
    
    }

    public function getAllPromotionals() {

        $sql = "select * from promotional order by type,id";
        $q = $this->pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $promotionals = $q->fetchAll();

        $this->pdo->disconnect();

        return $promotionals;
    }

    public function editPromotional($id) {

        $sql = "select * from promotional where id=?";
        $q = $this->pdo->prepare($sql);
        $q->execute([$id]);
        $promotional = $q->fetch(PDO::FETCH_ASSOC);

        $this->pdo->disconnect();

        if ($promotional) {
            
            return $promotional;
        }

    }

    public function addPromotional($post) {

        $name = $post['name'];
        $type = $post['type'];
        $promotionalimage = $post['promotionalimage'];
        $promotionalsubject = $post['promotionalsubject'];
        $promotionalbody = $post['promotionalbody'];
        $sql = "insert into promotional (name,type,promotionalimage,promotionalsubject,promotionaladbody) values (?,?,?,?,?)";
        $q = $this->pdo->prepare($sql);
        $q->execute([$name,$type,$promotionalimage,$promotionalsubject,$promotionalbody]);

        $this->pdo->disconnect();

        if ($type === 'banner') {

            $prettytype = 'Banner';
        } else {

            $prettytype = 'Email';
        }

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>New Promotional " . $prettytype . " was Added!</strong></div>";

    }

    public function savePromotional($id,$post) {

        $name = $post['name'];
        $promotionalimage = $post['promotionalimage'];
        $promotionalsubject = $post['promotionalsubject'];
        $promotionalbody = $post['promotionalbody'];
        $sql = "update promotional set name=?,promotionalimage=?,promotionalsubject=?,promotionalbody=? where id=?";
        $q = $this->pdo->prepare($sql);
        $q->execute([$name,$promotionalimage,$promotionalsubject,$promotionalbody]);

        $this->pdo->disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Promotional Ad ID#" . $id . " was Saved!</strong></div>";
    }

    public function deletePromotional($id) {

        $sql = "delete from promotional where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$id]);

        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Promotional Ad ID#" . $id . " Was Deleted</strong></div>";
    }
}

