<html>
<head>
	<?php include 'header.php'; ?>
	<title>Results</title>
	<link rel = "stylesheet" href = "../css/display.css">
	
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
<legend align = "center"><b>Results</b></legend>
<form action = "logOut.php" method = "post" id = "logOut">
			<input type = "submit" value = "Log Out">
</form>

<form action = "home.php" method = "post" id = "home">
		<input type = "submit" value = "Home">
</form>
<?php

//function for displaying a result from sql as a list
function display($result)
{
	if($result)
	{
		echo '<ul>';
		
		//while there is still data to display
		while($row = mysql_fetch_row($result))
		{
			//get the data in variables
			$ISBN = $row[0];
			$title = $row[1]; 
			$author = $row[2];
			$reserved = $row[3];
			
			//output to browser
			echo '<li>';
			echo $title;
			echo '<br>';
			echo $author;
			echo '<br>';
			echo $ISBN;
			echo '<br>';

			//if not reserved show a button for reserving
			if($reserved == 'N')
			{
				echo '<form action = "display.php" method = "post">';
				echo '<input type = "hidden" value = "'.$ISBN.'" name = "isbn">';
				echo '<input type = "submit" value = "Reserve" name = "submitRes">';
				echo '</form>';
			}
			else
			{
				echo 'Not available for reservation<br><br>';
			}
			echo '</li><br>';
		}
		
		echo '</ul>';
	}
	else
	{
		echo mysql_error();
	}
}

//if a reservation has been requested (from the reservation button)
if(isset($_POST['submitRes']))
{
	$ISBN = $_POST['isbn'];
	$username = $_SESSION['username'];
	
	//update books to mark it as reserved
	$result = mysql_query("update books
							set Reserved = 'Y'
							where ISBN = '$ISBN'");
	
	//add a new row to reservations
	$result1 = mysql_query("insert into reservations (ISBN,Username,ReservedDate)
							values ('$ISBN','$username',now())");
					
	//if both were successful inform the user
	if($result && $result1)
	{
		echo 'Reserved for' . $username;
	}
	else
	{
		echo mysql_error();
	}
}

//if searching by book title
if(isset($_POST['submitSearchTitle']))
{
	//make the search term a session variable(for paging)
	$_SESSION['searchParam'] = $_POST['searchTitle'];
	
	//find how many results there will be
	$result = mysql_query("select count(ISBN) from books
							where BookTitle like '%$_SESSION[searchParam]%'");
	
	$row = mysql_fetch_row($result);
	
	//make it a session variable
	$_SESSION['amount'] =  $row[0];
}
//if searching by author
elseif(isset($_POST['submitSearchAuth']))
{ 
	//make the search term a session variable(for paging)
	$_SESSION['searchParam'] = $_POST['searchAuthor'];
	
	//find how many results there will be
	$result = mysql_query("select count(ISBN) from books
							where Author like '%$_SESSION[searchParam]%'");
							
	$row = mysql_fetch_row($result);
	
	//make it a session variable
	$_SESSION['amount'] =  $row[0];
}
//if searching by category
elseif(isset($_POST['submitSearchCat']))
{
	//make the search term a session variable(for paging)
	$_SESSION['searchParam'] = $_POST['cat'];
	
	//find how many results there will be
	$result = mysql_query("select count(ISBN) from books
							join categories on CategoryID = Category
							where CategoryDescription = '$_SESSION[searchParam]' ");
							
	$row = mysql_fetch_row($result);
	
	//make it a session variable
	$_SESSION['amount'] =  $row[0];
}

//amount of results
$amount = $_SESSION['amount'];

//amount that can be shown per page
$limit = 5;
	
//how many pages are needed to show all results
$pages = ceil($amount/$limit);
	
//if current page is set
if(isset($_GET['currentpage']))
{	
	//get the curent page number and cast to int in case of the link being changed
	$currentpage = (int)$_GET['currentpage'];
}
else
{
	//if current page is not set then be on the first page
	$currentpage = 1;
}

//if current page has exceeded the max number of pages set it to the max
if($currentpage > $pages)
{
	$currentpage = $pages;
}

//current page has exceeded the min number of pages set it to the min
if($currentpage < 1)
{
	$currentpage = 1;
}

//find the offset (where the sql result starts)
$offset = ($currentpage-1)*$limit;

//if searching for the firs time or if viewing other pages of results query the database with the limit then display the result 
if(isset($_POST['submitSearchTitle']) || $_SESSION['query1'] == true)
{		
	$result = mysql_query("select ISBN, BookTitle, Author, Reserved from books 
							where BookTitle like '%$_SESSION[searchParam]%' LIMIT $offset,$limit");
	display($result);
	$_SESSION['query1'] = true;
}
elseif(isset($_POST['submitSearchAuth']) || $_SESSION['query2'] == true)
{		
	$result = mysql_query("select ISBN, BookTitle, Author, Reserved from books 
							where Author like '%$_SESSION[searchParam]%' LIMIT $offset,$limit");
	display($result);
	$_SESSION['query2'] = true;
}
elseif(isset($_POST['submitSearchCat']) || $_SESSION['query3'] == true)
{		
	$result = mysql_query("select ISBN, BookTitle, Author, Reserved from books
							join categories on CategoryID = Category
							where CategoryDescription = '$_SESSION[searchParam]' LIMIT $offset,$limit");
	display($result);
	$_SESSION['query3'] = true;
}

//showing the links between pages
$links = 3;

//if not on first page show the back links
if($currentpage > 1)
{
	//echo a link using the this php page + the current page = 1 this link will always bring you back to 1
	echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1'><<</a> ";
	
	//the next link brings you to the previous page
	$prevpage = $currentpage - 1;
	
	echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage'><</a> ";
}	

//loop to show direct page link in between
for ($i = ($currentpage - $links); $i < (($currentpage + $links) + 1); $i++) 
{
	//if i is over 0 and less then or equal to the amount of pages show the direct links
	if (($i > 0) && ($i <= $pages))
	{
		if ($i == $currentpage) 
		{
			//current page is in bold
			echo " [<strong>$i</strong>] ";
		}
		else
		{	
			//show links to other pages
			echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$i'>$i</a> ";
		} 
	} 
}

//if not on last page show links to next page and to final page
if ($currentpage != $pages) 
{
	$nextpage = $currentpage + 1;
	echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage'>></a> ";
	echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$pages'>>></a> ";
}


?>

</fieldset>
</body>
<?php include 'footer.php'; ?>
</html>
