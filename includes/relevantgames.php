<?php

# Database code to pull the list of relevant games for $userid

?>

<div id="gamelist">
<h3>Relevant Games</h3>

<ul>
<?php

# loop through all relevant games
# set the vars
# include gamebox

echo '<li>';
$sport = "hockey";
$time = "Friday";
$loc = "The rink";
$gameid = "1234";
include 'includes/gamebox.php';
echo '</li>';

?>
</ul>

<a href="profile.php">Fill out your profile for better recommendations</a>
</div>
