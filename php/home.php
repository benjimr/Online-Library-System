<html>
<head>
	<?php include 'header.php'; ?>
	<?php
		session_start();
	
		//if there is no active session make them login
		if (!isset($_SESSION['username']) || empty($_SESSION['username'])) 
		{
			header("Location: login.php");
			exit();
		}
	?>
	
	<title>Home</title>
	<link rel = "stylesheet" href = "../css/home.css";
</head>

<body>

	<fieldset>
		<legend align = "Center"><b>Welcome</b></legend>
		<form action = "logOut.php" method = "post" id = "logOut">
			<input type = "submit" value = "Log Out">
		</form>

		<nav>
			<p>
				Search for books <a href = "search.php">here</a><br>
				View reserved books <a href = "account.php">here</a>
			</p>
		</nav>
		

		
	</fieldset>
</body>

<?php include 'footer.php'; ?>

</html>


	
