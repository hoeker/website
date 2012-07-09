<!DOCTYPE html>
<html>
<head>
<title>Splash Page</title>

<script type="text/javascript" src="scripts/common.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text/css"/>

</head>


<body>
<h2>Welcome to Thing</h2>

<p>This is a thing we do.</p>

<h4>Log In</h4>
<form name="login" action="main.php" method="post">
User name: <input type="text" name="username" placeholder="User name" /><br />
Password: &nbsp;<input type="password" name="password" placeholder="Password" /><br />
<input type="hidden" name="action" value="login" />
<input type="submit" value="Log In" />
</form>

<h4>New User?</h4>
<form name="signup" action="main.php" method="post">
User name: <input type="text" name="username" /><br />
Email: <input type="email" name="email" /><br />
Password: &nbsp;<input type="password" name="password" /><br />
Repeat password: <input type="password" name="password2" /><br />
<input type="hidden" name="action" value="signup" />
<input type="submit" value="Sign Up" />
</form>

</body>
</html>
