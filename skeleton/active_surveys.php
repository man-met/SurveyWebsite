<?php

    // execute the header script:
    require_once "header.php";

    // declare the variables to store the data
    $all_answers = "";
    $all_question_id = "";
    $ques_i = "";
    $sql = "";
    $number_of_survey_responses = 0;

    // check if the survey_id is set using the get method and submit is not set using the post method
    if(isset($_GET['survey_id']) && !isset($_POST['submit']))
    {
        // now read the questions corresponding to the survey id from the tables...
        // create a connection to the database and store into $connection variable
        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            
        // check if the cocnnection is successful
        if(!$connection)
        {
            // connection not successful show error message
            die("Connection failed: " . $mysqli_connect_error);
        }
        
        // query for retrieving all the question of the survey selected
        $query = "SELECT * FROM questions WHERE survey_id='{$_GET['survey_id']}';";
        
        // this query retrieves all the questions existing in the survey
        $result = mysqli_query($connection, $query);
        
        // how many rows came back
        $n = mysqli_num_rows($result);
        
        // check if any record has been retrieved
        if($n > 0)
        {
            // read the surveys data from the tables where survey_id matches...
            // store the query into the variable $query2
            $query2 = "SELECT * FROM surveys WHERE survey_id='{$_GET['survey_id']}';";

            // store the data retrieved into the variable $result2
            $result2 = mysqli_query($connection, $query2);

            // check how many rows are in the result2
            $n2 = mysqli_num_rows($result2);

            // check if the number of rows retrieved is 1 as there is only one survey with the corresponding survey_id
            if($n2 == 1)
            {
                // store the row retrieved from the table into the variable $row2
                $row2 = mysqli_fetch_assoc($result2);

                // display the survey title and the message on the browser
                echo "<h3>{$row2['title']}</h3>";
                echo "<h4>{$row2['welcome_message']}</h4>";
                echo "<form action='active_surveys.php?survey_id={$_GET['survey_id']}' method='post'>";
                $number_of_survey_responses = $row2['number_of_responses'];
            }

            // check if all answers are received via post method and store them into the variables
            if(isset($_POST['all_answers']))
            {
                // check if an answer has been submitted and the answer is not empty string
                if(isset($_POST['answer']) && $_POST['answer'] != "")
                {
                    // check if the all_answers received have empty string or not
                    if($_POST['all_answers'] == "")
                    {
                        // add the answer received into the variable all_answers
                        $all_answers = $_POST['answer'];
                        // add the question_id received into the variable all question_id's
                        $all_question_id = $_POST['question_id'];
                    }
                    else
                    {
                        // add all the answers previously submitted in other questions and the current answer submitted into the variable all_answers
                        $all_answers = $_POST['all_answers'] . "," .$_POST['answer'];
                        // add all the question_id's and the current question_id submitted into the variable all_question_id
                        $all_question_id = $_POST['all_question_id'] . "," . $_POST['question_id'];
                    }
                }
                // check if the Post variable consists of any answers and is not empty
                else if($_POST['all_answers'] == "")
                {
                    // add null to the all answers variable
                    $all_answers = "null";
                    // add the questions_id into the variable
                    $all_question_id = $_POST['question_id'];
                }
                else
                {
                    // store all the answers into the variable
                    $all_answers = $_POST['all_answers'] . ",null";
                    // add all the question_id's into the variable
                    $all_question_id = $_POST['all_question_id'] . "," . $_POST['question_id'];
                }
                
            }

            // store the question index into the variable $ques_i
            if(isset($_POST['ques_i']))
            {
                // save the question_index into the variable if received via post method
                $ques_i = $_POST['ques_i'];
            }
            else
            {
                // store the question_index number into the variable if received via get method
                $ques_i = $_GET['ques_i'];
            }
            
            // display all the questions
            for($i = 0; $i < $n; $i++)
            {
                // fetch the row as an associative array
                $row = mysqli_fetch_assoc($result);
                $question_number = $i+1;
                
                //check if the question number that has to be displayed matches the question_index
                if($question_number == $ques_i)
                {
                    // run the displayquestion function to diplay the survey question
                    displayQuestion($row,$question_number);
                    // question has been displayed to the user, exit the loop
                    break;
                }
                else
                {
                    // question number to be displayed, did not match the question_index so continue with the loop
                    continue;
                }
            }

            // store the number of questions into a hidden form to keep track on what number of question we are and how many are left
            echo "<input type='hidden' name='number_of_questions' value='$n'>";

            // is the current question the last question?
            if($n != $ques_i)
            {
                // current question is not the last question so display the next button
                echo "<input type='submit' name='next' value='Next'>";
            } 
            else
            {
                // current question is the last question, so display the submit question
                echo "<input type='submit' name='submit' value='Submit'>";
            }
            // question_index is added by one as one more question has been displayed to the user
            $ques_i = $ques_i + 1;

            // store all the information that we will need in the next page into the hidden form
            echo "<input type='hidden' name='ques_i' value='$ques_i'>";
            echo "<input type='hidden' name='all_answers' value='$all_answers'>";
            echo "<input type='hidden' name='all_question_id' value='$all_question_id'>";
            echo "<input type='hidden' name='number_of_responses' value='$number_of_survey_responses'>";
            echo "</form><br><br><br>";

            // we're finished with the database, close the connection:
	        mysqli_close($connection);
        }
        else
        {
            // the survey you clicked on, does not exist anymore so show the message
            echo "This survey does not exist anymore";
        }
        
    }
    // the survey has been submitted insert the answers into the database
    else if(isset($_POST['submit']))
    {
        // does current survey have more than one questions?
        if($_POST['number_of_questions'] != 1)
        {
            // is the answer been submitted from the form and it is not empty?
            if(isset($_POST['answer']) && $_POST['answer'] != "")
            {
                // get the last answer and store it into the variables $all_answers and $all_question_id
                $all_answers = $_POST['all_answers'] . "," .$_POST['answer'];
                $all_question_id = $_POST['all_question_id'] . "," . $_POST['question_id'];
            }
            else
            {
                // answer submitted was empty or was not set, so store null into the variable and add the question_id into the variable
                $all_answers = $_POST['all_answers'] . ",null";
                $all_question_id = $_POST['all_question_id'] . "," . $_POST['question_id'];
            }

            // divide the answers and store them into the array
            $answer = explode(",", $all_answers);
            // divide the question_id's and store them into the array
            $question_id = explode(",", $all_question_id);
        }

        // create a connection to the database and store into $connection variable
        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            
        // check if the cocnnection is successful
        if(!$connection)
        {
            // connection not successful show error message
            die("Connection failed: " . $mysqli_connect_error);
        }
        // get the survey id from the get method
        $survey_id = (int)$_GET['survey_id'];
        // insert number of responses into the table
        $number_of_survey_responses = $_POST['number_of_responses'] + 1;
        // store the query into the sql variable
        $sql = "UPDATE surveys SET number_of_responses = '$number_of_survey_responses' WHERE survey_id = '$survey_id';";

        // did the query run successfully?
        if(mysqli_query($connection, $sql))
        {
            // insert the responses into the database one by one using the for loop
            for($i = 0; $i < $_POST['number_of_questions']; $i++)
            {
                // how many number of questions were answered?
                if($_POST['number_of_questions'] != 1)
                {
                    // store this query into the variable
                    $sql = "INSERT INTO responses(response, question_id, survey_id) VALUES ('$answer[$i]', '$question_id[$i]', '$survey_id');";
                }
                else
                {
                    // store the values received via post method into the variables
                    $answer = $_POST['answer'];
                    $question_id = $_POST['question_id'];
                    // query to store the data into the database
                    $sql = "INSERT INTO responses(response, question_id, survey_id) VALUES ('$answer', '$question_id', '$survey_id');";
                }
                
                // was the query successful?
                if(mysqli_query($connection, $sql))
                {
                    // the query was successfully inserted move to the next loop
                    continue;
                }
                else
                {
                    die("Error inserting row: " . mysqli_error($connection));
                }
            }
            // display the message to the user
            echo "Thanks for taking the survey";
        }
        else
        {
                die("Error inserting row: " . mysqli_error($connection));
        }
        // we're finished with the database, close the connection:
	    mysqli_close($connection);
    }
    else
    {
        // display the table that will consist of the active surveys and a link to the results
        echo <<<_END
        <table>
        <tr><th colspan=3 >Active surveys</th></tr>
_END;

        // create a connection to the database and store into $connection variable
        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            
        // check if the cocnnection is successful
        if(!$connection)
        {
            // connection not successful show error message
            die("Connection failed: " . $mysqli_connect_error);
        }

        // query to get all the active surveys
        $query = "SELECT survey_id, title, welcome_message FROM surveys WHERE active = true";

        // this query returns data, store into a variable
        $result = mysqli_query($connection, $query);

        // how many rows came back?
        $n = mysqli_num_rows($result);

        // show all the active surveys
        if($n > 0)
        {
            // loop over all records to output them into the table
            for($i = 0; $i < $n; $i++)
            {
                // store the current index row into the variable row
                $row = mysqli_fetch_assoc($result);

                // add survey row into the table with a take survey and see results button
                echo <<<_END

                    <tr>                
                        <td>{$row['title']}</td>
                        <td>
                            <form action='active_surveys.php?survey_id={$row['survey_id']}&survey_title={$row['title']}&welcome_message={$row['welcome_message']}' method="post">
                                <input type='submit' name='take_survey' value='Take Survey'>
                                <input type='hidden' name='ques_i' value='1'>
                            </form>
                        </td>
                        <td><a href='see_results.php?survey_id={$row['survey_id']}&survey_title={$row['title']}'><input type='button' name='see_results' value='See Results'></a></td>
                    </tr>
_END;
            }
            // close the table
            echo "</table>";

        }
        // we're finished with the database, close the connection:
	    mysqli_close($connection);
    }
    
    
    // displays the questions, checks the question type  and runs the function for the question type
    function displayQuestion($row, $question_number)
    {
        // store the data into the variables
        $question_type = $row['question_type'];
        $question_id = $row['question_id'];
        // get the boolean value of the required and store it into a variable
        $required = "";

        // is the current question compulsory to answer?
        if($row['required'] == true)
        {
            // save the string into the variable required
            $required = "required";
        }
        // display the question on the page
        echo "<div>";
        echo "Q$question_number) " . $row['question'];
        echo "<input type='hidden' name='question_id' value='$question_id'>";
        echo "</div>";

        // what type of questions is it?
        switch($question_type)
        {
            // run this case if it is multiple choice
            case "multiple choice":
                multipleChoice($row, $question_number, $required);
                break;
            
            // run this case if it is dropdown
            case "dropdown":
                dropdown($row, $question_number, $required);
                break;

            // run this if it is a short answer
            case "short answer":
                shortAnswer($row, $question_number, $required);
                break;

            // run this case if it is a paragraph
            case "paragraph":
                textArea($row, $question_number, $required);
                break;
        }
    }

    // function to display multiple choice radio buttons on the browser
    function multipleChoice($row, $question_number, $required)
    {
        // display the answers as radio buttons on the browser...
        echo "<div>";

        // get all the answers from the row, separate each answer that comes after a comma and store it into the variable options
        $options = explode("," , $row['answer']);

        // loop through all records to display multiple choices
        for($i = 0; $i < sizeof($options); $i++)
        {
            // display a radio button answer on the browser
            echo "<input type='radio' name='answer' value='$options[$i]' $required >$options[$i]<br>";
        }
        // close the div tag
        echo "</div><br>";
    }

    // function to display dropdown on the browser
    function dropdown($row, $question_number, $required)
    {
        // display a dropdown with the options on the browser
        echo "<div>";

        // get all the answers from the row, separate each answer that comes after a comma and store it into the variable options
        $options = explode("," , $row['answer']);

        // output the dropdown menu
        echo "<select name='answer' $required>";
        // create a default selected and disabled option for the dropdown
        echo "<option value='' selected disabled>Select</option>";
        // loop through all records to display dropdown
        for($i = 0; $i <sizeof($options); $i++)
        {
            // add options to the dropdown menu
            echo "<option value='$options[$i]'>$options[$i]</option>";
        }
        // close the dropdown menu tag
        echo "</select>";
        // close the div tag
        echo "</div><br>";
    }

    // function to display a short answer box on the browser
    function shortAnswer($row, $question_number, $required)
    {
        // display an input form on the browser
        echo <<<_END
        <div>
            <input type="text" name="answer" size=30 $required>
        </div><br>
_END;
    }

    // function to display a textarea on the browser
    function textArea($row, $question_number, $required)
    {
        // display a text area on the browser
        echo <<<_END
        <div>
            <textarea name="answer" cols=30 $required></textarea>
        </div><br>
_END;
    }
?>