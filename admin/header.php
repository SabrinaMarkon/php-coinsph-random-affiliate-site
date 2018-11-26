<!DOCTYPE html>
<html lang="en">

  <head>

  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="Sabrina Markon" />
  <base href = "/" />

  <title>RandomBTCAds.com</title>

  <!-- Bootstrap core CSS -->
  <link href="../js/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
  
  <!-- Custom styles -->
  <link href="../css/custom.css" rel="stylesheet" />

</head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top ja-navbottomadmin">
      <div class="container">
        <a class="navbar-brand" href="/">Random BTC Ads</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">

            <?php
            if ((!isset($_SESSION['adminusername'])) || (!isset($_SESSION['adminpassword'])))
            {
              ?>
                <li class="nav-item active">
                  <a class="nav-link" href="/">Home
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="/admin">Admin Login</a>
                </li>     
              <?php
            }
            else
            {
            ?>
              <li class="nav-item active">
                <a class="nav-link" href="/../" target="_blank">Site</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/main">Main</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/settings">Settings</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/adminwallets">Admin Wallets</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/members">Members</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/ads">Ads</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/randomizer">Randomizer</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/money">Money</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/mail">Mail</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/promotional">Promotional</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/pages">Pages</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/admin/logout">Logout</a>
              </li>
            <?php
            }
            ?>
					
          </ul>
        </div>
      </div>
    </nav>

    <!-- Page Content -->
    <div class="container ja-toppadding">

		<div class="row">
			<div class="col-sm-12">		

<!-- CENTRAL PAGE CONTENT APPEARS BELOW HERE -->		
