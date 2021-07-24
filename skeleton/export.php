<?php
    // database connection details:
    // consists of the data required to make connection to the database
    require_once "credentials.php";

    // is export set using the post method?
    if(isset($_POST['export']))
    {
        // store query received via post method into the variabels query
        $query = $_POST['query'];
        // connect to the host:
        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
        // exit the script with a useful message if there was an error:
        if (!$connection)
        {
            die("Connection failed: " . $mysqli_connect_error);
        }

        // connect to our database:
        mysqli_select_db($connection, $dbname);

        // run query to get the contents of the responses table
        $out = fopen('php://output', 'w');

        // store the result into the variable
        $results = mysqli_query($connection, $query);

        $first = true;

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="export.csv"');
        header('Cache-Control: max-age=0');

        $out = fopen('php://output', 'w');
        while($row = mysqli_fetch_assoc($results)){
            if($first){
                $titles = array();
                foreach($row as $key=>$val){
                    $titles[] = $key;
                }
                fputcsv($out, $titles);
                $first = false;
            }
            fputcsv($out, $row);
        }
        fclose($out);
    }
?>