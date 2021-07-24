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

// declare the variables to store information
$account['username']="";
$account['password']="";
$account['first_name']="";
$account['last_name']="";
$account['dob']="";
$account['tel_number']="";
$account['email']="";

// should we show the set profile form?:
$show_account_form = false;
// message to output to user:
$message = "";

// check if the user is logged in
if (!isset($_SESSION['loggedInSkeleton']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
else
{
	// check if the username is set using get method
	if(isset($_GET["username"]))
	{
		// username is set using get method, store the value into the variable
		$username = $_GET["username"];
	}
	// username is not set using the get method
	else
	{
		// read the session username and store it into the variables username
		$username = $_SESSION["username"];
	}
	
	// now read their profile data from the table...
	// connect directly to our database (notice 4th argument):
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	
	// if the connection fails, we need to know, so allow this exit:
	if (!$connection)
	{
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	// check for a row in our profiles table with a matching username:
	$query = "SELECT * FROM users WHERE username='$username'";
	
	// this query can return data ($result is an identifier):
	$result = mysqli_query($connection, $query);
	
	// how many rows came back? (can only be 1 or 0 because username is the primary key in our table):
	$n = mysqli_num_rows($result);
		
	// if there was a match then extract their profile data:
	if ($n > 0)
	{
		// use the identifier to fetch one row as an associative array (elements named after columns):
		$account = mysqli_fetch_assoc($result);
	}
	
	// show the set profile form:
	$show_account_form = true;

	// we're finished with the database, close the connection:
	mysqli_close($connection);
	
}

// show the account form if the variable is set to true
if ($show_account_form)
{
	// display user account data
	echo <<<_END
	<b>Profile Data: </b><br>
	<table>
		<tr><td>First name:</td><td>{$account['first_name']}</td></tr>
		<tr><td>Last name:</td><td>{$account['last_name']}</td></tr>
		<tr><td>Date of Birth:</td><td>{$account['dob']}</td></tr>
		<tr><td>Tel.:</td><td>{$account['tel_number']}</td></tr>
		<tr><td>Email:</td><td>{$account['email']}</td></tr>
		<tr><td>Update Account:</td>
			<td>
				<a href="account_set.php?username={$account['username']}"><input type="submit" value="Update Profile"></a>
				<br>
				<a href="update_password.php?username={$account['username']}"><input type="submit" value="Update Password"></a>
			</td>
	</table>
_END;
}

// display our message to the user:
echo $message;

// finish of the HTML for this page:
require_once "footer.php";
?>