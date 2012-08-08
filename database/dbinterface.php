<?php

$dbErrFile = "php://stdout";

function connectDB() {
  $connstr = "host=dbsrv1 dbname=csc309g9 user=csc309g9 password=ohs7ohd4";
  if (!$conn = pg_connect($connstr)) {
    return false;
  }

  return $conn;
}

function closeDB($conn) {
  return pg_close($conn);
}

function executeSQL($conn, $sql, $parameters) {
  return pg_query_params($conn, $sql, $parameters);
}

function getResultCount($result) {
  return pg_num_rows($result);
}

function getUpdateCount($result) {
  return pg_affected_rows($result);
}

function nextRow($result) {
  return pg_fetch_row($result);
}

function error($str) {
  error_log($str, 3, $dbErrFile);
}


?>
