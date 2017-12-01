<?php
    $connect = mysql_connect("localhost:3306", "root", "team66");
    if (!$connect) {
        die("Failed to connect to database");
    }

    mysql_select_db("erms") or die( "Unable to select database");
    session_start();

    if (empty($_SESSION['username'])) {
        header('Location: login.php');
    }

    $query = "UPDATE Resources SET RscStatus = 'Available' WHERE RscStatus = 'In Repair' ".
            " AND ResourceID IN (SELECT ResourceID FROM Repair WHERE DATE_ADD(Repair.StartDate, INTERVAL Repair.NumDays DAY) = CURDATE())";

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();
    } else {
        header("Location: menu.php");
    }
?>