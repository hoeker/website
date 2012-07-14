<!DOCTYPE html>

<?php

setcookie("username", "", time() - (24*60*60));
setcookie("password", "", time() - (24*60*60));
setcookie("userid", "", time() - (24*60*60));

?>

<html>
<head>
<title>Logout</title>

<script type="text/javascript" src="scripts/common.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text/css"/>

</head>


<body>
<h2>Goodbye!</h2>

<p>You are now successfully logged out.  Congratulations!</p>
<p>Click <a href="splash.php">here</a> to log back in.</p>

</body>
</html>
