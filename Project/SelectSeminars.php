<?php
$errors = 0;
$internID = 0;

if (!isset($_POST['submit'])) {
    ++$errors;
    echo "<p>You have not logged in or registered.  Please return to the <a href='InternLogin.php'>" . 
        "Registration / Login Page</a>.</p>\n";
} else {
    $userID = $_POST['userID'];
    $_POST['userID'] = null;
}
if ($errors == 0) {
    $IDS = array();
    foreach ($_POST as $name => $value) {
        $IDS[] = $name;
    }
    $string = implode(" ", $IDS);
    echo $string;
    $seminarIDS = array();
    if (preg_match("/([1-5]\D){1}/", $string, $matches)) {
        $seminarIDS[] = $matches[0];
    }
    if (preg_match("/([6-9]|10){1}/", $string, $matches)) {
        $seminarIDS[] = $matches[0];
    }
    if (preg_match("/(1[1-5]){1}/", $string, $matches)) {
        $seminarIDS[] = $matches[0];
    }
    echo "<pre>";
    print_r($seminarIDS);
    echo "</pre>";
    if (count($IDS) == 0) {
        ++$errors;
        echo "<p>You have not selected a seminar.  Please return to the <a href='AvailableSeminars.php?userID=" . $_POST['userID'] . "'>Seminars Page</a>.</p>\n";
    }
}

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
        echo "<p>Unable to connect to the database server.  Error code: " . mysqli_connect_error() . ".</p>\n";
    } else {
        $result = mysqli_select_db($DBConnect, $DBName);
        if (!$result) {
            ++$errors;
            echo "<p>Unable to select the database \"$DBName\".  Error code: " . mysqli_error($DBConnect) . ".</p>\n";
        }
    }
}
$displayDate = date("l, F j, Y, g:i A");
echo "\$displayDate: $displayDate<br>";
$dbDate = date("Y-m-d H:i:s");
echo "\$dbDate: $dbDate<br>";
//$errors = 1;

if ($errors == 0) {
    $TableName = "selected_seminars";
    $SQLstring = "DELETE FROM $TableName WHERE userID='$userID'";
    $queryResult = mysqli_query($DBConnect, $SQLstring);
    if (!$queryResult) {
        ++$errors;
        echo "<p>Unable to execute the query, error code: " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . ".</p>\n";
    } else {
        for ($i = 0; $i < count($seminarIDS); $i++) {
            $SQLstring = "INSERT INTO $TableName (seminarID, userID, dateSelected) VALUES($seminarIDS[$i], " . $userID . ", '$dbDate')";
            $queryResult = mysqli_query($DBConnect, $SQLstring);
            if (!$queryResult) {
                ++$errors;
                echo "<p>Unable to execute the query, error code: " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . ".</p>\n";
                break;
            }
        }
    }
    if ($errors == 0) {
        echo "<p>Your results choices have been updated on $displayDate.</p>\n";
    }
}
if ($DBConnect) { // if connection is open, close it
    echo "<p>Closing Database Connection...</p>\n";
    mysqli_close($DBConnect);
}
//if ($_POST['userID'] > 0) {
//    $body .= "<p>Return to the <a href='AvailableOpportunities.php'>Available Opportunities</a> page.</p>";
//} else {
//    $body .= "<p>Please <a href='InternLogin.php'>Register or Log In</a> to use this page.</p>";
//}
if ($errors == 0) {
    //echo "Setting cookie<br>";
    //setcookie("LastUpdateDate", urlencode($displayDate), time()+60*60*24*7);
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
    print_r($IDS);
    echo "</pre>";
    ?>
</body>

</html>
