<?php


include '../database/comment.php';
include '../database/game.php';

$gameId = null;


function setup() {
  global $gameId;
  $gameId = createGame(1, '2013-12-25', '9:00', 'Toronto', 'invite');
  if (!$gameId) {
    echo "setup--Failed to create game.\n";
    return false;
  }

  return true;
}


function testComment() {
  global $gameId;
  $txt = "This is a brand new comment!";

  /* Getting a list of comments when there are no comments */
  $comments = getComments($gameId);
  if ($comments === false) {
    echo "testComment--Failed to get empty comments.\n";
    return false;
  }

  /* Test adding a comment */
  $commentId = commentGame($gameId, $txt);
  if (!$commentId) {
    echo "testComment--Failed to create comment.\n";
    return false;
  }

  /* Getting a non-empty list of comments */
  $comments = getComments($gameId);
  if ($comments === false) {
    echo "testComment--Failed to get empty comments.\n";
    return false;
  } elseif (count($comments) != 1) {
    echo "testComments--Wrong number of comments.\n";
    return false;
  } elseif ($comments[0]["commentText"] != $txt) {
    echo "testComments--Wrong comment text.\n";
    return false;
  }

  /* Deleting a comment */
  if (!deleteCommentGame($commentId)) {
    echo "testComment--Failed to delete comment.\n";
    return false;
  }

  /* Make sure it stuck */
  $comments = getComments($gameId);
  if ($comments === false) {
    echo "testComment--Failed to get comments the third time.\n";
    return false;
  } elseif (count($comments) != 0) {
    echo "testComment--Deleting failed to stick.\n";
    return false;
  }

  return true;
}


function teardown() {
  global $gameId;
  if (!cancelGame($gameId)) {
    echo "teardown--Failed to cancel game.\n";
    return false;
  }

  return true;
}





$_SESSION['userId'] = 2;


if (setup()) {
  
  if (testComment()) {
    echo "testComment--All passed.\n";
  }

  teardown();
}



?>
