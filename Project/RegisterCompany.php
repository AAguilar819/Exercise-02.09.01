<?php
$body = "";
$errors = 0;
$compEmail = "";
$compPhone = "";

if (empty($_POST['compEmail'])) { // nothing was inserted via company email.
    ++$errors;
    $body .= "<p>You need to enter an email address.</p>";
} else {
    $compEmail = stripslashes($_POST['compEmail']);
    if (preg_match("/^[\w-]+(\.[\w-])*@[\w-]+(\.[\w-]+)*(\.[A-Za-z]{2,})$/i", $compEmail) == 0) { // checks if the email was properly typed.  calls an error otherwise
        ++$errors;
        $body .= "<p>You need to enter a valid email address.</p>";
        $compEmail = "";
    }
}
if (empty($_POST['compPhone'])) { // nothing was inserted via email.
    ++$errors;
    $body .= "<p>You need to enter a phone number.</p>";
} else {
    $compPhone = stripslashes($_POST['compPhone']);
    if (preg_match("/^\d{3}-\d{3}-\d{4}$/", $compPhone) == 0) { // checks if the phone number was properly typed.  calls an error otherwise
        ++$errors;
        $body .= "<p>You need to enter a valid phone number.</p>";
        $compPhone = "";
    }
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
        $body .= "<p>Unable to connect to the database server.  Error code: " . mysqli_connect_error() . ".</p>\n";
    } else {
        $result = mysqli_select_db($DBConnect, $DBName);
        if (!$result) {
            ++$errors;
            $body .= "<p>Unable to select the database \"$DBName\".  Error code: " . mysqli_error($DBConnect) . ".</p>\n";
        }
    }
   
}
$TableName = "users";
if ($errors == 0) { // if still no errors, write to the table
    $compName = stripslashes($_POST['compName']);
    $compAddr = stripslashes($_POST['compAddr']);
    $userID = $_COOKIE['userID'];
    $SQLstring = "UPDATE $TableName SET compName='$compName', compEmail='$compEmail', compPhone='$compPhone', compAddr='$compAddr' " . 
        "WHERE userID='$userID'";
    $queryResult = mysqli_query($DBConnect, $SQLstring);
    
    if (!$queryResult) { // failure to write to the table
        ++$errors;
        $body .= "<p>Unable to save your registration information.  Error code: " . mysqli_error($DBConnect) . ".</p>";
    }
}
if ($errors == 0) { // if STILL no errors, save the data for use
    $body .= "<p>Thank you. ";
    $body .= "Your company info has been saved.</p>\n";
}
if ($DBConnect) { // if connection is open, close it
    $body .= "<p>Closing Database Connection...</p>\n";
    mysqli_close($DBConnect);
}
if ($errors == 0) { // if no errors, set up the form to transfer data and link to a site
     $body .= "<p><a href='AvailableSeminars.php?userID=$userID'>View Available Seminars</a></p>\n";
}
if ($errors > 0) { // informs the user to fix errors if any
    $body .= "<p>Please use your browser's BACK button to return to the form and fix the errors indicated.</p>\n";
}
?>

<!doctype html>

<html>

<!--
    
    Exercise 02.09.05
    
    Author: Abraham Aguilar
    Date: 11.30.18
    
    RegisterIntern.php
    
-->

<head>
    <title>User Registration</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>Professional Conferences</h1>
    <h2>User Registration</h2>
    <?php
    echo $body;
    ?>
</body>

</html>
