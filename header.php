<!DOCTYPE html>
<html lang="en">

  <head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Sabrina Markon">

    <title>RandomBTCAds.com</title>

    <!-- Bootstrap core CSS -->
    <link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles -->
    <link href="css/custom.css" rel="stylesheet">

  </head>

  <body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">Random BTC Ads</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ml-auto">

			<?php
			if ((isset($_SESSION['username'])) && (isset($_SESSION['password'])))
			{
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
              <a class="nav-link" href="/earnings">Earnings</a>
			</li>
            <li class="nav-item">
              <a class="nav-link" href="/ads">Ads</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/contact">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/logout">Contact</a>
			</li>
			<?php
			}
			else
			{
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
            <h1 class="display-3 text-center text-white mt-4">Random BTC Ads</h1>
          </div>
        </div>
      </div>
    </header>

    <!-- Page Content -->
    <div class="container">

		<div class="row">
			<div class="col-sm-12">		

	asdfadsfdsfdsffds		
		

