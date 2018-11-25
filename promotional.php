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
echo $showcontent->showPage('Members Area Promotion Page');

# get all the promotional ads provided by the admin.
$allpromotionals = new Promotional();
$promotionals = $allpromotionals->getAllPromotionals();
?>

<div class="container">

<h1 class="ja-bottompadding my-5">Promotional Material</h1>

<?php
foreach ($promotionals as $promotional) {

    if ($promotional['type'] == "banner") {

        ?>
        <div class="table-responsive border border-dark mb-5">
            
            <table class="table table-condensed table-bordered table-striped text-center">
                <tbody>
                    <tr class="table-active">
                        <td>
                            <h4 class="text-body">BANNER: <?php echo $promotional['name']; ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="ja-promotionalimg">
                                <a href="<?php echo $domain ?>/r/<?php echo $username ?>" target="_blank"><img class="ja-promotionalimg" src="<?php echo $promotional['promotionalimage']; ?>"></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="referralurl">Referral URL:</label>
                            <input type="text" name="referralurl" value="<?php echo $domain ?>/r/<?php echo $username ?>" class="form-control input-lg" size="40" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="promotionalimage">Banner Image URL:</label>
                            <input type="text" name="promotionalimage" value="<?php echo $promotional['promotionalimage']; ?>" class="form-control input-lg" size="40" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="bannercode" class="control-label">Banner Code:&nbsp;</label>
                            <textarea id="bannercode" class="form-control ja-promotionaltext" rows="2" readonly><a href="<?php echo $domain ?>/r/<?php echo $username ?>" target="_blank"><img src="<?php echo $promotional['promotionalimage']; ?>"></a></textarea>
                            <button class="form-control btn btn-primary mt-2 w-25" onClick="copyToClipboard(document.getElementById('bannercode').value);return false;">COPY</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
    if ($promotional['type'] == "email") {

        ?>
        <div class="table-responsive border border-dark mb-5">

            <table class="table table-condensed table-bordered table-striped text-center">
                <tbody>
                    <tr class="table-active">
                        <td>
                            <h4 class="text-body">EMAIL: <?php echo $promotional['name']; ?></h4>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div style="height: 300px; padding: 4px; overflow:auto; background: #ffffff;"><?php echo $promotional['promotionaladbody']; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="referralurl">Referral URL:</label>
                            <input type="text" name="referralurl" value="<?php echo $domain ?>/r/<?php echo $username ?>" class="form-control input-lg" size="40" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="promotionalsubject">Subject:</label>
                            <input type="text" name="promotionalsubject" value="<?php echo $promotional['promotionalsubject']; ?>" class="form-control input-lg" size="40" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="emailcode">Message Text or HTML Code:</label>
                            <textarea name="emailcode" class="form-control ja-promotionaltext" id="emailcode" rows="10" readonly><?php echo $promotional['promotionaladbody']; ?></textarea>
                            <button class="form-control btn btn-primary mt-2 w-25" onClick="copyToClipboard(document.getElementById('emailcode').value);return false;">COPY</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
        
    }
}
?>

<div class="ja-bottompadding"></div>

</div>