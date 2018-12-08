<!doctype html>

<html>

<!--
    
    Project 02.09.05
    
    Author: Abraham Aguilar
    Date: 12.05.18
    
    AvailableSeminars.php
    
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
    if (isset($_REQUEST['userID'])) { // carries intern ID from other pages.
        $userID = $_REQUEST['userID'];
    } else { // reached without a submit from another page.
        $userID = -1;
    }
    
    $errors = 0;
    $hostName = "localhost";
    $username = "adminer";
    $passwd = "hurry-leave-06";
    $DBConnect = false;
    $DBName = "professionalConferences";
    
    if ($errors == 0) { // connects to database if no errors.
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
    if ($errors == 0) { // checks for if the user is registerred.
        $SQLstring = "SELECT * FROM $TableName WHERE userID='$userID'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) {
            ++$errors;
            echo "<p>Unable to execute the query.  Error code: " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . ".</p>\n";
        } else {
            if (mysqli_num_rows($queryResult) == 0) {
                ++$errors;
                echo "<p>Invalid User ID.</p>\n";
            }
        }
    }
    if ($errors == 0) { // sets the user's name for user benefits.
        $row = mysqli_fetch_assoc($queryResult);
        $userName = $row['first'] . " " . $row['last'];
    } else { // page was entered without an internID.
        $userName = "";
    }
    
    $TableName = "selected_seminars";
    if ($errors == 0) { // checks for if there is any approved opportunities.
        $SQLstring = "SELECT count(seminarID) FROM $TableName WHERE userID='$userID' AND dateApproved IS NOT NULL";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (mysqli_num_rows($queryResult) > 0) { // there is an approved opportunity.
            $row = mysqli_fetch_row($queryResult);
            $approvedSeminar = $row[0];
            mysqli_free_result($queryResult);
        }
    }
    if ($errors == 0) {
        $selectedSeminars = array(); // stores all opportunities selected by the user.
        $SQLstring = "SELECT seminarID FROM $TableName WHERE userID='$userID'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (mysqli_num_rows($queryResult) > 0) {
            while (($row = mysqli_fetch_row($queryResult)) != false) { // transfers the opportunities to the array.
                $selectedSeminars[] = $row[0];
            }
            mysqli_free_result($queryResult);
        }
        $assignedSeminars = array(); // stores all opportunities selected by the user.
        $SQLstring = "SELECT seminarID FROM $TableName WHERE dateApproved IS NOT NULL";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (mysqli_num_rows($queryResult) > 0) {
            while (($row = mysqli_fetch_row($queryResult)) != false) { // transfers the opportunities to the array.
                $assignedSeminars[] = $row[0];
            }
            mysqli_free_result($queryResult);
        }
    }
    $TableName = "seminars";
    $seminars = array(); // stores all opportunities.
    if ($errors == 0) {
        $SQLstring = "SELECT seminarID, topic, roomNumber, startTime, endTime, description FROM $TableName";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (mysqli_num_rows($queryResult) > 0) {
            while (($row = mysqli_fetch_assoc($queryResult)) != false) { // transfers the opportunities to the array.
                $seminars[] = $row;
            }
        }

    }
    if ($DBConnect) {
        echo "<p>Closing Database Connection...</p>\n";
        mysqli_close($DBConnect);
    }
    if (!empty($lastRequestDate)) {
        echo "<p>You last requested an internship opportunity on $lastRequestDate.</p>\n";
    }
    echo "<form action='SelectSeminars.php' method='post'>\n";
    echo "<input type='hidden' name='userID' value='$userID'>";
    echo "<table border='1' width='100%'>\n";
    echo "<tr>\n";
    echo "<th style='background-color: cyan;'>Topic</th>\n";
    echo "<th style='background-color: cyan;'>Room Number</th>\n";
    echo "<th style='background-color: cyan;'>Start Time</th>\n";
    echo "<th style='background-color: cyan;'>End Time</th>\n";
    echo "<th style='background-color: cyan;'>Description</th>\n";
    echo "<th style='background-color: cyan;'>Status</th>\n";
    echo "</tr>\n";
    foreach ($seminars as $seminar) {
        if (!in_array($seminar['seminarID'], $assignedSeminars)) {
            echo "<tr>\n";
            echo "<td>" . htmlentities($seminar['topic']) . "</td>\n";
            echo "<td>" . htmlentities($seminar['roomNumber']) . "</td>\n";
            echo "<td>" . htmlentities($seminar['startTime']) . "</td>\n";
            echo "<td>" . htmlentities($seminar['endTime']) . "</td>\n";
            echo "<td>" . htmlentities($seminar['description']) . "</td>\n";
            echo "<td>\n";
            if (in_array($seminar['seminarID'], $selectedSeminars)) {
                echo "Selected";
            } else if ($approvedSeminar) {
                echo "Open";
            } else {
                echo "<p><input type='checkbox' name='" . $seminar['seminarID'] . "'> Click to select</p>";
            }
            echo "</td>\n";
            echo "</tr>\n";
        }
    }
    echo "</table>\n";
    echo "<p><input type='submit' name='submit' value='Confirm'> (Choices can be re-done later)</p>\n";
    echo "</form>\n";
    echo "<p><a href='ConferenceLogin.php'>Log Out</a> (Selections will not be saved)</p>\n";
    ?>
</body>

</html>
