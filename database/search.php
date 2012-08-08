<?php

require_once 'dbinterface.php';


/* Search */
function search($criteria, $numResults, $offset) {
try {

  if (!$conn = connectDB()) {
    return false;
  }

  $userId = $_SESSION['userId'];
  if (!$userId) {
    return false;
  }

  /* Run the search stored proc */
  $args = array(
    array($criteria["sportID"], $criteria["startDate"],
          $criteria["endDate"], $criteria["location"]),
    $userId, $numResults, $offset,
  );
  $sql = <<<SQL
SELECT gameID, resultRank, totalResults
FROM search($1, $2, $3, $4)
ORDER by resultRank;
SQL;
  
  $result = executeSQL($conn, $sql, $args);
  if (!$result) {
    closeDB($conn);
    return false;
  }

  $ret = array();
  while ($row = nextRow($result)) {
    array_push($ret, $row[0]);
  }

  closeDB();
  return $ret;

} catch (Exception $e) {
  error("search: {$e}");
  closeDB($conn);
  return false;
}
}





/* Get a list of relevant games */
function suggestGames($numGames) {
  $criteria = array(
    "sportID" => null,
    "startDate" => null,
    "endDate" => null,
    "location" => null,
  );
  return search($criteria, $numGames, 0);
}



?>
