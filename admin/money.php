<?php
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
                        <th class="text-center">UserID</th>
                        <th class="text-center">Transaction</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Date Paid</th>
                        <th class="text-center">Amount</th>
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
                            <label class="sr-only" for="username">Username:</label>
                            <input type="text" name="username" value="<?php echo $transaction['username']; ?>" class="form-control input-md" size="40" placeholder="Username">
                        </td>
                        <td>
                            <label class="sr-only" for="transaction">Transaction ID:</label>
                            <input type="text" name="transaction" value="<?php echo $transaction['transaction']; ?>" class="form-control input-md" size="60" placeholder="Transaction ID">
                        </td>
                        <td>
                            <label class="sr-only" for="description">Description:</label>
                            <input type="text" name="description" value="<?php echo $transaction['description']; ?>" class="form-control input-md" size="60" placeholder="Description">
                        </td>
                        <td>
                            <label class="sr-only" for="datepaid">Date Paid:</label>
                            <input type="text" name="datepaid" value="<?php echo $datepaid ?>" class="form-control input-md" size="50" placeholder="Date Paid">
                        </td>
                        <td>
                            <label class="sr-only" for="amount">Amount:</label>
                            <input type="text" name="amount" value="<?php echo $transaction['amount']; ?>" class="form-control input-md" placeholder="Amount">
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