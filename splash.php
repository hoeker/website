<!DOCTYPE html>
<html>
<head>
<title>Splash Page</title>

<script type="text/javascript" src="scripts/common.js"></script>
<script type="text/javascript" src="scripts/validation.js"></script>
<script type="text/javascript" src="scripts/splash.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text/css"/>
<link rel="stylesheet" href="stylesheets/splash.css" type="text/css"/>

</head>


<body>
<h2>Welcome to Thing</h2>

<div id="about">
<h4>About Us</h4>
This is a thing we do.  It's a pretty cool thing.  I like it.  I think it's wicked.
</div>

<div id="login">
<h4>Log In</h4>
<form name="login" action="main.php" method="post">
User name: <input type="text" name="username" placeholder="User name" class="vUsername" /><br />
Password: <input type="password" name="password" placeholder="Password" class="vPassword" /><br />
<input type="hidden" name="action" value="login" />
<input type="submit" value="Log In" />
</form>
</div>

<div id="signup">
<h4>New User?</h4>
<form name="signup" action="main.php" method="post" >
User name: <input type="text" name="username" placeholder="User name" class="vUsername" /><br />
Email: <input type="email" name="email" placeholder="Email" class="vEmail" /><br />
Password: <input type="password" name="password" placeholder="Password" class="vPassword1" /><br />
Repeat password: <input type="password" name="password2" placeholder="Password" class = "vPassword2" /><br />
<input type="hidden" name="action" value="signup" />
<input type="submit" value="Sign Up" />
</form>
</div>

</body>
</html>
