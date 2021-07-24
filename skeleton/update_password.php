<?php

    //execute the header script:
    require_once "header.php";

    $show_updatePasswordform = false;
    $show_userVerify_form = true;

    // message to output to user
    $message = "";


    if(!isset($_SESSION['loggedInSkeleton']))
    {
        // user isn't logged in, display a message saying they must be:
	    echo "You must be logged in to view this page.<br>";
    }
    else if(isset($_POST['update_password']))
    {
        // store data into variables from the $show_updatePasswordform
        $new_password = $_POST['newPassword'];
        $new_confirm_password = $_POST['confirmPassword'];

        // create a connection to the database and store into $connection
        $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

        // check if connection is successful
        if(!$connection)
        {
            // connection not successful show error message
		    die("Connection failed: " . $mysqli_connect_error);
        }

        // sanitisation
        $sanitise_new_password = sanitise($new_password, $connection);
        $sanitise_confim_password = sanitise($new_confirm_password, $connection);
        // validation
        $new_password_val = validateString($sanitise_new_password, 0, 16);
        $new_confirm_password_val = validateString($sanitise_confim_password, 0, 16);

        // store all the validation strings into the variable $error
        $errors = $new_password_val . $new_confirm_password_val;

        // check the variable for any errors
        if($errors == "")
        {
            if($new_password == $new_confirm_password)
            {
                // insert the data into the database
                $query = "UPDATE users SET password='$new_password' WHERE username = '{$_POST['username']}'";

                // this query returns true or false
                if(mysqli_query($connection, $query))
                {
                    $message = "Password has been updated";
                }
                else
                {
                    die("Error updating row: " . mysqli_error($connection));
                }
            }
            else
            {
                $message = "Validation Error";
            }
        }
    }
    else if(isset($_POST['verify']) && isset($_SESSION['loggedInSkeleton']) || $_SESSION['username'] == 'admin' && $_GET['username'] != 'admin')
    {
        // verify the current password
        // verify the user, if verified display the update password form else display an error message
        if(isset($_GET["username"]))
        {
            $username = $_GET["username"];
            $show_updatePasswordform = true;
        }
        else
        {
            $username = $_POST["username"];
            $password = $_POST['password'];

            // create a connection to the database and store into $connection
            $connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

            // check if connection is successful
            if(!$connection)
            {
                // connection not successful show error message
                die("Connection failed: " . $mysqli_connect_error);
            }

            // Sanitisation
            $sanitised_password = sanitise($password, $connection);

            // validation // Make sure you check 0 and 16 are correct
            $password_val = validateString($sanitised_password, 0, 16);

            // store all the validation strings into the variable $error
            $errors = $password_val;
            // check the variable for any error messages
            if($errors == "")
            {
                // get data from the database
                $query = "SELECT password FROM users WHERE username='$username'";

                // user data is returned, store into a vairable
                $result = mysqli_query($connection, $query);

                // store number of rows in a variable
                $n = mysqli_num_rows($result);

                // check if data is returned, if returned, save into variable to verify
                if($n == 1)
                {
                    $user = mysqli_fetch_assoc($result);

                    $password_returned = $user['password'];
                }

                // check if the password matches
                if($password == $password_returned)
                {
                    //$user_verified = true;
                    $show_updatePasswordform = true;
                    //$show_userVerify_form = false;
                }
                else // check on how you can make this else statement better looking at the website
                {
                    echo "incorrect password";
                }
            }
        }
    }
    else
    {
        // ask the user to enter the current password
        if($show_userVerify_form)
        {
            $username = $_SESSION["username"];

            // get the username and the password from the database...

            // display the form to verify
            echo <<<_END
            <form action="update_password.php" method="post"> 
                <b>Verify that it is your account:</b><br>
                <table
                    <tr><td>Username:</td><td><input size="30" type="text" name="username" value="{$_GET['username']}" readonly required></td></tr>
                    <tr><td>Password:</td><td><input size="30" type="password" name="password" minlength="6" maxlength="16" placeholder="Password"  required></td></tr>
                </table>
                <input type="submit" name="verify" value="Verify">
            </form>
_END;
        }
        


    }

    if($show_updatePasswordform)
    {
        echo <<<_END
            <form action="update_password.php" method="post"> 
                <b>Enter your new Password:</b><br>
                <table
                    <tr><td>Username:</td><td><input size="30" type="text" name="username" value="$username" readonly required></td></tr>
                    <tr><td>New Password:</td><td><input size="30" type="password" minlength="6" maxlength="16" name="newPassword" placeholder="New Password"  required></td></tr>
                    <tr><td>Confirm New Password:</td><td><input size="30" type="password" minlength="6" maxlength="16" name="confirmPassword" placeholder="Confirm Password"  required></td></tr>
                </table>
                <input type="submit" name="update_password" value="Update">
            </form>
_END;
    }

    // display our message to the user
    echo $message;

    //finish off the HTML for this page
    require_once "footer.php";
?>