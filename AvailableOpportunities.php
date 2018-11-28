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
    if (isset($_REQUEST['internID'])) { // carries intern ID from other pages.
        $internID = $_REQUEST['internID'];
    } else { // reached without a submit from another page.
        $internID = -1;
    }
    
    $errors = 0;
    $hostName = "localhost";
    $username = "adminer";
    $passwd = "hurry-leave-06";
    $DBConnect = false;
    $DBName = "internships2";
    
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
    $TableName = "interns";
    if ($errors == 0) { // checks for if the user is registerred.
        $SQLstring = "SELECT * FROM $TableName WHERE internID='$internID'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (!$queryResult) {
            ++$errors;
            echo "<p>Unable to execute the query.  Error code: " . mysqli_errno($DBConnect) . ": " . mysqli_error($DBConnect) . ".</p>\n";
        } else {
            if (mysqli_num_rows($queryResult) == 0) {
                ++$errors;
                echo "<p>Invalid Intern ID.</p>\n";
            }
        }
    }
    if ($errors == 0) { // sets the user's name for user benefits.
        $row = mysqli_fetch_assoc($queryResult);
        $internName = $row['first'] . " " . $row['last'];
    } else { // page was entered without an internID.
        $internName = "";
    }
    
    $TableName = "assigned_opportunities";
    if ($errors == 0) { // checks for if there is any approved opportunities.
        $SQLstring = "SELECT count(opportunityID) FROM $TableName WHERE internID='$internID' AND dateApproved IS NOT NULL";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (mysqli_num_rows($queryResult) > 0) { // there is an approved opportunity.
            $row = mysqli_fetch_row($queryResult);
            $approvedOpportunity = $row[0];
            mysqli_free_result($queryResult);
        }
    }
    if ($errors == 0) {
        $selectedOpportunities = array(); // stores all opportunities selected by the user.
        $SQLstring = "SELECT opportunityID FROM $TableName WHERE internID='$internID'";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (mysqli_num_rows($queryResult) > 0) {
            while (($row = mysqli_fetch_row($queryResult)) != false) { // transfers the opportunities to the array.
                $selectedOpportunities[] = $row[0];
            }
            mysqli_free_result($queryResult);
        }
        $assignedOpportunities = array(); // stores all opportunities selected by the user.
        $SQLstring = "SELECT opportunityID FROM $TableName WHERE dateApproved IS NOT NULL";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (mysqli_num_rows($queryResult) > 0) {
            while (($row = mysqli_fetch_row($queryResult)) != false) { // transfers the opportunities to the array.
                $assignedOpportunities[] = $row[0];
            }
            mysqli_free_result($queryResult);
        }
    }
    $TableName = "opportunities";
    $opportunities = array(); // stores all opportunities.
    if ($errors == 0) {
        $SQLstring = "SELECT opportunityID, company, city, startDate, endDate, position, description FROM $TableName";
        $queryResult = mysqli_query($DBConnect, $SQLstring);
        if (mysqli_num_rows($queryResult) > 0) {
            while (($row = mysqli_fetch_assoc($queryResult)) != false) { // transfers the opportunities to the array.
                $opportunities[] = $row;
            }
        }

    }
    if ($DBConnect) {
        echo "<p>Closing Database Connection...</p>\n";
        mysqli_close($DBConnect);
    }
    echo "<table border='1' width='100%'>\n";
    echo "<tr>\n";
    echo "<th style='background-color: cyan;'>Company</th>\n";
    echo "<th style='background-color: cyan;'>City</th>\n";
    echo "<th style='background-color: cyan;'>Start Date</th>\n";
    echo "<th style='background-color: cyan;'>End Date</th>\n";
    echo "<th style='background-color: cyan;'>Position</th>\n";
    echo "<th style='background-color: cyan;'>Description</th>\n";
    echo "<th style='background-color: cyan;'>Status</th>\n";
    echo "</tr>\n";
    foreach ($opportunities as $opportunity) {
        if (!in_array($opportunity['opportunityID'], $assignedOpportunities)) {
            echo "<tr>\n";
            echo "<td>" . htmlentities($opportunity['company']) . "</td>\n";
            echo "<td>" . htmlentities($opportunity['city']) . "</td>\n";
            echo "<td>" . htmlentities($opportunity['startDate']) . "</td>\n";
            echo "<td>" . htmlentities($opportunity['endDate']) . "</td>\n";
            echo "<td>" . htmlentities($opportunity['position']) . "</td>\n";
            echo "<td>" . htmlentities($opportunity['description']) . "</td>\n";
            echo "<td>\n";
            if (in_array($opportunity['opportunityID'], $selectedOpportunities)) {
                echo "Selected";
            } else if ($approvedOpportunity) {
                echo "Open";
            } else {
                echo "<a href='RequestOpportunity.php?internID=$internID&opportunityID=" . $opportunity['opportunityID'] . "'>Available</a>";
            }
            echo "</td>\n";
            echo "</tr>\n";
        }
    }
    echo "</table>\n";
    echo "<p><a href='InternLogin.php'>Log Out</a></p>\n";
    ?>
</body>

</html>
