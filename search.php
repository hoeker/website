<!DOCTYPE html>

<html>
<head>
<title>Search</title>

<script type="text/javascript" src="scripts/common.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text/css" />

</head>

<body>

<?php
$header = "Search";
include 'includes/header.php';

$addr = "search.php";
include 'includes/nav.php';
?>

<div class="search">
<form name="query" action="search.php" method="get">
Sport: <input type="text" name="sport" placeholder="Sport" /><br/>
From: <input type="date" name="start" placeholder="Start date" /><br/>
To: <input type="date" name="end" placeholder="End date" /><br/>
Location: <input type="text" name="location" placeholder="Location" /><br/>
<input type="submit" value="Search" />
</form>
</div>

<?php
include 'includes/searchresults.php';
?>

</body>
</html>
