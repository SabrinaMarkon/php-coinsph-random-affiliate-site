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
$sitesettings = new Settings();
$settings = $sitesettings->getSettings();
foreach ($settings as $key => $value)
{
    $$key = $value;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

            <h1 class="ja-bottompadding">Site Settings</h1>

            <form action="/admin/settings" method="post" accept-charset="utf-8" class="form" role="form">

                <label for="adminuser">Admin Username:</label>
                <input type="text" name="adminuser" value="<?php echo $adminuser ?>" class="form-control input-lg" placeholder="Admin Username" required>

                <label for="adminpass" class="ja-toppadding">Admin Password:</label>
                <input type="password" name="adminpass" id="adminpass" value="<?php echo $adminpass ?>" class="form-control input-lg" placeholder="Admin Password" required>

                <label for="confirm_adminpass" class="ja-toppadding">Confirm Password:</label>
                <input type="password" name="confirm_adminpass" id="confirm_adminpass" value="<?php echo $adminpass ?>" class="form-control input-lg" placeholder="Confirm Password" required>

                <label for="adminemail" class="ja-toppadding">Admin Name:</label>
                <input type="text" name="adminname" value="<?php echo $adminname ?>" class="form-control input-lg" placeholder="Admin Name" required>

                <label for="adminemail" class="ja-toppadding">Your Admin Email:</label>
                <input type="text" name="adminemail" value="<?php echo $adminemail ?>" class="form-control input-lg" placeholder="Admin Email" required>

                <label for="sitename" class="ja-toppadding">Your Website Name:</label>
                <input type="text" name="sitename" value="<?php echo $sitename ?>" class="form-control input-lg" placeholder="Website Name" required>

                <label for="domain" class="ja-toppadding">Your Domain:</label>
                <input type="url" name="domain" value="<?php echo $domain ?>" class="form-control input-lg" placeholder="Website URL (start with http://)" required>

                <label for="admindefaultwalletid" class="ja-toppadding">Admin Default Wallet ID:</label>
                <input type="hidden" name="oldadmindefaultwalletid" value="<?php echo $admindefaultwalletid ?>">
                <input type="text" name="admindefaultwalletid" value="<?php echo $admindefaultwalletid ?>" class="form-control input-lg" placeholder="Admin Default Wallet ID">

                <label for="admindefaultcoinsphpid" class="ja-toppadding">Admin Default Coins.ph Peso ID:</label>
                <input type="hidden" name="oldadmindefaultcoinsphpid" value="<?php echo $admindefaultcoinsphpid ?>">
                <input type="text" name="admindefaultcoinsphpid" value="<?php echo $admindefaultcoinsphpid ?>" class="form-control input-lg" placeholder="Admin Default Coins.ph Peso ID">

                <div>
                    <label for="adminratio" class="ja-toppadding">Admin Ratio for Randomizer:</label>
                    <br/>Show an Admin Wallet ID Every&nbsp;
                    <select name="adminratio" class="form-control smallselect">
                        <?php
                        for ($i = 0; $i <= 50; $i++) {
                            ?>
                            <option value="<?php echo $i ?>" <?php if ($i === intval($adminratio)) { echo "selected"; } ?>><?php echo $i ?></option>
                            <?php
                        }
                        ?>
                    </select>&nbsp;Times (0 for never, 1 for every single time)
                </div>
                
                <label for="paysponsor" class="ja-toppadding">Users Should Pay Their Sponsor:</label>
                $&nbsp;<input type="text" name="paysponsor" value="<?php echo $paysponsor ?>" class="form-control smallselect" required>

                <div></div>

                <label for="payrandom" class="ja-toppadding">Users Should Pay a Random Member:</label>
                $&nbsp;<input type="text" name="payrandom" value="<?php echo $payrandom ?>" class="form-control smallselect" required>

                <div>
                    <label for="giveextratoadmin" class="ja-toppadding">If Member is Deleted, Give Randomizer Positions to Admin:</label>
                    <select name="giveextratoadmin" class="form-control smallselect">
                        <option value="1" <?php if (intval($giveextratoadmin) === 1) { echo "selected"; } ?>>Yes</option>
                        <option value="0" <?php if (intval($giveextratoadmin) !== 1) { echo "selected"; } ?>>No</option>
                    </select>
                </div>

                <div>
                    <label for="adminautoapprove" class="ja-toppadding">Auto-approve Ads:</label>
                    <select name="adminautoapprove" class="form-control smallselect">
                        <option value="1" <?php if (intval($adminautoapprove) === 1) { echo "selected"; } ?>>Yes</option>
                        <option value="0" <?php if (intval($adminautoapprove) !== 1) { echo "selected"; } ?>>No</option>
                    </select>
                </div>

                <label for="adclickstogetad" class="ja-toppadding">Click How Many Ads to get another Free Ad:</label>
                $&nbsp;<input type="number" min="1" step="1" name="adclickstogetad" value="<?php echo $adclickstogetad ?>" class="form-control smallselect" required>

                <div class="ja-bottompadding"></div>

                <button class="btn btn-lg btn-primary" type="submit" name="savesettings">Save Settings</button>

            </form>

            <div class="ja-bottompadding"></div>

        </div>
    </div>
</div>