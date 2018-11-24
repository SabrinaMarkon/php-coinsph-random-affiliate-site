<?php
/**
Handles admin adding, updating, or deleting pages.
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

class Page
{
    private $pdo;

    public function getAllPages() {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from pages order by name";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $pages = $q->fetchAll();
        $pagearray = array();
        foreach ($pages as $page) {
            array_push($pagearray, $page);
        }
        return $pagearray;

    }

    public function editPage($id) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from pages where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $editpage = $q->fetch();
        $found = $q->rowCount();
        Database::disconnect();
        if ($found > 0) {
            
            return $editpage;
        }
    }

    public function addPage($domain) {

        $name = $_POST['name'];
        $htmlcode = $_POST['htmlcode'];
        $slug = $_POST['slug'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "insert into `pages` set name=?, htmlcode=?, slug=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($name, $htmlcode, $slug));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>New Page: " . $name . " was Added!</strong><br>New URL: <a href=" . $domain . "/" . $slug . " target=_blank>" . $domain . "/" . $slug . "</a></div>";

    }

    public function savePage($id) {

        $name = $_POST['name'];
        $htmlcode = $_POST['htmlcode'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        # if slug cannot be edited, it means the page is a core url that cannot be changed,
        # even if its content can.
        if (isset($_POST['slug'])) {

            $slug = $_POST['slug'];
            $sql = "update pages set name=?, htmlcode=?, slug=? where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($name, $htmlcode, $slug, $id));
        } else {

            $sql = "update pages set name=?, htmlcode=? where id=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($name, $htmlcode, $id));      
        }
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Page Content for " . $name . " was Saved!</strong></div>";

    }

    public function deletePage($id) {

        $name = $_POST['name'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "delete from pages where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>The Page " . $name . " was Deleted</strong></div>";

    }
}