<?php

include '../database/profile.php';
include '../database/session.php';

/* Test profile updating and retrieval */
function testProfile() {
  $fail = false;

  /* Try to get a profile that doesn't exist */
  if (getProfile(-1) != -1) {
    $fail = true;
    echo 'testProfile--Bad return value for a $userId that doesn\'t exist.\n';
  }

  /* Now log in with a user that does exist */
  $userId = login("ryan", "asdfasdf");
  $_SESSION['userId'] = $userId;

  /* Did we successfully get the profile? */
  $profile = getProfile($userId);
  if (gettype($profile) != "array") {
    $fail = true;
    echo "testProfile--Could not retrieve profile.\n";
  }

  /* Test updating the profile */
  $oldloc = $profile["location"];
  $profile["location"] = "Nowheresville";
  if (!updateProfile($profile)) {
    $fail = true;
    echo "testProfile--Update failed.\n";
  }

  /* Get the profile again to make sure the update stuck */
  $profile = getProfile($userId);
  if (gettype($profile) != "array") {
    $fail = true;
    echo "testProfile--Could not retrieve profile after updating.\n";
  }

  /* Test that the value stuck */
  if ($profile["location"] != "Nowheresville") {
    $fail = true;
    echo "testProfile--Update did not stick.\n";
  }

  /* Update it again with the old value */
  $profile["location"] = $oldloc;
  if (!updateProfile($profile)) {
    $fail = true;
    echo "testProfile--Second update failed.\n";
  }

  return !$fail;
}


/* Test profile retrieval and updating */
if (testProfile()) {
  echo ("testProfile--All passed.\n");
}

?>
