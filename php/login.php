<!DOCTYPE html>
<html>

<head>
	<?php include 'header.php'; ?>
	<title>Login</title>
	<link rel = "stylesheet" href = "../css/login.css">
</head>

<body>
<?php
	session_start();
	
	if(isset($_POST['submitLog']))
	{
		require_once 'connect.php';
	
		$usernameEnt = $_POST["username"];
		$passwordEnt = $_POST["password"];
	
		$result = mysql_query("select Username from users where username = '$usernameEnt' and Password = $passwordEnt");
		
		if($result)
		{
			$row = mysql_fetch_row($result);
	
			if($row == false)
			{
				echo "Username or Password did not match our records. Please try again";
			}
			else
			{	
				$usernameDb = $row[0];
			
				$_SESSION['username'] = $usernameDb;
				
				header("Location: home.php");
				exit;
			}
		}
		else
		{
			echo "Username or Password did not match our records. Please try again";
		}


		mysql_close($db);
	}	
?>		
	
	<fieldset>
	<legend align = "center"><h3>Please Login to access the library database</h3></legend>
	
	<form action = "login.php" method = "post" />
		<p>Username: <input type = "text" name = "username" required></p>
		<p>Password: <input type = "password" name = "password" required></p>
		<input type = "submit" value = "Login" name = "submitLog"/>
	</form>
	
	<h3>Don't have an account?</h3>
	
	<a href ="register.php">Register Now.</a>
	</fieldset>
</body>

<?php include 'footer.php'; ?>

</html>