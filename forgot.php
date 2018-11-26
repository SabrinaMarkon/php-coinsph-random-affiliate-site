<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

if (isset($showforgot))
{
echo $showforgot;
}
?>
<div class="container">

		<h1 class="ja-bottompadding">Email Password</h1>

		<form action="/forgot" method="post" accept-charset="utf-8" class="form" role="form">

			<label class="sr-only" for="usernameoremail">Your Username or Email Address</label>
			<input type="text" name="usernameoremail" value="" class="form-control input-lg" placeholder="Your Username or Email Address">

			<div class="ja-bottompadding"></div>

			<button class="btn btn-lg btn-primary" type="submit" name="forgotlogin">Email Password</button>

		</form>

		<div class="ja-bottompadding"></div>

</div>
