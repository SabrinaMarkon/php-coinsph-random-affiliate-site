<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (!isset($_SESSION))
{
    session_start();
}
require_once "../config/Database.php";
require_once "../config/Settings.php";
require_once "../config/Layout.php";

function sabrina_autoloader($class) {
    require '../classes/' . $class . ".php";
}
spl_autoload_register("sabrina_autoloader");

# get main site settings.
$sitesettings = new Settings();
$settings = $sitesettings->getSettings();
foreach ($settings as $key => $value) {
    $$key = $value;
}

# id variable is for the id of a single member, mail, etc. to update in the database.
if (isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
} else {
    $id = "";
}

# get the Layout template.
$Layout = new Layout();

$show = '';
$errors = '';

# get the form validation class instance to use for all the pages that post.
if (isset($_POST)) {

    $formvalidation = new FormValidation($_POST);
}

if (isset($_POST['login'])) {

    # admin clicked the login button.

    $_SESSION['adminusername'] = $_REQUEST['adminuser'];
    $_SESSION['adminpassword'] = $_REQUEST['adminpass'];

    $logincheck = new Admin($_POST);
    $newlogin = $logincheck->adminLogin($_SESSION['adminusername'],$_SESSION['adminpassword']);

    if ($newlogin === false) {

        # failed login.
        $logout = new Admin();
        $logout->adminLogout();
        $Layout->showHeader();
        $showcontent = new AdminLoginForm();
        echo $showcontent->showLoginForm(1);
        $Layout->showFooter();
        exit;
    } else {

        # successful admin login. Show the admin menu (in the header if the session vars are present).
        $Layout->showHeader();
        $showgravatar = $logincheck->getGravatar($adminemail);
        include 'main.php';
        $Layout->showFooter();
        exit;
    }
} else {

    if (isset($_POST['saveadminnotes'])) {

        # admin clicked to save the admin notes.
        $update = new AdminNote();
        $show = $update->setAdminNote($_POST['htmlcode']);
    }
    
    if (isset($_POST['savesettings'])) {
    
        $errors = $formvalidation->validateAll($_POST,$errors);
        if (!empty($errors)) {

            $show = $errors;
        } else {

            # admin clicked the button to save main settings.
            $update = new Setting();
            $show = $update->saveSettings($_SESSION['adminusername'], $_SESSION['adminpassword']);   
        }
    }
    
    if (isset($_POST['editmail'])) {
    
        # admin clicked to edit a saved email.
        $editmail = new Mail();
        $showeditmail = $editmail->editMail($id);
    }
    
    if (isset($_POST['addmail'])) {
    
        $errors = $formvalidation->validateAll($_POST,$errors);
        if (!empty($errors)) {

            $show = $errors;
        } else {

            # admin added a new email.
            $update = new Mail();
            $show = $update->addMail();
        }
    }
    
    if (isset($_POST['savemail'])) {
    
        $errors = $formvalidation->validateAll($_POST,$errors);
        if (!empty($errors)) {

            $show = $errors;
        } else {

            # admin saved an existing email they were editing. 
            $update = new Mail();
            $show = $update->saveMail($id);
        }
    }
    
    if (isset($_POST['sendverifications'])) {
    
        # admin resent verification emails to all unverified members.
        $verify = new Mail();
        $show = $verify->sendVerifications($settings);
    }
    
    if (isset($_POST['deletemail'])) {
    
        $errors = $formvalidation->validateAll($_POST,$errors);
        if (!empty($errors)) {

            $show = $errors;
        } else {

            # admin deleted an email.
            $delete = new Mail();
            $show = $delete->deleteMail($id);
        }
    }
    
    if (isset($_POST['sendmail'])) {
    
        $errors = $formvalidation->validateAll($_POST,$errors);
        if (!empty($errors)) {

            $show = $errors;
        } else {

            # admin clicked to send an email.
            $send = new Mail();
            $show = $send->sendMail($id);
        }
    }
    
    if (isset($_POST['editpage'])) {
    
        # admin selected an existing page to edit.
        $editpage = new Page();
        $showeditpage = $editpage->editPage($id);
    }
    
    if (isset($_POST['addpage'])) {
    
        $errors = $formvalidation->validateAll($_POST,$errors);
        if (!empty($errors)) {

            $show = $errors;
        } else {
        
            # admin added a new page.
            $update = new Page();
            $show = $update->addPage($domain);
        }
    }
    
    if (isset($_POST['savepage'])) {
        
        $errors = $formvalidation->validateAll($_POST,$errors);
        if (!empty($errors)) {

            $show = $errors;
        } else {
        
            # admin saved a page they were editing.
            $update = new Page();
            $show = $update->savePage($id);
        }
    }
    
    if (isset($_POST['deletepage'])) {
    
        $errors = $formvalidation->validateAll($_POST,$errors);
        if (!empty($errors)) {

            $show = $errors;
        } else {
        
            # admin deleted a page.
            $delete = new Page();
            $show = $delete->deletePage($id);
        }
    }
    
    if (isset($_POST['addmember'])) {
    
        # admin added a new member.
        $add = new Member();
        $show = $add->addMember($settings);
    }
    
    if (isset($_POST['savemember'])) {
    
        # admin saved a member they edited.
        $update = new Member();
        $show = $update->saveMember($id);
    }

    if (isset($_POST['deletemember'])) {
    
        # admin deleted a member and their ads and positions.
        $delete = new Member();
        $show = $delete->deleteMember($id,$giveextratoadmin);
    }
  
    if (isset($_POST['savetransaction'])) {
    
        # admin saved a transaction they were editing.
        $update = new Money();
        $show = $update->saveTransaction($id);
    }
    
    if (isset($_POST['deletetransaction'])) {
    
        # admin deleted a transaction.
        $delete = new Money();
        $show = $delete->deleteTransaction($id);
    }

    if (isset($_POST['addrandomizer'])) {

        # admin added a new randomizer position.
        $username = $_POST['username'];
        $walletid = $_POST['walletid'];
        $returnmessage = 1;
        $update = new Randomizer();
        $show = $update->addRandomizer($username,$walletid,$returnmessage);
    }

    if (isset($_POST['saverandomizer'])) {
    
        # admin saved a randomizer position they edited.
        $username = $_POST['username'];
        $walletid = $_POST['walletid'];
        $update = new Randomizer();
        $show = $update->saveRandomizer($username,$walletid,$id);
    }
    
    if (isset($_POST['deleterandomizer'])) {
    
        # admin deleted a randomizer position.
        $delete = new Randomizer();
        $show = $delete->deleteRandomizer('',$id);
    }

    if (isset($_POST['addadminwallet'])) {
    
        # admin added a new admin wallet id.
        $add = new AdminWallet();
        $show = $add->addAdminWallet();
    }
    
    if (isset($_POST['saveadminwallet'])) {
    
        # admin saved an admin wallet id they edited.
        $update = new AdminWallet();
        $show = $update->saveAdminWallet($id);
    }
    
    if (isset($_POST['deleteadminwallet'])) {
    
        # admin deleted an admin wallet id.
        $delete = new AdminWallet();
        $show = $delete->deleteAdminWallet($id);
    }

    if ((empty($_REQUEST['page'])) or 
    ((!empty($_REQUEST['page']) and ($_REQUEST['page'] === 'index' or $_REQUEST['page'] === 'logout' or $_REQUEST['page'] === 'forgot' or $_REQUEST['page'] === 'control'))) or 
    ((!empty($_GET['page'])) and ((!file_exists($_GET['page'] . ".php"))))) {
    
        # 1 - the URL is simply /admin without a /page on the end, so just go to the login form.
        # 2 - OR the URL has a page like /admin/page, but that page is /admin/index (this file).
        # 3 - OR the URL has a page like /admin/page, but that page is /admin/logout.
        # 4 - OR the URL has a page like /admin/page, but that page is /admin/forgot (which doesn't have its own view. It sends an email than shows the login form again).
        # 5 - OR a page was requested like /admin/page, but the filename to match does not exist ie. /admin/blahblah.
        # 6 - OR the session control.php file was requested.
        ### going to admin/index (this file) or /admin/logout or /admin/forgot or /admin/adfadsfadslkjal or /admin/control is the same as going to admin/ and killing the login session.
        $logout = new Admin();
        $logout->adminLogout();  
        $showcontent = new AdminLoginForm();
    
        $Layout->showHeader();
        
        # admin clicked the forgotten password link.
        if ((!empty($_REQUEST['page']) and $_REQUEST['page'] === 'forgot')) {
    
            # we need to email the forgotten login details, and say so before we show the login form.
            echo $logout->forgotLogin($sitename,$domain,$adminemail,$adminuser,$adminpass);
        }

        echo $showcontent->showLoginForm(0);
    
    } elseif ((!empty($_GET['page'])) and ((file_exists($_GET['page'] . ".php")))) {
    
        # there is a page.php that exists, and is not /admin/index (this file) or /admin/logout or /admin/forgot or some non-existent file. 
        $Layout->showHeader();
        $page = $_REQUEST['page'];
        include $page . ".php";
    }
    
    else {
        
        # show the main admin area page because everything was ok to login, but no specific admin page was specified in the request.
        $Layout->showHeader();
        include "main.php";
    }

    # show the admin footer design.
    $Layout->showFooter();

}





// IGNORE BELOW for now (works without but it would be nicer is all)
// REFACTOR LATER to make better routes etc.
//if (isset($_POST['_method'])) {
//
//    $_method = $_POST['_method'];
//    if($_method === 'DELETE') {
//
//        $delete = new Money();
//        $showdelete = $delete->deleteTransaction($id);
//
//    }
//    elseif($_method === 'PATCH')
//    {
//
//        $update = new Money();
//        $show = $update->saveTransaction($id);
//    }
//}

######################################