<?php
class AdminNote
{
    public $adminnote;

    public function __construct() {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from adminnotes";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $this->adminnote['htmlcode'] = $q->fetch();

    }

    public function setAdminNote($htmlcode) {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update adminnotes set htmlcode=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($htmlcode));
        Database::disconnect();
        return "<center><div class=\"alert alert-success\" style=\"width:75%;\"><strong>Saved Your Admin Notes!</strong></div>";
    }

    public function getAdminNote()
    {
        return $this->adminnote['htmlcode'];
    }
}

