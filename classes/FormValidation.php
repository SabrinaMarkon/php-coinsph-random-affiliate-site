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
    $key,
    $val,
    $errors,
    $username,
    $password,
    $confirm_password,
    $adminpass,
    $confirm_adminpass,
    $adminuser,
    $referid,
    $walletid,
    $admindefaultwalletid,
    $sitename,
    $recipient,
    $transaction,
    $slug,
    $title,
    $firstname,
    $lastname,
    $name,
    $subject,
    $country,
    $adminname,
    $datepaid,
    $description,
    $message,
    $email,
    $adminemail,
    $url,
    $imageurl,
    $domain,
    $recipienttype,
    $giveextratoadmin,
    $adminautoapprove,
    $recipientapproved,
    $signupip,
    $adminratio,
    $id,
    $paysponsor,
    $payrandom,
    $amount;


    public function __construct($post) {

        # create db connection.
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        
        # assign the post variables into properties for form validation.
        foreach ($post as $key => $val) {

            $this->key = $key;
            $this->val = $val;
        }    
        
    }

    public function validateAll($post,$errors) {

        $errors .= $this->checkLength($post,$errors);

        if ($this->username) {
    
            # if a username was submitted, does it already exist in the system?
            $errors .= $this->checkUsernameDuplicates($this->username,$errors);
        }
        if ($this->password && $this->confirm_password) {
    
            # if password fields were submitted, are they the same?
            $errors .= $this->checkPasswordsMatch($this->password,$this->confirm_password,$errors);
        }
        if ($this->adminpass && $this->confirm_adminpass) {
    
            # if admin password fields were submitted, are they the same?
            $errors .= $this->checkPasswordsMatch($this->adminpass,$this->confirm_adminpass,$this->errors);
        }
        if ($this->referid) {
    
            # if a referid was submitted, does it exist?
            $errors .= $this->checkReferidExists($this->referid,$errors);
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

            if ($varname === 'username' || $varname === 'password' || $varname === 'confirm_password' || 
                $varname === 'walletid' || $varname === 'adminuser' || $varname === 'adminpass' || $varname === 'confirm_adminpass' || 
                $varname === 'admindefaultwalletid' || $varname === 'sitename' || $varname === 'recipient' || $varname === 'transaction') {

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_STRING);
                $numchars = strlen($varvalue);

                if ($numchars === 0) {

                    $errors .= "<div><strong>". $varname . " cannot be blank.</strong></div>";
                } elseif ($numchars < 5) {

                    $errors .= "<div><strong>The size of " . $varname ." must be 5 or more characters.</strong></div>";
                } elseif ($numchars > 50) {

                    $errors .= "<div><strong>The size of " . $varname . " must be 50 or less characters.</strong></div>";
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

                    $errors .= "<div><strong>". $varname . " cannot be blank.</strong></div>";
                }
                elseif ($numchars > 50) {

                    $errors .= "<div><strong>The size of " . $varname . " must be 50 or less characters.</strong></div>";
                }

            } elseif ($varname === 'title' || $varname === 'slug') {

                # ad's title.
                # page slug.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_STRING);
                $numchars = strlen($varvalue);

                if ($numchars === 0) {

                    $errors .= "<div><strong>". $varname . " cannot be blank.</strong></div>";
                }
                elseif ($numchars > 20) {

                    $errors .= "<div><strong>The size of " . $varname . " must be 20 or less characters.</strong></div>";
                }

            } elseif ($varname === 'description') {

                # ad's description.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_STRING);

                if (empty($varvalue)) {

                    $errors .= "<div><strong>". $varname . " cannot be blank.</strong></div>";
                }

            } elseif ($varname === 'message') {

                # admin email message body.

                if (empty($varvalue)) {

                    $errors .= "<div><strong>". $varname . " cannot be blank.</strong></div>";
                } 

            } elseif ($varname === 'email' || $varname === 'adminemail') {

                # user's or admin's email address.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_EMAIL);
                $numchars = strlen($varvalue);

                if ($numchars === 0) {

                    $errors .= "<div><strong>". $varname . " cannot be blank.</strong></div>";
                }
                elseif ($numchars < 8) {

                    $errors .= "<div><strong>". $varname . " must be 8 or more characters.</strong></div>";
                }
                elseif ($numchars > 300) {

                    $errors .= "<div><strong>The size of " . $varname . " must be 300 or less characters.</strong></div>";
                }
                elseif (!filter_var($varvalue,FILTER_VALIDATE_EMAIL)) {

                    $errors .= "<div><strong>The value of " . $varname . " must be a valid email address.</strong></div>";
                }

            } elseif ($varname === 'url' || $varname === 'imageurl' || $varname === 'domain') {

                # ad's url or image url.
                # admin's emailed url.
                # admin setting's domain.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_URL);
                $numchars = strlen($varvalue);

                if ($numchars === 0) {

                    $errors .= "<div><strong>". $varname . " cannot be blank.</strong></div>";
                }
                elseif ($numchars < 8) {

                    $errors .= "<div><strong>". $varname . " must be 8 or more characters.</strong></div>";
                }
                elseif ($numchars > 300) {

                    $errors .= "<div><strong>The size of " . $varname . " must be 300 or less characters.</strong></div>";
                }
                elseif (!filter_var($varvalue,FILTER_VALIDATE_URL)) {

                    $errors .= "<div><strong>The value of " . $varname . " must be a valid URL.</strong></div>";
                }

            } elseif ($varname === 'recipienttype') {

                # whether a randomizer payee is a sponsor or random.
                if ($varvalue !== 'random' && $varvalue !== 'sponsor') {

                    $errors .= "<div><strong>The type of randomizer recipient must be either a random member or a sponsor.</strong></div>";
                }

            } elseif ($varname === 'giveextratoadmin' || $varname === 'adminautoapprove' || $varname === 'recipientapproved') {

                # make sure the flag to auto-approve ads or give deleted randomizer positions to the admin area boolean values.
                # make sure flag on tranactions that a recipient has approved a transaction is boolean.

                if (!filter_var($varvalue, FILTER_VALIDATE_BOOLEAN)) {

                    $errors .= "<div><strong>The value of " . $varname . " must be Boolean. </strong></div>";

                }

            } elseif ($varname === 'signupip') {

                # admin area signupip for members.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_STRING);
                
                if (!filter_var($varvalue, FILTER_VALIDATE_IP)) {

                    $errors .= "<div><strong>The value of " . $varname . " must be an IP address. </strong></div>";
                    
                }

            } elseif ($varname === 'adminratio' || $varname === 'id') {

                # admin settings adminratio for randomizer.
                # any posted id value for a database record.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_NUMBER_INT);
                
                if (!filter_var($varvalue, FILTER_VALIDATE_INT) || $varvalue <= 0) {

                    $errors .= "<div><strong>The value of " . $varname . " must be an integer greater than 0. </strong></div>";
                    
                }
                 
            } elseif ($varname === 'paysponsor' || $varname === 'payrandom' || $varname === 'amount') {

                # admin settings paysponsor,payrandom.
                # amount owed to a recipient in the transactions money table.

                $varvalue = filter_var($varvalue, FILTER_SANITIZE_NUMBER_FLOAT);
                
                if (!filter_var($varvalue, FILTER_VALIDATE_FLOAT)) {

                    $errors .= "<div><strong>The value of " . $varname . " must be a dollar figure (optionally with a decimal i.e. 5.42). </strong></div>";
                    
                }

            }
            
        }

        return $errors;

    }

    # make sure the new username isn't already in the database.
    public function checkUsernameDuplicates($username,$errors) {

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

            $sql = "select * from members where username=?";
            $q = $pdo->prepare($sql);
            $q->execute(array($referid));
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $data = $q->fetch();
            if ($data['referid'] !== $referid)
            {
                
                $errors .= "<div><strong>The sponsor you entered does not exist in the system. Please check your spelling, or please just use 'admin' in the field if you are unsure.</strong></div>";
            }
        }

        return $errors;

    }

    # close database connection.
    public function __destruct() {

        DATABASE::disconnect();
    }

}