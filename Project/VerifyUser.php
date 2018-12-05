<!doctype html>

<html>

<!--
    
    Project 02.09.05
    
    Author: Abraham Aguilar
    Date: 12.04.18
    
    VerifyUser.php
    
-->

<head>
    <title>Verify User Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>Professional Conferences</h1>
    <h2>Verify User Login</h2>
    <?php
    $errors = 0;
    $hostName = "localhost";
    $username = "adminer";
    $passwd = "hurry-leave-06";
    $DBConnect = false;
    $DBName = "professionalConferences";
    $TableName = "users";
    
    if ($errors == 0) { //if no errors, connect to server
        $DBConnect = mysqli_connect($hostName, $username, $passwd);
        if (!$DBConnect) {
            ++$errors;
            echo "<p>Unable to connect to the database server.  Error code: " . mysqli_connect_error() . ".</p>\n";
        } else {
            $result = mysqli_select_db($DBConnect, $DBName);
            if (!$result) {
                ++$errors;
                echo "<p>Unable to select the database \"$DBName\".  Error code: " . mysqli_error($DBConnect) . ".</p>\n";
            }
        }
    }
    if ($errors == 0) { // if still no errors, check for matching inputs
        $SQLstring = "SELECT userID, first, last FROM $TableName WHERE email='" . stripslashes($_POST['email']) . "' AND password_md5='" .
            md5(stripslashes($_POST['password'])) . "'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) { // query failed
            ++$errors;
            echo "<p>Query not executed, bad SQL syntax.</p>\n";   
        }
        if ($errors == 0) {
            if (mysqli_num_rows($queryResult) == 0) {
                ++$errors; // not enough of a match
                echo "<p>The e-mail address/password combination entered is not valid.</p>\n";
            } else { // match found, login successful
                $row = mysqli_fetch_assoc($queryResult);
                $userID = $row['userID'];
                $userName = $row['first'] . " " . $row['last'];
                mysqli_free_result($queryResult);
                echo "<p>Welcome back $userName!</p>\n";
            }
        }
    }
    if ($DBConnect) { // if connection is open, close it
        echo "<p>Closing Database Connection...</p>\n";
        mysqli_close($DBConnect);
    }
    if ($errors == 0) { // if no errors, set up the form to transfer data and link to a site
        echo "<p><a href='AvailableSeminars.php?userID=$userID'>View Available Seminars</a></p>\n";
    }
    if ($errors > 0) { // informs the user to fix errors if any
        echo "<p>Please use your browser's BACK button to return to the form and fix the errors indicated.</p>\n";
    }
    ?>
</body>

</html>
