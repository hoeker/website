
<?php

$connstr = "host=dbsrv1 dbname=csc309g9 user=csc309g9 password=ohs7ohd4";
$conn = pg_connect($connstr);

$viewuserid = htmlspecialchars($_GET["userid"]);
$userid = htmlspecialchars($_COOKIE["userid"]);

if (!$viewuserid) {
  $viewuserid = $userid;
}

/* Updaet the user's profile  if they edited it */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $firstname = htmlspecialchars($_POST["firstname"]);
  $lastname = htmlspecialchars($_POST["lastname"]);
  $location = htmlspecialchars($_POST["location"]);
  $emailaddress = htmlspecialchars($_POST["emailaddress"]);
  $phonenumber = htmlspecialchars($_POST["phonenumber"]);

  $args = array($userid, $firstname, $lastname,
                $location, $emailaddress, $phonenumber);
  $sql = <<<SQL
UPDATE WebUser
SET firstname=$2,
    lastname=$3,
    location=$4,
    emailaddress=$5,
    phonenumber=$6
WHERE userid=$1
SQL;

  $result = pg_query_params($conn, $sql, $args);
}

if (!$viewuserid) {
  $error = "Invalid userid.";
} else {

  $sql = <<<SQL
SELECT
    username,
    firstname,
    lastname,
    location,
    emailaddress,
    phonenumber
FROM WebUser
WHERE userID=$1;
SQL;

  $result = pg_query_params($conn, $sql, array($viewuserid));

  if (pg_num_rows($result) != 1) {
    $error = "Invalid userid.";
  } else {

    $row = pg_fetch_row($result);

    $username = $row[0];
    $firstname = $row[1];
    $lastname = $row[2];
    $location = $row[3];
    $emailaddress = $row[4];
    $phonenumber = $row[5];

  }
}

?>

<!DOCTYPE html>

<html>
<head>
<title>Organise a Game</title>

<script type="text/javascript" src="scripts/common.js"></script>
<script type="text/javascript" src="scripts/validation.js"></script>
<script type="text/javascript" src="scripts/profile.js"></script>
<link rel="stylesheet" href="stylesheets/common.css" type="text/css" />
<link rel="stylesheet" href="stylesheets/profile.css" type="text/css" />

</head>

<body>

<?php

if ($error) {
  echo $error;
} else {

  $header="Profile";
  include 'includes/header.php';

  $addr="profile.php";
  include 'includes/nav.php';

echo <<<HTML1
<form name="profile" action="profile.php" method="post" id="editableForm" class="uneditable">
Username: {$username}<br/>
First Name: <span class="editfield" name="firstname">{$firstname}</span>
<input type="text" name="firstname" value="{$firstname}" /><br/>
Last Name: <span class="editfield" name="lastname">{$lastname}</span>
<input type="text" name="lastname" value="{$lastname}" /><br/>
Favourite Sports: <span class="editfield" name="sports"></span>
<input type="text" name="sports" value="" /><br/>
Location: <span class="editfield" name="location">{$location}</span>
<input type="text" name="location" value="{$location}" /><br/>
Email: <span class="editfield" name="email">{$emailaddress}</span>
<input type="email" name="emailaddress" value="{$emailaddress}" class="vEmail" /><br/>
Phone: <span class="editfield" name="phone">{$phonenumber}</span>
<input type="text" name="phonenumber" value="{$phonenumber}" class="vPhone" /><br/>
HTML1;

  if ($viewuserid == $userid) {
    echo <<<HTML2
<input type="submit" value="Save" />
<button type="button" id="editButton" onclick="return false;">Edit</button>
HTML2;
  }

  echo "</form>";

}
?>

</body>
</html>
