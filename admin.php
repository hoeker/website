<!DOCTYPE html>

<html>
<head>
<title>Main page</title>

<script type="text/javascript" src="scripts/common.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text/css" />

</head>

<body>

<?php
$header = "Admin Page";
include 'includes/header.php';

$addr = "admin.php";
include 'includes/nav.php';
?>

<div class="adminables">
Delete game: <input type="text" id="gameId" placeholder="Game ID" />
<a href="#">Delete</a><br/>
Ban user: <input type="text" id="userId" placeholder="User ID" />
<a href="#">Ban</a><br/>
</div>

<div class="statistics">
<h3>Statistics</h3>
<ul>
<li>Users: 1000</li>
<li>Games: 200</li>
<li>Sports: 15</li>
</ul>
</div>

</body>
</html>
