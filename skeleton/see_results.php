<?php

    // execute the header script:
    require_once "header.php";

    // declare variables
    $number_of_responses = 0;
    if(isset($_GET['survey_id']) && isset($_GET['survey_title']))
    {
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

         // display the survey title and results
         echo "<h4>{$_GET['survey_title']} - Results</h4><br>";

         // check if any record has been retrieved
         if($n > 0)
         {
             // loop over all records
             for($i = 0; $i < $n; $i++)
             {
                 // store the row from the result into the row variables
                $row = mysqli_fetch_assoc($result);

                // store the question number into the variables added by one into the $i
                $question_number = $i+1;

                // print out the question
                echo "Q$question_number) " . $row['question'] . "<br>";

                // run the display question result function and pass the connection and the row into the function
                displayQuestionResult($connection, $row);
             }
         }
         else
         {
             // display a message if no results are there to display
             echo "There are no results to display";
         }
        // we're finished with the database, close the connection:
	    mysqli_close($connection);

    }

    // function to choose the function to run depending on the question type
    function displayQuestionResult($connection, $row)
    {
        // store the question_type information in the variable $question_type
        $question_type = $row['question_type'];

        // what type of questin is it?
        switch($question_type)
        {
            case "multiple choice":
                optionsResponses($connection, $row);
                break;
            
            case "dropdown":
                optionsResponses($connection, $row);
                break;

            case "short answer":
                textResponses($connection, $row);
                break;

            case "paragraph":
                textResponses($connection, $row);
                break;
        }
        echo "<br>";
    }

    // run this function of the question type consists of the type options
    function optionsResponses($connection, $row)
    {
        // get all the options in the row and divide them to individual and store into an array
        $options = explode("," , $row['answer']);

        // display the table
        echo <<<_END
        <table>
            <tr>
                <th>Options</th>
                <th>Total</th>
                <th>Summary</th>
            </tr>
_END;
        // reset the variable
        $number_of_responses = 0;

        // run the loop for the number of times the size of options variable is
        for($i = 0; $i < sizeof($options); $i++)
        {
            // pass the option to variable answer
            $answer = $options[$i];
            
            // store the query to run into a variable
            $sql = "SELECT COUNT(response) AS 'no_of_Responses' FROM responses WHERE response = '$answer';";

            // run the query and store the result into $answers_result variable
            $answers_result = mysqli_query($connection, $sql);

            // how many rows came back
            $number_rows = mysqli_num_rows($answers_result);

            // display the answer onto the table
            echo "<tr><td>$answer</td>";

            // display the answers and count
            if($number_rows == 1)
            {
                $record = mysqli_fetch_assoc($answers_result);
                echo "<td>{$record['no_of_Responses']}</td>";
                echo "<td>{$record['no_of_Responses']} number of respondents chose $answer</td></tr>";
                $number_of_responses = $number_of_responses + $record['no_of_Responses'];
            }
            else
            {
                // if number of responses is 0 then display 0 in the field
                echo "<td>0</td></tr>";
            }
        }
        // close the table
        echo "</table>";
        // show how many number of responses were give to the question
        echo "<h5>Total number of responses to this question is $number_of_responses</h5>";
        echo "<br>";
        // run the plot chart function to display a chart
        plotChart($connection, $row);
    }

    function textResponses($connection, $row)
    {
        // store  the question_id into the variable
        $question_id = $row['question_id'];
        // create a table
        echo <<<_END
        <table>
            <tr>
                <th>Responses</th>
            </tr>
_END;
        // sql query to get the responses
        $sql = "SELECT response FROM responses WHERE question_id='$question_id' AND response != 'null';";

        // run the query and store the result into $answers_result variable
        $answers_result = mysqli_query($connection, $sql);

        // how many rows came back
        $number_rows = mysqli_num_rows($answers_result);

        if($number_rows > 0)
        {
            // reset the variable
            $number_of_responses = 0;
            for($i = 0; $i < $number_rows; $i++)
            {
                // move through each row for each loop and store the row into the associative array record
                $record = mysqli_fetch_assoc($answers_result);

                // is response in the record null?
                if($record['response'] != "null")
                {
                    // response is not null display on the table
                    echo "<tr><td>{$record['response']}</td></tr>";
                    // count number of responses by adding one into the variable
                    $number_of_responses = $number_of_responses + 1;
                }
            }
            
        }
        else
        {
            // display a message no answers to the user as no records were retrieved
            echo "<td>No answers</td></tr>";
        }
        echo "</table>";
        // show total numver of responses to the question
        echo "<h5>Total number of responses is $number_of_responses</h5>";
    }

    function plotChart($connection, $row)
    {
        //reset query and result variables as we used them earlier
        $query = $result = "";

        // store the query to run in the database in the variable $query
        $query = "SELECT response, count(response) AS 'Number of Responses' FROM responses WHERE question_id = '{$row['question_id']}' AND survey_id = '{$_GET['survey_id']}' AND response != 'null' GROUP BY response;";
        
        // this query can return data ($result is an identifier):
        $result2 = mysqli_query($connection, $query);
        // how many rows of data come back?
        $n = mysqli_num_rows($result2);

        // if we got some results then use them to plot a graph
        if($n > 0)
        {
            // create unique id names to give to the containers id's to plot the charts
            $chart_div = (String) "chart_div" .rand(10,10000);
            $pie_div = (String) "pie_div" . rand(10,10000);
            $filter_div = (String) "filter_div" . rand(10,10000);
            // create a HEREDOC to hold the Google charts script
            echo <<<_END
            <!--Load the AJAX API-->
            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">

            // Load the Visualization API and the corechart package - notice the 'controls' portion added
            google.charts.load('current', {'packages':['corechart', 'controls']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(drawChart);

            // Callback that creates and populates a data table,
            // instantiates the pie chart, passes in the data and
            // draws it.

            function drawChart() {

                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'response');
                data.addColumn('number', 'Number of Responses');
                data.addRows([
_END;
            // loop over all rows, to fill the DataTable
            for($i = 0; $i < $n; $i++)
            {
                // fetch one row as an associative array (elements named after columns):
                $data = mysqli_fetch_assoc($result2);
                // set the size of the bar to plot based upon number of votes versus total votes
                echo "['{$data['response']}', {$data['Number of Responses']}],";

            }

            echo <<<_END

            ]);

            // set chart options
            var options = {'title':'{$row['question']}',
                            'width':600,
                            'height':300,
                            legend: {position: "left"},
                            };
            
            // Instantiate and draw our chart, passing in some options.
            // this creates the normal bar chart
            var chart = new google.visualization.BarChart(document.getElementById('$chart_div'));
            chart.draw(data, options);
            
            //////////////////////////////
            // CREATION OF THE PIE CHART
            //////////////////////////////

            // Create a dashboard.
            var dashboard = new google.visualization.Dashboard(
                document.getElementById('dashboard_div'));

            // Create a range slider, passing some options
            var slider = new google.visualization.ControlWrapper({
                'controlType': 'NumberRangeFilter',
                'containerId': '$filter_div',
                'options': {
                'filterColumnLabel': 'Number of Responses'
                }
            });

            // set pie chart options
            var pieChart = new google.visualization.ChartWrapper({
                'chartType': 'PieChart',
                'containerId': '$pie_div',
                'options': {
                    'title':'{$row['question']}',
                    'pieHole': 0.4,
                    'width': 600,
                    'height': 300,
                    'pieSliceText': 'value',
                    'legend': 'right'
                 }
            });
            
            // Establish dependencies, declaring that 'filter' drives 'pieChart',
            // so that the pie chart will only display entries that are let through
            // given the chosen slider range.
            dashboard.bind(slider, pieChart);
            dashboard.draw(data);
        }
        </script>
        <table bgcolor='#ffffff' cellspacing='0' cellpadding='2'><tr>
                    <!--Divs that will hold the bar charts-->
                    <td>
                        <div id="$chart_div"></div>
                    </td>
                    
                    <!-- divs to hodl the pie chart -->
                    <td><div id="dashboard_div">
                        <div id="$filter_div"></div>
                        <div id="$pie_div"></div>
                    </div></td>
                    </tr></table>
_END;
        }
        // if anything else happens indicate a problem
        else {
            echo "No data available to plot<br>";
        }
    }

    
?>