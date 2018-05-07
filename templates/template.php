<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset = "utf-8" />
        <title><?= $title ?></title>

        <!-- Meta-tags -->

        <!-- Open Graph data -->

        <!-- Twitter Card data -->

        <!-- IE -->

        <!-- Devices -->
        <meta name="viewport" content="width = device-width, initial-scale = 1">

        <!-- Stylesheets -->
			<!-- Bootstrap-->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
			<!-- Fontawesome -->
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css" integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
        	<!-- Project's stylesheet -->
		<link rel="stylesheet" href="../public/css/style.css"/>
    </head>

    <body>

		<header class="page-header">
			<!-- Loading the necessary nav bar regarding if the visitor is connected or not -->
			<nav class="navbar navbar-expand-md navbar-light bg-light">

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<?php
					if(!isset($_SESSION['id'])){
						require('navbar/visitor.php');
					} else {
						require('navbar/connected.php');
					}
					?>
				</div>
			</nav>
		</header>

		<div class="container-fluid" id="content">
            <?= $content ?>
		</div>

        <!-- JavaScript Files -->
		<!-- Bootstrap -->
		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

    </body>

    <footer>

    </footer>
</html>