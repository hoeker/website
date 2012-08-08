<?php

require_once 'dbinterface.php';


/* Get an array of userIds that $userId has played with */
function getFriends($userId) {
try {
  if ($userId === null) {$userId = $_SESSION['userId'];}

  if (!$conn = connectDB()) {
    return false;
  }

  /* Get a list of friends */
  $args = array($userId);
  $sql = <<<SQL
SELECT userID1
FROM Friend
WHERE userID2=$1
    UNION
SELECT userID2
FROM Friend
WHERE userID1=$1;
SQL;

  $result = executeSQL($conn, $sql, $args);

  /* Fill out the array */
  $ret = array();
  while ($row = nextRow($result)) {
    array_push($ret, $row[0]);
  }

  closeDB($conn);
  return $ret;

} catch (Exception $e) {
  error("getFriends: {$e}");
  closeDB($conn);
  return false;
}
}


/* Make $userId1 and $userId2 friends if they aren't already */
function makeFriends($userId1, $userId2) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Need to sort out which is lower */
  if ($userId1 < $userId2) {
    $lower = $userId1;
    $higher = $userId2;
  } elseif ($userId1 > $userId2) {
    $lower = $userId2;
    $higher = $userId1;
  } else {
    /* Equal!! */
    return false;
  }

  /* befriend them, if they aren't already */
  $args = array($lower, $higher);
  $sql = <<<SQL
INSERT INTO Friend (userID1, userID2)
SELECT $1, $2
WHERE NOT EXISTS (SELECT * FROM Friend WHERE userID1=$1 and userID2=$2);
SQL;

  $result = executeSQL($conn, $sql, $args);
  if (getUpdateCount($result) != 1) {
    /* They're already friends */
    closeDB($conn);
    return false;
  }

  closeDB($conn);
  return true;

} catch (Exception $e) {
  error("makeFriends: {$e}");
  closeDB($conn);
  return false;
}
}


/* Get one player's rating of another */
function getRating($userId, $raterId) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  $args = array($userId, $raterId);
  $sql = <<<SQL
SELECT rating
FROM Rating
WHERE userID=$1
    AND ratedBy=$2;
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getResultCount($result) != 1) {
    return null;
  }

  $row = nextRow($result);
  $rating = $row[0];

  closeDB($conn);
  return $rating;

} catch (Exception $e) {
  error("getRating: {$e}");
  closeDB($conn);
  return false;
}
}


/* Check if two players ar efriends */
function areFriends($userId1, $userId2) {
  $friends = getFriends($userId1);
  if (!$friends) {
    return false;
  }

  return in_array($userId2, $friends);
}


/* Remove the friend relationship between two players.
   Really only neceesary for testing... */
function unFriend($userId1, $userId2) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  $args = array($userId1, $userId2);
  $sql = <<<SQL
DELETE FROM Friend
WHERE userID1 IN ($1, $2)
    AND userID2 IN ($1, $2);

SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getUpdateCount($result) != 1) {
    closeDB($conn);
    return false;
  }

  $sql = <<<SQL
DELETE FROM Rating
WHERE userId IN ($1, $2)
    AND ratedBy IN ($1, $2);
SQL;

  $result = executeSQL($conn, $sql, $args);

  closeDB($conn);
  return true;

} catch (Exception $e) {
  error("unFriend: {$e}");
  closeDB($conn);
  return false;
}
}


/* Rate a player.  $rating is an integer, +1 or -1 */
function ratePlayer($userId, $rating) {
try {
  $activeUser = $_SESSION['userId'];
  if (!$activeUser) {
    return false;
  }

  if ($rating != 1 and $rating != -1) {
    /* Invalid rating */
    return false;
  }

  if (!areFriends($activeUser, $userId)) {
    /* Can only rate friends! */
    return false;
  }

  $currentRating = getRating($userId, $activeUser);
  if ($currentRating === false) {
    return false;
  }

  /* Get the current rating, if any */
  if (!$conn = connectDB()) {
    return false;
  }

  /* Insert a dummy row if there is no current rating */
  if ($currentRating === null) {
    $currentRating = 0;
    $args = array($userId, $activeUser);
    $sql = <<<SQL
INSERT INTO Rating (userID, ratedBy, rating)
SELECT $1, $2, 0
WHERE NOT EXISTS (SELECT * FROM Rating WHERE userID=$1 AND ratedBy=$2);
SQL;
    executeSQL($conn, $sql, $args);
  }

  $args = array($userId, $activeUser, $rating);
  $sql = <<<SQL
UPDATE Rating
SET rating = rating + $3
WHERE userID=$1
    AND ratedBy=$2;
SQL;

  $result = executeSQL($conn, $sql, $args);
  if (getUpdateCount($result) != 1) {
    /* Update failed.  PANIC!! */
    closeDB($conn);
    return false;
  }

  closeDB($conn);
  return true;

} catch (Exception $e) {
  error("ratePlayer: {$e}");
  closeDB($conn);
  return false;
}
}


/* Clear a rating.  Mostly for testing. */
function clearRating($userId, $raterId) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Delete the rating */
  $args = array($userId, $raterId);
  $sql = <<<SQL
DELETE FROM Rating
WHERE userID=$1
    AND ratedBy=$2;
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (!getUpdateCount($result) != 1) {
    /* Failed to delete */
    closeDB($conn);
    return false;
  }

  closeDB($conn);
  return true;

} catch (Exception $e) {
  error("clearRating: {$e}");
  closeDB($conn);
  return false;
}
}

?>
