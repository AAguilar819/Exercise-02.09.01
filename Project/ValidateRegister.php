<!doctype html>

<html>

<!--
    
    Project 02.09.05
    
    Author: Abraham Aguilar
    Date: 12.06.18
    
    ValidateRegister.php
    
-->

<head>
    <title>User Validation</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>Professional Conferences</h1>
    <h2>Information Validation</h2>
    <?php
    $body = "";
    $errors = 0;

    if (empty($_POST['userID'])) { // nothing was inserted via company email.
        ++$errors;
        echo "<p>Please access this page the intended way next time.</p>";
    }

    $hostName = "localhost";
    $username = "adminer";
    $passwd = "hurry-leave-06";
    $DBConnect = false;
    $DBName = "professionalConferences";

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
    $TableName = "users";
    if ($errors == 0) { // if still no errors, write to the table
        $userID = $_POST['userID'];
        $SQLstring = "UPDATE $TableName SET first='" . htmlentities($_POST['first']) . "', last='" . htmlentities($_POST['last']) . "', email='" .
        htmlentities($_POST['email']) . "', phoneNumber='" . htmlentities($_POST['phone']) . "', compName='" . htmlentities($_POST['compName']) .
        "', compEmail='" . htmlentities($_POST['compEmail']) . "', compPhone='" . htmlentities($_POST['compPhone']) . "', compAddr='" . htmlentities($_POST['compAddr']) . "', registered='true' WHERE userID='$userID'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);

        if (!$queryResult) { // failure to write to the table
            ++$errors;
            echo "<p>Unable to validate your information.  Error code: " . mysqli_error($DBConnect) . ".</p>";
        }
    }
    if ($DBConnect) { // if connection is open, close it
        echo "<p>Closing Database Connection...</p>\n";
        mysqli_close($DBConnect);
    }
    if ($errors == 0) {
        echo "<p>Thank you, " . htmlentities($_POST['first']) . " " . htmlentities($_POST['last']) . ".</p>";
        echo "<p>Please now <a href='AvailableSeminars.php?userID=$userID'>select seminars</a> to attend.</p>";
    }
    if ($errors > 0) { // informs the user to fix errors if any
        echo "<p>Please use your browser's BACK button to return to the form and fix the errors indicated.</p>\n";
    }
    ?>

</body>

</html>
