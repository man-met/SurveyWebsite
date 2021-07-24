<?php

// Things to notice:
// This script is called by every other script (via require_once)
// It begins the HTML output, with the customary tags, that will produce each of the pages on the web site
// It starts the session and displays a different set of menu links depending on whether the user is logged in or not...
// ... And, if they are logged in, whether or not they are the admin
// It also reads in the credentials for our database connection from credentials.php

// database connection details:
// consists of the data required to make connection to the database
require_once "credentials.php";

// our helper functions:
// it makes sure that the data entered by the use is in correct format
// validates Strings and int's
require_once "helper.php";

// start/restart the session:
// as the sessions starts, automatically the session id cookie is created in the browser
session_start();

echo <<<_END
<!DOCTYPE html>
<html>
<head><title>A Survey Website</title></head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<body>
<div class="container">
_END;
loadStyle();

if (isset($_SESSION['loggedInSkeleton']))
{
	// THIS PERSON IS LOGGED IN
	// show the logged in menu options:

echo <<<_END
<a href='about.php'>About</a> ||
<a href='account.php'>My Account</a> ||
<a href='surveys_manage.php'>My Surveys</a> ||
<a href='active_surveys.php'>Active Surveys</a> ||
<a href='competitors.php'>Design and Analysis</a> ||
<a href='sign_out.php'>Sign Out ({$_SESSION['username']})</a>
_END;
	// add an extra menu option if this was the admin:
	if ($_SESSION['username'] == "admin")
	{
		echo " |||| <a href='admin.php'>Admin Tools</a>";
	}
}
else
{
	// THIS PERSON IS NOT LOGGED IN
	// show the logged out menu options:
	
echo <<<_END
<a href='about.php'>About</a> ||
<a href='active_surveys.php'>Active Surveys</a> ||
<a href='sign_up.php'>Sign Up</a> ||
<a href='sign_in.php'>Sign In</a>
_END;
}
echo <<<_END
<br>
<h1>2CWK50: A Survey Website</h1>
_END;

// load the style for the tables that are displayed on the browser
function loadStyle()
    {
        echo <<<_END
        <style>
            table, th, td {border: 1px solid black; align: center;}
                
                th, td {
                text-align: left;
                padding: 8px;
            }
            
            tr:nth-child(even){background-color: #f2f2f2}
            
            th {
                background-color: #b3e6ff;
                color: black;
            }
        </style>
_END;
    }
?>