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
$positions = $allpositions->getAllRandomizersForOneUser($username);
/* for generating the walletids to pay, if payment hasn't been made yet,
or for getting the walletids paid from the transactions table. */
$bitcoin = new Bitcoin();
?>
<div class="container">
		
			<h1 class="ja-bottompadding">Your Randomizer</h1>

            <form class="form-group form-inline my-5" disabled>
            <label for="referralurl" class="control-label">Your Referral URL:&nbsp;</label>
            <input type="text" id="referralurl" class="form-control mr-2 w-50" value="<?php echo $domain ?>/r/<?php echo $username ?>">
            <button class="form-control mr-2" onClick="copyToClipboard(document.getElementById('referralurl').value);return false;">COPY</button>
			</form>

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
				echo $bitcoin->showBitCoinWalletIds($username,$settings);
			
			} else {
                
                # check to see if the person owes for any other positions still.
				echo $bitcoin->showBitCoinWalletIds($username,$settings);
                
				# person has at least one randomizer position they paid for (sponsor and random) that has been added.
                # show those positions.

				?>
				<div class="table-responsive ja-toppadding">
					<table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
						<thead>
						<tr>
                            <th class="text-center small">Record ID #</th>
							<th class="text-center small">Position ID #</th>
							<th class="text-center small">Your&nbsp;Wallet</th>
                            <th class="text-center small">Amount</th>
                            <th class="text-center small">Earning&nbsp;Type</th>
                            <th class="text-center small">Date&nbsp;Paid&nbsp;to&nbsp;You</th>
                            <th class="text-center small ja-yellowbg"><strong>Were&nbsp;You&nbsp;Paid?</strong></th>
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
                                $payor = $transaction['username']; // this is NOT the user logged in. This is the user that pays the user logged in.
                                $amount = $transaction['amount'];
                                $recipient = $transaction['recipient']; // THIS is the user logged in who has received payment from $payor.
                                $recipientwalletid = $transaction['recipientwalletid'];
                                $recipienttype = $transaction['recipienttype'];
                                $recipientapproved = $transaction['recipientapproved'];
                                $datepaid = $transaction['datepaid'];
                                if($datepaid === '') {
                                    
                                    $datepaid = 'Still Unpaid';
                                } else {

                                    $datepaid = date('Y-m-d'); 
                                }
                                if ($recipientapproved === "1") {

                                    # the user has already received this payment.
                                    $userverifiedpayment = "You Were Paid";
                                } else {

                                    # get the walletid of the user who paid this one.
                                    $payorswallet = $bitcoin->getUsersWalletID($payor);

                                    # show the confirmation button so the user can click it when they receive payment.
                                    $userverifiedpayment = '<form action="/randomizer" method="post" accept-charset="utf-8" class="form" role="form">
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="hidden" name="id" value="' . $transactionid . '">
                                    <input type="hidden" name="userwhopaid" value="' . $payor . '">
                                    <input type="hidden" name="userwhopaidwalletid" value="' . $payorswallet . '">
                                    <button class="btn btn-sm ja-yellowbg" type="submit" name="confirmpaid">CONFIRM!</button>';
                                }

                                ?>
                                <tr>
                                    <td class="small"><?php echo $transactionid ?></td>
                                    <td class="small"><?php echo $id; ?></td>
                                    <td class="small"><?php echo $walletid; ?></td>
                                    <td class="small"><?php echo $amount; ?></td>
                                    <td class="small"><?php echo $recipienttype; ?></td>
                                    <td class="small"><?php echo $datepaid; ?></td>
                                    <td class="small">
                                        <?php echo $userverifiedpayment; ?>
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