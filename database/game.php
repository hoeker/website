<?php

require_once 'dbinterface.php';


function createGame($sportId, $date, $time, $location, $privacy) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Insert the row into the game tbale */
  $userId = $_SESSION['userId'];
  $args = array($userId, $sportId, $location, $date, $time, $privacy);
  $sql = <<<SQL
INSERT INTO Game (organiserID, sportID, location, date, time, privacy)
VALUES ($1, $2, $3, $4, $5, $6)
RETURNING gameID;
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getUpdateCount($result) != 1) {
    /* Failed to insert row in game table */
    closeDB($conn);
    return false;
  }

  /* Get the gameId to return */
  $row = nextRow($result);
  $gameId = $row[0];

  closeDB($conn);
  return $gameId;

} catch (Exception $e) {
  error("createGame: {$e}");
  closeDB($conn);
  return false;
}
}


/* Join a game */
function joinGame($gameId) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Create the row in the joined table */
  $args = array($gameId);
  $sql = <<<SQL
SELECT privacy
FROM Game
WHERE gameID=$1;
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getResultCount($result) != 1) {
    /* Failed to find the game */
    closeDB($conn);
    return false;
  }

  $row = nextRow($result);
  $privacy = $row[0];
  if ($privacy == "private") {
    /* Private game */
    closeDB($conn);
    return false;

  } elseif ($privacy == "invite") {
    /* Confirmation required, so not confirmed by default */
    $confirmed = "false";

  } elseif ($privacy == "public") {
    /* Public game, so automatically confirmed */
    $confirmed = "true";
  }

  /* Insert the record in the Joined table */
  $userId = $_SESSION['userId'];
  $args = array($userId, $gameId, $confirmed);
  $sql = <<<SQL
INSERT INTO Joined (userID, gameID, confirmed)
SELECT $1, $2, $3
WHERE NOT EXISTS (SELECT * FROM Joined WHERE userID=$1 AND gameID=$2);
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getUpdateCount($result) != 1) {
    /* Failed to join the game */
    closeDB($conn);
    return false;
  }
  
  closeDB($conn);
  return true;

} catch (Exception $e) {
  error("joinGame: {$e}");
  closeDB($conn);
  return false;
}
}


/* Un-join a game */
function leaveGame($gameId) {
try {

  $userId = $_SESSION['userId'];
  if (!$userId) {
    return false;
  }

  if (!$conn = connectDB()) {
    return false;
  }

  /* Delete the row in Joined */
  $args = array($gameId, $userId);
  $sql = <<<SQL
DELETE FROM Joined
WHERE gameID=$1
    AND userID=$2;
SQL;

  $result = executeSQL($conn, $sql, $args);
  if (getUpdateCount($result) != 1) {
    closeDB($conn);
    return false;
  }

  closeDB($conn);
  return true;

} catch (Exception $e) {
  error("leaveGame: {$e}");
  closeDB($conn);
  return false;
}
}


/* Retrieve info about a game */
function getGameInfo($gameId) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Find info about the game */
  $args = array($gameId);
  $sql = <<<SQL
SELECT
    g.organiserId, wu.firstName, wu.lastName,
    g.sportId, s.sportName,
    g.location, g.date, g.time, g.privacy,
    g.description
FROM Game AS g
LEFT OUTER JOIN WebUser AS wu
    ON wu.userID=g.organiserID
INNER JOIN Sport AS s
    ON s.sportID=g.sportID
WHERE gameID=$1;
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getResultCount($result) != 1) {
    /* Couldn't find the game */
    closeDB($conn);
    return false;
  }

  /* Get the results, fill out the retuyn array */
  $row = nextRow($result);

  $ret = array(
    "organiserId" => $row[0],
    "organiserFirstName" => $row[1],
    "organiserLastName" => $row[2],
    "sportId" => $row[3],
    "sportName" => $row[4],
    "location" => $row[5],
    "date" => $row[6],
    "time" => $row[7],
    "privacy" => $row[8],
    "description" => $row[9],
  );

  closeDB($conn);
  return $ret;

} catch (Exception $e) {
  error("getGameInfo: {$e}");
  closeDB($conn);
  return false;
}
}


function setGameInfo($gameId, $info) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Update the game info */
  $userId = $_SESSION['userId'];
  $args = array(
    $info["location"],
    $info["date"],
    $info["time"],
    $info["privacy"],
    $info["description"],
    $gameId,
    $userId,
  );
  $sql = <<<SQL
UPDATE Game
SET location=$1,
    date=$2,
    time=$3,
    privacy=$4,
    description=$5
WHERE gameID=$6
    AND organiserID=$7;
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getUpdateCount($result) != 1) {
    /* Update filed */
    closeDB($conn);
    return false;
  }

  closeDB($conn);
  return true;
  
} catch (Exception $e) {
  error("setGameInfo: {$e}");
  closeDB($conn);
  return false;
}
}


/* Completely delete an existing game */
function deleteGame($gameId) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Ensure the user is an admin */
  $userId = $_SESSION['userId'];
  $args = array($userId);
  $sql = <<<SQL
SELECT admin
FROM WebUser
WHERE userID=$1;
SQL;

  $result = executeSQL($conn, $sql, $args);

  $row = nextRow($result);
  if (!$row or $row[0] == false) {
    /* Couldn't find user or user is not an admin */
    closeDB($conn);
    return false;
  }

  /* Delete the game */
  $args = array($gameId);
  $sql = <<<SQL
DELETE FROM Game
WHERE gameID=$1;
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getUpdateCount($result) != 1) {
    /* Couldn't delete the row */
    closeDB($conn);
    return false;
  }

  closeDB($conn);
  return true;

} catch (Exception $e) {
  error("deleteGame: {$e}");
  closeDB($conn);
  return false;
}
}


/* Cancel a game.
   Right now it just deletes a game... should maybe do something better. */
function cancelGame($gameId) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Ensure the user is an admin */
  $userId = $_SESSION['userId'];
  $args = array($gameId);
  $sql = <<<SQL
SELECT organiserId
FROM Game
WHERE gameID=$1;
SQL;

  $result = executeSQL($conn, $sql, $args);

  $row = nextRow($result);
  if (!$row or $row[0] != $userId) {
    /* Couldn't find user or user is not the organiser */
    closeDB($conn);
    return false;
  }

  /* Delete the game */
  $args = array($gameId);
  $sql = <<<SQL
DELETE FROM Game
WHERE gameID=$1;
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getUpdateCount($result) != 1) {
    /* Couldn't delete the row */
    closeDB($conn);
    return false;
  }

  closeDB($conn);
  return true;

} catch (Exception $e) {
  error("cancelGame: {$e}");
  closeDB($conn);
  return false;
}
}

/* Get a list of game participants */
function getParticipants($gameId, $confirmed = null) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  $args = array($gameId, (int) $confirmed);
  $sql = <<<SQL
SELECT userID
FROM Joined
WHERE gameID=$1
    AND confirmed=coalesce($2, confirmed);
SQL;

  $result = executeSQL($conn, $sql, $args);

  $ret = array();
  while ($row = nextRow($result)) {
    array_push($ret, $row[0]);
  }

  return $ret;
} catch (Exception $e) {
  error("getParticipants: {$e}");
  closeDB($conn);
  return false;
}
}


/* Test whether a user has joined a game */
function hasJoined($userId, $gameId, $confirmed = null) {
try {

  $participants = getParticipants($gameId, $confirmed);
  if (!$participants) {
    return false;
  }

  return in_array($userId, $participants);

} catch (Exception $e) {
  error("hasJoined: {$e}");
  closeDB($conn);
  return false;
}
}


/* Get the organiser of $gameId */
function getOrganiser($gameId) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  $args = array($gameId);
  $sql = <<<SQL
SELECT organiserID
FROM Game
WHERE GameID=$1;
SQL;

  $result = executeSQL($conn, $sql, $args);

  if (getResultCount($result) != 1) {
    closeDB($conn);
    return false;
  }

  $row = nextRow($result);
  closeDB($conn);
  return $row[0];

} catch (Exception $e) {
  error("getOrganiser: {$e}");
  closeDB($conn);
  return false;
}
}


/* Get a game description */
function getGameDescription($gameId) {

  $gameInfo = getGameInfo($gameId);
  if (!$gameInfo) {
    return false;
  }

  return $gameInfo["description"];

}


/* Test is $userId is the game organiser */
function isOrganiser($userId, $gameId) {
  return $userId == getOrganiser($gameId);
}


/* Set the confirmation status of a participant */
function setConfirmed($gameId, $userId, $confirmed) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Update the table */
  $args = array($gameId, $userId, (int) $confirmed);
  $sql = <<<SQL
UPDATE Joined
SET confirmed=$3
WHERE gameID=$1
    AND userID=$2;
SQL;

  $result = executeSQL($conn, $sql, $args);
  if (getUpdateCount($result) != 1) {
    /* No record to update */
    closeDB($conn);
    return false;
  }

  closeDB($conn);
  return true;

} catch(Exception $e) {
  error("setConfirmed: {$e}");
  closeDB($conn);
  return false;
}
}


/* Confirm a user */
function confirmUser($gameId, $userId) {
  return setConfirmed($gameId, $userId, true);
}


/* Unconfirm a user */
function unconfirmUser($gameId, $userId) {
  return setConfirmed($gameId, $userId, false);
}
?>
