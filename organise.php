<html>
<head>
<title>Organise a Game</title>

<script type="text/javascript" src="scripts/common.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text\css" />

</head>

<body>
<?php
$header="Organise a game";
include 'includes/header.php';

$addr="organise.php";
include 'includes/nav.php';
?>

<form name="organise" action="game.php" method="put">
Sport: <input type="text" name="sport" /><br/>
Date and Time: <input type="datetime" name="time" /><br/>
Location: <input type="text" name="location" /><br/>
Privacy: <select name="privacy">
<option value="public">Public</option>
<option value="private">Friends Only</option>
<option value="invite">Invite Only</option>
</select><br/>

<input type="submit" value="Create" />
</form>

</body>
</html>
