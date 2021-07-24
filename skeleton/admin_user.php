<?php

// Things to notice:
// You need to add code to this script to implement the admin functions and features
// Notice that the code not only checks whether the user is logged in, but also whether they are the admin, before it displays the page content
// You can implement all the admin tools functionality from this script, or...

// execute the header script:
require_once "header.php";
// declare the variables
$message = "";

// is the user loggedin?
if (!isset($_SESSION['loggedInSkeleton']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
else
{
	// only display the page content if this is the admin account (all other users get a "you don't have permission..." message):
	if ($_SESSION['username'] == "admin")
	{
		// create a connection to the database and store into $connection variable
		$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

		// check if the cocnnection is successful
		if(!$connection)
		{
			// connection not successful show error message
			die("Connection failed: " . $mysqli_connect_error);
		}
		// is the user logged admin and is the username set using the get method?
		else if($_SESSION['username'] == "admin" && isset($_GET['username']))
		{
			// delete user query for the database
			$query = "DELETE FROM users WHERE username = '{$_GET['username']}'";

			// this query returns true/false, test for the boolean value
			if(mysqli_query($connection, $query))
			{
				// show a message to the user
				$message = "The row has been deleted";
				// run the show users table function and pass the connection and the message
				showUsersTable($connection, $message);
			}
			else
			{
				die("Error deleting row: " . mysqli_error($connection));
			}
		}
		else
		{
			// is message set using the get method?
			if(isset($_GET['message']))
			{
				// store the message into the message variable
				$message = $_GET['message'];
			}
			// run showusers table function and pass the connection and the message
			showUsersTable($connection, $message);
		}
		// we're finished with the database, close the connection:
		mysqli_close($connection);
	}
	else
	{
		// display the message to the user logged in and is not admin
		echo "You don't have permission to view this page...<br>";
	}
}


// finish off the HTML for this page:
require_once "footer.php";


// display the table with all the registered users
function showUsersTable ($connection, $display_message) 
{
    // find all users in the database
	$query = "SELECT username, first_name, last_name FROM users where username != 'admin';";

	// this query returns data store into a variable
	$result = mysqli_query($connection, $query);

	// how many rows came back?
	$n = mysqli_num_rows($result);

	// if we got some results then show them in a table
	if($n > 0)
	{
		// create a table to display the records
		echo "<table>";
		echo "<tr><th>Username</th><th>First Name</th><th>Last Name</th><th>Edit</th><th>Delete</th></tr>";
		// loop over all records to output them into the table
		for($i=0; $i<$n; $i++)
		{
			// fetch one row as an associative array
			$row = mysqli_fetch_assoc($result);
			// add the record into our table
			echo <<<_END
			<tr>
					
    			<td>{$row['username']}</td>
    			<td>{$row['first_name']}</td><td>{$row['last_name']}</td>
    			<td><a href="account.php?username={$row['username']}"><input type='button' name='edit' value='Edit'></a></td>
                <td><a href="admin_user.php?username={$row['username']}"><input type='button' name='delete' value='Delete'></td>

			</tr>
_END;
		}
		// end the table
		echo "</table>";
		// print and add new user button under the table
		echo "<a href='admin_add_user.php' ><input type='button' name='addUser' value='Add New User'></a>";
	}
	else
	{
		// display the message if no users account were found
		echo "No users account found<br>";
	}
	// display the message to the user
	echo "<br>" . $display_message;

}

?>