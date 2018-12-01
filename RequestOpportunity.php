<?php
session_start();

$body = "";
$errors = 0;
$internID = 0;

// if (isset($_GET['internID'])) {
//     $internID = $_GET['internID'];
// }
if (!isset($_SESSION['internID'])) {
    ++$errors;
    $body .= "<p>You have not logged in or registered.  Please return to the <a href='InternLogin.php?PHPSESSID=" . session_id() . "'>" . 
        "Registration / Login Page</a>.</p>\n";
}
if ($errors == 0) {
   if (isset($_GET['opportunityID'])) {
        $opportunityID = $_GET['opportunityID'];
    } else {
        ++$errors;
        $body .= "<p>You have not selected an opportunity.  Please return to the <a href='AvailableOpportunities.php'>Opportunities Page</a>.</p>\n";
    } 
} 
$hostName = "localhost";
$username = "adminer";
$passwd = "hurry-leave-06";
$DBConnect = false;
$DBName = "internships2";
// $TableName = "interns";
if ($errors == 0){
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
$displayDate = date("l, F j, Y, g:i A");
$body .= "\$displayDate: $displayDate<br>";
$dbDate = date("Y-m-d H:i:s");
$body .= "\$dbDate: $dbDate<br>";
if ($errors == 0) {
    $TableName = "assigned_opportunities";
    $SQLstring = "INSERT INTO $TableName (opportunityID, internID, dateSelected) VALUES($opportunityID, " . $_SESSION['internID'] . ", '$dbDate')";
    $queryResult = mysqli_query($DBConnect, $SQLstring);
    if (!$queryResult) {
        ++$errors;
        $body .= "<p>Unable to execute the query, error code: " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . ".</p>\n";
    } else {
        $body .= "<p>Your results for opportunity #$opportunityID have been entered on $displayDate.</p>\n";
    }
}
if ($DBConnect) { // if connection is open, close it
    $body .= "<p>Closing Database Connection...</p>\n";
    mysqli_close($DBConnect);
}
if ($_SESSION['internID'] > 0) {
    $body .= "<p>Return to the <a href='AvailableOpportunities.php?PHPSESSID=" . session_id() . "'>Available Opportunities</a> page.</p>";
} else {
    $body .= "<p>Please <a href='InternLogin.php'>Register or Log In</a> to use this page.</p>";
}
if ($errors == 0) {
    $body .= "Setting cookie<br>";
    setcookie("LastRequestDate", urlencode($displayDate), time()+60*60*24*7);
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
    <title>Request Opportunity</title>
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
