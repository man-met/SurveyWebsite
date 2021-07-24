<?php
    // execute the header script:
    require_once "header.php";

    // declared variables to use in the page
    $question_field = "";
    $row = "";
    $checked = "";
    $required = false;

    // check if the usere is logged in
    if(!isset($_SESSION['loggedInSkeleton']))
    {
        // user is not logged in, display a message saying they must be:
        echo "You must be logged in to view this page.<br>";
    }
    else
    {
        // check if the post and get variabels are set...
        if(isset($_GET['new_question']) && isset($_SESSION['loggedInSkeleton']) || isset($_POST['edit']) && isset($_SESSION['loggedInSkeleton']))
        {
            // create a connection to the database and store into $connection variable
            $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
            
            // check if the cocnnection is successful
            if(!$connection)
            {
                // connection not successful show error message
                die("Connection failed: " . $mysqli_connect_error);
            }

            // if there is a question stored in the post variable, then store it into the variable question_field to display in the textarea new_question
            if(isset($_POST['new_question']))
            {
                // store the question stored into the new question field into the varialbe
                $question_field = $_POST['new_question'];
            }
            
            // is edit set using the post variable?
            if(isset($_POST['edit']))
            {

                // retrieve the question data from the questions table where the quesiton_id matches...
                // store the sql query into the variable
                $sql = "SELECT * FROM questions WHERE question_id='{$_POST['question_id']}';";

                // this query returns data, store into a variable
                $result = mysqli_query($connection, $sql);

                // how many rows came back?
                $n = mysqli_num_rows($result);

                // store the variables if record has been retrieved
                if($n == 1)
                {
                    // store all the information in the row variable
                    $row = mysqli_fetch_assoc($result);

                    // store the $question field variable with the information retrieved from the query
                    $question_field = $row['question'];
                    $question_type = $row['question_type'];
                }
            }

            // is save post variables set via the post method?
            if(isset($_POST['save']))
            {
                // save the question into the database
                $question_type = $_POST['question_type_set'];
                
                // check the question type and run the corresponding switch statement
                switch($question_type)
                {
                    case "multiple choice":
                        insertOptionsType($connection, $question_type);
                        break;
                    
                    case "dropdown":
                        insertOptionsType($connection, $question_type);
                        break;

                    case "short answer":
                        insertTextType($connection, $question_type);
                        break;

                    case "paragraph":
                        insertTextType($connection, $question_type);
                        break;
                }
            }
            else
            {
                // is required variable set via the post method?
                if(isset($_POST['required']))
                {
                    // store the string into the variable
                    $checked = "checked";
                }
                // does the question row retrieved have the column required?
                else if(isset($row['required']))
                {
                    // column required set to true?
                    if($row['required'] == true)
                    {
                        // store the string into the variable
                        $checked = "checked";
                    }
                }
                else
                {
                    // store an empty string into the variables checked
                    $checked = "";
                }

                // display the form for the user so the user can enter the question, decide if it is required and select the quesiton type
                echo <<<_END
                <form action="question.php?survey_id={$_GET['survey_id']}&survey_title={$_GET['survey_title']}&new_question=true" method="post">
                    <h4>Question title:</h4><textarea name="new_question" cols=30 placeholder="Enter your Question">$question_field</textarea><br>
                    Answer Required <input type="checkbox" name="required" value="true" $checked> <br>
                    <select name="question_type">
                        <option value="" selected disabled >Question_type</option>
                        <option value="multiple choice">Multiple Choice</option>
                        <option value="dropdown">Dropdown</option>
                        <option value="paragraph">Paragraph</option>
                        <option value="short answer">Short Answer</option>
                    </select>
                    <input type="submit" name="select" value="Select"><br>
_END;
                // check if the question_type has been set
                if(isset($_POST['question_type']) || isset($_POST['question_type_set']) || isset($_POST['edit']))
                {
                    // save the question_type into the variable question_type to pass in the function
                    if(isset($_POST['question_type']))
                    {
                        $question_type = $_POST['question_type'];
                        // display the type of fields required for each type of question
                        selectedQuestionType($question_type, $row);
                    }
                    else if(isset($_POST['question_type_set']))
                    {
                        $question_type = $_POST['question_type_set'];
                        // display the type of fields required for each type of question
                        selectedQuestionType($question_type, $row);
                    }
                    else
                    {
                        // display the type of fields required for each type of question
                        selectedQuestionType($question_type, $row);
                    }

                    // is the edit post variable set?
                    if(isset($_POST['edit']))
                    {
                        // if edit post variable is set store this information into the hidden forms
                        echo <<<_END
                        <input type='hidden' name='edit' value='Edit'>
                        <input type='hidden' name='question_id' value='{$_POST['question_id']}'>
_END;
                    }
                    // close the form with a Save question button
                    echo <<<_END
                    <input type="submit" name="save" value="Save Question">
                    </form>
_END;
                }
                
            }
        }
        // we're finished with the database, close the connection:
	    mysqli_close($connection);
    }

    function selectedQuestionType($question_type, $row)
    {
        // choose which type of input box or form to display on the screen
        switch($question_type)
        {
            case "multiple choice":
                optionsType($question_type, $row);
                break;

            case "dropdown":
                optionsType($question_type, $row);
                break;

            case "short answer":
                textType($question_type, $row);
                break;

            case "paragraph":
                textType($question_type, $row);
                break;
        }
    }

    // function to deal with the types of questions that require options
    function optionsType($question_type, $row)
    {
        // display the options input boxes on to the browser
        echo "<h5>Enter your options</h5>";

        // is option or edit set via post method?
        if(isset($_POST['option']) || isset($_POST['edit']))
        {
            // reset $_POST['option'] if $_POST['select'] is set to delete its contents
            if(isset($_POST['select']))
            {
                $_POST['option'] = "";
            }

            // check if option post variable exists
            if(isset($_POST['option']))
            {
                // store the value of the post option into the variable
                $options = $_POST['option'];
            }
            else
            {
                // store the options variable with the data retrieved from the query
                $options = explode("," , $row['answer']);
            }
            
            // is $options an array?
            if(is_array($options))
            {
                // loop throug each option and display it in the options input box
                for($i = 0; $i < sizeof($options); $i++)
                {
                    $j = (String) $i+1;
                    echo "Option $j<br>";
                    echo "<input type='text' name='option[]' value='$options[$i]' placeholder='Option'><br>";
                }
                // store the option number into the variable
                $i = (String) sizeof($options) + 1;

                // is add option post variable set?
                if(isset($_POST['add_option']))
                {
                    // display the option number and an input box for the user to enter the option
                    echo "Option $i<br>";
                    echo "<input type='text' name='option[]' placeholder='Option'><br>";
                }
                
            }
            else
            {
                // display two input boxes for the user to enter the options and store them into the option[] array
                echo <<<_END
                Option 1<br>
                <input type='text' name='option[]' placeholder='Option'><br>
                Option 2<br>
                <input type='text' name='option[]' placeholder='Option'><br>
_END;
            }
        }
        else
        {
            // display two input boxes for the user to enter the options and store them into the option[] array
            echo <<<_END
            Option 1<br>
            <input type='text' name='option[]' placeholder='Option'><br>
            Option 2<br>
            <input type='text' name='option[]' placeholder='Option'><br>
_END;
        }
        
        // display the an add option button to the user so the user can add another option
        echo <<<_END
        <input type="submit" name="add_option" value="Add Option"><br>
        <input type="hidden" name="question_type_set" value="$question_type">
_END;
    }

    // function to display an input box or a text area depending on the type of the quesiton
    function textType($question_type, $row)
    {
        // if question type is paragraph then display a textbox area
        if($question_type == "paragraph")
        {
            echo "Answer box: <br>";
            echo "<textarea name='textarea' cols=30 disabled></textarea><br>";
        }
        // if question type is short answer then display a input box
        else
        {
            echo "Input box: <br><input type='text' name='text' disabled><br>";
        }
        echo "<input type='hidden' name='question_type_set' value='$question_type'>";
    }

    // function to insert the question into the questions table when the question type consists of options
    function insertOptionsType($connection, $question_type)
    {
        // is required set via post method?
        if(isset($_POST['required']))
        {
            // required is set via post method store the true valu in the variable
            $required = true;
        }
        else
        {
            // no required is set via post method so store the false value in the variable
            $required = false;
        }
        // get all the data posted and store them into the variables to get ready to run the sql query
        $options = implode("," , $_POST['option']);
        $question = $_POST['new_question'];
        $survey_id = $_GET['survey_id'];
        
        // is the edit via the post value?
        if(isset($_POST['edit']))
        {
            // Delete all the answers related to this question from the database running this query
            $sql = "DELETE FROM responses WHERE question_id='{$_POST['question_id']}';";

            // no data returned, we just test for true(success)/false(failure):
            if (mysqli_query($connection, $sql)) 
            {
                // store the query to update the question corresponding to the quesiton id
                $sql = "UPDATE questions SET question='$question', question_type='$question_type', answer='$options', required = '$required' WHERE question_id='{$_POST['question_id']}';";

                //  is the query successful?
                if (mysqli_query($connection, $sql)) 
                {
                    // the query is successful, display the message and a click here button
                    echo "Question successfully saved <a href='survey_build.php?survey_id={$_GET['survey_id']}&survey_title={$_GET['survey_title']}'>Click here</a>";
                }
                else 
                {
                    die("Error updating row: " . mysqli_error($connection));
                }
            }
            else 
            {
                die("Error deleting responses: " . mysqli_error($connection));
            }
        }
        else
        {
            // store the insert query into the variabel
            $sql = "INSERT INTO questions (question, question_type, answer, survey_id, required) 
            VALUES ('$question', '$question_type', '$options', '$survey_id', '$required')";
            
            // no data returned, we just test for true(success)/false(failure):
            if (mysqli_query($connection, $sql)) 
            {
                // display a message to the and display a click here link to the user
                echo "Question successfully saved <a href='survey_build.php?survey_id={$_GET['survey_id']}&survey_title={$_GET['survey_title']}'>Click here</a>";
            }
            else 
            {
                die("Error inserting question: " . mysqli_error($connection));
            }
        }
        
    }

    // function to insert questions into the database when the question type is a short answer or a paragraph
    function insertTextType($connection, $question_type)
    {
        // is required set via post method?
        if(isset($_POST['required']))
        {
            // required is set via post method store the true valu in the variable
            $required = true;
        }
        else
        {
            // no required is set via post method so store the false value in the variable
            $required = false;
        }
        // get all the data posted and store them into the variables to get ready to run the sql query
        $question = $_POST['new_question'];
        $survey_id = $_GET['survey_id'];
        $answer = null;

        // is the edit via the post value?
        if(isset($_POST['edit']))
        {
            // Delete all the answers related to this question from the database running this query
            $sql = "DELETE FROM responses WHERE question_id='{$_POST['question_id']}';";

            // no data returned, we just test for true(success)/false(failure):
            if (mysqli_query($connection, $sql)) 
            {
                // store the query to update the question corresponding to the quesiton id
                $sql = "UPDATE questions SET question='$question', question_type='$question_type', answer='$answer', required = '$required' WHERE question_id='{$_POST['question_id']}';";

                //  the query successful?
                if (mysqli_query($connection, $sql)) 
                {
                    // the query is successful, display the message and a click here button
                    echo "Question successfully saved <a href='survey_build.php?survey_id={$_GET['survey_id']}&survey_title={$_GET['survey_title']}'>Click here</a>";
                }
                else 
                {
                    die("Error updating row: " . mysqli_error($connection));
                }
            }
        }
        else
        {
            // store the insert query
            $sql = "INSERT INTO questions (question, question_type, answer, survey_id, required) 
            VALUES ('$question', '$question_type', '$answer', '$survey_id', '$required')";

            // no data returned, we just test for true(success)/false(failure):
            if (mysqli_query($connection, $sql)) 
            {
                // the query is successful, display the message and a click here button
                echo "Question successfully saved <a href='survey_build.php?survey_id={$_GET['survey_id']}&survey_title={$_GET['survey_title']}'>Click here</a>";
            }
            else 
            {
                die("Error creating table: " . mysqli_error($connection));
            }
        }
    }
?>