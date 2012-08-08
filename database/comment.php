<?php

require_once 'dbinterface.php';


/* Add a comment to a game */
function commentGame($gameId, $commentText) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  $userId = $_SESSION['userId'];
  if (!$userId) {
    return false;
  }

  /* Add a new comment */
  $args = array($gameId, $userId, $commentText);
  $sql = <<<SQL
INSERT INTO Comment (gameID, userID, postTime, commentText)
VALUES ($1, $2, current_timestamp, $3)
RETURNING commentID;
SQL;

  $result = executeSQL($conn, $sql, $args);
  if (!getUpdateCount($result)) {
    closeDB($conn);
    return false;
  }

  $row = nextRow($result);
  $commentId = $row[0];

  closeDB($conn);
  return $commentId;

} catch (Exception $e) {
  error("commentGame: {$e}");
  closeDB($conn);
  return false;
}
}


/* Delete a comment */
function deleteCommentGame($commentId) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  /* Delete the comment */
  $args = array($commentId);
  $sql = <<<SQL
DELETE FROM Comment
WHERE commentID=$1
SQL;

  $result = executeSQL($conn, $sql, $args);
  if (getUpdateCount($result) != 1) {
    closeDB($conn);
    return false;
  }

  closeDB($conn);
  return true;

} catch (Exception $e) {
  error("deleteCommentGame: {$e}");
  closeDB($conn);
  return false;
}
}


/* Get all the comments on a game */
function getComments($gameId) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  $args = array($gameId);
  $sql = <<<SQL
SELECT c.commentID, u.userID, u.username, c.postTime, c.commentText
FROM Comment AS c
LEFT OUTER JOIN WebUser AS u
    ON u.userID=c.userID
WHERE gameID=$1
ORDER BY postTime DESC;
SQL;

  $result = executeSQL($conn, $sql, $args);
  if (!$result) {
    closeDB($conn);
    return false;
  }

  $ret = array();
  while ($row = nextRow($result)) {
    array_push($ret, array(
      "commentID" => $row[0],
      "userID" => $row[1],
      "username" => $row[2],
      "postTime" => $row[3],
      "commentText" => $row[4],
    ));
  }

  closeDB($conn);
  return $ret;

} catch (Exception $e) {
  error("getComments: {$e}");
  closeDB($conn);
  return false;
}
}


?>
