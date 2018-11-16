<!doctype html>

<html>

<!--
    
    Exercise 02.09.01
    
    Author: Abraham Aguilar
    Date: 11.15.18
    
    AvailableOpportunities.php
    
-->

<head>
    <title>Available Opportunities</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>College Internship</h1>
    <h2>Available Opportunities</h2>
    <?php
    if (isset($_REQUEST['internID'])) {
        $internID = $_REQUEST['internID'];
    } else {
        $internID = -1;
    }
    //debug
    echo "\$internID: $internID\n";
    
    $errors = 0;
    $hostName = "localhost";
    $username = "adminer";
    $passwd = "hurry-leave-06";
    $DBConnect = false;
    $DBName = "internships2";
    
    if ($errors == 0) {
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
    $TableName = "interns";
    if ($errors == 0) {
        $SQLstring = "SELECT * FROM $TableName WHERE internID='$internID'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) {
            ++$errors;
            echo "<p>Unable to execute the query.  Error code: " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . ".</p>\n";
        }
    }
    if ($DBConnect) {
        echo "<p>Closing Database Connection...</p>\n";
        mysqli_close($DBConnect);
    }
    ?>
</body>

</html>
