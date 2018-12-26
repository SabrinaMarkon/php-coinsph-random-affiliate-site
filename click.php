<?php
# Prevent direct access to this file. Show browser's default 404 error instead.
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

# get the end part of the url, which is normally the referid for the main site files, but will be actually the id of the clicked ad for this php file only.
$id = $_SESSION['referid'];
// session_unset();

$giveclicks = new Rotator();
$click = $giveclicks->giveClick($id);

if ($click) {

    header('Location: ' . $click);
    exit;

} else {

    echo "<div class=\"alert alert-danger\" style=\"width:75%;\"><strong>The URL for this ad was invalid.</strong></div>";
}

