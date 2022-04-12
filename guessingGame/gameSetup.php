<?php
  session_start();
?>
<!DOCTYPE html>
<html>
<body>
<script>
  function redirectGame() {
    window.location.replace("https://www.codybutterfield82.epizy.com/game.php");
  }
</script>
<?php  
  $_SESSION["gameNumber"] = rand(1, 100);
  $_SESSION["guessCount"] = 0;
  $_SESSION["guessFeedback"] = "Make a guess!";
  echo '<script type="text/javascript">',
       'redirectGame();',
       '</script>';
?>
<a href="game.php">Play</a>
</body>
</html>