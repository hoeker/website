
<?php

$connstr = "host=dbsrv1 dbname=csc309g9 user=csc309g9 password=ohs7ohd4";
$conn = pg_connect($connstr);

$action = htmlspecialchars($_POST["action"]);
$username = htmlspecialchars($_POST["username"]);
$password = htmlspecialchars($_POST["password"]);
$passwordAlt = htmlspecialchars($_POST["password2"]);
$email = htmlspecialchars($_POST["email"]);

if (!$username) $username = htmlspecialchars($_COOKIE["username"]);
if (!$password) $password = htmlspecialchars($_COOKIE["password"]);
$userid = htmlspecialchars($_COOKIE["userid"]);

$loginSuccess = false;

if ($action == "signup") {
  /* check that passwords match */
  if ($password != $passwordAlt) {
    $error = "Passwords do not match";
  } else {

    /* Attempt to create new username and password in database */
    $args = array($username, $password, $email);
    $sql = <<<SQL
INSERT INTO WebUser (userName, password, emailaddress)
VALUES ($1, $2, $3);
SQL;

    $result = pg_query_params($conn, $sql, $args);

    if (pg_affected_rows($result) != 1) {
      /* User creation failed, set error message */
      $error = "Username already in use.  Please pick another.";
    }
  }
}

/* Now we retrieve the user's information.
   If they just signed up, this will still work. */
if (!$error) {
  $args = array($username, $password);
  $sql = <<<SQL
SELECT userID, firstname, active, admin
FROM WebUser
WHERE userName=$1
    AND password=$2;
SQL;

  $result = pg_query_params($conn, $sql, $args);

  if (pg_num_rows($result) != 1) {
    /* Login failed, redirect to splash page */
    $num = pg_num_rows($result);
    $error = "Incorrect username and password combination.";

  } else {
    /* If success, logged in */
    $loginSuccess = true;
    $userRow = pg_fetch_row($result);

    $userid = $userRow[0];
    $firstname = $userRow[1];
    $active = $userRow[2];
    $admin = $userRow[3];

    if ($active == "f") {
      $error = "You're banned!";
      $loginSuccess = false;
    }

  }
}

if ($loginSuccess) {
  /* Cheap hack for convenience */
  setcookie("username", $username);
  setcookie("password", $password);
  setcookie("userid", $userid);
}
?>


<!DOCTYPE html>

<html>
<head>
<title>Main page</title>

<script type="text/javascript" src="scripts/common.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text/css" />
<link rel="stylesheet" href="stylesheets/main.css" type="text/css" />

</head>

<body>

<?php

if ($loginSuccess) {

  $header = "Main Page";
  include 'includes/header.php';

  $addr = "main.php";
  include 'includes/nav.php';

  include 'includes/alerts.php';

  include 'includes/relevantgames.php';

} else {

  echo $error;

}

?>


</body>
</html>
