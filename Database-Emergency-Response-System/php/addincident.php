<?php

session_start();

if (empty($_SESSION['username'])) {
    header('Location: login.php');
}

/* connect to database */	
$connect = mysql_connect("localhost:3306", "root", "team66");
if (!$connect) {
	die("Failed to connect to database");
}

mysql_select_db("erms") or die("Unable to select database");

function checkCoordinates() {
  if ( ($_POST['long'] > 180) or ($_POST['long'] < -180) ) {
    $invalid = 'longitude';
  }
  else if ( ($_POST['lat'] > 90) or ($_POST['lat'] < -90) ) {
    $invalid = 'latitude';
  }
  return $invalid;
}

function errHandler($msg) {
  print "<p class='error'>[Error creating " . $msg . "]: " . mysql_error() . "</p>";
  exit();
}

function isValidDate($date_str) {
  $date_arr = date_parse($date_str);
  if ( !checkdate($date_arr['month'], $date_arr['day'], $date_arr['year']) ) {
    $invalidDate = $date_str;
    return $invalidDate;
  }
}

function createIncident() {
    // Create the new incident.
    $query = "INSERT INTO Incidents " .
      "(Description, IncidentDate, Longitude, Latitude, Username) " .
      "VALUES ('{$_POST['desc']}', '${_POST['date']}', " . 
      "{$_POST['long']}, {$_POST['lat']}, '{$_SESSION['username']}');";

    $result = mysql_query($query);
    if (!$result) {
      errHandler('Incident');
    }
    $_SESSION['incid'] = mysql_insert_id();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $invalid = checkCoordinates();
  $invalidDate = isValidDate($_POST['date']);
  if (empty($invalid) && empty($invalidDate)) {
    createIncident();
    $added = true;
  }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Add Incident</title>
<!-- <link rel="stylesheet" type="text/css" href="style.css" /> -->

</head>
<body>
  <h1>New Incident Info</h1>

  <form action="addincident.php" method="post">
    <label>Date</label>
    <input type="date" name="date" required><br/><br/>
    <?php
        if (!empty($invalidDate)) {
          print "<p class='error'>Error: " . $invalidDate . " is not a valid date value.</p>";
          unset($invalidDate);
        }
    ?>
    <label>Description</label>
    <input type="text" name="desc" required><br/><br/>
    <?php
        if (!empty($invalid)) {
          print "<p class='error'>Error: Invalid " . $invalid . " value.</p>";
          unset($invalid);
        }
    ?>
    <label>Location</label><br/><br/>
    <label>Latitude</label>
    <input type="number" name="lat" step="0.000001" required>
    <label>Longitude</label>
    <input type="number" name="long" step="0.000001" required>
    <br/><br/>
    <button type="reset" onclick="location.href='menu.php'">Cancel</button>
    <button type="submit">Save</button>
    <?php
        if (isset($added)) {
          print "<p class='error'>Added incident with ID [" . $_SESSION['incid'] . "].</p>";
          unset($added);
        }
    ?>
  </form>
</body>
</html>
