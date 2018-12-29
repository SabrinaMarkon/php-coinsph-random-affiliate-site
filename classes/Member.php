<?php
/**
Handles admin adding, updating, or deleting members.
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

class Member
{
    private $pdo,$username,$password,$walletid,$coinsphpid,$firstname,$lastname,$country,$email,$signupip,$referid;

    public function getAllMembers() {

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from members order by username";
        $q = $pdo->prepare($sql);
        $q->execute();
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $members = $q->fetchAll();
        $memberarray = array();
        foreach ($members as $member) {
            array_push($memberarray, $member);
        }
//        print_r($memberarray);
//        exit;
        return $memberarray;

    }

    public function addMember($settings) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $walletid = $_POST['walletid'];
        $coinsphpid = $_POST['coinsphpid'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $country = $_POST['country'];
        $email = $_POST['email'];
        $signupip = $_SERVER['REMOTE_ADDR'];
        $referid = $_POST['referid'];

        if (empty($referid)) {
            
            $referid = 'admin';
        }

        $pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "select * from members where username=?";
		$q = $pdo->prepare($sql);
		$q->execute(array($username));
		$q->setFetchMode(PDO::FETCH_ASSOC);
		$data = $q->fetch();
		if ($data['username'] == $username)
		{
			Database::disconnect();

			return "<div class=\"alert alert-danger\" style=\"width:75%;\"><strong>The username you chose isn't available.</strong></div>";
        }
        
        $verificationcode = time() . mt_rand(10, 100);

        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "insert into members (username,password,walletid,coinsphpid,firstname,lastname,email,country,referid,signupdate,signupip,verificationcode) values (?,?,?,?,?,?,?,?,?,NOW(),?,?)";
        $q = $pdo->prepare($sql);
        $q->execute(array($username,$password,$walletid,$coinsphpid,$firstname,$lastname,$email,$country,$referid,$signupip,$verificationcode));

        # get the walletid & coinsphpid of the sponsor.
        if ($referid === 'admin') {

            $referidwalletid = $settings['admindefaultwalletid'];
            $referidcoinsphpid = $settings['admindefaultcoinsphpid'];
        } else {

            $sql = "select walletid,coinsphpid from members where username=?";
            $q = $pdo->prepare($sql);
            $q->execute([$referid]);
            $data = $q->fetch();

            if ($data) {

                $referidwalletid = $data['walletid'];
                $referidcoinsphpid = $data['coinsphpid'];
            }
        }

        # get a random payee from the randomizer, or an admin wallet depending on the ratio. Then add 1 to the ratiocounter.
        if ($settings['ratiocounter'] === $settings['adminratio']) {
            
            # time to give the admin a random payment.
            $sql = "select walletid,coinsphpid from adminwallets order by rand() limit 1";
            $q = $pdo->query($sql);
            $data = $q->fetch();

            if ($data) {

                $randomwalletid = $data['walletid'];
                $randomcoinsphpid = $data['coinsphpid'];
            }
            $randompayee = 'admin';

            # reset the ratiocounter.
            $sql = "update adminsettings set adminratio=0";
            $q = $pdo->query($sql);
        } else {

            # get a random wallet from the randomizer.
            $sql = "select * from randomizer order by rand() limit 1";
            $q = $pdo->query($sql);
            $data = $q->fetch();
            $randompayee = $data['username'];
            $randomwalletid = $data['walletid'];
            $randomcoinsphpid = $data['coinsphpid'];

            # add 1 to the ratiocounter.
            $sql = "update adminsettings set adminratio=adminratio+1";
            $q = $pdo->query($sql);
        }

        # create two unpaid transactions, one for the sponsor, and one for a random walletid and coinsphpid in the randomizer. If none exist, add admin walletid and coinsphpid.
        $sql = "insert into transactions (username,amount,recipient,recipientwalletid,recipientcoinsphpid,recipienttype) values (?,?,?,?,?,'sponsor')";
        $q = $pdo->prepare($sql);
        $q->execute([$username,$settings['paysponsor'],$referid,$referidwalletid,$referidcoinsphpid]);

        $sql = "insert into transactions (username,amount,recipient,recipientwalletid,recipientcoinsphpid,recipienttype) values (?,?,?,?,?,'random')";
        $q = $pdo->prepare($sql);
        $q->execute([$username,$settings['payrandom'],$randompayee,$randomwalletid,$randomcoinsphpid]);
                
        Database::disconnect();

        $subject = "Welcome to " . $settings['sitename'] . "!";
        $message = "Click to Verify your Email: " . $settings['domain'] . "/verify/" . $verificationcode . "\n\n";
        $message .= "Login URL: " . $settings['domain'] . "/login\nUsername: " . $username . "\nPassword: " . $password . "\n\n";
        $message .= "Your Referral URL: " . $settings['domain'] . "/r/" . $username . "\n\n";
        $message .= "Before receiving your ad and randomizer spot, you will need to send:\n";
        $message .= "1) To your Sponsor: " . $settings['paysponsor'] . "\n";
        
        if ($referidwalletid !== '') {
            $message .= "to Bitcoin Wallet ID: " . $referidwalletid;
        }
        if ($referidwalletid !== '' && $referidcoinsphpid !== '') {
            $message .= "\nOR\n";
        }
        if ($referidcoinsphpid !== '') {
            $message .= "to Coins.ph Peso Wallet ID: " . $referidcoinsphpid . "\n\n";
        }
        
        $message .= "2) To a Random Member: " . $settings['payrandom'] . "\n";
        
        if ($randomwalletid !== '') {
            $message .= " to Bitcoin Wallet ID: " . $randomwalletid; 
        }
        if ($randomwalletid !== '' && $randomcoinsphpid !== '') {
            $message .= "\nOR\n";
        }
        if ($randomcoinsphpid !== '') {
            $message .= "to Coins.ph Peso Wallet ID: " . $randomcoinsphpid . "\n\n";
        }

        $sendsiteemail = new Email();
        $send = $sendsiteemail->sendEmail($email, $settings['adminemail'], $subject, $message, $settings['sitename'], $settings['adminemail'], '');

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>New Member " . $username . " was Added!</strong></div>"; 
    }

    public function saveMember($id) {

        $username = $_POST['username'];
        $password = $_POST['password'];
        $walletid = $_POST['walletid'];
        $coinsphpid = $_POST['coinsphpid'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $country = $_POST['country'];
        $email = $_POST['email'];
        $signupip = $_POST['signupip'];
        $referid = $_POST['referid'];

        if (empty($referid)) {

            $referid = 'admin';
        }
        
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "update `members` set username=?, password=?, walletid=?, coinsphpid=?, firstname=?, lastname=?, country=?, email=?, signupip=?, referid=? where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($username, $password, $walletid, $coinsphpid, $firstname, $lastname, $country, $email, $signupip, $referid, $id));

        # update randomizer wallet ids.
		$sql = "update randomizer set walletid=?, coinsphpid=? where username=?";
		$q = $pdo->prepare($sql);
        $q->execute([$walletid,$coinsphpid,$username]);
        
        # update transactions wallet ids.
		$sql = "update transactions set recipientwalletid=?, recipientcoinsphpid=? where recipient=?";
		$q = $pdo->prepare($sql);
        $q->execute([$walletid,$coinsphpid,$username]);
        
        Database::disconnect();

        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Member " . $username . " was Saved!</strong></div>";
    }

    public function deleteMember($id,$giveextratoadmin) {

        $username = $_POST['username'];
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        
        # delete randomizer positions - reassign to admin or delete depending on admin setting giveextratoadmin.
        if ($giveextratoadmin === 1) {

            $sql = "update randomizer set username='admin' where username=?";
        } else {
            
            $sql = "delete from randomizer where username=?";
        }
        $q = $pdo->prepare($sql);
        $q->execute(array($username));

        # delete ads.
        $sql = "delete from ads where username=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($username));

        # delete transactions.
        $sql = "delete from transactions where username=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($username));

        # delete account.
        $sql = "delete from members where id=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));

        Database::disconnect();
        return "<div class=\"alert alert-success\" style=\"width:75%;\"><strong>Member " . $username . " was Deleted</strong></div>";
    }
}