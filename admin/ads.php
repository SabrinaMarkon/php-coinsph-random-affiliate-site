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
$allads = new Ad();
$ads = $allads->getAllAds();
?>

<div class="container">
					
			<?php


				?>
				<h1 class="ja-bottompadding">Create Ad</h1>

				<form action="/admin/ads" method="post" accept-charset="utf-8" class="form" role="form">

					<label for="name">Name of Ad (only you see):</label>
					<input type="text" name="name" class="form-control input-lg" placeholder="Name" required>

					<label for="title">Ad Title:</label>
					<input type="text" name="title" class="form-control input-lg" placeholder="Ad Title" required>

					<label for="url">Click-Thru URL:</label>
					<input type="url" name="url" class="form-control input-lg" placeholder="Click-Thru URL" required>

					<label for="description">Ad Text:</label>
					<input type="text" name="description" class="form-control input-lg" placeholder="Ad Text" required>

					<label for="imageurl">Image URL: (100 x 100 pixels only)</label>
					<input type="url" name="imageurl" class="form-control input-lg" placeholder="Image URL" required>

					<div class="ja-bottompadding"></div>

					<button class="btn btn-lg btn-primary ja-bottompadding ja-toppadding" type="submit" name="createad">CREATE AD</button>

				</form>

			<div class="ja-bottompadding ja-toppadding"></div>

			<h1 class="ja-bottompadding ja-toppadding mb-4">All Member Ads</h1>
			
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
						if (!$ads) {


						} else {

							foreach ($ads as $ad) {

								$adddate = $ad['adddate'];
	
								if (trim($adddate) == '' || substr($adddate,0,10) == '0000-00-00') {
	
									$dateadadded = 'Not Yet';
								} else {
	
									$dateadadded = date('Y-m-d');
								}
								?>
								<tr>
									<form action="/admin/ads/<?php echo $ad['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
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
											<input type="url" name="url" value="<?php echo $ad['url']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="http://" required>
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
										<td class="small">
											<select name="approved" class="form-control widetableselect<?php if ($ad['approved'] !== "1") { echo ' ja-yellowbg'; } ?>">
												<option value="0" <?php if ($ad['approved'] !== "1") { echo "selected"; } ?>>No</option>
												<option value="1" <?php if ($ad['approved'] === "1") { echo "selected"; } ?>>Yes</option>
											</select>
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
										<form action="/admin/ads/<?php echo $ad['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
											<input type="hidden" name="_method" value="DELETE">
											<input type="hidden" name="name" value="<?php echo $ad['name']; ?>">
											<button class="btn btn-sm btn-primary" type="submit" name="deletead">DELETE</button>
										</form>
									</td>
								</tr>
								<?php
							}
						}
						?>

						</tbody>
					</table>
				</div>

			<div class="ja-bottompadding"></div>

</div>