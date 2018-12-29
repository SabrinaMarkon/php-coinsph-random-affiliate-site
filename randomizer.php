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

# get all user's randomizer positions.
$allpositions = new Randomizer();
$positions = $allpositions->getAllRandomizersForOneUser($username);

$transactions = new Money();

# get all the transactions the user still owes.
$owed = $transactions->getUserTransactions($username,'owes');

# get all the transactions the user will get paid or has already been paid.
$gets = $transactions->getUserTransactions($username,'gets');

# get all the transactions that the user owed but already paid.
$paid = $transactions->getUserTransactions($username,'paid');

/* for generating the walletids & coinphpids to pay, if payment hasn't been made yet,
or for getting the walletids & coinphpids paid from the transactions table. */
$bitcoin = new Bitcoin();
?>
<div class="container">
		
			<h1 class="ja-bottompadding">Your Randomizer</h1>

			<?php
			if (empty($positions)) {

                # user has transactions to pay.

                echo "<div class=\"ja-bottompadding ja-topadding my-5\">You currently have no positions in the randomizer. 
                Please pay BOTH your sponsor and a random member below. If you already have, please wait for 
                BOTH recipients to verify that they have received a payment from you.
                Afterwards, your randomizer position will be awarded to you and shown here.</p><p>If you have ALREADY paid them BOTH, and have
                been waiting a long time for the recipients to validate, please contact us with PROOF of
                both your payments, so we can approve release of your ads, as well as your position in the randomizer.</div>";

                # Show bitcoin wallet IDs for BOTH sponsor and the random payee.
                $bitcoin = new Bitcoin();
                $showbitcoin = $bitcoin->showBitCoinWalletIds($username,$settings);
                if ($showbitcoin) {

                    echo "<div class=\"ja-yellowbg ja-bitcoinbox\">" . $showbitcoin . "</div>";
                }
			
			} else {
                
                # check to see if the person owes for any other positions still.
                $showbitcoin = $bitcoin->showBitCoinWalletIds($username,$settings);
                if ($showbitcoin) {

                    echo "<div class=\"ja-yellowbg ja-bitcoinbox\">" . $showbitcoin . "</div>";
                }

                echo "<div class=\"ja-bottompadding mb-5\"></div>";
                
				# person has at least one randomizer position they paid for (sponsor and random) that has been added.
                # show their referral url AND those positions.

				?>
                <form class="form-group form-inline my-5" disabled>
                <label for="referralurl" class="control-label">Your Referral URL:&nbsp;</label>
                <input type="text" id="referralurl" class="form-control mr-2 w-50" value="<?php echo $domain ?>/r/<?php echo $username ?>" readonly>
                <button class="form-control mr-2" onClick="copyToClipboard(document.getElementById('referralurl').value);return false;">COPY</button>
                </form>

				<div class="table-responsive ja-toppadding">
					<table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
						<thead>
						<tr>
                            <th class="text-center small">Record ID #</th>
							<th class="text-center small">Position ID #</th>
							<th class="text-center small">Bitcoin&nbsp;Wallet</th>
                            <th class="text-center small">Coins.ph&nbsp;Peso&nbsp;Wallet</th>
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
                              $coinsphpid = $position['coinsphpid'];
                            
                              # each walletid/coinsphpid could have been paid/owed multiple times in transactions table. 
                              $transactions = $bitcoin->getPaymentsReceived($username,$walletid,$coinsphpid);
                            
                              foreach ($transactions as $transaction) {

                                $transactionid = $transaction['id'];
                                $payor = $transaction['username']; // this is NOT the user logged in. This is the user that pays the user logged in.
                                $amount = $transaction['amount'];
                                $recipient = $transaction['recipient']; // THIS is the user logged in who has received payment from $payor.
                                $recipientwalletid = $transaction['recipientwalletid'];
                                $recipientcoinsphpid = $transaction['recipientcoinsphpid'];
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

                                    # get the walletid & coinsphpid of the user who paid this one.
                                    $payorswallets = $bitcoin->getUsersWalletIDs($username);
                                    if ($payorswallets) {
                        
                                        $payorswallet = $payorswallets['walletid'];
                                        $payorscoinsphp = $payorswallets['coinsphpid'];
                                    }

                                    # show the confirmation button so the user can click it when they receive payment.
                                    $userverifiedpayment = '<form action="/randomizer" method="post" accept-charset="utf-8" class="form" role="form">
                                    <input type="hidden" name="_method" value="PATCH">
                                    <input type="hidden" name="id" value="' . $transactionid . '">
                                    <input type="hidden" name="userwhopaid" value="' . $payor . '">
                                    <input type="hidden" name="userwhopaidwalletid" value="' . $payorswallet . '">
                                    <input type="hidden" name="userwhopaidcoinsphpid" value="' . $payorscoinsphp . '">
                                    <button class="btn btn-sm ja-yellowbg" type="submit" name="confirmpaid">CONFIRM!</button>';
                                }

                                ?>
                                <tr>
                                    <td class="small"><?php echo $transactionid ?></td>
                                    <td class="small"><?php echo $id; ?></td>
                                    <td class="small"><?php echo $walletid; ?></td>
                                    <td class="small"><?php echo $coinsphpid; ?></td>
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