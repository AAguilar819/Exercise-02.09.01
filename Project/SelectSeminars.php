<?php
$body = "";
$errors = 0;
$internID = 0;

if (!isset($_POST['submit'])) {
    ++$errors;
    $body .= "<p>You have not logged in or registered.  Please return to the <a href='InternLogin.php'>" . 
        "Registration / Login Page</a>.</p>\n";
} else {
    $userID = $_POST['userID'];
    $_POST['userID'] = null;
}
if ($errors == 0) {
    $seminarIDS = array();
   foreach ($_POST as $name => $value) {
       if (!in_array(1 || 2 || 3 || 4, $seminarIDS)) {
           if (!in_array(6 || 7 || 8 || 9 , $seminarIDS)) {
               if (!in_array(11 || 12 || 13 || 14, $seminarIDS)) {
                   $seminarIDS[] = $name;
               }
           }
       }
   }
    array_pop($seminarIDS);
    array_shift($seminarIDS);
    if (count($seminarIDS) == 0) {
        ++$errors;
        $body .= "<p>You have not selected a seminar.  Please return to the <a href='AvailableSeminars.php?userID=" . $_POST['userID'] . "'>Seminars Page</a>.</p>\n";
    }
}
$errors = 1;
$hostName = "localhost";
$username = "adminer";
$passwd = "hurry-leave-06";
$DBConnect = false;
$DBName = "professionalConferences";
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
    $TableName = "selected_seminars";
    $SQLstring = "INSERT INTO $TableName (opportunityID, internID, dateSelected) VALUES($opportunityID, " . $_POST['userID'] . ", '$dbDate')";
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
//if ($_POST['userID'] > 0) {
//    $body .= "<p>Return to the <a href='AvailableOpportunities.php'>Available Opportunities</a> page.</p>";
//} else {
//    $body .= "<p>Please <a href='InternLogin.php'>Register or Log In</a> to use this page.</p>";
//}
if ($errors == 0) {
    $body .= "Setting cookie<br>";
    setcookie("LastUpdateDate", urlencode($displayDate), time()+60*60*24*7);
}
?>
<!doctype html>

<html>

<!--
    
    Project 02.09.05
    
    Author: Abraham Aguilar
    Date: 12.07.18
    
    SelectSeminars.php
    
-->

<head>
    <title>Select Seminars</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>Professional Conferences</h1>
    <h2>Seminars Saved</h2>
    <?php
    echo "<pre>";
    print_r($_POST);
    echo "<pre>";
    echo $body;
    echo "<pre>";
    print_r($seminarIDS);
    echo "<pre>";
    ?>
</body>

</html>
