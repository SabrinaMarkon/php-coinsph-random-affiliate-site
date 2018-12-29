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
                <input type="text" name="walletid" value="" class="form-control input-lg" placeholder="Bitcoin Wallet ID">

                <label for="coinsphpid" class="ja-toppadding">Coins.ph Peso Wallet ID:</label>
                <input type="text" name="coinsphpid" value="" class="form-control input-lg" placeholder="Coins.ph Peso Wallet ID">

                <div class="ja-bottompadding"></div>

                <button class="btn btn-lg btn-primary ja-toppadding ja-bottompadding" type="submit" name="addrandomizer">Add</button>

			</form>				

			<div class="ja-bottompadding"></div>

            <h1 class="ja-bottompadding">All Randomizer Positions</h1>

            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
                    <thead>
                    <tr>
                        <th class="text-center small">Position ID#</th>
                        <th class="text-center small">Username</th>
                        <th class="text-center small">Bitcoin</th>
                        <th class="text-center small">Coins.ph</th>
                        <th class="text-center small">Was Paid as Sponsor</th>
                        <th class="text-center small">Is Owed as Sponsor</th>
                        <th class="text-center small">Was Paid as Random Payee</th>
                        <th class="text-center small">Is Owed as Random Payee</th>
                        <th class="text-center small">Edit</th>
                        <th class="text-center small">Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($randomizers as $randomizer) {

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
                                <input type="text" name="walletid" value="<?php echo $randomizer['walletid']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Bitcoin">
                            </td>
                            <td>
                                <input type="text" name="coinsphpid" value="<?php echo $randomizer['coinsphpid']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Coins.ph">
                            </td>
                            <td>
                                <?php echo $randomizer['sponsorpaid']; ?>
                            </td>
                            <td>
                                <?php echo $randomizer['sponsorowed']; ?>
                            </td>
                            <td>
                                <?php echo $randomizer['randompaid']; ?>
                            </td>
                            <td>
                                <?php echo $randomizer['randomowed']; ?>
                            </td>
                            <td>
                                <input type="hidden" name="_method" value="PATCH">
                                <button class="btn btn-sm btn-primary" type="submit" name="saverandomizer">SAVE</button>
                            </td>
                            </form>
                            <td>
                                <form action="/admin/randomizer/<?php echo $randomizer['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="username" value="<?php echo $randomizer['username']; ?>">
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