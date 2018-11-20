<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

require "control.php";
if (isset($show))
{
echo $show;
}
$showcontent = new PageContent();
echo $showcontent->showPage('Members Area Ads Page');
$allads = new Ad();
$ads = $allads->getAds($username);
?>

<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
		
			<h1 class="ja-bottompadding">Create Ad</h1>
				
			<?php
			if (empty($ads)) {

				# means the person hasn't paid someone yet. Show pay buttons (either one or two).
				echo "<div class=\"ja-bottompadding ja-topadding\">You have no paid ads yet. Please pay BOTH your sponsor and a random member below. 
				If you already have, please wait for BOTH recipients to verify that they have received a payment from you,
				then the form to create your ad will become available here.</p><p>If you have ALREADY paid them BOTH, and have
				been waiting a long time for the recipients to validate, please contact us with PROOF of
				both your payments, so we can approve release of your ads, as well as your position in the randomizer.</div>";

				# Show bitcoin wallet IDs for BOTH sponsor and the random payee.
				$bitcoin = new Bitcoin();
				echo $bitcoin->showBitCoinWalletIds($username, $settings['paysponsor'], $settings['payrandom']);
			
			} else {
				/* person has at least one blank ad they paid (2 people) for.
				show form to create ad with an id to update the 2 paid transactions' adid. */

				# get the first available ad in the ads 
				$ad = $allads->getBlankAd($username);

				?>
				<form action="/ads" method="post" accept-charset="utf-8" class="form" role="form">

				<label for="name">Name of Ad:</label>
				<input type="text" name="name" class="form-control input-lg" placeholder="Name of Ad" required>

				<label for="title">Title:</label>
				<input type="text" name="title" class="form-control input-lg" placeholder="Title" required>

				<label for="url">Click-Thru URL:</label>
				<input type="url" name="url" class="form-control input-lg" placeholder="Click-Thru URL" required>

				<label for="description">Ad Text:</label>
				<input type="text" name="description" class="form-control input-lg" placeholder="Ad Text" required>

				<label for="imageurl">Image URL: (image will be resized to 100 x 100!)</label>
				<input type="url" name="imageurl" class="form-control input-lg" placeholder="Image URL (image will be resized to 100 x 100!)" required>

				<div class="ja-bottompadding"></div>

				<button class="btn btn-lg btn-primary ja-bottompadding ja-toppadding" type="submit" name="createad">CREATE AD</button>

				</form>
				<?php
			}
			?>

			<div class="ja-bottompadding ja-toppadding"></div>

			<h1 class="ja-bottompadding ja-toppadding">Your Ads</h1>
			
			<?php
			if (empty($ads)) {

				# the person has no ads yet. Say so, and tell them once they've paid they can create one.
				echo "<p>You have no ads yet. After paying for one to both your sponsor and another random member, 
				you can create one using the form which will appear above.</p>";
			
			} else {
				
				# person has at least one ad they paid for (sponsor and random), and have added.
				# show those ads and allow edit, save, delete.

				?>
					<table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
						<thead>
						<tr>
							<th class="text-center small">Ad #</th>
							<th class="text-center small">Image</th>
							<th class="text-center small">Name</th>
							<th class="text-center small">Title</th>
							<th class="text-center small">Click-Thru&nbsp;URL</th>
							<th class="text-center small">Short&nbsp;URL</th>
							<th class="text-center small">Ad&nbsp;Text</th>
							<th class="text-center small">Image&nbsp;URL</th>
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
						foreach ($ads as $ad) {

							$adddate = $ad['adddate'];
							$dateadadded = date('Y-m-d');
							?>
							<tr>
								<form action="/ads" method="post" accept-charset="utf-8" class="form" role="form">
								<td class="small"><?php echo $ad['id']; ?>
								</td>
								<td class="small">
									<img src="<?php echo $ad['imageurl']; ?>" alt="<?php echo $ad['title'] ?>" class="card-image">
								</td>
								<td class="small">
									<input type="text" name="name" value="<?php echo $ad['name']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Name" required>
								</td>
								<td class="small">
									<input type="text" name="title" value="<?php echo $ad['title']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Title" required>
								</td>
								<td>
									<input type="url" name="url" value="<?php echo $ad['url']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="http://L" required>
								</td>
								<td>
									<a href="<?php echo $ad['shorturl'] ?>" target="_blank"><?php echo $ad['shorturl'] ?></a>
								</td>
								<td>
									<input type="text" name="description" value="<?php echo $ad['description']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Ad Text" required>
								</td>
								<td>
									<input type="url" name="imageurl" value="<?php echo $ad['imageurl']; ?>" class="form-control input-sm widetableinput" size="60" placeholder="http://" required>
								</td>
								<td>
									<?php 
									if ($ad['approved'] === 1) { echo "Yes"; }
									else { echo "No"; }
									?>
								</td>
								<td class="small">
									<?php echo $ad['hits']; ?>
								</td>
								<td class="small">
									<?php echo $ad['clicks']; ?>
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
									<form action="/ads/<?php echo $ad['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
										<input type="hidden" name="_method" value="DELETE">
										<input type="hidden" name="username" value="<?php echo $ad['username']; ?>">
										<button class="btn btn-sm btn-primary" type="submit" name="deletead">DELETE</button>
									</form>
								</td>
							</tr>
							<?php
						}
						?>

						</tbody>
					</table>
				<?php
			}
			?>
			<div class="ja-bottompadding"></div>

		</div>
	</div>
</div>