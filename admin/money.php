<?php
require "control.php";
if (isset($showupdate))
{
    echo $showupdate;
}
$alltransactions = new Money();
$transactions = $alltransactions->getAllTransactions();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h1 class="ja-bottompadding">Transaction Records</h1>

            <div class="table-responsive">
                <table class="table table-condensed table-bordered text-center">
                    <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Payer</th>
                        <th class="text-center">Payee</th>
                        <th class="text-center">Payee Approved</th>
                        <th class="text-center">Payment Type</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Date Paid</th>
                        <th class="text-center">Transaction</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($transactions as $transaction) {

                    $date = new DateTime($transaction['datepaid']);
                    $datepaid = $date->format('Y-m-d');
                    ?>
                    <tr>
                        <form action="/admin/money/<?php echo $transaction['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
                        <td><?php echo $transaction['id']; ?>
                        </td>
                        <td>
                            <label class="sr-only" for="username">Payer:</label>
                            <input type="text" name="username" value="<?php echo $transaction['username']; ?>" class="form-control input-md" size="40" placeholder="Payer">
                        </td>
                        <td>
                            <label class="sr-only" for="recipient">Payee:</label>
                            <input type="text" name="recipient" value="<?php echo $transaction['recipient']; ?>" class="form-control input-md" size="40" placeholder="Payee">
                        </td>
                        <td>
                            <label class="sr-only" for="recipientapproved">Payee Approved:</label>
                            <select name="recipientapproved" value="<?php echo $transaction['recipientapproved']; ?>" class="form-control input-md">
                                <option value="0" <?php if ($transaction['recipientapproved'] !== 1) { echo "selected"; } ?>>No</option>
                                <option value="1" <?php if ($transaction['recipientapproved'] === 1) { echo "selected"; } ?>>Yes</option>
                            </select>
                        </td>
                        <td>
                            <label class="sr-only" for="recipienttype">Payment Type:</label>
                            <select name="recipienttype" value="<?php echo $transaction['recipienttype']; ?>" class="form-control input-md">
                                <option value="random" <?php if ($transaction['recipienttype'] !== "sponsor") { echo "selected"; } ?>>Sponsor</option>
                                <option value="sponsor" <?php if ($transaction['recipienttype'] === "sponsor") { echo "selected"; } ?>>Random</option>
                            </select>
                        </td>
                        <td>
                            <label class="sr-only" for="amount">Amount:</label>
                            <input type="text" name="amount" value="<?php echo $transaction['amount']; ?>" class="form-control input-md" placeholder="Amount">
                        </td>
                        <td>
                            <label class="sr-only" for="datepaid">Date Paid:</label>
                            <input type="text" name="datepaid" value="<?php echo $datepaid ?>" class="form-control input-md" size="50" placeholder="Date Paid">
                        </td>
                        <td>
                            <label class="sr-only" for="transaction">Transaction:</label>
                            <input type="text" name="transaction" value="<?php echo $transaction['transaction']; ?>" class="form-control input-md" size="60" placeholder="Transaction">
                        </td>
                        <td>
                            <input type="hidden" name="_method" value="PATCH">
                            <button class="btn btn-md btn-primary" type="submit" name="savetransaction">SAVE</button>
                        </td>
                        </form>
                        <td>
                            <form action="/admin/money/<?php echo $transaction['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-md btn-primary" type="submit" name="deletetransaction">DELETE</button>
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