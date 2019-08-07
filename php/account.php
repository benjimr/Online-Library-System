<html>
<head>
	<?php include 'header.php'; ?>
	<title>Account</title>
	<link rel = "stylesheet" href = "../css/account.css">
	<?php
	
	require_once 'connect.php';
	
	session_start();
	
	if (!isset($_SESSION['username']) || empty($_SESSION['username'])) 
	{
		header("Location: login.php");
		exit();
	}
	?>
</head>
<body>
	<fieldset>
	<legend	align = "center"><b>Reservations</b></legend>
	<form action = "logOut.php" method = "post" id = "logOut">
			<input type = "submit" value = "Log Out">
	</form>
	<form action = "home.php" method = "post" id = "home">
			<input type = "submit" value = "Home">
	</form>
	<?php
		//if removal of reservation requested attempt to remove it before loading list of reservations
		if(isset($_POST['submitRem']))
		{
			$ISBN = $_POST['isbn'];
			
			//mark the book as not reserved
			$result = mysql_query("update books
									set Reserved = 'N'
									where ISBN = '$ISBN'");
									
			//delete row from reservations
			$result1 = mysql_query("delete from reservations
									where ISBN = '$ISBN' and Username = '$_SESSION[username]'");
						
			//if both were successful inform user
			if($result && $result1)
			{
				echo "Reservation successfully removed.";
			}
			else
			{
				echo mysql_error();
			}
		}
		
		//get data from tables
		$result = mysql_query("select reservations.ISBN, reservations.ReservedDate, books.BookTitle, books.Author from reservations
								join books on reservations.ISBN = books.ISBN
								where Username like '$_SESSION[username]'");
		
		//if successful display data
		if($result)
		{
			echo '<ul>';					
			while($row = mysql_fetch_row($result))
			{
				$ISBN = $row[0];
				$date = $row[1];
				$title = $row[2];
				$author = $row[3];
				
				echo '<li>';
				echo $ISBN.'<br>';
				echo $title.'<br>';
				echo $author.'<br>';
				echo $date.'<br>';
				
				//button for removing reservation
				echo '<form action = "account.php" method = "post">';
				echo '<input type = "hidden" value = "'.$ISBN.'" name = "isbn">';
				echo '<input type = "submit" value = "Remove Reservation" name = "submitRem">';
				echo '</form>';
				echo '</li><br>';
				
		
			}
			echo '</ul>';
		}
		else
		{
			echo mysql_error();
		}
		
	?>
	</fieldset>
	<?php include 'footer.php'; ?>

</body>
</html>