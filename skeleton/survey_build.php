<?php
    // execute the header script:
    require_once "header.php";

    // create a basic html and form on the page
    if(!isset($_SESSION['loggedInSkeleton']))
    {
        // user isn't logged in, display a message saying they must be:
	    echo "You must be logged in to view this page.<br>";
    }
    else
    {
        // create a connection to the database and store into $connection variable
        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        
        // check if the cocnnection is successful
        if(!$connection)
        {
            // connection not successful show error message
            die("Connection failed: " . $mysqli_connect_error);
        }

        if(isset($_GET['question_id']) && isset($_SESSION['loggedInSkeleton']))
        {
            $question_id = $_GET['question_id'];
            // delete the answers of the corresponding questions from the answers table
            $sql = "DELETE FROM responses WHERE question_id='$question_id';";

            // this query returns true/false, test for the boolean value
			if(mysqli_query($connection, $sql))
			{
                // delete question from questions table
                $sql = "DELETE FROM questions WHERE question_id='$question_id';";

                // this query returns true/false, test for the boolean value
                if(mysqli_query($connection, $sql))
                {
                    // show a message to the user
				    echo "The row has been deleted <a href='survey_build.php?survey_id={$_GET['survey_id']}&survey_title={$_GET['survey_title']}'>Click here</a>";
                }
                else
                {
                    die("Error deleting row: " . mysqli_error($connection));
                }
            }
            else
            {
                die("Error deleting row: " . mysqli_error($connection));
            }
        }
        else
        {
            // print the survey title of the survey on the browser
            echo "<h3>{$_GET['survey_title']}</h3>";
            
            echo "<table>";
            echo "<tr><th>Question no.</th><th>Question</th><th>Question Type</th><th>Edit</th><th>Delete</th></tr>";
            
            // query to get all the questions already existing in the database with that survey_id
            $query = "SELECT * FROM questions where survey_id = '{$_GET['survey_id']}'";

            // this query returns data, store into a variable
            $result = mysqli_query($connection, $query);

            // how many rows came back?
            $n = mysqli_num_rows($result);

            // show the questions if data is retrieved
            if($n > 0)
            {
                // loop over all records to output them into the table
                for($i = 0; $i < $n; $i++)
                {
                    //fetch the row as an associative array
                    $row = mysqli_fetch_assoc($result);
                    $question_no = $i + 1;


                    // add question into the table
                    echo <<<_END

                    <tr>
                        
                        <td>Q$question_no</td>
                        <td>{$row['question']}</td>
                        <td>{$row['question_type']}</td>
                        <td>
                            <form action="question.php?survey_id={$_GET['survey_id']}&survey_title={$_GET['survey_title']}" method="POST">
                                <input type='submit' name='edit' value='Edit'>
                                <input type='hidden' name='question_id' value='{$row['question_id']}'>
                            </form>
                        </td>
                        <td><a href="survey_build.php?survey_id={$_GET['survey_id']}&survey_title={$_GET['survey_title']}&question_id={$row['question_id']}"><input type='button' name='delete' value='Delete'></td>
                    </tr>
_END;
                }

            }
            else
            {
                echo <<<_END

                    <tr>
                        
                        <td colspan=5 >There are no question in this survey</td>
                        
                    </tr>
_END;
            }
            echo "</table>";
            echo "<a href='question.php?survey_id={$_GET['survey_id']}&survey_title={$_GET['survey_title']}&new_question=true'><input type='button' name='op_question' value='New Question'>";
        }
        // we're finished with the database, close the connection:
	    mysqli_close($connection);
    }
    
    
?>