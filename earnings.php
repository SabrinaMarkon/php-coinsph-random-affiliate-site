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
echo $showcontent->showPage('Members Area Earnings Page');
?>

<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
		
			<h1 class="ja-bottompadding">Your Earnings</h1>
			

					



			<div class="ja-bottompadding"></div>

			<h1 class="ja-bottompadding">Randomizer</h1>
			

					



			<div class="ja-bottompadding"></div>

		</div>
	</div>
</div>