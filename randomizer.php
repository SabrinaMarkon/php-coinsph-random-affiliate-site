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
echo $showcontent->showPage('Members Area Randomizer Page');
$allpositions = new Randomizer();
$positions = $allpositions->getAllForOneUser($username);
/* for generating the walletids to pay, if payment hasn't been made yet,
or for getting the walletids paid from the transactions table. */
$bitcoin = new Bitcoin();
?>
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
		
			<h1 class="ja-bottompadding">Your Randomizer</h1>
				
			<?php
			if (empty($positions)) {

				# means the person hasn't paid someone yet. Show pay buttons (either one or two).
                echo "<div class=\"ja-bottompadding ja-topadding\">You have no positions in the randomizer yet. 
                Please pay BOTH your sponsor and a random member below. 
				If you already have, please wait for BOTH recipients to verify that they have received a payment from you,
				then you will see your positions listed here.</p><p>If you have ALREADY paid them BOTH, and have
				been waiting a long time for the recipients to validate, please contact us with PROOF of
				both your payments, so we can approve addition of your position in the randomizer, as well as your ads.</div>";

				# Show bitcoin wallet IDs for BOTH sponsor and the random payee.
				echo $bitcoin->showBitCoinWalletIds($username, $settings['paysponsor'], $settings['payrandom']);
			
			} else {
                
                # check to see if the person owes for any other positions still.
				echo $bitcoin->showBitCoinWalletIds($username, $settings['paysponsor'], $settings['payrandom']);
                
				# person has at least one randomizer position they paid for (sponsor and random) that has been added.
                # show those positions.
                
                ### ALONG WITH PAY BUTTONS IF THEY OWE FOR ANY OTHERS IN THE TRANSACTIONS TABLE!!

				?>
				<div class="table-responsive ja-toppadding">
					<table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
						<thead>
						<tr>
							<th class="text-center small">Position ID #</th>
							<th class="text-center small">Your&nbsp;Wallet</th>
                            <th class="text-center small">Amount</th>
                            <th class="text-center small">Earning&nbsp;Type</th>
                            <th class="text-center small">Date&nbsp;Paid</th>
                            <th class="text-center small" style="background:lightyellow;">Verify&nbsp;You&nbsp;Were&nbsp;Paid</th>
                            <th class="text-center small">Save</th>
						</tr>
						</thead>
						<tbody>

						<?php
						foreach ($positions as $position) {

                              $id = $position['id'];
                              $walletid = $position['walletid'];
                            
                              # each walletid could have been paid/owed multiple times in transactions table. 
                              $transactions = $bitcoin->getPaymentsReceived($username,$walletid);
                            
                              foreach ($transactions as $transaction) {

                                $transactionid = $transaction['id'];
                                $payor = $transaction['username'];
                                $amount = $transaction['amount'];
                                $recipienttype = $transaction['recipienttype'];
                                $recipientapproved = $transaction['recipientapproved'];
                                $datepaid = $transaction['datepaid'];
                                if($datepaid === '') { $datepaid = 'Still Unpaid'; } else { $datepaid = date('Y-m-d'); }

                                ?>
                                <tr>
                                    <form action="/randomizer/<?php echo $transactionid ?>" method="post" accept-charset="utf-8" class="form" role="form">
                                    <td class="small"><?php echo $id; ?></td>
                                    <td class="small"><?php echo $transactionid; ?></td>
                                    <td class="small"><?php echo $walletid; ?></td>
                                    <td class="small"><?php echo $amount; ?></td>
                                    <td class="small"><?php echo $recipienttype; ?></td>
                                    <td class="small"><?php echo $datepaid; ?></td>
                                    <td class="small"><?php echo $datepaid; ?></td>
                                    <td>
                                        <select name="recipientapproved" class="form-control input-md">
                                            <option value="1"<?php if ($recipientapproved === 1) { echo " selected"; } ?>>Yes</option>
                                            <option value="0"<?php if ($recipientapproved !== 1) { echo " selected"; } ?>>No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" name="_method" value="PATCH">
                                        <button class="btn btn-sm btn-primary" type="submit" name="savead">SAVE</button>
								    </td>
                                </tr>
                                <?php
                              }
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