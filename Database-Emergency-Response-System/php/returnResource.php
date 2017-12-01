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

    //set todays date, update deploy table
    $today = date('Ymd');
    $query = "UPDATE Deploys SET ReturnDate = $today, Active = 0 WHERE ResourceID = {$_POST['rscid']} AND IncidentID = {$_POST['incid']}";

    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error returning </p>';
        exit();
    } else {

        //Check repair table for scheduled repair
        $query2 = "SELECT MAX(StartDate) as mdate FROM repair WHERE ResourceID = {$_POST['rscid']}";
        $result2 = mysql_query($query2);

        //check for errors
        if (!$result2){
            print '<p class="error">Error checking repairs</p>';
            exit();
        } else {
            //get max repair startdate
            $row = mysql_fetch_array($result2);

            //if date is found and it matches return date
            if(mysql_num_rows($result2) == 1 && $row['mdate'] == $_POST['rdate']){
                $today = date('Y-m-d');
                //move repair startdate to date of return
                $query3 = "UPDATE Repair SET StartDate = '$today', Started = 1 WHERE ResourceID = {$_POST['rscid']} AND StartDate = '{$row['mdate']}'";
                $result3 = mysql_query($query3);
                if (!$result3){
                    print '<p class="error">Error updating repair Start Date</p>';
                    exit();
                } else {
                    //set resource status to in repair
                    $query4 = "UPDATE Resources SET RscStatus = 'In Repair' WHERE ResourceID = {$_POST['rscid']} ";
                    $result4 = mysql_query($query4);
                    if (!$result4){
                        print '<p class="error">Error updating resource status</p>';
                        exit();
                    } else {
                        header("Location: {$_POST['ret']}");
                    }
                }

            } else {

                //if no scheduled repair found, set status to available
                $query3 = "UPDATE Resources SET RscStatus = 'Available' WHERE ResourceID = {$_POST['rscid']} ";
                $result3 = mysql_query($query3);
                if (!$result3){
                    print '<p class="error">Error updating status</p>';
                    exit();
                } else{
                    header("Location: {$_POST['ret']}");
                }
            }
        }
    }
?>