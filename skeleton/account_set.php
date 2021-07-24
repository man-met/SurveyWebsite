<?php

// Things to notice:
// This script will let a logged-in user VIEW their account details and allow them to UPDATE those details
// The main job of this script is to execute an INSERT or UPDATE statement to create or update a user's account information...
// ... but only once the data the user supplied has been validated on the client-side, and then sanitised ("cleaned") and validated again on the server-side
// It's your job to add these steps into the code
// Both sign_up.php and sign_in.php do client-side validation, followed by sanitisation and validation again on the server-side -- you may find it helpful to look at how they work 
// HTML5 can validate all the account data for you on the client-side
// The PHP functions in helper.php will allow you to sanitise the data on the server-side and validate *some* of the fields... 
// There are fields you will want to add to allow the user to update them...
// ... you'll also need to add some new PHP functions of your own to validate email addresses, telephone numbers and dates

// execute the header script:
require_once "header.php";

// default values we show in the form:
// associative array for profile information
$account['username'] = "";
$account['first_name']="";
$account['last_name']="";
$account['dob']="";
$account['tel_number']="";
$account['email']="";
    
// strings to hold any validation error messages:
$new_first_name_val = "";
$new_last_name_val = "";
$new_dob_val = "";
$new_tel_number_val = "";
$new_email_val = "";
 
// should we show the set profile form?:
$show_account_form = false;
// message to output to user:
$message = "";

if (!isset($_SESSION['loggedInSkeleton']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
else //User is logged in therefore run this code
{
	// check if username is set using the get method.
	if(isset($_GET["username"]))
	{
		// username is set using get method, store it into the variable 
		$username = $_GET["username"];
	}
	// username is not set using get method so get the username sent using post method and store it into the variable
	else
	{
		$username = $_POST["username"];
	}

	// create a connection to the database and store into $connection variable
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// check if the connection is successful
	if (!$connection)
	{
		// connection not successful show error message
		die("Connection failed: " . $mysqli_connect_error);
	}

	if(isset($_POST['update']) && isset($_SESSION['loggedInSkeleton']) )
	{
		// Sanitisation
		$new_first_name = sanitise($_POST["first_name"], $connection);
		$new_last_name = sanitise($_POST["last_name"], $connection);
		$new_dob = sanitise($_POST["dob"], $connection);
		$new_tel_number = sanitise($_POST["tel_number"], $connection);
		$new_email = sanitise($_POST["email"], $connection);
		
		// SERVER-SIDE VALIDATION
		$new_first_name_val = validateString($new_first_name, 0, 16);
		$new_last_name_val = validateString($new_last_name, 0, 16);
		$new_dob_val = validateDOB($new_dob);
		$new_tel_number_val = validateString($new_tel_number, 9, 13);
		$new_email_val = validateEmail($new_email);

		// store all the validation strings into the variable $errors
		$errors =  $new_first_name_val . $new_last_name_val . $new_dob_val . $new_tel_number_val . $new_email_val;
		// check the variable $errors for any error messages
		if($errors == "")
		{
			// insert the data into the database
			$sql = "UPDATE users SET first_name='$new_first_name', last_name='$new_last_name', 
			dob='$new_dob', tel_number='$new_tel_number', email='$new_email' WHERE username='$username'";

			// this query returns true/false, test for the boolean value
			if(mysqli_query($connection, $sql))
			{
				// Display the account updated message
				$message = "<br>Account has been Updated";
			}
			else
			{
				// display the error to the user for inserting the data into the table
				die("Error inserting row: " . mysqli_error($connection));
			}
		}
		else
		{
			// store the validation error message into the variable
			$message = "Validation Error";
		}
	}

	// show users account data
	// run query to find the data of the logged in user
	$query = "SELECT * FROM users WHERE username='$username'";

	// user data is returned, store into a variable
	$result = mysqli_query($connection, $query);
	// store number of rows in a variable
	$n = mysqli_num_rows($result);
	// check if there is data to show
	if($n > 0)
	{
		// store retrieved data into the associative array
		$account = mysqli_fetch_assoc($result);
	}

	// If the user clicks the update button, all the information entered will be displayed on the forms...
	// ... including a validation error message if the data is not valid. Otherwise it will show the...
	// ... updated data on the forms.
	if (isset($_POST["first_name"])) {
		$account['first_name']=$_POST["first_name"];
	}

	if (isset($_POST["last_name"])) {
		$account['last_name']=$_POST["last_name"];
	}

	if (isset($_POST["dob"])) {
		$account['dob']=$_POST["dob"];
	}

	if (isset($_POST["tel_number"])) {
		$account['tel_number']=$_POST["tel_number"];
	}

	if (isset($_POST["email"])) {
		$account['email']=$_POST["email"];
	}

	// show the set profile form:
	$show_account_form = true;
	
	// we're finished with the database, close the connection:
	mysqli_close($connection);
}

// show the form if the variable is set to true
if ($show_account_form)
{

	echo <<<_END
	<form action="account_set.php" method="post">
	<b>Update your profile info: </b><br>
		<table>
			<tr><td>Username:</td><td><input size="30" type="text" name="username" value="{$account['username']}" readonly required>  </td></tr>
			<tr><td>First Name:</td><td><input size="30" type="text" name="first_name" minlength="1" maxlength="16" placeholder="Enter your First Name" value="{$account['first_name']}" required> $new_first_name_val </td></tr>
			<tr><td>Last Name:</td><td><input size="30" type="text" name="last_name" minlength="1" maxlength="16" placeholder="Enter your Last Name" value="{$account['last_name']}" required> $new_last_name_val </td></tr>
			<tr><td>DOB:</td><td><input size="30" type="date" name="dob" value="{$account['dob']}" required> $new_dob_val </td></tr>
			<tr><td>Tel.:</td><td><input size="30" type="number" name="tel_number" placeholder="Telephone Number" value="{$account['tel_number']}"> $new_tel_number_val </td></tr>
			<tr><td>Email address:</td><td><input size="30" type="email" name="email" maxlength="64" placeholder="Email Address" value="{$account['email']}" required> $new_email_val </td></tr>
		</table>
		<input type="submit" name="update" value="Update">
	</form>
	<a href="account.php"><input type="submit" value="Cancel"></a>
_END;

}

// display our message to the user:
echo $message;

// finish of the HTML for this page:
require_once "footer.php";
?>