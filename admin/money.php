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
$alltransactions = new Money();
$transactions = $alltransactions->getAllTransactions();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">		

			<h1 class="ja-bottompadding">Create Invoice (one user owes another)</h1>
			
			<form action="/admin/money" method="post" accept-charset="utf-8" class="form" role="form">

                <label for="username" class="ja-toppadding">Username (Payor):</label>
                <input type="text" name="username" value="" class="form-control input-lg" placeholder="Username" required>

                <label for="referid" class="ja-toppadding">Recipient (Payee):</label> 
                <input type="text" name="recipient" value="" class="form-control input-lg" placeholder="Recipient" required>

                <label for="recipientwalletid" class="ja-toppadding">Recipient Bitcoin Wallet ID:</label>
                <input type="text" name="recipientwalletid" value="" class="form-control input-lg" placeholder="Recipient Bitcoin Wallet ID">

                <label for="recipientcoinsphpid" class="ja-toppadding">Recipient Coins.ph Peso Wallet ID:</label>
                <input type="text" name="recipientcoinsphpid" value="" class="form-control input-lg" placeholder="Recipient Coins.ph Peso Wallet ID">
                
                <label for="recipienttype" class="ja-toppadding">Recipient Type:</label>
                <select name="recipienttype" class="form-control input-lg">
                    <option value="sponsor">Sponsor</option>
                    <option value="random">Random</option>
                </select>
                				
                <label for="amount" class="ja-toppadding">Amount Owing:</label>
                <input type="text" name="amount" value="" class="form-control input-lg" placeholder="Amount Owing" required>

                <label for="transaction" class="ja-toppadding">Transaction:</label>
                <input type="text" name="transaction" value="Bitcoin" class="form-control input-lg" placeholder="Transaction" required>

                <div class="ja-bottompadding"></div>

                <button class="btn btn-lg btn-primary ja-toppadding ja-bottompadding" type="submit" name="addtransaction">Add Transaction</button>

			</form>				

			<div class="ja-bottompadding"></div>
            
            <h1 class="ja-bottompadding">Transaction Records</h1>

            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
                    <thead>
                    <tr>
                        <th class="text-center small">#</th>
                        <th class="text-center small">Payer</th>
                        <th class="text-center small">Payee</th>
                        <th class="text-center small">Verified&nbsp;by&nbsp;Payee</th>
                        <th class="text-center small">Payment&nbsp;Type</th>
                        <th class="text-center small">Amount</th>
                        <th class="text-center small">Date&nbsp;Paid</th>
                        <th class="text-center small">Transaction</th>
                        <th class="text-center small">Edit</th>
                        <th class="text-center small">Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($transactions as $transaction) {

                        $datepaid = $transaction['datepaid'];
                        if($datepaid === '') { $datepaid = 'Not Yet'; } else { $datepaid = date('Y-m-d'); }
                    ?>
                        <tr>
                            <form action="/admin/money/<?php echo $transaction['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
                            <td><?php echo $transaction['id']; ?>
                            </td>
                            <td>
                                <label class="sr-only" for="username">Payer:</label>
                                <input type="text" name="username" value="<?php echo $transaction['username']; ?>" class="form-control input-sm widetableinput" placeholder="Payer" required>
                            </td>
                            <td>
                                <label class="sr-only" for="recipient">Payee:</label>
                                <input type="text" name="recipient" value="<?php echo $transaction['recipient']; ?>" class="form-control input-sm widetableinput" placeholder="Payee" required>
                            </td>
                            <td>
                                <label class="sr-only" for="recipientapproved">Verified by Payee:</label>
                                <input type="hidden" name="oldrecipientapproved" value="<?php echo $transaction['recipientapproved']; ?>">
                                <select name="recipientapproved" class="form-control widetableselect<?php if ($transaction['recipientapproved'] !== "1") { echo ' ja-yellowbg'; } ?>">
                                    <option value="0" <?php if ($transaction['recipientapproved'] !== "1") { echo "selected"; } ?>>No</option>
                                    <option value="1" <?php if ($transaction['recipientapproved'] === "1") { echo "selected"; } ?>>Yes</option>
                                </select>
                            </td>
                            <td>
                                <label class="sr-only" for="recipienttype">Payment Type:</label>
                                <select name="recipienttype" class="form-control widetableselect">
                                    <option value="random" <?php if ($transaction['recipienttype'] !== "sponsor") { echo "selected"; } ?>>Random</option>
                                    <option value="sponsor" <?php if ($transaction['recipienttype'] === "sponsor") { echo "selected"; } ?>>Sponsor</option>
                                </select>
                            </td>
                            <td>
                                <label class="sr-only" for="amount">Amount:</label>
                                <input type="text" name="amount" value="<?php echo $transaction['amount']; ?>" class="form-control input-sm widetableinput" placeholder="Amount" required>
                            </td>
                            <td>
                                <label class="sr-only" for="datepaid">Date Paid:</label>
                                <input type="text" name="datepaid" value="<?php echo $datepaid ?>" class="form-control input-sm widetableinput" size="50" placeholder="Date Paid" required>
                            </td>
                            <td>
                                <label class="sr-only" for="transaction">Transaction:</label>
                                <input type="text" name="transaction" value="<?php echo $transaction['transaction']; ?>" class="form-control input-sm widetableinput" size="60" placeholder="Transaction" required>
                            </td>
                            <td>
                                <input type="hidden" name="_method" value="PATCH">
                                <button class="btn btn-sm btn-primary" type="submit" name="savetransaction">SAVE</button>
                            </td>
                            </form>
                            <td>
                                <form action="/admin/money/<?php echo $transaction['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-sm btn-primary" type="submit" name="deletetransaction">DELETE</button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                    </tbody>
                </table>
            </div>

            <div class="ja-bottompadding"></div>

        </div>
    </div>
</div>

