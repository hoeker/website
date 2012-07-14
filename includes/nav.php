<nav>
<ul>
<li><a href="main.php">Main</a></li>
<li><a href="organise.php">Organise a Game</a></li>
<li><a href="search.php">Search</a></li>

<?php

echo <<<HTML1
<li><a href="friends.php?userid={$userid}">Friends</a></li>
<li><a href="profile.php?userid={$userid}">Profile</a></li>
HTML1;

if ($admin == "t") {
  echo <<<HTML2
<li><a href="admin.php">Admin</a></li>
HTML2;

}

?>

<li><a href="logout.php">Logout</a></li>
</ul>
</nav>

