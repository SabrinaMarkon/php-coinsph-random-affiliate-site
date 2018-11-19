<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

require "control.php";
if (isset($showupdate))
{
    echo $showupdate;
}
$allmembers = new Member();
$members = $allmembers->getAllMembers();
?>

<div class="container">
    <div class="row">
        <div class="col-sm-12">

			<h1 class="ja-bottompadding">Add New Member</h1>
			
			<form action="//admin/members" method="post" accept-charset="utf-8" class="form" role="form">
			
				<div class="row">
					<div class="col-xs-6 col-md-6">
						<label class="sr-only" for="firstname">First Name</label>
						<input type="text" name="firstname" value="" class="form-control input-lg" placeholder="First Name" required>
					</div>
					<div class="col-xs-6 col-md-6">
						<label class="sr-only" for="lastname">Last Name</label>
						<input type="text" name="lastname" value="" class="form-control input-lg" placeholder="Last Name" required>
					</div>
				</div>
				
                <label class="sr-only" for="email">Email</label>
                <input type="email" name="email" value="" class="form-control input-lg" placeholder="Your Email" required>

                <label class="sr-only" for="username">Username</label>
                <input type="text" name="username" value="" class="form-control input-lg" placeholder="Username" required>

                <label class="sr-only" for="password">Password</label>
                <input type="password" name="password" id="password" value="" class="form-control input-lg" placeholder="Password" required>

                <label class="sr-only" for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control input-lg" placeholder="Confirm Password" required>

                <label class="sr-only" for="walletid">Bitcoin Wallet ID</label>
                <input type="text" name="walletid" value="" class="form-control input-lg" placeholder="Bitcoin Wallet ID" required>

                <label class="sr-only" for="country">Country</label>
                <select name="country" class="form-control input-lg">
                    <option value="United States">United States</option>
                    <option value="Canada">Canada</option>
                    <?php
                    $country = '';
                    $countrylist = new Countries();
                    echo $countrylist->showCountries($country);
                    ?>
                </select>

                <label class="sr-only" for="referid">Sponsor</label>
                <input type="text" name="referid" value="" class="form-control input-lg" placeholder="Sponsor" required>

                <div class="ja-bottompadding"></div>

                <button class="btn btn-lg btn-primary" type="submit" name="addmember">Create Account</button>

			</form>				

			<div class="ja-bottompadding"></div>

            <h1 class="ja-bottompadding">Website Members</h1>

            <div class="table-responsive">
                <table class="table table-condensed table-bordered table-striped table-hover text-center table-sm">
                    <thead>
                    <tr>
                        <th class="text-center small">#</th>
                        <th class="text-center small">Username</th>
                        <th class="text-center small">Password</th>
                        <th class="text-center small">Wallet ID</th>
                        <th class="text-center small">First Name</th>
                        <th class="text-center small">Last Name</th>
                        <th class="text-center small">Email</th>
                        <th class="text-center small">Verified</th>
                        <th class="text-center small">Date Verified</th>
                        <th class="text-center small">Country</th>
                        <th class="text-center small">Signup Date</th>
                        <th class="text-center small">Signup IP</th>
                        <th class="text-center small">Last Login</th>
                        <th class="text-center small">Sponsor</th>
                        <th class="text-center small">Edit</th>
                        <th class="text-center small">Delete</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    foreach ($members as $member) {

                        $signupdate = new DateTime($member['signupdate']);
                        $datesignedup = $signupdate->format('Y-m-d');

                        $verifieddate = new DateTime($member['verifieddate']);
                        if($member['verifieddate'] === NULL){ $dateverified = 'Not Yet'; } else { $dateverified = $verifieddate->format('Y-m-d'); }

                        $lastlogin = new DateTime($member['lastlogin']);
                        if($member['lastlogin'] === NULL){ $datelastlogin = 'Not Yet'; } else { $datelastlogin = $lastlogin->format('Y-m-d'); }
                        ?>
                        <tr>
                            <form action="/admin/members/<?php echo $member['id']; ?>" method="post" accept-charset="utf-8" class="form" role="form">
                            <td class="small"><?php echo $member['id']; ?>
                            </td>
                            <td>
                                <label class="sr-only" for="username">Username:</label>
                                <input type="text" name="username" value="<?php echo $member['username']; ?>" class="form-control input-sm small" size="40" placeholder="Username" required>
                            </td>
                            <td>
                                <label class="sr-only" for="password">Password:</label>
                                <input type="text" name="password" value="<?php echo $member['password']; ?>" class="form-control input-sm small" size="40" placeholder="Password" required>
                            </td>
                            <td>
                                <label class="sr-only" for="walletid">Bitcoin Wallet ID:</label>
                                <input type="text" name="walletid" value="<?php echo $member['walletid']; ?>" class="form-control input-sm small" size="40" placeholder="Wallet ID" required>
                            </td>
                            <td>
                                <label class="sr-only" for="firstname">First Name:</label>
                                <input type="text" name="firstname" value="<?php echo $member['firstname']; ?>" class="form-control input-sm small" size="40" placeholder="First Name" required>
                            </td>
                            <td>
                                <label class="sr-only" for="lastname">Last Name:</label>
                                <input type="text" name="lastname" value="<?php echo $member['lastname']; ?>" class="form-control input-sm small" size="40" placeholder="Last Name" required>
                            </td>
                            <td>
                                <label class="sr-only" for="email">Email:</label>
                                <input type="email" name="email" value="<?php echo $member['email']; ?>" class="form-control input-sm small" size="60" placeholder="Email" required>
                            </td>
                            <td>
                                <label class="sr-only" for="verified">Verified:</label>
                                <select name="verified" class="form-control input-md">
                                    <option value="yes"<?php if ($member['verified'] === 'yes') { echo " selected"; } ?>>yes</option>
                                    <option value="no"<?php if ($member['verified'] !== 'yes') { echo " selected"; } ?>>no</option>
                                </select>
                            </td>
                            <td class="small">
                                <?php echo $dateverified ?>
                            </td>
                            <td>
                                <label class="sr-only" for="country">Country:</label>
                                <select name="country" class="form-control input-md">
                                    <option value="United States"<?php if ($member['country'] == "United States") { echo " selected"; } ?> >United States</option>
                                    <option value="Canada"<?php if ($member['country'] === "Canada") { echo " selected"; } ?>>Canada</option>
                                    <?php
                                    $countrylist = new Countries();
                                    echo $countrylist->showCountries($member['country']);
                                    ?>
                                </select>
                            </td>
                            <td class="small">
                                <?php echo $datesignedup ?>
                            </td>
                            <td>
                                <label class="sr-only" for="signupip">IP:</label>
                                <input type="text" name="signupip" value="<?php echo $member['signupip']; ?>" class="form-control input-sm small" size="60" placeholder="IP" required>
                            </td>
                            <td class="small">
                                <?php echo $datelastlogin ?>
                            </td>
                            <td>
                                <label class="sr-only" for="referid">Sponsor:</label>
                                <input type="text" name="referid" value="<?php echo $member['referid']; ?>" class="form-control input-sm small" size="40" placeholder="Sponsor" required>
                            </td>
                            <td>
                                <input type="hidden" name="_method" value="PATCH">
                                <button class="btn btn-sm btn-primary" type="submit" name="savemember">SAVE</button>
                            </td>
                            </form>
                            <td>
                                <form action="/admin/members/<?php echo $member['id']; ?>" method="POST" accept-charset="utf-8" class="form" role="form">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="username" value="<?php echo $member['username']; ?>">
                                    <button class="btn btn-sm btn-primary" type="submit" name="deletemember">DELETE</button>
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