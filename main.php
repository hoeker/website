<!DOCTYPE html>

<html>
<head>
<title>Main page</title>

<script type="text/javascript" src="scripts/common.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text/css" />

</head>

<body>

<?php
$header = "Main Page";
include 'includes/header.php';

$addr = "main.php";
include 'includes/nav.php';

$userid = 123;
include 'includes/alerts.php';

include 'includes/relevantgames.php';
?>


</body>
</html>
