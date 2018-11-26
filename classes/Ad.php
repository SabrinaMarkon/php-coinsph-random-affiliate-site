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

    /* Get all the ads for all members. */
    public function getAllAds() {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from ads order by approved asc,id desc";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $ads = $q->fetchAll();

        Database::disconnect();

        return $ads;
    }

    /* Get all the ads for one member. */
    public function getAllUsersAds($username) {
        
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from ads where username=? and added=1 order by id desc";
        $q = $pdo->prepare($sql);
        $q->execute(array($username));
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $ads = $q->fetchAll();

        Database::disconnect();
        
        if ($ads) {

            return $ads;
        }
    }

    /* Call this when we need to get the member a blank ad to create a new ad in the form. */
    public function getBlankAd($username) {
        
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "select * from ads where username=? and added=0 order by id limit 1";
        $q = $pdo->prepare($sql);
        $q->execute([$username]);
        $blankad = $q->fetch();

        Database::disconnect();

        if ($blankad) {

            return $blankad;
        }        
    }

    /* Call this when the user or admin submits their ad. */
    public function createAd($id,$username,$adminautoapprove,$isadmin,$post) {

        $name = $post['name'];
        $title = $post['title'];
        $url = $post['url'];
        $description = $post['description'];
        $imageurl = $post['imageurl'];

        # generate shorturl - FIREBASE LINKS ****
        $shorturl = '';
        
        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        # is it a user or the admin posting the ad?
        if ($isadmin) {

            $username = 'admin';
            $sql = "insert into ads (username,name,title,url,shorturl,description,imageurl,added,approved,adddate) values ('admin',?,?,?,?,?,?,1,1,NOW())";
            $q = $pdo->prepare($sql);
            $q->execute([$name,$title,$url,$shorturl,$description,$imageurl]);

        } else {

            $sql = "update ads set name=?,title=?,url=?,description=?,imageurl=?,shorturl=?,added=1,approved=?,hits=0,clicks=0,adddate=NOW() where id=?";
            $q = $pdo->prepare($sql);
            $q->execute([$name,$title,$url,$description,$imageurl,$shorturl,$adminautoapprove,$id]);
        }
        
        Database::disconnect();
        
        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>New Ad " . $name . " was Created!</strong></div>";
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
    public function saveAd($id,$adminautoapprove,$isadmin,$post) {

        $name = $post['name'];
        $title = $post['title'];
        $url = $post['url'];
        $description = $post['description'];
        $imageurl = $post['imageurl'];

        # generate shorturl - FIREBASE LINKS ****
        $shorturl = '';

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        if ($isadmin) {

            # admin has the option to choose to approve right away or not.
            $autoapprove = $post['approved'];
        } else {

            $autoapprove = $adminautoapprove;
        }
        $sql = "update ads set name=?,title=?,url=?,description=?,imageurl=?,shorturl=?,added=1,approved=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute([$name,$title,$url,$description,$imageurl,$shorturl,$autoapprove,$id]);
        
         Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>The Ad " . $name . " was Saved!</strong></div>";
    }

    /* Call this to delete an ad. */
    public function deleteAd($id, $name) {

        $pdo = DATABASE::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "delete from ads where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));

        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>The Ad " . $name . " was Deleted</strong></div>";
    }

}