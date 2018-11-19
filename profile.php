<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

require "control.php";
if (isset($showupdate))
{
echo $showupdate;
}
$showcontent = new PageContent();
echo $showcontent->showPage('Members Area Profile Page');
?>

<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
		
			<h1 class="ja-bottompadding">Your Profile</h1>
			

					<div class="text-center">
						<?php
						echo $showgravatar;
						?>
						<h3 class="ja-bottompadding"><?php echo $username ?></h3>
					</div>
					

					<form action="/profile" method="post" accept-charset="utf-8" class="form" role="form">

						<label class="sr-only" for="firstname">First Name</label>
						<input type="text" name="firstname" value="<?php echo $firstname ?>" class="form-control input-lg" placeholder="First Name" required>

						<label class="sr-only" for="lastname">Last Name</label>
						<input type="text" name="lastname" value="<?php echo $lastname ?>" class="form-control input-lg" placeholder="Last Name" required>

						<label class="sr-only" for="email">Your Email</label>
						<input type="hidden" name="oldemail" value="<?php echo $email ?>">
						<input type="email" name="email" value="<?php echo $email ?>" class="form-control input-lg" placeholder="Your Email" required>

						<label class="sr-only" for="password">Password</label>
						<input type="password" name="password" id="password" value="<?php echo $password ?>" class="form-control input-lg" placeholder="Password" required>

						<label class="sr-only" for="confirm_password">Confirm Password</label>
						<input type="password" name="confirm_password" id="confirm_password" value="<?php echo $password ?>" class="form-control input-lg" placeholder="Confirm Password" required>

						<label class="sr-only" for="walletid">Bitcoin Wallet ID</label>
						<input type="text" name="walletid" value="" class="form-control input-lg" placeholder="Bitcoin Wallet ID" required>

						<label class="sr-only" for="country">Country</label>
						<select name="country" class="form-control input-lg">
							<option value="United States"<?php if ($country == "United States") { echo " selected"; } ?> >United States</option>
							<option value="Canada"<?php if ($country === "Canada") { echo " selected"; } ?>>Canada</option>
							<?php
							$countrylist = new Countries();
							echo $countrylist->showCountries($country);
							?>
						</select>

						<div class="ja-bottompadding"></div>

						<button class="btn btn-lg btn-primary" type="submit" name="saveprofile">Save My Profile</button>

					</form>

			<div class="ja-bottompadding"></div>

		</div>
	</div>
</div>