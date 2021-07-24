<?php

    // execute the header script:
    require_once "header.php";

    // variables
    $message = "";
    $add_user_form = false;
    $username_returned['username'] = "";

    // variable to hold data
    $username = "";
    $password = "";
    $first_name = "";
    $last_name = "";
    $dob = "";
    $tel_number = "";
    $email ="";

    // validation variables
    $username_val = "";
    $password_val = "";
    $first_name_val = "";
    $last_name_val = "";
    $dob_val = "";
    $tel_number_val = "";
    $email_val = "";

    // is the user logged in?
    if(!isset($_SESSION['loggedInSkeleton']))
    {
        // user isn't logged in, display a message saying they must be:
        echo "You must be logged in to view this page.<br>";
    }
    else
    {
        // is the user logged in the admin and does add_new_user exist?
        if($_SESSION['username'] == 'admin' && isset($_POST['add_new_user']))
        {
            // connect directly to our database (notice 4th argument) we need the connection for sanitisation:
	        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

            // sanitisation of the data received from the forms
            $username = sanitise($_POST['username'], $connection);
            $password = sanitise($_POST['password'], $connection);
            $first_name = sanitise($_POST['first_name'], $connection);
            $last_name = sanitise($_POST['last_name'], $connection);
            $dob = sanitise($_POST['dob'], $connection);
            $tel_number = sanitise($_POST['tel_number'], $connection);
            $email = sanitise($_POST['email'], $connection);

            // server side validation
            $username_val = validateString($username, 0, 16);
            $password_val = validateString($password, 0, 16);
            $first_name_val = validateString($first_name, 0 , 16);
            $last_name_val = validateString($last_name, 0, 16);
            $dob_val = validateDOB($dob);
            $email_val = validateEmail($email);

            // store all the validation string errors into the variable
            $errors = $username_val . $password_val . $first_name_val . $last_name_val . $dob_val . $tel_number_val . $email_val;

            echo $errors . "<br>";
            // check for errors for any error messages
            if($errors == "")
            {
                // check if the username already exists
                $sql = "SELECT username FROM users WHERE username = '$username'";

                // store the returned data into the identifier
                $result = mysqli_query($connection, $sql);
                
                // how many rows are back?
                $n = mysqli_num_rows($result);

                // if there was a mathc extract the username
                if($n > 0)
                {
                    // store data returned into the variable
                    $username_returned = mysqli_fetch_assoc($result);
                }

                // does the username retrieved from the database match the username the user entered?
                if($username_returned['username'] != $username)
                {
                    // add the new details:
                    $query = "INSERT INTO users (username, password, first_name, last_name, dob, tel_number, email) 
                    VALUES ('$username', '$password', '$first_name', '$last_name', '$dob', '$tel_number', '$email')";

                    // store the result of the query into the variable
                    $result = mysqli_query($connection, $query);
                    
                    // no data returned, we just test for true(success)/false(failure):
                    if ($result) 
                    {
                        // show a successful signup message:
                        $message = "Account has been created<br>";
                        header("Location: admin_user.php?message=$message");
                    } 
                    else 
                    {
                        // show the form:
                        $add_user_form = true;
                        // show an unsuccessful signup message:
                        $message = "Sign up failed, please try again<br>";
                    }
                }
                else
                {
                    //show the form
                    $add_user_form = true;
                    // show a message stating that username already exists
                    $message = "Username already exist, choose a different username";
                }
            }
            else
            {
                // validation failed, show the form again with guidance:
                $add_user_form = true;
                // show an unsuccessful signin message:
                $message = "Sign up failed, please check the errors shown above and try again<br>";
            }
            // we're finished with the database, close the connection:
	        mysqli_close($connection);
        }
        else
        {
            // set the add user form variable to true
            $add_user_form = true;
        }
    }

    // is add user form variable  = true?
    if($add_user_form)
    {
        // print the new user form
        echo <<<_END

        <form action="admin_add_user.php" method="post">
        <b>Update your profile info: </b><br>
            <table>
                <tr><td>Username:</td><td><input size="30" type="text" name="username" value="$username" placeholder="Enter Username" required> $username_val  </td></tr>
                <tr><td>Password:</td><td><input size="30" type="password" name="password" value="$password" placeholder="Enter Password" required> $password_val  </td></tr>
                <tr><td>First Name:</td><td><input size="30" type="text" name="first_name" minlength="1" maxlength="16" placeholder="Enter your First Name" value="$first_name" required> $first_name_val </td></tr>
                <tr><td>Last Name:</td><td><input size="30" type="text" name="last_name" minlength="1" maxlength="16" placeholder="Enter your Last Name" value="$last_name" required> $last_name_val </td></tr>
                <tr><td>DOB:</td><td><input size="30" type="date" name="dob" value="$dob" required> $dob_val </td></tr>
                <tr><td>Tel.:</td><td><input size="30" type="number" name="tel_number" maxlength="13" placeholder="Telephone Number" value="$tel_number" required> $tel_number_val </td></tr>
                <tr><td>Email address:</td><td><input size="30" type="email" name="email" maxlength="64" placeholder="Email Address" value="$email" required> $email_val </td></tr>
            </table>
            <input type="submit" name="add_new_user" value="Add New User">
        </form>

_END;
    }

    // display the message to the user
    echo $message;
    
    // finish off the HTML for this page:
    require_once "footer.php";
?>