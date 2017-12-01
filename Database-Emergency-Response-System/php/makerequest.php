<?php
session_start();

/* connect to database */
$connect = mysql_connect("localhost:3306", "root", "team66");
if (!$connect) {
    die("Failed to connect to database");
}

mysql_select_db("erms") or die("Unable to select database");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Request Resource</title>
    <!-- <link rel="stylesheet" type="text/css" href="style.css" /> -->
</head>

<body>

  <h1>Request Resource</h1>
  <br>

<?php
  $query1 = "SELECT IncidentID, ResourceID FROM " .
      "Requests WHERE IncidentID = {$_POST['incid']} AND ResourceID = {$_POST['rscid']}";

  $result1 = mysql_query($query1);
  if (!$result1) {
    print "Error accessing request table";
    exit();
  } else {
    $row = mysql_fetch_array($result1);
    if (!empty($row['IncidentID'])) {
      print "Resource already been requested/deployed for incident.<br/>";
        print "<button type='submit' onclick=location.href='menu.php'>Menu</button>";
        print "<button type='submit' onclick=location.href='searchresources.php'>Return to Search Resources</button>";

        exit();
    } else {
      print "<form action='requestResource.php' method = 'POST'>";
      print "<input type='hidden' value='{$_POST['rscid']}' name='rscid' />";
      print "<input type='hidden' value='{$_POST['incid']}' name='incid' />";
      print "<input type='hidden' value='{$_POST['ret']}' name='ret' />";
      print "<label>Expected Return Date: </label>";
      print "<input type='date' name='rdate' required><br/><br/>";
      print "<button type='submit'>Submit Request</button></br><br/>";
      print "</form>";
    }
  }

?>

<div class="menu">
    <button type="submit" onclick=location.href='menu.php'>Menu</button>
    <button type="submit" onclick=location.href='logout.php'>Logout</button>
</div>

</body>
</html>
