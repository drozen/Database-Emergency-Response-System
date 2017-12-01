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
    $query = "DELETE FROM repair WHERE ResourceID = {$_POST['rscid']} AND StartDate = {$_POST['sdate']}";
    $result = mysql_query($query);
    if (!$result) {
        print '<p class="error">Error: ' . mysql_error() . '</p>';
        exit();
    } else {
        header("Location: {$_POST['ret']}");
    }
?>