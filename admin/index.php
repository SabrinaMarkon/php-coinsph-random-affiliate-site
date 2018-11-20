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
require_once "../classes/Countries.php";
require_once "../classes/Email.php";
require_once "../classes/Randomizer.php";
require_once "classes/LoginForm.php";
require_once "classes/Admin.php";

function sabrina_autoloader($class) {
    require 'classes/' . $class . ".php";
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

if (isset($_POST['login'])) {

    # admin clicked the login button.

    $_SESSION['adminusername'] = $_REQUEST['adminuser'];
    $_SESSION['adminpassword'] = $_REQUEST['adminpass'];

    $logincheck = new Admin();
    $newlogin = $logincheck->adminLogin($_SESSION['adminusername'],$_SESSION['adminpassword']);

    if ($newlogin === false) {

        # failed login.
        $logout = new Admin();
        $logout->adminLogout();
        $Layout->showHeader();
        $showcontent = new LoginForm();
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
        // $Layout->showHeader();
        $update = new AdminNote();
        $show = $update->setAdminNote($_POST['htmlcode']);
    }
    
    if (isset($_POST['savesettings'])) {
    
        # admin clicked the button to save main settings.
        // $Layout->showHeader();
        $update = new Setting();
        $show = $update->saveSettings($_SESSION['adminusername'], $_SESSION['adminpassword']);
    }
    
    if (isset($_POST['editmail'])) {
    
        # admin clicked to edit a saved email.
        // $Layout->showHeader();
        $editmail = new Mail();
        $showeditmail = $editmail->editMail($id);
    }
    
    if (isset($_POST['addmail'])) {
    
        # admin added a new email.
        // $Layout->showHeader();
        $update = new Mail();
        $show = $update->addMail();
    }
    
    if (isset($_POST['savemail'])) {
    
        # admin saved an existing email they were editing. 
        // $Layout->showHeader();
        $update = new Mail();
        $show = $update->saveMail($id);
    }
    
    if (isset($_POST['sendverifications'])) {
    
        # admin resent verification emails to all unverified members.
        // $Layout->showHeader();
        $verify = new Mail();
        $showverify = $verify->sendVerifications($settings);
    }
    
    if (isset($_POST['deletemail'])) {
    
        # admin deleted an email.
        // $Layout->showHeader();
        $delete = new Mail();
        $show = $delete->deleteMail($id);
    }
    
    if (isset($_POST['sendmail'])) {
    
        # admin clicked to send an email.
        // $Layout->showHeader();
        $send = new Mail();
        $show = $send->sendMail($id);
    }
    
    if (isset($_POST['editpage'])) {
    
        # admin selected an existing page to edit.
        // $Layout->showHeader();
        $editpage = new Page();
        $showeditpage = $editpage->editPage($id);
    }
    
    if (isset($_POST['addpage'])) {
    
        # admin added a new page.
        // $Layout->showHeader();
        $update = new Page();
        $show = $update->addPage($domain);
    }
    
    if (isset($_POST['savepage'])) {
        
        # admin saved a page they were editing.
        // $Layout->showHeader();
        $update = new Page();
        $show = $update->savePage($id);
    }
    
    if (isset($_POST['deletepage'])) {
    
        # admin deleted a page.
        // $Layout->showHeader();
        $delete = new Page();
        $show = $delete->deletePage($id);
    }
    
    if (isset($_POST['addmember'])) {
    
        # admin added a new member.
        // $Layout->showHeader();
        $add = new Member();
        $showadd = $add->addMember($settings);
    }
    
    if (isset($_POST['savemember'])) {
    
        # admin saved a member they edited.
        // $Layout->showHeader();
        $update = new Member();
        $show = $update->saveMember($id);
    }
    
    if (isset($_POST['deletemember'])) {
    
        # admin deleted a member and their ads and positions.
        // $Layout->showHeader();
        $delete = new Member();
        $show = $delete->deleteMember($id,$giveextratoadmin);
    }
    
    if (isset($_POST['savetransaction'])) {
    
        # admin saved a transaction they were editing.
        // $Layout->showHeader();
        $update = new Money();
        $show = $update->saveTransaction($id);
    }
    
    if (isset($_POST['deletetransaction'])) {
    
        # admin deleted a transaction.
        // $Layout->showHeader();
        $delete = new Money();
        $show = $delete->deleteTransaction($id);
    }

    if (isset($_POST['addadminwallet'])) {
    
        # admin added a new member.
        // $Layout->showHeader();
        $add = new AdminWallet();
        $show = $add->addAdminWallet();
    }
    
    if (isset($_POST['saveadminwallet'])) {
    
        # admin saved a member they edited.
        // $Layout->showHeader();
        $update = new AdminWallet();
        $show = $update->saveAdminWallet($id);
    }
    
    if (isset($_POST['deleteadminwallet'])) {
    
        # admin deleted a member and their ads and positions.
        // $Layout->showHeader();
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
        $showcontent = new LoginForm();
    
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