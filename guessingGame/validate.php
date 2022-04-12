<!DOCTYPE html>
<html>
<body>
	<script>
        function redirectGameSetup() {
            window.location.replace("https://www.codybutterfield82.epizy.com/gameSetup.php");
        }
        
        function redirectLogin() {
            window.location.replace("https://www.codybutterfield82.epizy.com/login.php");
        }
    </script>
    <?php
    session_start();

    $username = $_GET["username"];
	$password = $_GET["password"];

    $dbservername = "sql204.epizy.com";
    $dbusername = "epiz_30809346";
    $dbpassword = "lIY5xtN9yw8p";
    $dbname = "epiz_30809346_guessing_game";
    
    $conn = new mysqli($dbservername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sqlCheckUser = "SELECT username FROM Users WHERE Username = '" . $username . "'";
    $resultCheckUser = $conn->query($sqlCheckUser);
    if ($resultCheckUser->num_rows > 0) {
        $sqlGetSalt = "SELECT salt FROM Users WHERE Username = '" . $username . "'";
        $resultGetSalt = $conn->query($sqlGetSalt);
        while ($rowSalt = $resultGetSalt->fetch_assoc()) {
            $salt = $rowSalt["salt"];
        }
        $hashedPass = hash('sha256', $password . $salt);
        $sqlValidateUser = "SELECT * FROM Users WHERE username = '" . $username . "' AND password = '" . $hashedPass . "'";
        $resultValidateUser = $conn->query($sqlValidateUser);
        if ($resultValidateUser->num_rows > 0) {
            $_SESSION["username"] = $username;
            echo '<script type="text/javascript">',
                 'redirectGameSetup();',
                 '</script>';
        } else {
            echo '<script type="text/javascript">',
             'redirectLogin();',
             '</script>';
        }
    } else {
        echo '<script type="text/javascript">',
             'redirectLogin();',
             '</script>';
    }

    $conn->close();
?>
</body>
</html>