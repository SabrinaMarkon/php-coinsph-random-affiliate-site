<?php
require "control.php";
if (isset($showupdate))
{
    echo $showupdate;
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

                <label class="sr-only" for="adminuser">Your Website Name:</label>
                <input type="text" name="adminuser" value="<?php echo $adminuser ?>" class="form-control input-lg" placeholder="Admin Username" required>

                <label class="sr-only" for="adminpass">Admin Password</label>
                <input type="password" name="adminpass" value="<?php echo $adminpass ?>" class="form-control input-lg" placeholder="Admin Password" required>

                <label class="sr-only" for="confirm_adminpass">Confirm Password</label>
                <input type="password" name="confirm_adminpass" value="<?php echo $adminpass ?>" class="form-control input-lg" placeholder="Confirm Password" required>

                <label class="sr-only" for="adminemail">Admin Name</label>
                <input type="text" name="adminname" value="<?php echo $adminname ?>" class="form-control input-lg" placeholder="Admin Name" required>

                <label class="sr-only" for="adminemail">Your Admin Email</label>
                <input type="text" name="email" value="<?php echo $adminemail ?>" class="form-control input-lg" placeholder="Admin Email" required>

                <label class="sr-only" for="sitename">Your Website Name:</label>
                <input type="text" name="sitename" value="<?php echo $sitename ?>" class="form-control input-lg" placeholder="Website Name" required>

                <label class="sr-only" for="domain">Your Domain:</label>
                <input type="url" name="domain" value="<?php echo $domain ?>" class="form-control input-lg" placeholder="Website URL (start with http://)" required>

                <div>
                    <label class="sr-only" for="adminratio">Admin Ratio for Randomizer:</label>
                    Show Admin Payment Button Every&nbsp;
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
                
                <div>
                    <label class="sr-only" for="adminautoapprove">Auto-approve Ads</label>
                    Auto-approve Ads:&nbsp;<select name="adminautoapprove" class="form-control smallselect">
                        <option value="1" <?php if (intval($adminautoapprove) === 1) { echo "selected"; } ?>>Yes</option>
                        <option value="0" <?php if (intval($adminautoapprove) !== 1) { echo "selected"; } ?>>No</option>
                    </select>
                </div>

                <div class="ja-bottompadding"></div>

                <button class="btn btn-lg btn-primary" type="submit" name="savesettings">Save Settings</button>

            </form>

            <div class="ja-bottompadding"></div>

        </div>
    </div>
</div>