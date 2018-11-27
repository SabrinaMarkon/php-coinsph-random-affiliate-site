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
	<link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous" />
    
    <!-- Custom styles -->
    <link href="css/custom.css" rel="stylesheet" />

  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="/">Random BTC Ads</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">

			<?php
			if ((isset($_SESSION['username'])) && (isset($_SESSION['password']))) {

        ?>
        <li class="nav-item active">
          <a class="nav-link" href="/members">Main
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/profile">Profile</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/randomizer">Randomizer</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/ads">Ads</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/promotional">Promote</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/faq">FAQ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/terms">Terms</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/contact">Contact</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/logout">Logout</a>
        </li>
        <?php
			}	else {

        ?>
        <li class="nav-item active">
          <a class="nav-link" href="/">Home
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/login">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/register">Register</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/about">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/faq">FAQ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/terms">Terms</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/contact">Contact</a>
        </li>
        <?php
			}
			?>
					
          </ul>
        </div>
      </div>
    </nav>

    <!-- Header with Background Image -->
    <header class="ja-headerimage">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="title display-3 text-center text-white mt-4">Random BTC Ads</h1>
          </div>
        </div>
      </div>
    </header>

    <!-- Page Content -->
    <div class="container">

		<div class="row">
			<div class="col-sm-12">		

<!-- CENTRAL PAGE CONTENT APPEARS BELOW HERE -->		

<!-- <p>Where the content, forms, etc. start to appear:</p>
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean diam felis, ornare sed elit ac, convallis cursus arcu. Duis non feugiat augue. Cras auctor mauris quis tortor efficitur, venenatis auctor libero porttitor. Vivamus pharetra non est sit amet viverra. Aenean et arcu tellus. Mauris tincidunt odio eu orci finibus pulvinar. Fusce sit amet iaculis nulla, vel semper libero. Morbi vitae dolor ac dolor mattis consequat. Nulla vitae auctor magna, at varius nunc. Curabitur vehicula libero vel feugiat condimentum. Curabitur diam est, pulvinar in orci vel, gravida sagittis ligula. Praesent eget mi ullamcorper arcu vulputate dictum. Fusce a justo vel augue varius finibus nec nec arcu. Suspendisse et convallis nulla, sit amet fermentum erat. In vestibulum mauris vitae mattis tempor.</p>
<p>Morbi accumsan quis mi vel interdum. Duis sit amet libero gravida, pharetra leo in, congue sem. Suspendisse potenti. Suspendisse dui ipsum, convallis id hendrerit in, euismod eget lectus. Nunc non lorem nec libero dapibus aliquam. Nunc porta ipsum tellus, quis tempus metus aliquam sit amet. Praesent ipsum magna, venenatis sit amet lacus ut, consectetur ornare libero. Aenean auctor sem nisl, ut fringilla enim mattis at. Sed finibus justo eu nulla molestie convallis. Aliquam nec arcu at dolor ornare tristique id et augue. Pellentesque non ultrices neque. Vestibulum tempor lorem sit amet dictum vulputate.</p>
<p>Pellentesque non sem nec tortor euismod gravida. Nulla facilisis purus mauris, et facilisis tortor porta pharetra. Interdum et malesuada fames ac ante ipsum primis in faucibus. Etiam imperdiet aliquet bibendum. Praesent vel pellentesque nisl, sit amet viverra purus. Vestibulum sed diam sit amet erat facilisis iaculis. Ut sodales elit nisi, eu lacinia lectus dapibus luctus.</p> -->
