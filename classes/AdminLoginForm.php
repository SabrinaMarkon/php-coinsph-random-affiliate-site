<?php
/**
Admin login form.
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

class AdminLoginForm
{
    public $loginerror;
    public $showloginerror;
    public $content;

    public function showLoginForm($loginerror) {

        $showloginerror = "";
        if ($loginerror == 1) {
            $showloginerror = "<div class=\"alert alert-danger\"><strong>Incorrect Login</strong></div>";
		}

$content = <<<HEREDOC
	<div class="container">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				
			<h1 class="ja-bottompadding">Admin Login</h1>

			<form action="/admin/main" method="post" accept-charset="utf-8" class="form" role="form">

				$showloginerror

				<label class="sr-only" for="adminuser">Username</label>
				<input type="text" name="adminuser" value="" class="form-control input-lg" placeholder="Username">

				<label class="sr-only" for="adminpass">Password</label>
				<input type="password" name="adminpass" value="" class="form-control input-lg" placeholder="Password">

				<button class="btn btn-lg btn-primary" type="submit" name="login">Login</button>

				<span class="help-block"><a href="/admin/forgot">Forgot Password?</a></span>

			</form>

			<div class="ja-bottompadding"></div>

			</div>
		</div>
	</div>
HEREDOC;

        return $content;
    }
}
