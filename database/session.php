<?php

require_once 'dbinterface.php';

/* Log a user in */
function login($username, $password) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  $args = array($username, $password);
  $sql = <<<SQL
SELECT userID
FROM WebUser
WHERE userName=$1
    AND password=$2;
SQL;

  $result = executeSQL($conn, $sql, $args);
  
  if (getResultCount($result) != 1) {
    /* Login failed */
    closeDB($conn);
    return false;
  }

  $row = nextRow($result);
  $userid = $row[0];

  closeDB($conn);
  return $userid;

} catch (Exception $e) {
  error("login: {$e}");
  closeDB($conn);
  return false;
}
}


/* Create a new user */
function signup($username, $password, $email) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  $args = array($username, $password, $email);
  $sql = <<<SQL
INSERT INTO WebUser (userName, password, emailaddress)
SELECT $1, $2, $3
WHERE NOT EXISTS (SELECT * FROM WebUser WHERE userName=$1);
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getUpdateCount($result) != 1) {
    /* User creation failed, username already exists */
    closeDB($conn);
    return -1;
  }

  /* User creation successful, now log the user in */
  closeDB($conn);
  return login($username, $password);

} catch (Exception $e) {
  error("signup {$e}");
  closeDB($conn);
  return -2;
}
}


/* Change a password */
function changePassword($oldPassword, $newPassword) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Attempt to change the password */
  $userId = $_SESSION['userId'];
  $args = array($userId, $oldPassword, $newPassword);
  $sql = <<<SQL
UPDATE WebUser
SET password=$3
WHERE userID=$1
    AND password=$2;
SQL;

  $result = executeSQL($conn, $sql, $args);
  if (getUpdateCount($result) != 1) {
    /* Failed to update. */
    closeDB($conn);
    return false;
  }

  closeDB($conn);
  return true;

} catch (Exception $e) {
  error("changePassword: {$e}");
  closeDB($conn);
  return false;
}
}

?>
