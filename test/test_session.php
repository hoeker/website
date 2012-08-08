<?php

include '../database/session.php';


/* Test the login() function */
function testLogin() {
  $fail = false;

  if (!login("ryan", "asdfasdf")) {
    echo "testLogin--Failed to log in successfully.\n";
    $fail = true;
  }

  if (login("ryan", "fdsafdsa")) {
    echo "testLogin--Login didn't fail with incorrect password.\n";
    $fail = true;
  }

  if (login("notarealuser", "password")) {
    echo "testLogin--Login didn't fail with incorrect username.\n";
    $fail = true;
  }

  return !$fail;
}


/* Test the signup() function */
function testSignup() {

  if (signup("ryan", "asdfasdf", "hoeker@gmail.com") != -1) {
    echo "testSignup--Username already in use, incorrect return value.\n";
    return false;
  }

  return true;
}


/* Test changing the password */
function testChangePassword() {

  if (!changePassword('asdfasdf', 'fdsafdsa')) {
    echo "testChangePassword--Failed to change password.\n";
    return false;
  }

  if (!changePassword('fdsafdsa', 'asdfasdf')) {
    echo "testChangePassword--Failed to change password back.\n";
    return false;
  }

  return true;
}


$_SESSION['userId'] = 2;

if (testLogin()) {
  echo "testLogin--All passed.\n";
}

if (testSignup()) {
  echo "testSignup--All passed.\n";
}

if (testChangePassword()) {
  echo "testChangePassword--All passed.\n";
}
?>
