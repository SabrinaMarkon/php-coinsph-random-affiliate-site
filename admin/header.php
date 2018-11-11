<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Junior Artists</title>
    <link href="/../css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/../css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <link href="/../css/custom.css" rel="stylesheet" media="screen">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container text-center">

<!--    <header id="heading">-->
<!--        <img src="/../images/header.jpg" alt="Junior Artists" id="ja-headerimage">-->
<!--    </header>-->

    <div class="btn-group btn-group-justified ja-navgroup" role="group" aria-label="Main Navigation Menu" id="navbar">

        <?php
        if ((isset($_SESSION['username'])) && (isset($_SESSION['password'])))
        {
            ?>
            <a href="/../" type="button" class="btn ja-navbutton" role="button" target="_blank">SITE</a>
            <a href="/admin/main" type="button" class="btn ja-navbutton" role="button">MAIN</a>
            <a href="/admin/settings" type="button" class="btn ja-navbutton" role="button">SETTINGS</a>
            <a href="/admin/members" type="button" class="btn ja-navbutton" role="button">MEMBERS</a>
            <a href="/admin/mail" type="button" class="btn ja-navbutton" role="button">MAIL</a>
            <a href="/admin/money" type="button" class="btn ja-navbutton" role="button">MONEY</a>
            <a href="/admin/pages" type="button" class="btn ja-navbutton" role="button">PAGES</a>
            <a href="/admin/logout" type="button" class="btn ja-navbutton" role="button">LOGOUT</a>
            <?php
        }
        else
        {
            ?>
<!--            UNCOMMENT WHEN ADMIN AUTH IS SET UP-->
<!--            <a href="/" type="button" class="btn ja-navbutton" role="button">HOME</a>-->
<!--            <a href="/login" type="button" class="btn ja-navbutton" role="button">LOGIN</a>-->
<!--            <a href="/register" type="button" class="btn ja-navbutton" role="button">REGISTER</a>-->
<!--            <a href="https://www.facebook.com/junior.artists.7/photos" target="_blank" type="button" class="btn ja-navbutton" role="button">GALLERY</a>-->
<!--            <a href="/aboutus" type="button" class="btn ja-navbutton" role="button">ABOUT</a>-->
<!--            <a href="/contact" type="button" class="btn ja-navbutton" role="button">CONTACT</a>-->
            <a href="/../" type="button" class="btn ja-navbutton" role="button" target="_blank">SITE</a>
            <a href="/admin/main" type="button" class="btn ja-navbutton" role="button">MAIN</a>
            <a href="/admin/settings" type="button" class="btn ja-navbutton" role="button">SETTINGS</a>
            <a href="/admin/members" type="button" class="btn ja-navbutton" role="button">MEMBERS</a>
            <a href="/admin/mail" type="button" class="btn ja-navbutton" role="button">MAIL</a>
            <a href="/admin/money" type="button" class="btn ja-navbutton" role="button">MONEY</a>
            <a href="/admin/pages" type="button" class="btn ja-navbutton" role="button">PAGES</a>
            <a href="/admin/logout" type="button" class="btn ja-navbutton" role="button">LOGOUT</a>
            <?php
        }
        ?>
    </div>

    <div class="ja-content">
