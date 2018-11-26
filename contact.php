<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

if (isset($showcontact))
{
echo $showcontact;
}
?>
<div class="container">
			
		<h1 class="ja-bottompadding">Send Us a Message!</h1>

		<form action="/contact" method="post" accept-charset="utf-8" class="form" role="form">

			<label class="sr-only" for="username">Username</label>
			<input type="text" name="username" value="<?php if (isset($_SESSION['username'])) { echo $_SESSION['username']; } ?>" class="form-control input-lg" placeholder="Username">

			<label class="sr-only" for="email">Email</label>
			<input type="text" name="email" value="<?php if (isset($_SESSION['email'])) { echo $_SESSION['email']; } ?>" class="form-control input-lg" placeholder="Email">

			<label class="sr-only" for="subject">Message Subject</label>
			<input type="text" name="subject" value="" class="form-control input-lg" placeholder="Message Subject">

			<label class="sr-only" for="message">Message Body</label>
			<textarea name="message" value="" class="form-control input-lg" rows="10" placeholder="Message Body"></textarea>

			<div class="ja-bottompadding"></div>

			<button class="btn btn-lg btn-primary" type="submit" name="contactus">Send Message</button>

		</form>

</div>