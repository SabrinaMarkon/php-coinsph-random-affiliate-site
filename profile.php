<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

require "control.php";
if (isset($showsaveprofile))
{
echo $showsaveprofile;
}
$showcontent = new PageContent();
echo $showcontent->showPage('Members Area Profile Page');
?>

<div class="container">
		
			<h1 class="ja-bottompadding">Your Profile</h1>
			

					<div class="text-center">
						<?php
						echo $showgravatar;
						?>
						<h3 class="ja-bottompadding"><?php echo $username ?></h3>
					</div>
					

					<form action="/profile" method="post" accept-charset="utf-8" class="form" role="form">

						<label for="firstname">First Name:</label>
						<input type="text" name="firstname" value="<?php echo $firstname ?>" class="form-control input-lg" placeholder="First Name" required>

						<label for="lastname" class="ja-toppadding">Last Name:</label>
						<input type="text" name="lastname" value="<?php echo $lastname ?>" class="form-control input-lg" placeholder="Last Name" required>

						<label for="email" class="ja-toppadding">Your Email:</label>
						<input type="hidden" name="oldemail" value="<?php echo $email ?>">
						<input type="email" name="email" value="<?php echo $email ?>" class="form-control input-lg" placeholder="Your Email" required>

						<label for="password" class="ja-toppadding">Password:</label>
						<input type="password" name="password" id="password" value="<?php echo $password ?>" class="form-control input-lg" placeholder="Password" required>

						<label for="confirm_password" class="ja-toppadding">Confirm Password:</label>
						<input type="password" name="confirm_password" id="confirm_password" value="<?php echo $password ?>" class="form-control input-lg" placeholder="Confirm Password" required>

						<label for="walletid" class="ja-toppadding">Bitcoin Wallet ID:</label>
						<input type="text" name="walletid" value="<?php echo $walletid ?>" class="form-control input-lg" placeholder="Bitcoin Wallet ID">

						<label for="coinsphpid" class="ja-toppadding">Coins.ph Peso Wallet ID:</label>
						<input type="text" name="coinsphpid" value="" class="form-control input-lg" placeholder="Coins.ph Peso Wallet ID">

						<label for="country" class="ja-toppadding">Country:</label>
						<select name="country" class="form-control input-lg">
							<option value="Philippines"<?php if ($country === "Philippines") { echo " selected"; } ?>>Philippines</option>
							<option value="United States"<?php if ($country == "United States") { echo " selected"; } ?>>United States</option>
							<option value="Canada"<?php if ($country === "Canada") { echo " selected"; } ?>>Canada</option>
							<?php
							$countrylist = new Countries();
							echo $countrylist->showCountries($country);
							?>
						</select>

						<div class="ja-bottompadding"></div>

						<button class="btn btn-lg btn-primary ja-toppadding" type="submit" name="saveprofile">Save My Profile</button>

						<button class="btn btn-lg btn-primary ja-toppadding" type="submit" name="resendverification">Resend Email Verification</button>

					</form>									

			<div class="ja-bottompadding"></div>

</div>