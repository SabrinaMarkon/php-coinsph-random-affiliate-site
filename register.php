<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

if (isset($showregistration))
{
echo $showregistration;
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
	<div class="row">
		<div class="col-md-6 col-md-offset-3">

		<h1 class="ja-bottompadding">Sign Up</h1>

			<form action="/register" method="post" accept-charset="utf-8" class="form" role="form">
			
				<div class="row">
					<div class="col-xs-6 col-md-6">
						<label class="sr-only" for="firstname">First Name</label>
						<input type="text" name="firstname" value="" class="form-control input-lg" placeholder="First Name" required>
					</div>
					<div class="col-xs-6 col-md-6">
						<label class="sr-only" for="lastname">Last Name</label>
						<input type="text" name="lastname" value="" class="form-control input-lg" placeholder="Last Name" required>
					</div>
				</div>
				
						<label class="sr-only" for="email">Your Email</label>
						<input type="email" name="email" value="" class="form-control input-lg" placeholder="Your Email" required>

						<label class="sr-only" for="username">Username</label>
						<input type="text" name="username" value="" class="form-control input-lg" placeholder="Username" required>

						<label class="sr-only" for="password">Password</label>
						<input type="password" name="password" id="password" value="" class="form-control input-lg" placeholder="Password" required>

						<label class="sr-only" for="confirm_password">Confirm Password</label>
						<input type="password" name="confirm_password" id="confirm_password" value="" class="form-control input-lg" placeholder="Confirm Password" required>

						<label class="sr-only" for="walletid">Bitcoin Wallet ID</label>
						<input type="text" name="walletid" value="" class="form-control input-lg" placeholder="Bitcoin Wallet ID" required>

						<label class="sr-only" for="country">Country</label>
						<select name="country" class="form-control input-lg">
							<option value="United States">United States</option>
							<option value="Canada">Canada</option>
							<?php
							$country = '';
							$countrylist = new Countries();
							echo $countrylist->showCountries($country);
							?>
						</select>

						<label class="sr-only" for="referid">Your Sponsor</label>
						<input type="text" name="referid" value="" class="form-control input-lg" placeholder="<?php echo $_SESSION['referid']; ?>" required>

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
	</div>
</div>
