<?php
require "control.php";
if (isset($showupdate))
{
echo $showupdate;
}
$showcontent = new PageContent();
echo $showcontent->showPage('Members Area Ads Page');
$allads = new Ad();
$ads = $allads->showAds($username);
?>

<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
		
			<h1 class="ja-bottompadding">Create Ad</h1>
				
			<?php
			if (empty($ads)) {

				# means the person hasn't paid someone yet. Show pay buttons.
				echo "<p>You have no paid ads yet. Please pay BOTH your sponsor and a random member below, 
				or if you already have, please wait for both recipients to validate that they have received their payment,
				then the form to create your ad will become available here.</p><p>If you have already paid, and have
				been waiting a long time for the recipients to validate, please contact us with proof of
				both your payments, so we can approve release of your ads, as well as your addition to the randomizer.</p>";

				# TWO BITCOIN PAYMENT BUTTONS - one for sponsor, and one for random member. Ratio for admin button to appear in randomizer button.
				$bitcoinbuttons = new Bitcoin();
				echo $bitcoinbuttons->getBitcoinButtons($username);
			
			} else {
				# person has at least one ad they paid (2 people) for waiting for them to create.
				# show form to create ad.




			}
			?>

			<div class="ja-bottompadding"></div>

			<h1 class="ja-bottompadding">Your Ads</h1>
			
			<?php
			if (empty($ads)) {

				# the person has no ads yet. Say so, and tell them once they've paid they can create one.
				echo "<p>You have no ads yet. After paying for one to both your sponsor and another random member, 
				you can create one using the form which will appear above.</p>";
			
			} else {
				
				# person has at least one ad they paid for (sponsor and random), and have added.
				# show those ads and allow edit, save, delete.

				?>
				<div class="table-responsive">
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


//     id integer unsigned not null primary key auto_increment,
// username varchar(255) not null default 'admin',
// name varchar(255) not null,
// title varchar(255) not null,
// url varchar(500) not null,
// shorturl varchar(255) not null,
// description varchar(255) not null,
// imageurl varchar(500) not null,
// added tinyint(4) not null default '0',
// approved tinyint(4) not null default '0',
// hits integer unsigned not null default '0',
// clicks integer unsigned not null default '0',
// adddate datetime not null,


							$adddate = new DateTime($ad['adddate']);
							$dateadadded = $adddate->format('Y-m-d');
							?>
							<tr>
								<form action="/ads" method="post" accept-charset="utf-8" class="form" role="form">
								<td class="small"><?php echo $ad['id']; ?>
								</td>
								<td class="small">
									<img src="<?php echo $ad['imageurl']; ?>" alt="<?php echo $ad['title'] ?>" height="100" width="100">
								</td>
								<td>
									<label class="sr-only" for="name">Name:</label>
									<input type="text" name="name" value="<?php echo $ad['name']; ?>" class="form-control input-sm small" size="40" placeholder="Name">
								</td>
								<td>
									<label class="sr-only" for="url">Click-Thru URL:</label>
									<input type="text" name="url" value="<?php echo $ad['url']; ?>" class="form-control input-sm small" size="40" placeholder="Click-Thru URL">
								</td>
								<td>
									<a href="<?php echo $ad['shorturl'] ?>" target="_blank"><?php echo $ad['shorturl'] ?></a>
								</td>
								<td>
									<label class="sr-only" for="description">Ad Text:</label>
									<input type="text" name="description" value="<?php echo $ad['description']; ?>" class="form-control input-sm small" size="40" placeholder="Ad Text">
								</td>
								<td>
									<label class="sr-only" for="imageurl">Image URL:</label>
									<input type="text" name="imageurl" value="<?php echo $ad['imageurl']; ?>" class="form-control input-sm small" size="60" placeholder="Image URL">
								</td>
								<td>
									<label class="sr-only" for="approved">Verified:</label>
									<select name="approved" class="form-control input-md">
										<option value="1"<?php if ($ad['approved'] === 1) { echo " selected"; } ?>>Yes</option>
										<option value="0"<?php if ($ad['approved'] !== 1) { echo " selected"; } ?>>No</option>
									</select>
								</td>
								<td class="small">
									<?php echo $ad['hits']; ?>
								</td>
								<td class="small">
									<?php echo $ad['clicks']; ?>
								</td>
								<td class="small">
									<?php echo $adddate ?>
								</td>
								<td>
									<input type="hidden" name="_method" value="PATCH">
									<button class="btn btn-sm btn-primary" type="submit" name="savemember">SAVE</button>
								</td>
								</form>
								<td>
									<form action="/admin/members/<?php echo $ad['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
										<input type="hidden" name="_method" value="DELETE">
										<input type="hidden" name="username" value="<?php echo $ad['username']; ?>">
										<button class="btn btn-sm btn-primary" type="submit" name="deletemember">DELETE</button>
									</form>
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
	</div>
</div>