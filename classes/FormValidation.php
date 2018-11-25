<?php
/**
Value checking for form input.
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

class FormValidation {

    private 
    $pdo,
    $post,
    $errors;

    private $PRETTY_VARNAMES = [

        'username' => 'username',
        'password' => 'password',
        'confirm_password' => 'password confirmation',
        'adminpass' => 'admin password',
        'confirm_adminpass' => 'admin password confirmation',
        'adminuser' => 'admin username',
        'referid' => 'referid',
        'walletid' => 'wallet ID',
        'admindefaultwalletid' => 'admin wallet ID',
        'sitename' => 'site name',
        'recipient' => 'recipient',
        'transaction' => 'transaction',
        'slug' => 'slug',
        'title' => 'title',
        'firstname' => 'first name',
        'lastname' => 'last name',
        'name' => 'name',
        'subject' => 'subject',
        'country' => 'country',
        'adminname' => 'admin name',
        'datepaid' => 'date paid',
        'description' => 'ad desscription',
        'message' => 'message',
        'email' => 'email',
        'adminemail' => 'admin email',
        'url' => 'URL',
        'imageurl' => 'Image URL',
        'domain' => 'domain',
        'recipienttype' => 'type of recipient',
        'giveextratoadmin' => 'the value give admin deleted positions',
        'adminautoapprove' => 'for auto approve ads',
        'recipientapproved' => 'the value to show if the recipient has approved payment',
        'signupip' => 'signup IP',
        'adminration' => 'the admin ratio for randomizer',
        'id' => 'id',
        'paysponsor' => 'the amount a member should pay their sponsor',
        'payrandom' => 'the amount a member should pay a random member',
        'amount' => 'amount'
    ];

    public function validateAll($post) {

        $errors = '';
        
        $errors = $this->checkLength($post,$errors);

        if (isset($post['username'])) {
  
            if (isset($post['addmember']) || isset($post['savemember']) || isset($post['register'])) {

                # if a username was submitted for registration or saving profile in admin, does it already exist in the system?
                $errors = $this->checkUsernameDuplicates($post['username'],$errors);
            }
            elseif (isset($post['addrandomizer']) || isset($post['saverandomizer']) || isset($post['addtransaction']) || isset($post['savetransaction'])) {

                # if a username was submitted to add a randomizer position or transaction, it should already exist in the system.
                $errors = $this->checkUserExists($post['username'],'username',$errors);
            }

        }
        if (isset($post['recipient'])) {

                # if a recipient username was submitted to add a randomizer position or transaction, it should already exist in the system.
                $errors = $this->checkUserExists($post['recipient'],'recipient',$errors);
        }
        if (isset($post['password']) && isset($post['confirm_password'])) {
    
            # if password fields were submitted, are they the same?
            $errors = $this->checkPasswordsMatch($post['password'],$post['confirm_password'],$errors);
        }
        if (isset($post['adminpass']) && isset($post['confirm_adminpass'])) {
    
            # if admin password fields were submitted, are they the same?
            $errors = $this->checkPasswordsMatch($post['adminpass'],$post['confirm_adminpass'],$errors);
        }
        if (isset($post['referid'])) {
    
            # if a referid was submitted, does it exist?
            $errors = $this->checkReferidExists($post['referid'],$errors);
        }

        if (!empty($errors)) {

            return "<div class=\"alert alert-danger\" style=\"width:75%;\"><strong>" . $errors . "</strong></div>";

        } else {

            return;
        }

    }

   
    # check the size limitations for each variable that was submitted.
    public function checkLength($post,$errors) {

        foreach ($post as $varname => $varvalue) {

            # user's username, password, confirm_password, walletid.
            # admin's username, password, confirm_password, walletid, admindefaultwalletid, sitename.
            # admin money area's transaction.
            # admin area randomizer's username and walletid for randomizer positions.

            if (in_array($varname, $this->PRETTY_VARNAMES)) {

                $pretty_varname = $this->PRETTY_VARNAMES[$varname];
            } else {

                $pretty_varname = $varname;
            }

            if ($varname === 'username' || $varname === 'password' || $varname === 'confirm_password' || 
                $varname === 'walletid' || $varname === 'adminuser' || $varname === 'adminpass' || $varname === 'confirm_adminpass' || 
                $varname === 'admindefaultwalletid' || $varname === 'sitename' || $varname === 'recipient' || $varname === 'transaction') {

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_STRING);
                $numchars = strlen($varvalue);

                if ($numchars < 5) {

                    $errors .= "<div><strong>The size of " . $pretty_varname ." must be 5 or more characters.</strong></div>";
                } elseif ($numchars === 0) {

                    $errors .= "<div><strong>". $pretty_varname . " cannot be blank.</strong></div>";
                } elseif ($numchars > 50) {

                    $errors .= "<div><strong>The size of " . $pretty_varname . " must be 50 or less characters.</strong></div>";
                }

            } elseif ($varname === 'firstname' || $varname === 'lastname' || $varname === 'name' || $varname === 'subject' || $varname === 'country' || 
                        $varname === 'adminname' || $varname === 'datepaid') {

                # user's firstname, lastname.
                # user's country.
                # ad's name, admin's walletid's name.
                # admin email's subject.
                # admin's settings name.
                # randomizer's datapaid.
                # page name.
                
                $varvalue = filter_var($varvalue, FILTER_SANITIZE_STRING);
                $numchars = strlen($varvalue);

                if ($numchars === 0) {

                    $errors .= "<div><strong>". $pretty_varname . " cannot be blank.</strong></div>";
                }
                elseif ($numchars > 50) {

                    $errors .= "<div><strong>The size of " . $pretty_varname . " must be 50 or less characters.</strong></div>";
                }

            } elseif ($varname === 'title' || $varname === 'slug') {

                # ad's title.
                # page slug.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_STRING);
                $numchars = strlen($varvalue);

                if ($numchars === 0) {

                    $errors .= "<div><strong>". $pretty_varname . " cannot be blank.</strong></div>";
                }
                elseif ($numchars > 20) {

                    $errors .= "<div><strong>The size of " . $pretty_varname . " must be 20 or less characters.</strong></div>";
                }

            } elseif ($varname === 'description') {

                # ad's description.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_STRING);

                if (empty($varvalue)) {

                    $errors .= "<div><strong>". $pretty_varname . " cannot be blank.</strong></div>";
                }

            } elseif ($varname === 'message') {

                # admin email message body.

                if (empty($varvalue)) {

                    $errors .= "<div><strong>". $pretty_varname . " cannot be blank.</strong></div>";
                } 

            } elseif ($varname === 'email' || $varname === 'adminemail') {

                # user's or admin's email address.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_EMAIL);
                $numchars = strlen($varvalue);

                if ($numchars === 0) {

                    $errors .= "<div><strong>". $pretty_varname . " cannot be blank.</strong></div>";
                }
                elseif ($numchars < 8) {

                    $errors .= "<div><strong>". $pretty_varname . " must be 8 or more characters.</strong></div>";
                }
                elseif ($numchars > 300) {

                    $errors .= "<div><strong>The size of " . $pretty_varname . " must be 300 or less characters.</strong></div>";
                }
                elseif (!filter_var($varvalue,FILTER_VALIDATE_EMAIL)) {

                    $errors .= "<div><strong>The value of " . $pretty_varname . " must be a valid email address.</strong></div>";
                }

            } elseif ($varname === 'url' || $varname === 'imageurl' || $varname === 'domain') {

                # ad's url or image url.
                # admin's emailed url.
                # admin setting's domain.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_URL);
                $numchars = strlen($varvalue);

                if ($numchars === 0) {

                    $errors .= "<div><strong>". $pretty_varname . " cannot be blank.</strong></div>";
                }
                elseif ($numchars < 8) {

                    $errors .= "<div><strong>". $pretty_varname . " must be 8 or more characters.</strong></div>";
                }
                elseif ($numchars > 300) {

                    $errors .= "<div><strong>The size of " . $pretty_varname . " must be 300 or less characters.</strong></div>";
                }
                elseif (!filter_var($varvalue,FILTER_VALIDATE_URL)) {

                    $errors .= "<div><strong>The value of " . $pretty_varname . " must be a valid URL.</strong></div>";
                }

            } elseif ($varname === 'recipienttype') {

                # whether a randomizer payee is a sponsor or random.
                if ($varvalue !== 'random' && $varvalue !== 'sponsor') {

                    $errors .= "<div><strong>The type of randomizer recipient must be either a random member or a sponsor.</strong></div>";
                }

            } elseif ($varname === 'giveextratoadmin' || $varname === 'adminautoapprove' || $varname === 'recipientapproved') {

                # make sure the flag to auto-approve ads or give deleted randomizer positions to the admin area boolean values.
                # make sure flag on tranactions that a recipient has approved a transaction is boolean.

                if ($varvalue !== '0' && $varvalue !== '1') {

                    $errors .= "<div><strong>The value of " . $pretty_varname . " must be Yes or No. </strong></div>";

                }

            } elseif ($varname === 'signupip') {

                # admin area signupip for members.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_STRING);
                
                if (!filter_var($varvalue, FILTER_VALIDATE_IP)) {

                    $errors .= "<div><strong>The value of " . $pretty_varname . " must be an IP address. </strong></div>";
                    
                }

            } elseif ($varname === 'adminratio' || $varname === 'id') {

                # admin settings adminratio for randomizer.
                # any posted id value for a database record.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_NUMBER_INT);
                
                if (!filter_var($varvalue, FILTER_VALIDATE_INT) || $varvalue <= 0) {

                    $errors .= "<div><strong>The value of " . $pretty_varname . " must be an integer greater than 0. </strong></div>";
                    
                }
                 
            } elseif ($varname === 'paysponsor' || $varname === 'payrandom' || $varname === 'amount') {

                # admin settings paysponsor,payrandom.
                # amount owed to a recipient in the transactions money table.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_NUMBER_FLOAT);
                
                if (!filter_var($varvalue, FILTER_VALIDATE_FLOAT)) {

                    $errors .= "<div><strong>The value of " . $pretty_varname . " must be a dollar figure (optionally with a decimal i.e. 5.42). </strong></div>";
                    
                }

            }
            
        }

        return $errors;

    }

    # check if a username/recipient/referid exists.
    public function invalidMemberCheck($username,$errors) {

        # create db connection.
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        $sql = "select * from members where username=?";
        $q = $pdo->prepare($sql);
        $q->execute(array($username));
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $data = $q->fetch();
        if (empty($data)) {

            # username does not exist.
            $errors .= "<div><strong>The username you entered, " . $username . " does not exist yet. Please sign them up first before adding to their account.</strong></div>";
        }

        return $errors;

    }

    # make sure the new username isn't already in the database.
    public function checkUsernameDuplicates($username,$errors) {

        # create db connection.
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$sql = "select * from members where username=?";
		$q = $pdo->prepare($sql);
		$q->execute(array($username));
		$q->setFetchMode(PDO::FETCH_ASSOC);
		$data = $q->fetch();
		if ($data['username'] === $username)
		{
            
			$errors .= "<div><strong>The username you chose isn't available.</strong></div>";
        }

        return $errors;

    }

    # make sure that password and confirm password match.
    public function checkPasswordsMatch($password,$confirm,$errors) {

        if ($password !== $confirm) {

            $errors .= "<div><strong>Your passwords do not match.</strong></div>";
        }

        return $errors;
    }

    # make sure that a non-admin referring member exists in the database.
    public function checkReferidExists($referid,$errors) {

        if ($referid !== 'admin') {

        # create db connection.
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $sql = "select * from members where username=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($referid));
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $data = $q->fetch();
            if (!$data['referid'])
            {
                
                $errors .= "<div><strong>The sponsor you entered does not exist in the system. Please check your spelling, or please just use 'admin' in the field if you are unsure.</strong></div>";
            }
        }

        return $errors;

    }

        # make sure that a user exists in the system.
    public function checkUserExists($username,$usertype,$errors) {

        if ($username !== 'admin') {

            # create db connection.
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            $sql = "select * from members where username=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($username));
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $data = $q->fetch();
            if (!$data['username'])
            {
                
                $errors .= "<div><strong>The " . $usertype . " you entered does not exist in the system. Please check the spelling.</strong></div>";
            }
        }

        return $errors;

    }

    # close database connection.
    public function __destruct() {

        Database::disconnect();
    }

}