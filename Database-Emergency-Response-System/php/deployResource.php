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

    $query = "INSERT INTO Deploys(IncidentID, ResourceID, ReturnDate, StartDate, Active) " .
        "VALUES ({$_POST['incid']}, {$_POST['rscid']}, '{$_POST['rdate']}', CURDATE(), 1)";

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: could not deploy.</p>';
        exit();
    }
    else {
        $query1 ="UPDATE Resources " .
            "SET RscStatus = 'In Use' " .
            "WHERE ResourceID = {$_POST['rscid']}";
        $result1 = mysql_query($query1);
        if (!$result1) {
            print '<p class="error">Error updating resource status</p>';
            exit();
        }
        else {
            header("Location: {$_POST['ret']}");
        }
    }


?>
