<script>
    function redirectGameSetup() {
        window.location.replace("https://www.codybutterfield82.epizy.com/gameSetup.php");
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
        echo "Need a unique username";
    } else {
        $sqlCheckSalt = "SELECT salt FROM Users";
        $resultCheckSalt = $conn->query($sqlCheckSalt);

        do {
            $salt = bin2hex(random_bytes(2));
            $existingSalt = "";
            while ($row = $resultCheckSalt->fetch_assoc()) {
                if ($salt == $row["salt"]) {
                    $existingSalt = $salt;
                    break;
                }
            }
        } while ($existingSalt == $salt);

        $hashedPass = hash('sha256', $password . $salt);
        $sqlCreateAcct = "INSERT INTO Users (username, password, salt) VALUES ('" . $username . "', '" . $hashedPass . "', '" . $salt . "')";
        if ($conn->query($sqlCreateAcct) === TRUE){
            $_SESSION["username"] = $username;
            echo '<script type="text/javascript">',
             'redirectGameSetup();',
             '</script>';
        } else {
            echo "Error: " . $sqlCreateAcct . "<br>" . $conn->error;
        }
    }
    $conn->close();
?>