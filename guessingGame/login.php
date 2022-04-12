<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>
	<form action="validate.php" method="get">
	<p>Username: <input type="text" name="username"/></p>
	<p>Password: <input type="text" name="password"/></p>

	<input type="submit" value="Login"/>
	<br>
	<a href="signUp.php">Don't have an account? Sign up here!</a>
	</form>
</body>
</html>