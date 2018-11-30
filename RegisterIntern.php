<?php
session_start();

$body = "";
$errors = 0;
$email = "";

if (empty($_POST['email'])) { // nothing was inserted via email.
    ++$errors;
    $body .= "<p>You need to enter an email address.</p>";
} else {
    $email = stripslashes($_POST['email']);
    if (preg_match("/^[\w-]+(\.[\w-])*@[\w-]+(\.[\w-]+)*(\.[A-Za-z]{2,})$/i", $email) == 0) { // checks if the email was properly typed.  calls an error otherwise
        ++$errors;
        $body .= "<p>You need to enter a valid email address.</p>";
        $email = "";
    }
}
if (empty($_POST['password'])) { // nothing was inserted for password
    ++$errors;
    $body .= "<p>You need to enter a password.</p>\n";
} else {
    $password = stripslashes($_POST['password']);
}
if (empty($_POST['password2'])) { // nothing was inserted for password2
    ++$errors;
    $body .= "<p>You need to enter a confirmation password.</p>\n";
} else {
    $password2 = stripslashes($_POST['password2']);
}
if (!empty($password) && !empty($password2)) {
    if (strlen($password) < 6) { // password is too short
        ++$errors;
        $body .= "<p>The password is too short.</p>\n";
        $password = "";
        $password2 = "";
    }
    if ($password <> $password2) { // passwords don't match
        ++$errors;
        $body .= "<p>The passwords do not match.</p>\n";
        $password = "";
        $password2 = "";
    }
}

$hostName = "localhost";
$username = "adminer";
$passwd = "hurry-leave-06";
$DBConnect = false;
$DBName = "internships2";

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
$TableName = "interns";
if ($errors == 0) { // if still no errors, check for duplicate email
    $SQLstring = "SELECT count(*) FROM $TableName WHERE email='$email'";
    $queryResult = mysqli_query($DBConnect, $SQLstring);
    if ($queryResult) {
        $row = mysqli_fetch_row($queryResult);
        if ($row[0] > 0) {
            ++$errors;
            $body .= "<p>The E-mail address entered (" . htmlentities($email) . ") is already registered.</p>\n";
        }
    }
}
if ($errors == 0) { // if still no errors, write to the table
    $first = stripslashes($_POST['first']);
    $last = stripslashes($_POST['last']);
    $SQLstring = "INSERT INTO $TableName (first, last, email, password_md5) VALUES ('$first', '$last', '$email', '" . md5($password) . "')";
    $queryResult = mysqli_query($DBConnect, $SQLstring);
    
    if (!$queryResult) { // failure to write to the table
        ++$errors;
        $body .= "<p>Unable to save your registration information.  Error code: " . mysqli_error($DBConnect) . ".</p>";
    } else {
        // $internID = mysqli_insert_id($DBConnect);
        $_SESSION['internID'] = mysqli_insert_id($DBConnect);
    }
}
if ($errors == 0) { // if STILL no errors, save the data for use
    $internName = $first . " " . $last;
    $body .= "<p>Thank you, $internName. ";
    $body .= "Your new Intern ID is <strong>" . $_SESSION['internID'] . "</strong>.</p>\n";
}
if ($DBConnect) { // if connection is open, close it
    setcookie("internID", $_SESSION['internID']);
    $body .= "<p>Closing Database Connection...</p>\n";
    mysqli_close($DBConnect);
}
if ($errors == 0) { // if no errors, set up the form to transfer data and link to a site
//     $body .= "<form action='AvailableOpportunities.php' method='post'>\n";
//     $body .= "<input type='hidden' name='internID' value='$internID'>";
//     $body .= "<input type='submit' name='submit' value='View Available Opportunities'>";
//     $body .= "</form>\n";
    $body .= "<p><a href='AvailableOpportunities.php?PHPSESSID=" . session_id() . "'>View Available Opportunities</a></p>\n";
}
if ($errors > 0) { // informs the user to fix errors if any
    $body .= "<p>Please use your browser's BACK button to return to the form and fix the errors indicated.</p>\n";
}
?>

<!doctype html>

<html>

<!--
    
    Exercise 02.09.01
    
    Author: Abraham Aguilar
    Date: 11.13.18
    
    RegisterIntern.php
    
-->

<head>
    <title>Internship Registration</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>College Internship</h1>
    <h2>Intern Registration</h2>
    <?php
    echo $body;
    ?>
</body>

</html>
