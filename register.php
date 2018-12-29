<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

if (isset($showregister))
{
echo $showregister;
$showcontent = new PageContent();
echo $showcontent->showPage('Thank You Page - New Member Signup');
$Layout = new Layout();
$Layout->showFooter();
exit;
}
# ajax request?

$showcontent = new PageContent();
echo $showcontent->showPage('Registration Page');
?>

<div class="container">

		<h1 class="ja-bottompadding">Sign Up</h1>

			<form action="/register" method="post" id="registerform" accept-charset="utf-8" class="form" role="form">
			
				<div class="row">
					<div class="col-xs-6 col-md-6">
						<label for="firstname" class="ja-toppadding">First Name:</label>
						<input type="text" name="firstname" value="" class="form-control input-lg" placeholder="First Name" required>
					</div>
					<div class="col-xs-6 col-md-6">
						<label for="lastname" class="ja-toppadding">Last Name:</label>
						<input type="text" name="lastname" value="" class="form-control input-lg" placeholder="Last Name" required>
					</div>
				</div>
				
						<label for="email" class="ja-toppadding">Your Email:</label>
						<input type="email" name="email" value="" class="form-control input-lg" placeholder="Your Email" required>

						<label for="username" class="ja-toppadding">Username:</label>
						<input type="text" name="username" value="" class="form-control input-lg" placeholder="Username" required>

						<label for="password" class="ja-toppadding">Password:</label>
						<input type="password" name="password" id="password1" value="" class="form-control input-lg" placeholder="Password" required>

						<label for="confirm_password" class="ja-toppadding">Confirm Password:</label>
						<input type="password" name="confirm_password" id="password2" value="" class="form-control input-lg" placeholder="Confirm Password" required>

						<label for="walletid" class="ja-toppadding">Bitcoin Wallet ID:</label>
						<input type="text" name="walletid" value="" class="form-control input-lg" placeholder="Bitcoin Wallet ID">

						<label for="coinsphpid" class="ja-toppadding">Coins.ph Peso Wallet ID:</label>
						<input type="text" name="coinsphpid" value="" class="form-control input-lg" placeholder="Coins.ph Peso Wallet ID">

						<label for="country" class="ja-toppadding">Country:</label>
						<select name="country" class="form-control input-lg">
							<option value="Philippines">Philippines</option>
							<option value="United States">United States</option>
							<option value="Canada">Canada</option>
							<?php
							$country = '';
							$countrylist = new Countries();
							echo $countrylist->showCountries($country);
							?>
						</select>

						<label for="referid" class="ja-toppadding">Your Sponsor:</label>
						<input type="text" name="referid" value="<?php echo $_SESSION['referid']; ?>" class="form-control input-lg" placeholder="<?php echo $_SESSION['referid']; ?>" required>

						<span class="help-block">By clicking Create My Account, you agree to our <a href="#" data-toggle="modal" data-target="#termsModal">Terms</a></span>
						
						<div class="ja-bottompadding"></div>

						<button class="btn btn-lg btn-primary" type="submit" name="register">Create My Account</button>

			</form>

			<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModal" aria-hidden="true">
				<div class="modal-dialog ja-modal-width">
					<div class="modal-content ja-modal">
						<div class="modal-body">

						<?php
						$terms = new PageContent();
						$showterms = $terms->showPage('Terms Page');
						echo $showterms;
						?>

						</div>
						<div class="modal-footer">
							<button class="btn btn-lg btn-primary" type="button" data-dismiss="modal" aria-hidden="true">Close</button>
						</div>
					</div>
				</div>
			</div>

			<div class="ja-bottompadding"></div>

</div>
