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
    $errors = 0;
    $email = "";
    
    if (empty($_POST['email'])) {
        ++$errors;
        echo "<p>You need to enter an email address.</p>";
    } else {
        //"/^[\w-]+(\.[\w-]+)*@" . "[\w-]+(\.[\w-]+)*" . "(\.[a-z]{2,})$/i"
        //"/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*(\.[a-z]{2,})$/i"
        //"/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*(\.[A-Za-z]){2,}$/i"
        $email = stripslashes($_POST['email']);
        if (preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*(\.[A-Za-z]){2,}$/i", $email) == 0) {
            ++$errors;
            echo "<p>You need to enter a valid email address.</p>";
            $email = "";
        }
    }
    if (empty($_POST['password'])) {
        ++$errors;
        echo "<p>You need to enter a password.</p>\n";
    } else {
        $password = stripslashes($_POST['password']);
    }
    if (empty($_POST['password2'])) {
        ++$errors;
        echo "<p>You need to enter a confirmation password.</p>\n";
    } else {
        $password2 = stripslashes($_POST['password2']);
    }
    if (!empty($password) && !empty($password2)) {
        if (strlen($password) < 6) {
            ++$errors;
            echo "<p>The password is too short.</p>\n";
            $password = "";
            $password2 = "";
        }
        if ($password <> $password2) {
            ++$errors;
            echo "<p>The passwords do not match.</p>\n";
            $password = "";
            $password2 = "";
        }
    }
    ?>
</body>


</html>
