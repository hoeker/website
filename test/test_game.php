<?php

include '../database/game.php';

/* Test game creation */
function testCreate() {

  $gameId = createGame(1, '2012-12-25', '9:00', 'Downtown', 'invite');
  if (!$gameId) {
    echo "testCreate--Failed to create game.\n";
    return false;
  }

  return $gameId;
}

/* Test join */
function testJoin($userId, $gameId) {

  if (!joinGame($gameId)) {
    echo "testJoin--Failed to join game.\n";
    return false;
  }

  if (!hasJoined($userId, $gameId)) {
    echo "testJoin--Join failed to stick: hasJoined.\n";
    return false;
  }

  $participants = getParticipants($gameId);
  if (!$participants
        or count($participants) != 1 
        or !in_array($userId, $participants)) {
    echo "testJoin--Join failed to stick: getParticipants.\n";
    return false;
  }

  if (!confirmUser($gameId, $userId)) {
    echo "testJoin--Failed to confirm.\n";
    return false;
  }

  $participants = getParticipants($gameId, true);
  if (!in_array($userId, $participants)) {
    echo "testJoin--Confirm failed to stick.\n";
    return false;
  }

  $participants = getParticipants($gameId, false);
  if (in_array($userId, $participants)) {
    echo "testJoin--Incorrectly returned as unconfirmd participant.\n";
    return false;
  }

  if (!unconfirmUser($gameId, $userId)) {
    echo "testJoin--Failed to unconfirm.\n";
    return false;
  }

  $participants = getParticipants($gameId, false);
  if (!in_array($userId, $participants)) {
    echo "testJoin--Unconfirm failed to stick.\n";
    return false;
  }
  if (!leaveGame($gameId)) {
    echo "testJoin--Failed to leave game.\n";
    return false;
  }

  $participants = getParticipants($gameId);
  if ($participants === false
        or count($participants) != 0
        or in_array($userId, $participants)) {
    echo "testJoin--Leaving game failed to stick.\n";
    return false;
  }

  return true;
}

/* Test getting game info */
function testGetInfo($gameId) {

  $info = getGameInfo($gameId);
  if (!$info) {
    echo "testGetInfo--Failed to get info.\n";
    return false;
  }

  return $info;
}

/* Test updating game info */
function testSetInfo($gameId, $info) {

  /* Set info */
  $info["location"] = "Nowheresville";
  if (!setGameInfo($gameId, $info)) {
    echo "testSetInfo--Failed to set info.\n";
    return false;
  }

  /* Check that it stuck */
  $info = getGameInfo($gameId);
  if (!$info) {
    echo "testSetInfo--Failed to get info.\n";
    return false;
  } elseif ($info["location"] != "Nowheresville") {
    echo "testSetInfo--Update failed to stick.\n";
    return false;
  }

  return true;
}

/*Test deleting a game */
function testDelete($gameId) {

  if (!deleteGame($gameId)) {
    echo "testDelete--Failed to delete game.\n";
    return false;
  }

  return true;
}


function testOrganiser($userId, $gameId) {

  if (getOrganiser($gameId) != $userId) {
    echo "testOrganiser--Failed to retrieve organiser: getOrganiser.\n";
    return false;
  }

  if (!isOrganiser($userId, $gameId)) {
    echo "testOrganiser--Failed to confirm organiser: isOrganiser.\n";
    return false;
  }

  return true;
}




/* Set user to an admin */
$_SESSION['userId'] = 1;


$gameId = testCreate();
if ($gameId) { $joinPass = testJoin(1, $gameId); }
if ($gameId) { $info = testGetInfo($gameId); }
if ($gameId and $info) { $orgPass = testOrganiser(1, $gameId); }
if ($gameId and $info) { $setPass = testSetInfo($gameId, $info); }
if ($gameId and $info and $setPass) { $delPass = testDelete($gameId); }

if ($gameId and $joinPass and $info and $orgPass and $setPass and $delPass) {
  echo "testGame--All passed.\n";
}

?>
