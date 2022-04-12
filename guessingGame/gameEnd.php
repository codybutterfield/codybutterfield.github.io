<?php
    session_start();
?>
<!DOCTYPE html>
<html>
<body>
    <p>You won!</p>
    <p>The number was: <?php echo $_SESSION["gameNumber"]?></p>
    <p><?php echo "You guessed the number in " . $_SESSION["guessCount"] . " guesses!"?></p>
    <p>
        <?php
            $dbservername = "sql204.epizy.com";
            $dbusername = "epiz_30809346";
            $dbpassword = "lIY5xtN9yw8p";
            $dbname = "epiz_30809346_guessing_game";
            
            $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sqlHighScore = "SELECT * FROM High_Scores ORDER BY score LIMIT 10";
            $resultHighScore = $conn->query($sqlHighScore);
            while ($row = $resultHighScore->fetch_assoc()) {
            echo "Username: " . $row["username"] . " # of Guesses: " . $row["score"] . "<br>";
            }
        ?>
    </p>
    <a href="gameSetup.php">Play Again!</a>
</body>
</html>