<?php

// Things to notice:
// This is the page where each user can MANAGE their surveys
// As a suggestion, you may wish to consider using this page to LIST the surveys they have created
// Listing the available surveys for each user will probably involve accessing the contents of another TABLE in your database
// Give users options such as to CREATE a new survey, EDIT a survey, ANALYSE a survey, or DELETE a survey, might be a nice idea
// You will probably want to make some additional PHP scripts that let your users CREATE and EDIT surveys and the questions they contain
// REMEMBER: Your admin will want a slightly different view of this page so they can MANAGE all of the users' surveys

// execute the header script:
require_once "header.php";

// variables
$show_templates = false;
$username = "";
$message = "";

if (!isset($_SESSION['loggedInSkeleton']))
{
	// user isn't logged in, display a message saying they must be:
	echo "You must be logged in to view this page.<br>";
}
else
{
	echo "<h3>All Surveys</h3>";

	// create a connection to the database and store into $connection variable
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

	// check if the cocnnection is successful
	if(!$connection)
	{
		// connection not successful show error message
		die("Connection failed: " . $mysqli_connect_error);
	}
	
	if(isset($_GET['change_state']) && isset($_SESSION['loggedInSkeleton']))
	{
		// change the active status
		$sql ="";
		// store the sql query depending on the active status of the survey
		if($_GET['change_state'] == 1)
		{
			// change state is true so run this query
			$sql = "UPDATE surveys SET active=false WHERE survey_id='{$_GET['survey_id']}'";
		}
		else
		{
			// change state is false so store this query into the variable
			$sql = "UPDATE surveys SET active=true WHERE survey_id='{$_GET['survey_id']}'";
		}

		// this query returns true/false, test for the boolean value
		if(mysqli_query($connection, $sql))
		{
			// is session username admin?
			if($_SESSION['username'] == 'admin')
			{
				// session username is admin, run this function
				adminTools($connection);
			}
			else
			{
				// run the showSurveys function to display all the surveys
				showSurveys($connection);
			}
			// show the templates to the logged in user
			$show_templates = true;
		}
		else
		{
			die("Error updating row: <br>" . mysqli_error($connection));
		}
	}
	// check if delete post is set and the user is logged in
	else if(isset($_POST['delete']) && isset($_SESSION['loggedInSkeleton']))
	{
		// delete responses from the table
		$sql[] = "DELETE FROM responses WHERE survey_id='{$_GET['survey_id']}';";
		// delete questions from the table
		$sql[] = "DELETE FROM questions WHERE survey_id='{$_GET['survey_id']}';";
		// delete survey from the database
		$sql[] = "DELETE FROM surveys WHERE survey_id = '{$_GET['survey_id']}';";

		// loop to run all the sql queries
		for($i = 0; $i < sizeof($sql); $i++)
		{
			// this query returns true/false, test for the boolean value
			if(mysqli_query($connection, $sql[$i]))
			{
				// the query ran successfully, jump to the next loop
				continue;
			}
			else
			{
				die("Error deleting row: " . mysqli_error($connection));
			}
		}
		
		// is session usersname admin?
		if($_SESSION['username'] == 'admin')
		{
			// run the admin tools function and pass the connection
			adminTools($connection);
		}
		else
		{
			// run the showSurveys function to display all the surveys
			showSurveys($connection);
		}
		// show the templates to the logged in user
		$show_templates = true;
	}
	// check if the post create is set and the user is logged in
	else if(isset($_POST['create']) && isset($_SESSION['loggedInSkeleton']))
	{
		// is the username passed via the post method?
		if(isset($_POST['username']))
		{
			// store the username into the variable
			$username = $_POST['username'];
		}
		else
		{
			// store the session username into the variable
			$username = $_SESSION['username'];
		}
		// store created survey into the database
		$sql =  "INSERT INTO surveys (title, welcome_message, username, active) VALUES 
		('{$_POST['title']}', '{$_POST['welcome_message']}', '$username', false);";

		// this query returns true or false
		if(mysqli_query($connection, $sql))
		{
			// is the session username = admin?
			if($_SESSION['username'] == 'admin')
			{
				// run the admin tools
				adminTools($connection);
			}
			else
			{
				// Display the surveys table and a message that survey has been created
				showSurveys($connection);
			}
			// store the message string into the variable
			$message = "<br>Survey has been created<br>";
			// set show templates to true
			$show_templates = true;
		}
		else
		{
			die("Error inserting row: " . mysqli_error($connection));
		}
	}
	// is create survey set via get method and the session username is set?
	else if(isset($_GET['create_survey']) && isset($_SESSION['username']))
	{
		// set show templates to false
		$show_templates = false;

		// store the username into the variable
		// check if the username is set using th epost method
		if(isset($_POST['username']))
		{
			// store the username into the username variable
			$username = $_POST['username'];
		}
		else
		{
			// store the session username into the variable
			$username = $_SESSION['username'];
		}

		// show create a survey form to the user
		echo <<<_END

		<form action="surveys_manage.php" method="post"> 
			<b>Create a New Survey</b><br>
            	<table>
					<tr><td>Survey/Title:</td><td><input size="30" type="text" name="title" placeholder="Enter Survey/Title" required>  </td></tr>
					<tr><td>Welcome Message:</td><td><textarea name="welcome_message" maxlength="500" placeholder="Welcome Message" cols="33"></textarea></td></tr>
				</table>
				<input type="hidden" name="username" value="$username">
                <input type="submit" name="create" value="Create">
        </form>
_END;
	}
	// check if th esession username is admin
	else if($_SESSION['username'] == 'admin')
	{
		// run the admin tools function and pass the connection
		adminTools($connection);
		// show the templates to the logged in user
		$show_templates = true;
	}
	else
	{
		// run the function to show the tables
		showSurveys($connection);
		// show the templates to the logged in user
		$show_templates = true;
	}
	// we're finished with the database, close the connection:
	mysqli_close($connection);

}

// if show templates is set to true show the templates to the user
if($show_templates)
{
	echo <<<_END
	<br>
	<br>
	<h3>Use a template</h3>
	<ul>
		<li><a href="survey_build.php?survey_id=1&survey_title=Favourite Food">Favourite Food</a></li>
		<li><a href="survey_build.php?survey_id=2&survey_title=Computer Games">Computer Games</a></li>
		<li><a href="survey_build.php?survey_id=3&survey_title=Films">Films</a></li>
		<li><a href="survey_build.php?survey_id=4&survey_title=Music">Music</a></li>
	</ul>
_END;
}

// display the message to the user
echo $message;
// finish off the HTML for this page:
require_once "footer.php";

	// function to print the table with the surveys created by the user
	function showSurveys($connection)
	{
		// create a table to show the user their surveys
		echo <<<_END
		<div>
		<table>
			<thead class="thead-dark">
				<tr>
					<th scope="col">Title</th>
					<th scope="col">Design</th>
					<th scope="col">Delete</th>
					<th scope="col">Export CSV</th>
					<th scope="col">Number of Responses</th>
					<th scope="col">Active Status</th>
					<th scope="col">Share URL</th>
				</tr>
			</thead>
_END;
		// check if the username is set via post method and the session username is admin
		if(isset($_POST['username']) && $_SESSION['username'] == 'admin')
		{
			// display the user that is selected whose survey will be displayed
			echo "<h2>User: " . $_POST['username'] . "</h2>";
			// find the survey of the user
			$query = "SELECT * FROM surveys WHERE username = '{$_POST['username']}'";
		}
		else
		{
			// find the surveys created by the user
			$query = "SELECT * FROM surveys WHERE username = '{$_SESSION['username']}'";
		}

		// this query returns data store into a variable
		$result = mysqli_query($connection, $query);

		// how many rows came back?
		$n = mysqli_num_rows($result);

		// if we got some result then show them in a table
		if($n > 0)
		{
			for($i = 0; $i < $n; $i++)
			{
				//fetch the row as an associative array
				$row = mysqli_fetch_assoc($result);

				// add the record into the table
				echo <<<_END
				<tr>
					<td>{$row['title']}</td>
					<td><a href="survey_build.php?survey_id={$row['survey_id']}&survey_title={$row['title']}" ><input type="submit" value="Design Survey"></a></td>
					<td>
						<form action="surveys_manage.php?survey_id={$row['survey_id']}" method="post">
							<input type="submit" name="delete" value="Delete">
_END;
				// check if the username is set via post method
				if(isset($_POST['username']))
				{
					// username is set so create an input type hidden and store the username
					echo "<input type='hidden' name='username' value='{$_POST['username']}'>";
				}
				echo <<<_END
				</form>
				</td>
				<td>
					<form action="export.php" method="post">
						<input type="submit" name="export" value="Export CSV">
						<input type="hidden" name="query" value="SELECT question, response, count(response) AS 'Number of responses' 
						FROM responses INNER JOIN questions ON responses.question_id = questions.question_id WHERE responses.survey_id = {$row['survey_id']}
						GROUP BY response;">
					</form>
				</td>
_END;
				// is number of responses = null?
				if($row['number_of_responses'] == null)
				{
					// then display 0 in the field
					echo "<td>0</td>";
				}
				else
				{
					//display number of responses for the survey from the retrieved data from the table
					echo "<td>{$row['number_of_responses']}</td>";
				}
				// store the url if the survey is active else display a message
				$url = "";
				$active_value = 0;
				$active = "";

				// check if the row active is true or false
				if($row['active'])
				{
					// if row active is true then store these values into the variables
					$active_value = 1;
					$active = "active";
					$url = (String) "http://localhost/PHPAssessment/skeleton/active_surveys.php?survey_id=" . $row['survey_id'] . "&ques_i=1";
				}
				else
				{
					// if row is false then store these values into the variables
					$active = "Inactive";
					$url = "The survey has to be active to display the URL.";
				}

				// create a form using the values stored in the variables
				echo <<<_END
				<td>
					<form action='surveys_manage.php?change_state=$active_value&survey_id={$row['survey_id']}' method="post">
						<input type='submit' name='status' value='$active'>
_END;
				if(isset($_POST['username']) && $_SESSION['username'] == 'admin')
				{
					echo "<input type='hidden' name='username' value='{$_POST['username']}'>";
				}
				echo <<<_END
				<td>$url</td>
				</form>
				</td>
_END;
				echo "</tr>";
			}
			echo "</table>";
		}
		else
		{
			echo <<<_END

				<tr>
					<td colspan="7">You do not have any surveys</td>
				</tr>
			</table>
_END;

		}
		// create a button to create a new survey usgin the method post
		echo <<<_END
		<form action='surveys_manage.php?create_survey=true' method='post'>
		<input type='submit' name='create_survey' value='Create a Survey'>
_END;
		// is the username set using the post method
		if(isset($_POST['username']))
		{
			// store the username into the input type hidden to pass to the next page
			echo "<input type='hidden' name='username' value='{$_POST['username']}'>";
		}
		echo "</form>";
	}

	// function to create admin tools to display when admin is logged in the dropdown menu of the user to be specific
	function adminTools($connection)
	{
		// show admin tools
		// is admin logged in?
		if($_SESSION['username'] == 'admin')
		{
			// store the query that you want to run
            $query = "SELECT username FROM users;";

            // this query retrieves information
            $result = mysqli_query($connection, $query);

            // how many rows came back?
            $n = mysqli_num_rows($result);

            // check if any record has been retrieved
            if($n > 0)
            {
                // open the form to create a dropdown for usernames to select and post the values
                echo <<<_END
                <form action='surveys_manage.php' method='post'>
                <h4>Users List</h4>
                <select name='username'>;
                <option value='Select' selected disabled>Select</option>
_END;
                for($i = 0; $i < $n; $i++)
                {
                    //fetch the row as an associative array
                    $row = mysqli_fetch_assoc($result);
                    
                    // create options for the dropdown
                    echo "<option value='{$row['username']}'>{$row['username']}</option>";
                }
                echo "</select>";
                echo "<input type='submit' name='select' value='Select'>";
                echo "</form>";

                // show surveys and pass the connection
                showSurveys($connection);
            }
		}
	}
?>