<?php
  session_start();
?>
<!DOCTYPE html>
<html>
<body>
    <script>
        function redirectPage() {
            window.location.replace("https://www.codybutterfield82.epizy.com/gameEnd.php");
        }
    </script>
    <?php
        $dbservername = "sql204.epizy.com";
        $dbusername = "epiz_30809346";
        $dbpassword = "lIY5xtN9yw8p";
        $dbname = "epiz_30809346_guessing_game";
        
        $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $userGuess = $_GET["userGuess"];
        $_SESSION["userGuess"] = $userGuess;

        if(isset($_SESSION["userGuess"])) {
            $_SESSION["guessCount"] = $_SESSION["guessCount"] + 1;
            if ($userGuess < $_SESSION["gameNumber"]) {
                $_SESSION["guessFeedback"] = "Guess higher!";
            } else if ($userGuess > $_SESSION["gameNumber"]) {
                $_SESSION["guessFeedback"] = "Guess lower!";
            } else {
                $sqlAddScore = "INSERT INTO High_Scores (username, score) VALUES ('" . $_SESSION["username"] . "', " . $_SESSION["guessCount"] . ")";
                $conn->query($sqlAddScore);
                echo '<script type="text/javascript">',
                    'redirectPage();',
                    '</script>';
            }
        }
    ?>

    <p><?php echo "Last Guess: " . $_SESSION["userGuess"]?></p>
    <p><?php echo $_SESSION["guessFeedback"]?></p>
	<p>Number of guesses: <?php echo $_SESSION["guessCount"] ?></p>
  
    <form action="game.php" method="get">
        <input type="number" min="1" max="100" name="userGuess">
        <input type="submit" value="Guess"/>
	</form>
</body>
</html>