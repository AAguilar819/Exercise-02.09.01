<!doctype html>

<html>

<!--
    
    Exercise 02.09.01
    
    Author: Abraham Aguilar
    Date: 11.15.18
    
    VerifyLogin.php
    
-->

<head>
    <title>Verify Intern Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>College Internship</h1>
    <h2>Verify Intern Login</h2>
    <?php
    $errors = 0;
    $hostName = "localhost";
    $username = "adminer";
    $passwd = "hurry-leave-06";
    $DBConnect = false;
    $DBName = "internships2";
    $TableName = "interns";
    
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
        $SQLstring = "SELECT internID, first, last FROM $TableName WHERE email='" . stripslashes($_POST['email']) . "' AND password_md5='" .
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
                $internID = $row['internID'];
                $internName = $row['first'] . " " . $row['last'];
                mysqli_free_result($queryResult);
                echo "<p>Welcome back $internName!</p>\n";
            }
        }
    }
    if ($DBConnect) { // if connection is open, close it
        echo "<p>Closing Database Connection...</p>\n";
        mysqli_close($DBConnect);
    }
    if ($errors == 0) { // if no errors, set up the form to transfer data and link to a site
        // echo "<form action='AvailableOpportunities.php' method='post'>\n";
        // echo "<input type='hidden' name='internID' value='$internID'>";
        // echo "<input type='submit' name='submit' value='View Available Opportunities'>";
        // echo "</form>\n";
        echo "<p><a href='AvailableOpportunities.php?internID=$internID'>Available Opportunities</a></p>\n";
    }
    if ($errors > 0) { // informs the user to fix errors if any
        echo "<p>Please use your browser's BACK button to return to the form and fix the errors indicated.</p>\n";
    }
    ?>
</body>

</html>
