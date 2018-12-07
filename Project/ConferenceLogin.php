<!doctype html>

<html>

<!--
    
    Project 02.09.05
    
    Author: Abraham Aguilar
    Date: 12.04.18
    
    ConferenceLogin.php
    
-->

<head>
    <title>Conference Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0">
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>Professional Conferences</h1>
    <h2>Register / Login</h2>
    <!-- Basic form to run the series of webpages -->
    <p>For those that want to register for this conference, please complete the top form to register. Those wanting to adjust or finish their plans, please complete the second form.</p>

    <h3>New User Registration</h3>
    <form action="RegisterUser.php" method="post">
        <p>
            Enter your name: First
            <input type="text" name="first">
            Last
            <input type="text" name="last">
        </p>
        <p>
            Enter your e-mail address:
            <input type="text" name="email">
        </p>
        <p>
            Enter your phone number:
            <input type="text" name="phone" placeholder="nnn-nnn-nnnn">
        </p>
        <p>
            Enter a password for your account:
            <input type="password" name="password">
        </p>
        <p>
            Confirm your password:
            <input type="password" name="password2">
        </p>
        <p><em>(Passwords are case-sensitive and must be at least 6 characters long)</em></p>
        <input type="reset" name="reset" value="Reset Registration Form">&nbsp;&nbsp;<input type="submit" name="register" value="Register">
    </form>
    <h3>Returning User Login</h3>
    <form action="VerifyUser.php" method="post">
        Enter your e-mail address:
        <input type="text" name="email">
        </p>
        <p>
            Enter your password:
            <input type="password" name="password">
        </p>
        <p><em>(Passwords are case-sensitive and must be at least 6 characters long)</em></p>
        <input type="reset" name="reset" value="Reset Login Form">&nbsp;&nbsp;<input type="submit" name="login" value="Log In">
    </form>
</body>

</html>
