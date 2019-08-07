<html>
<head>
	<?php include 'header.php'; ?>
	<title>Register</title>
	<link rel = "stylesheet" href = "../css/register.css">
</head>
<body>

<?php
if(isset($_POST['submitReg']))
{	
	require_once 'connect.php';
	
	//for validation
	$required = array('username', 'password', 'firstName', 'surname', 'address1', 'address2', 'city', 'tel', 'mobile');

	//for holding error messages
	$error = false;//any error
	$error1 = false;//missing field
	$error2 = false;//passwords don't match
	$error3 = false;//password length
	$error4 = false;//tel length
	$error5 = false;//mobile length
	$error6 = false;//username taken

	//checking if all fields are supplied
	foreach($required as $field) 
	{
		if ("" == trim($_POST[$field]) ) 
		{
			$error1 = "All fields are required";
			$error = true;
		}
	}
	
	//only move on if all fields are supplied
	if($error1 == false)
	{
		//get all fields by post
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$username = $_POST['username'];
		$firstName = $_POST['firstName'];
		$surname = $_POST['surname'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$city = $_POST['city'];
		$tel = $_POST['tel'];
		$mobile = $_POST['mobile'];
		
		//validation of fields
		if($password != $password2)
		{
			$error2 = "Passwords do not match <br>";
			$error = true;
		}
		
		if(strlen($password) != 6)
		{
			$error3 = "Password must be 6 characters long <br>";
			$error = true;
		}
		
		if(strlen($tel) != 7)
		{
			$error4 = "Telephone number must be 7 characters long <br>";
			$error = true;
		}
		
		if(strlen($mobile) != 10)
		{
			$error5 ="Mobile number must be 10 characters long<br>";
			$error = true;
		}

		//if no errors occur attempt to insert data in to database
		if($error == false)
		{
			$insert = mysql_query("insert into users(Username,Password,FirstName,Surname,AddressLine1,AddressLine2,City,Telephone,Mobile)
								values ('$username','$password','$firstName','$surname','$address1','$address2','$city','$tel','$mobile')");

			//if insert returns an error
			if($insert == false)
			{
				$error = true;
				
				if(strstr(mysql_error(),"Duplicate"))
				{
					$error6 = "Username already taken<br>";
				}
				else
				{
					$error6 = mysql_error();
				}
			}
		}
	}
	
	//error reporting
	if($error == true)
	{
		echo'<fieldset>
				<legend>Error</legend>';
				
		if($error1 != false)
		{
			echo $error1;
		}
			
		if($error2 != false)
		{
			echo $error2;
		}
		
		if($error3 != false)
		{
			echo $error3;
		}
			
		if($error4 != false)
		{
			echo $error4;
		}
			
		if($error5 != false)
		{
			echo $error5;
		}
			
		if($error6 != false)
		{
			echo $error6;
		}
			
		echo' </fieldset>';
	}
	else
	{
		echo "Regiserted successfully! Please login below <br>";
		header("Location: login.php");
		exit();
	}			
}
?>

<fieldset>
<legend align = "center"><b>Register</b></legend>
	<div id = "form1">
		<form action = "register.php" method = "post">
			<p><label class = "field">Username:</label><label class = "input" for "username"><input type = "text" name = "username" required></label></p>
			<p><label class = "field">First Name:</label><input type = "text" name = "firstName" required></p>
			<p><label class = "field">Surname:</label><input type = "text" name = "surname" required></p>
			<p><label class = "field">Password: </label><input type = "password" name = "password" required></p>
			<p><label class = "field">Confirm Password:</label><input type = "password" name = "password2" required></p>
			<p><label class = "field">Address Line 1:</label><input type = "text" name = "address1" required></p>
			<p><label class = "field">Address Line 2:</label><input type = "text" name = "address2" required></p>
			<p><label class = "field">City:</label><input type = "text" name = "city" required></p>
			<p><label class = "field">Telephone:</label><input type = "tel" name = "tel" required></p>
			<p><label class = "field">Mobile:</label><input type = "tel" name = "mobile" required></p>
			<input type = "submit" name = "submitReg" value = "Register">
			<input type = "reset" name = "resetReg" value = "Reset Form">
		</form>
	</div>
</fieldset>	

<?php include 'footer.php'; ?>
</body>
</html>