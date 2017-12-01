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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Search Resources</title>
<script type="text/javascript">
function enableDistance() {
  var opt = document.getElementById("selectopt");
  if (opt.value == "0") {
    document.getElementById("distance").readOnly = true;
    document.getElementById("distance").value = '';
  }
  else {
    document.getElementById("distance").readOnly = false;
  }
}
</script>
</head>
<body>
<h1>Search Resource</h1>

<form action="searchresults.php" method="post">
    <!-- keyword -->
    <label>Keyword</label>
    <input type="text" name="keyword"><br/><br/>

    <!-- esf select -->
    <label>ESF</label>
    <select name="esf">
    <option selected="default" value="0">-- All ESFs --</option>
    <?php
        $query = "SELECT * FROM Functions";
        $result = mysql_query($query);
        while ($row = mysql_fetch_array($result)) {
          print "<option value='" . $row['ESFId'] . "'>(" . $row['ESFId'] . ") " . $row['ESFDescription'] . "</option>";
        }
    ?>
    </select><br/><br/>

    <!-- location select -->
    <div style="width:60%;padding-bottom:15px;">
        <div class="item_label" style="display:inline-block;">Location:</div>
        <div style="display:inline-block;">Within <input type="number" id="distance" name="distance" step="0.01" width = "6" readonly> Kilometers of incident</div>
    </div>

    <!-- Incident Select -->
    <div>
    <select name="incident" onchange="enableDistance()" id="selectopt">
    <option selected="default" value="0">No Incident</option>
    <?php
        $query = "SELECT * FROM Incidents WHERE Incidents.Username = '{$_SESSION['username']}'";
        $result = mysql_query($query);
        while ($row = mysql_fetch_array($result)) {
          print "<option value='" . $row['IncidentID'] . "'>(" . $row['IncidentID'] . ") " . $row['Description'] . "</option>";
        }
    ?>
    </select><br/><br/>
    <button type="reset" onclick="location.href='menu.php'">Cancel</button>
    <button type="submit">Search</button>
    </div>

</form>

</body>
</html>
