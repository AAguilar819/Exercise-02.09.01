<?php
$body = "";
$errors = 0;
$internID = 0;

if (isset($_GET['internID'])) {
    $internID = $_GET['internID'];
} else {
    ++$errors;
    $body .= "<p>You have not logged in or registered.  Please return to the <a href='InternLogin.php'>Registration / Login Page</a>.</p>\n";
}
if ($errors == 0) {
   if (isset($_GET['opportunityID'])) {
        $internID = $_GET['opportunityID'];
    } else {
        ++$errors;
        $body .= "<p>You have not elected an opportunity.  Please return to the <a href='AvailableOpportunities.php'>Opportunities Page</a>.</p>\n";
    } 
} 
if ($errors == 0){
    $hostName = "localhost";
    $username = "adminer";
    $passwd = "hurry-leave-06";
    $DBConnect = false;
    $DBName = "internships2";
    // $TableName = "interns";
    
    if ($errors == 0) { //if no errors, connect to server
        $DBConnect = mysqli_connect($hostName, $username, $passwd);
        if (!$DBConnect) {
            ++$errors;
            $body .= "<p>Unable to connect to the database server.  Error code: " . mysqli_connect_error() . ".</p>\n";
        } else {
            $result = mysqli_select_db($DBConnect, $DBName);
            if (!$result) {
                ++$errors;
                $body .= "<p>Unable to select the database \"$DBName\".  Error code: " . mysqli_error($DBConnect) . ".</p>\n";
            }
        }
    }
    if ($DBConnect) { // if connection is open, close it
        $body .= "<p>Closing Database Connection...</p>\n";
        mysqli_close($DBConnect);
    }
}
?>

<!doctype html>

<html>

<!--
    
    Exercise 02.09.01
    
    Author: Abraham Aguilar
    Date: 11.26.18
    
    RequestOpportunity.php
    
-->

<head>
    <title>Available Opportunities</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>College Internship</h1>
    <h2>Opportunity Requested</h2>
    <?php
    echo $body;
    ?>
</body>

</html>
