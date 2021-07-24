<?php

// Things to notice:
// This file is the first one we will run when we mark your submission
// Its job is to: 
// Create your database (currently called "skeleton", see credentials.php)... 
// Create all the tables you will need inside your database (currently it makes a simple "users" table, you will probably need more and will want to expand fields in the users table to meet the assignment specification)... 
// Create suitable test data for each of those tables 
// NOTE: this last one is VERY IMPORTANT - you need to include test data that enables the markers to test all of your site's functionality

// read in the details of our MySQL server:
require_once "credentials.php";

// We'll use the procedural (rather than object oriented) mysqli calls

// connect to the host:
$connection = mysqli_connect($dbhost, $dbuser, $dbpass);

// exit the script with a useful message if there was an error:
if (!$connection)
{
	die("Connection failed: " . $mysqli_connect_error);
}
  
// build a statement to create a new database:
$sql = "CREATE DATABASE IF NOT EXISTS " . $dbname;

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql)) 
{
	echo "Database created successfully, or already exists<br>";
} 
else
{
	die("Error creating database: " . mysqli_error($connection));
}

// connect to our database:
mysqli_select_db($connection, $dbname);


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////// DROP TABLES ////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// if there's an old version of our table, then drop it:
	$sql = "DROP TABLE IF EXISTS responses";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "Dropped existing table: responses<br>";
	} 
	else 
	{	
		die("Error checking for existing table: " . mysqli_error($connection));
	}

// if there's an old version of our table, then drop it:
	$sql = "DROP TABLE IF EXISTS questions";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "Dropped existing table: questions<br>";
	} 
	else 
	{	
		die("Error checking for existing table: " . mysqli_error($connection));
	}

// if there's an old version of our table, then drop it:
	$sql = "DROP TABLE IF EXISTS surveys";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "Dropped existing table: surveys<br>";
	} 
	else 
	{	
		die("Error checking for existing table: " . mysqli_error($connection));
	}


// if there's an old version of our table, then drop it:
	$sql = "DROP TABLE IF EXISTS users";

	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "Dropped existing table: users<br>";
	} 
	else 
	{	
		die("Error checking for existing table: " . mysqli_error($connection));
	}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////// CREATE TABLES & INSERT DATA SECTION/////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////// USERS TABLE ///////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////


// if there
// make our table:
// i have assigned size of 13 to tel_number as the numbers are 11 char long and if it is international number it will require country code
$sql = "CREATE TABLE users (username VARCHAR(16), password VARCHAR(16),first_name VARCHAR(16), last_name VARCHAR(16),
dob DATE, tel_number VARCHAR(13), email VARCHAR(64), PRIMARY KEY(username))";

// no data returned, we just test for true(success)/false(failure):
if (mysqli_query($connection, $sql)) 
{
	echo "Table created successfully: users<br>";
}
else 
{
	die("Error creating table: " . mysqli_error($connection));
}

//////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// USERS TABLE INSERT DATA ///////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
// put some data in our table:
// row1
$usernames[] = 'barrym'; $passwords[] = 'letmein'; $first_name[] = 'barry'; $last_name[] = 'm'; $dob[] = '1985-05-22'; $telNumber[] = '7665325226'; $emails[] = 'barry@m-domain.com'; 
// row2
$usernames[] = 'mandyb'; $passwords[] = 'abc123'; $first_name[] = 'mandy'; $last_name[] = 'b'; $dob[] = '1990-03-15'; $telNumber[] = '5487956358'; $emails[] = 'webmaster@mandy-g.co.uk';
// row3
$usernames[] = 'timmy'; $passwords[] = 'secret95'; $first_name[] = 'timmy'; $last_name[] = 'lassie'; $dob[] = '1995-10-06'; $telNumber[] = '7845895125'; $emails[] = 'timmy@lassie.com';
// row4
$usernames[] = 'briang'; $passwords[] = 'password'; $first_name[] = 'brian'; $last_name[] = 'g'; $dob[] = '1966-11-26'; $telNumber[] = '7785415625'; $emails[] = 'brian@quahog.gov';
// row5
$usernames[] = 'a'; $passwords[] = 'test'; $first_name[] = 'a'; $last_name[] = 'z'; $dob[] = '1979-02-13'; $telNumber[] = '7745587152'; $emails[] = 'a@alphabet.test.com';
// row6
$usernames[] = 'b'; $passwords[] = 'test'; $first_name[] = 'b'; $last_name[] = 'y'; $dob[] = '1993-12-21'; $telNumber[] = '7445895824'; $emails[] = 'b@alphabet.test.com';
// row7
$usernames[] = 'c'; $passwords[] = 'test'; $first_name[] = 'c'; $last_name[] = 'x'; $dob[] = '1988-09-02'; $telNumber[] = '7442589512'; $emails[] = 'c@alphabet.test.com';
// row8
$usernames[] = 'd'; $passwords[] = 'test'; $first_name[] = 'd'; $last_name[] = 'w'; $dob[] = '1950-04-29'; $telNumber[] = '7860616852'; $emails[] = 'd@alphabet.test.com';
// row9
$usernames[] = 'admin'; $passwords[] = 'secret'; $first_name[] = 'Admin'; $last_name[] = 'Admin'; $dob[] = '1950-04-29'; $telNumber[] = '7812458416'; $emails[] = 'admin@admin.com';

// loop through the arrays above and add rows to the table:
for ($i=0; $i<count($usernames); $i++)
{
	///////////////////////////////////////////////// THE INSERT SQL QUERY ///////////////////////////////////////////////////////////////////
	$sql = "INSERT INTO users (username, password, first_name, last_name, dob, tel_number, email) VALUES ('$usernames[$i]', 
	'$passwords[$i]', '$first_name[$i]', '$last_name[$i]', '$dob[$i]', '$telNumber[$i]', '$emails[$i]')";
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "row inserted<br>";
	}
	else 
	{
		die("Error inserting row: " . mysqli_error($connection));
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////// SURVEYS TABLE ////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// if there
	// make our table:
	// i have assigned size of 13 to tel_number as the numbers are 11 char long and if it is international number it will require country code
	$sql = "CREATE TABLE surveys (survey_id INT NOT NULL AUTO_INCREMENT, title VARCHAR(64), welcome_message VARCHAR(1000), username VARCHAR(16), number_of_responses INT, active BOOLEAN, PRIMARY KEY(survey_id), FOREIGN KEY(username) REFERENCES users(username))";
	
	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "Table created successfully: surveys<br>";
	}
	else 
	{
		die("Error creating table: " . mysqli_error($connection));
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// SURVEYS TABLE INSERT DATA ////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// put some data into the table
	// row1
	$title[] = 'Films'; $welcome_message[] = 'Please answer the questions honestly.'; $active[] = true; $number_responses[] = 102;
	// row2
	$title[] = 'Computer Games'; $welcome_message[] = 'Please answer the questions honestly.'; $active[] = true; $number_responses[] = 116;
	// row3
	$title[] = 'Favourite Food'; $welcome_message[] = 'Please answer the questions honestly.'; $active[] = true; $number_responses[] = 88;

	// loop through the arrays above and add rows to the table:
	for ($i=0; $i<count($title); $i++)
	{
		
		/////////////////////////////////// THE INSERT SQL QUERY ////////////////////////////////////////////////////
		$sql = "INSERT INTO surveys (title, welcome_message, username, number_of_responses, active) VALUES 
		('$title[$i]', '$welcome_message[$i]', 'admin', '$number_responses[$i]', '$active[$i]')";
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// no data returned, we just test for true(success)/false(failure):
		if (mysqli_query($connection, $sql)) 
		{
			echo "row inserted<br>";
		}
		else 
		{
			die("Error inserting row: " . mysqli_error($connection));
		}
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////// QUESTIONS TABLE ////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// if there
	// make our table:
	// i have assigned size of 13 to tel_number as the numbers are 11 char long and if it is international number it will require country code
	$sql = "CREATE TABLE questions (question_id INT NOT NULL AUTO_INCREMENT, question VARCHAR(500), question_type VARCHAR(20), answer VARCHAR(500),
	 survey_id INT, required BOOLEAN, PRIMARY KEY(question_id), FOREIGN KEY(survey_id) REFERENCES surveys(survey_id))";
	
	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "Table created successfully: surveys<br>";
	}
	else 
	{
		die("Error creating table: " . mysqli_error($connection));
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// QUESTIONS TABLE INSERT DATA //////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	////////////////////////////////////// FILMS //////////////////////////////////////////////////

	//row 1
	$question[] = 'How old are you?'; $question_type[] = "multiple choice";
	$answer[] = array("0-10","11-20","21-30","31-40", "41-50", "51-60","61-70", "70+");
	$survey_id[] = 1;

	//row2
	$question[] = 'What is your gender?'; $question_type[] = "multiple choice";
	$answer[] = array("Male","Female","Other");
	$survey_id[] = 1;

	//row3
	$question[] = 'What is your favourite film genre?'; $question_type[] = "multiple choice";
	$answer[] = array("Action","Thriller","Horror","Comedy", "Romantic", "Sci-Fi");
	$survey_id[] = 1;

	//row4
	$question[] = 'How do you watch films?'; $question_type[] = "multiple choice";
	$answer[] = array("At the cinema", "Online", "At home", "All of the above");
	$survey_id[] = 1;

	//row5
	$question[] = 'How often do you watch films?'; $question_type[] = "multiple choice";
	$answer[] = array("Everyday","Once a week","Once a month","Once a year", "Other");
	$survey_id[] = 1;

	//row6
	$question[] = 'Where do you find out about upcoming movies?'; $question_type[] = "multiple choice";
	$answer[] = array("At the cinema", "Newspaper", "Trailers on TV", "Social Network(e.g Facebook - Youtube - Twitter)");
	$survey_id[] = 1;

	//row7
	$question[] = 'What make you decide to watch a film?'; $question_type[] = "paragraph";
	$answer[] = array("");
	$survey_id[] = 1;

	////////////////////////////////////// FILMS //////////////////////////////////////////////////

	////////////////////////////////////// COMPUTER GAMES //////////////////////////////////////////////////

	//row 1
	$question[] = 'Please choose how you play your games?'; $question_type[] = "multiple choice";
	$answer[] = array("XBOX","Computer","Playstation","Nintendo", "Phone or Tablet");
	$survey_id[] = 2;

	//row2
	$question[] = 'Choose the game that you currently play most:'; $question_type[] = "multiple choice";
	$answer[] = array("FIFA","Game of Thrones","Battlefield","Call of Duty", "GTA 5");
	$survey_id[] = 2;

	//row3
	$question[] = 'How many hours a week do you play the game that you chose earlier?'; $question_type[] = "dropdown";
	$answer[] = array("1-4","5-10","11-20","20+");
	$survey_id[] = 2;

	//row4
	$question[] = 'What do you like most about the game that you chose?'; $question_type[] = "short answer";
	$answer[] = array("");
	$survey_id[] = 2;

	//row5
	$question[] = 'How likely is that you will buy a new game console in the next 6 months?'; $question_type[] = "multiple choice";
	$answer[] = array("Very Unlikely","Unlikely","Not sure","Likely", "Very Likely");
	$survey_id[] = 2;

	//row6
	$question[] = 'If you would like to leave a feedback on a game or a game console, please write here:'; $question_type[] = "paragraph";
	$answer[] = array("");
	$survey_id[] = 2;

	////////////////////////////////////// COMPUTER GAMES //////////////////////////////////////////////////

	////////////////////////////////////// FAVOURITE FOOD //////////////////////////////////////////////////
	// put some data into the table
	// row1
	$question[] = 'Which is your favourite Restaurant?'; $question_type[] = "multiple choice";
	$answer[] = array("Abduls","Nandos","Gelatos","Popolinos");
	$survey_id[] = 3;

	// row2
	$question[] = 'What is your favourite food?'; $question_type[] = "multiple choice";
	$answer[] = array("Pizza","Chicken Grill","Curry","Burgers");
	$survey_id[] = 3;

	// row3
	$question[] = 'Which city are you based on??'; $question_type[] = "dropdown";
	$answer[] = array("Manchester","London","Birmingham","Bradford","Leeds");
	$survey_id[] = 3;

	//row4
	$question[] = 'Enter your email address.'; $question_type[] = "short answer";
	$answer[] = array("");
	$survey_id[] = 3;

	// row5
	$question[] = 'Please leave feedback.'; $question_type[] = "paragraph";
	$answer[] = array("");
	$survey_id[] = 3;

	////////////////////////////////////// FAVOURITE FOOD //////////////////////////////////////////////////

	// loop through the arrays above and add rows to the table:
	for ($i=0; $i<sizeof($answer); $i++)
	{
		$answer_row = implode("," , $answer[$i]);
		/////////////////////////////////// THE INSERT SQL QUERY ////////////////////////////////////////////////////
		$sql = "INSERT INTO questions (question_id, question, question_type, answer, survey_id, required) 
		VALUES ('$question[$i]', '$question[$i]', '$question_type[$i]', '$answer_row', $survey_id[$i], true)";
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// no data returned, we just test for true(success)/false(failure):
		if (mysqli_query($connection, $sql)) 
		{
			echo "row inserted<br>";
		}
		else 
		{
			die("Error inserting row: " . mysqli_error($connection));
		}
	}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////// ANSWERS TABLE ////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// if there
	// make our table:
	$sql = "CREATE TABLE responses (response_id INT NOT NULL AUTO_INCREMENT, response VARCHAR(500), question_id INT, survey_id INT,
	PRIMARY KEY(response_id), FOREIGN KEY(question_id) REFERENCES questions(question_id), FOREIGN KEY(survey_id) REFERENCES surveys(survey_id))";
	
	// no data returned, we just test for true(success)/false(failure):
	if (mysqli_query($connection, $sql)) 
	{
		echo "Table created successfully: responses<br>";
	}
	else 
	{
		die("Error creating table: " . mysqli_error($connection));
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////// ANSWERS TABLE INSERT DATA ////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// set the maximum execuation time of the loop to 5 minutes
	ini_set('max_execution_time', 300);
 
	// reset and declare the variables
	$question_no = 0;
	$survey_no =1;
	$change_survey_no = 0;
	echo "<br>";

	// loop through the number of answers
	for($i = 0; $i < sizeof($answer); $i++)
	{
		// loop through the number of answers store in the current index of the answer
		for($j = 0; $j < sizeof($answer[$i]); $j++)
		{
			// is current loop index equsl to the question_no?
			if($i == $question_no)
			{
				// add one to the question_no and store it into the change_survey_no varaibel
				$question_no = $question_no +1;
				$change_survey_no = $question_no;
			}
			// does change_survey_no match the statement?
			if($change_survey_no == 7 || $change_survey_no == 13)
			{
				// add one to the sruvey _no
				$survey_no = $survey_no + 1;
				$change_survey_no = $change_survey_no + 1;
			}
			// store the responses of the current index into the variabel
			$response = $answer[$i][$j];
			
			// is the response an empty string?
			if($response == "")
			{
				// replace it with this string
				$response = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do";
			}
			// generate a random number
			$times_loop = rand(5,10);

			// loop the number of times generated by the random number of generator and insert into the table
			for($k = 0; $k < $times_loop; $k++)
			{
				// store the query into the variable
				$sql = "INSERT INTO responses (response, question_id, survey_id) VALUES 
				('$response', '$question_no', '$survey_no');";

				// no data returned, we just test for true(success)/false(failure):
				if (mysqli_query($connection, $sql)) 
				{
					echo "row inserted<br>";
				}
				else 
				{
					die("Error inserting row: " . mysqli_error($connection));
				}
			}

		}
		echo "<br>";
	}
	// set the maximum execuation time of the loop back to 30 seconds
	ini_set('max_execution_time', 30);
// we're finished, close the connection:
mysqli_close($connection);
?>