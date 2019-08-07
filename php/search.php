<html>
<head>
	<?php include 'header.php'; ?>
	<title>Search</title>
	<link rel = "stylesheet" href = "../css/search.css">
</head>

<body>

<?php
require_once 'connect.php';
session_start();

//session variables used in pagin on display.php
$_SESSION['query1'] = false;
$_SESSION['query2'] = false;
$_SESSION['query3'] = false;

if (!isset($_SESSION['username']) || empty($_SESSION['username'])) 
{
	header("Location: login.php");
	exit();
}
else
{
	?>
	<fieldset>
	<legend align = "center"><b>Search</b></legend>
	
	<form action = "logOut.php" method = "post" id = "logOut">
		<input type = "submit" value = "Log Out"><br>
	</form>
	
	<form action = "home.php" method = "post" id = "home">
		<input type = "submit" value = "Home">
	</form>
	
	<p>
	<form action = "display.php" method = "post">
		<div id = "box"><input type = "search" name = "searchTitle" placeholder = "Title"></div>
		<div id = "button"><input type = "submit" value = "Search by Book Title" name = "submitSearchTitle"></div>
	</form>
	</p>
	
	<br><br>
	
	<p>
	<form action = "display.php" method = "post">
		<div id = "box"><input type = "search" name = "searchAuthor" placeholder = "Author"></div> 
		<div id = "button"><input type = "submit" value = "Search by Author" name = "submitSearchAuth"></div>
	</form>
	</p>
	
	<br><br>
	
	<p>
	<div id = "box">
	<?php
	//get and display categories in drop down list
		$result = mysql_query("select CategoryDescription from categories");
		
		echo '<select form = "catForm" name = "cat" >';
		while($row = mysql_fetch_row($result))
		{	
			echo '<option value="';
			echo (htmlentities($row[0]));
			echo '">';
			echo (htmlentities($row[0]));
			echo '</option>';
		}
		echo'</select>';
	?>
	</div>
	<form action = "display.php" method = "post" id = "catForm">
		<div id = "button"><input type = "submit" value = "Search by Category" name = "submitSearchCat"></div>
	</form>
	</div>
	</p>
	</legend>
	<?php
}
?>

<?php include 'footer.php'; ?>
</body>
</html>