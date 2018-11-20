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
$alladminwallets = new AdminWallet();
$adminwallets = $alladminwallets->getAllAdminWallets();
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12">

			<h1 class="ja-bottompadding">Add New Admin Wallet ID</h1>
			
			<form action="/admin/adminwallets" method="post" accept-charset="utf-8" class="form" role="form">
	                
                <label for="name" class="ja-toppadding">Wallet Name:</label>
                <input type="text" name="name" value="" class="form-control input-lg" placeholder="Wallet Name" required>

                <label for="walletid" class="ja-toppadding">Wallet ID:</label>
                <input type="text" name="walletid" value="" class="form-control input-lg" placeholder="Wallet ID" required>

                <div class="ja-bottompadding"></div>

                <button class="btn btn-lg btn-primary ja-toppadding ja-bottompadding" type="submit" name="addadminwallet">Add Wallet ID</button>

			</form>				

			<div class="ja-bottompadding"></div>

            <h1 class="ja-bottompadding">Admin Wallets</h1>

            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
                    <thead>
                    <tr>
                        <th class="text-center small">#</th>
                        <th class="text-center small">Wallet Name</th>
                        <th class="text-center small">Wallet ID</th>
                        <th class="text-center small">Edit</th>
                        <th class="text-center small">Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($adminwallets as $adminwallet) {

                    ?>
                        <tr>
                            <form action="/admin/adminwallets/<?php echo $adminwallet['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
                            <td class="small">
                                <?php echo $adminwallet['id']; ?>
                            </td>
                            <td>
                                <input type="text" name="name" value="<?php echo $adminwallet['name']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Wallet Name" required>
                            </td>
                            <td>
                                <input type="text" name="walletid" value="<?php echo $adminwallet['walletid']; ?>" class="form-control input-sm widetableinput" size="40" placeholder="Wallet ID" required>
                            </td>
                            <td>
                                <input type="hidden" name="_method" value="PATCH">
                                <button class="btn btn-sm btn-primary" type="submit" name="saveadminwallet">SAVE</button>
                            </td>
                            </form>
                            <td>
                                <form action="/admin/adminwallets/<?php echo $adminwallet['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="name" value="<?php echo $adminwallet['name']; ?>">
                                    <button class="btn btn-sm btn-primary" type="submit" name="deleteadminwallet">DELETE</button>
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