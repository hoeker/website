<html>
<head>
<title>Organise a Game</title>

<script type="text/javascript" src="scripts/common.js"></script>
<script type="text/javascript" src="srcipts/validation.js"></script>
<script type="text/javascript" src="scripts/profile.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text/css" />
<link rel="stylesheet" href="stylesheets/profile.css" type="text/css" />

</head>

<body>
<?php
$header="Profile";
include 'includes/header.php';

$addr="profile.php";
include 'includes/nav.php';
?>

<form name="profile" action="profile.php" method="post" id="editableForm" class="uneditable">
Username: joep<br/>
Name: <span class="editfield" name="name">Joe Poster</span>
<input type="text" name="name" value="Joe Poster" /><br/>
Favourite Sports: <span class="editfield" name="sports">Hockey, volleyball</span>
<input type="text" name="time" value="Hockey, volleyball" /><br/>
Location: <span class="editfield" name="location">Toronto</span>
<input type="text" name="location" value="Toronto" /><br/>
Email: <span class="editfield" name="email">joe@example.com</span>
<input type="email" name="email" value="joe@example.com" class="vEmail" /><br/>
Phone: <span class="editfield" name="phone">416-555-1234</span>
<input type="text" name="phone" "416-555-1234" class="vPhone" /><br/>
<input type="submit" value="Save" />
<button type="button" id="editButton" onclick="return false;">Edit</button>
</form>

</body>
</html>
