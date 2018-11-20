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
$allrandomizers = new Randomizer();
$randomizers = $allrandomizers->getAllRandomizers();
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">

			<h1 class="ja-bottompadding">Add New Randomizer Position</h1>
			
			<form action="/admin/randomizer" method="post" accept-charset="utf-8" class="form" role="form">

                <label for="username" class="ja-toppadding">Username:</label>
                <input type="text" name="username" value="" class="form-control input-lg" placeholder="Username" required>

                <label for="walletid" class="ja-toppadding">Bitcoin Wallet ID:</label>
                <input type="text" name="walletid" value="" class="form-control input-lg" placeholder="Bitcoin Wallet ID" required>

                <label for="referid" class="ja-toppadding">Sponsor:</label>
                <input type="text" name="referid" value="" class="form-control input-lg" placeholder="Sponsor" required>

                <div class="ja-bottompadding"></div>

                <button class="btn btn-lg btn-primary ja-toppadding ja-bottompadding" type="submit" name="addrandomizer">Add</button>

			</form>				

			<div class="ja-bottompadding"></div>

            <h1 class="ja-bottompadding">All Randomizer Positions</h1>

            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
                    <thead>
                    <tr>
                        <th class="text-center small">#</th>
                        <th class="text-center small">Username</th>
                        <th class="text-center small">Wallet ID</th>
                        <th class="text-center small">Paid from Sponsoring</th>
                        <th class="text-center small">Paid from Randomizer</th>
                        <th class="text-center small">Owed Payments</th>
                        <th class="text-center small">Edit</th>
                        <th class="text-center small">Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($randomizers as $randomizer) {

                        # get user's earnings from sponsoring and from random payments. Also find out how much other members own them currently.

                        # method in Randomizer class for this.

                        ?>
                        <tr>
                            <form action="/admin/randomizer/<?php echo $randomizer['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
                            <td class="small">
                                <?php echo $randomizer['id']; ?>
                            </td>
                            <td>
                                <input type="text" name="username" value="<?php echo $randomizer['username']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Username" required>
                            </td>
                            <td>
                                <input type="text" name="walletid" value="<?php echo $randomizer['walletid']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Wallet ID" required>
                            </td>
                            <td>
                                <?php echo $paidassponsor ?>
                            </td>
                            <td>
                                <?php echo $paidfromrandomizer ?>
                            </td>
                            <td>
                                <?php echo $owedpayments ?>
                            </td>

                            <td>
                                <input type="hidden" name="_method" value="PATCH">
                                <button class="btn btn-sm btn-primary" type="submit" name="saverandomizer">SAVE</button>
                            </td>
                            </form>
                            <td>
                                <form action="/admin/randomizer/<?php echo $randomizer['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="btn btn-sm btn-primary" type="submit" name="deleterandomizer">DELETE</button>
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