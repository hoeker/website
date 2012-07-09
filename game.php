<!DOCTYPE html>

<?php

# Pull the game info from the database

$sport = 'Hockey';
$time = 'Friday';
$loc = 'The rink';

?>

<html>
<head>
<title>Game page</title>

<script type="text/javascript" src="scripts/common.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text/css" />
<link rel="stylesheet" href="stylesheets/game.css" type="text/css" />

</head>

<body>

<?php
$header = "Game Page";
include 'includes/header.php';

$addr = "game.php";
include 'includes/nav.php';
?>

<div class="details">
<h3>Details</h3>

<?php
echo "Sport: $sport<br/>";
echo "Time: $time<br/>";
echo "Location: $loc<br/>";
?>
<a href="#">Interested in this game?</a>
</div>

<?php
# pull the posts
include 'includes/gameposts.php';
?>

</body>
</html>
