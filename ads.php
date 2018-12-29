<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

require "control.php";
if (isset($showad))
{
echo $showad;
}

$showcontent = new PageContent();
echo $showcontent->showPage('Members Area Ads Page');

$ads = new Ad();
$transactions = new Money();

# see if the user has any blank ads, and if so, get the first one (by id).
$oneblankad = $ads->getBlankAd($username);

# get all the user's active ads (to see clicks,hits,edit,etc.)
$activeads = $ads->getAllUsersAds($username);

# get all the transactions the user still owes.
// $owed = $alltransactions->getUserTransactions($username,'owes');

# get all the transactions the user will get paid or has already been paid.
// $gets = $alltransactions->getUserTransactions($username,'gets');

# get all the transactinos that the user owed but already paid.
// $paid = $alltransactions->getUserTransactions($username,'paid');
?>

<div class="container">
					
			<?php
			if (empty($oneblankad)) {

				# There are no blank ads, so check to see if there are any transactions that the member needs to pay.
				$owed = $transactions->getUserTransactions($username,'owes');

				if (!empty($owed)) {

					# user has transactions to pay.

					echo "<div class=\"ja-bottompadding ja-topadding my-5\">You currently have no blank ads. Please pay BOTH your sponsor and a random member below. 
					If you already have, please wait for BOTH recipients to verify that they have received a payment from you,
					then the form to create your ad will become available here.</p><p>If you have ALREADY paid them BOTH, and have
					been waiting a long time for the recipients to validate, please contact us with PROOF of
					both your payments, so we can approve release of your ads, as well as your position in the randomizer.</div>";
	
					# Show bitcoin and coinsph wallet IDs for BOTH sponsor and the random payee.
					$bitcoin = new Bitcoin();
					$showbitcoin = $bitcoin->showBitCoinWalletIds($username,$settings);
					
					if ($showbitcoin) {

						echo "<div class=\"ja-yellowbg ja-bitcoinbox\">" . $showbitcoin . "</div>";
					}
				
				} else {

					# user has no blank ads but doesn't owe anyone either.
					echo "<div class=\"ja-bottompadding ja-topadding mb-5\">You have no paid ads available.</div>";

				}

				echo "<div class=\"ja-bottompadding mb-5\"></div>";

			
			} else {
				
				# the user has at least one blank ad they can submit, so they must have paid the sponsor and random user to have received it.

				/* person has at least one blank ad they paid (2 people) for.
				show form to create ad with an id to update the 2 paid transactions' adid. */

				# get the ad's id for the adid.
				$adid = $oneblankad['id'];

				?>
				<h1 class="ja-bottompadding">Create Ad</h1>

				<form action="/ads/<?php echo $adid ?>" method="post" accept-charset="utf-8" class="form" role="form">

					<label for="name">Name of Ad (only you see):</label>
					<input type="text" name="name" id="name" class="form-control input-lg" placeholder="Name" required>

					<label for="title">Ad Title:</label>
					<input type="text" name="title" id="title" class="form-control input-lg" placeholder="Ad Title" required>

					<label for="url">Click-Thru URL:</label>
					<input type="url" name="url" id="url" class="form-control input-lg" placeholder="Click-Thru URL" required>

					<label for="description">Ad Text:</label>
					<input type="text" name="description" id="description" class="form-control input-lg" placeholder="Ad Text" required>

					<label for="imageurl">Image URL: (100 x 100 pixels only)</label>
					<input type="url" name="imageurl" id="imageurl" class="form-control input-lg" placeholder="Image URL" required>

					<div class="ja-bottompadding"></div>

					<input type="hidden" name="id" value="<?php echo $adid ?>">
					<button class="btn btn-lg btn-primary ja-bottompadding ja-toppadding" type="submit" name="createad">CREATE AD</button>

				</form>
				<?php
			}
			?>

			<div class="ja-bottompadding ja-toppadding"></div>

			<h1 class="ja-bottompadding ja-toppadding">Your Ads</h1>
			
			<?php
			# dpes the user have existing ads in the rotation already?
			if (empty($activeads)) {

				# the person has no ads yet. Say so, and tell them once they've paid they can create one.
				echo "<div class=\"ja-bottompadding ja-topadding mb-5\">You have no ads yet. After paying for one to both your sponsor and another random member, 
				you can create one using the form which will appear above.</div>";
			
			} else {
				
				# person has at least one ad they paid for (paid both sponsor and a random user), and have added it to the system.
				# show those ads and allow edit, save, delete.

				?>
				<div class="table-responsive">
					<table class="table table-bordered table-striped table-hover text-center table-sm">
						<thead>
						<tr>
							<th class="text-center small">Ad&nbsp;#</th>
							<th class="text-center small" style="min-width: 100px;">Image</th>
							<th class="text-center small" style="min-width: 100px;">Name</th>
							<th class="text-center small" style="min-width: 100px;">Title</th>
							<th class="text-center small" style="min-width: 200px;">Click-Thru&nbsp;URL</th>
							<th class="text-center small">Short&nbsp;URL</th>
							<th class="text-center small" style="min-width: 200px;">Ad&nbsp;Text</th>
							<th class="text-center small" style="min-width: 200px;">Image&nbsp;URL</th>
							<th class="text-center small">Approved</th>
							<th class="text-center small">Impressions</th>
							<th class="text-center small">Clicks</th>
							<th class="text-center small">Date</th>
							<th class="text-center small">Save</th>
							<th class="text-center small">Delete</th>
						</tr>
						</thead>
						<tbody>

						<?php
						foreach ($activeads as $activead) {

							$adddate = $activead['adddate'];
							$dateadadded = date('Y-m-d');
							?>
							<tr>
								<form action="/ads/<?php echo $activead['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
									<td class="small"><?php echo $activead['id']; ?>
									</td>
									<td class="small">
										<img src="<?php echo $activead['imageurl']; ?>" alt="<?php echo $activead['title'] ?>" class="card-image">
									</td>
									<td class="small">
										<input type="text" name="name" value="<?php echo $activead['name']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Name" required>
									</td>
									<td class="small">
										<input type="text" name="title" value="<?php echo $activead['title']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Title" required>
									</td>
									<td>
										<input type="url" name="url" value="<?php echo $activead['url']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="http://" required>
									</td>
									<td>
										<a href="<?php echo $activead['shorturl'] ?>" target="_blank"><?php echo $activead['shorturl'] ?></a>
									</td>
									<td>
										<input type="text" name="description" value="<?php echo $activead['description']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Ad Text" required>
									</td>
									<td>
										<input type="url" name="imageurl" value="<?php echo $activead['imageurl']; ?>" class="form-control input-sm widetableinput" size="60" placeholder="http://" required>
									</td>
									<td class="small">
										<?php 
										if ($activead['approved'] === 1) { echo "Yes"; }
										else { echo "No"; }
										?>
									</td>
									<td class="small">
										<?php echo $activead['hits']; ?>
									</td>
									<td class="small">
										<?php echo $activead['clicks']; ?>
									</td>
									<td class="small">
										<?php echo $dateadadded ?>
									</td>
									<td>
										<input type="hidden" name="_method" value="PATCH">
										<button class="btn btn-sm btn-primary" type="submit" name="savead">SAVE</button>
									</td>
								</form>
								<td>
									<form action="/ads/<?php echo $activead['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
										<input type="hidden" name="_method" value="DELETE">
										<input type="hidden" name="name" value="<?php echo $activead['name']; ?>">
										<button class="btn btn-sm btn-primary" type="submit" name="deletead">DELETE</button>
									</form>
								</td>
							</tr>
							<?php
						}
						?>

						</tbody>
					</table>
				</div>
				<?php
			}
			?>
			<div class="ja-bottompadding"></div>

</div>