<?php

// Things to notice:
// You need to add code to this script to implement the admin functions and features
// Notice that the code not only checks whether the user is logged in, but also whether they are the admin, before it displays the page content
// You can implement all the admin tools functionality from this script, or...

// execute the header script:
require_once "header.php";

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
		// admin is logged in show him the tools
		echo <<<_END

		<table>
			<tr><td><a href="admin_user.php" >Users</a></td></tr>
			<tr><td><a href="surveys_manage.php" >Surveys</a></td></tr>
		</table>
_END;
	}
	else
	{
		// diplay the message if the user logged in is not the admin
		echo "You don't have permission to view this page...<br>";
	}
}

// finish off the HTML for this page:
require_once "footer.php";
?>