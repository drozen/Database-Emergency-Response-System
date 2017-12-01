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

$query = "SELECT Name " . 
		 "FROM User " .
		 "WHERE Username = '{$_SESSION['username']}'";

$result = mysql_query($query);
if (!$result) {
	print "<p class='error'>Error: " . mysql_error() . "</p>";
	exit();
}

$row = mysql_fetch_array($result);
$_SESSION['name'] = $row['Name'];
unset($result);

function checkCoordinates() {
  if ( ($_POST['long'] > 180) or ($_POST['long'] < -180) ) {
    $invalid = 'longitude';
  }
  else if ( ($_POST['lat'] > 90) or ($_POST['lat'] < -90) ) {
    $invalid = 'latitude';
  }
  return $invalid;
}

function checkNegative() {
  if ($_POST['cost'] < 0) {
    $invalid = 'negative';
  }
  return $invalid;
}

function errHandler($msg) {
  print "<p class='error'>[Error creating " . $msg . "]: " . mysql_error() . "</p>";
  exit();
}

function createResource() {
    // Create the new resource.
    $model = empty($_POST['model']) ? "NULL" : "'{$_POST['model']}'";
    $query = "INSERT INTO Resources " .
      "(RscName, Model, Longitude, Latitude, Cost, TimePeriod, " .
      "RscStatus, Username)" .
      "VALUES ('{$_POST['rname']}', $model, " . 
      "{$_POST['long']}, {$_POST['lat']}, {$_POST['cost']}, '{$_POST['period']}', " . 
      "'Available', '{$_SESSION['username']}');";

    $result = mysql_query($query);
    if (!$result) {
      errHandler('Resource');
    }
    $_SESSION['rid'] = mysql_insert_id();
}

// Add Primary ESF of resource to its table.
function createPrimaryEsf() {
    $query = "INSERT INTO PrimaryESF " .
      "VALUES ({$_SESSION['rid']}, {$_POST['primary']});";

    $result = mysql_query($query);
    if (!$result) {
      errHandler('PrimaryESF');
    }
}

// Add additional ESFs if present.
function createAdditionalEsf() {
  if (isset($_POST['additional'])) {
    foreach ($_POST['additional'] as $add) {
      if ($add != $_POST['primary']) {
        $query = "INSERT INTO ResourceESFs VALUES ({$_SESSION['rid']}, $add);";
        $result = mysql_query($query);
        if (!$result) {
          errHandler('additional ESF');
        }
      }
    }
  }
}

// Add capabilities if they exist.
function createCapabilities() {
  if (isset($_POST['caps'])) {
    foreach ($_POST['caps'] as $cap) {
      print "Capability to be added: $cap";
      $query = "INSERT INTO Capabilities VALUES ({$_SESSION['rid']}, '$cap');";
      $result = mysql_query($query);
      if (!$result) {
        errHandler('Capabilities');
      }
    }
  }
}

/*
 * Cost is unsigned integer so we don't need to check it with PHP.
 *
 * We do need to ensure longitude and latitude values are legal.
 *
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $invalidCoor = checkCoordinates();
  $invalidNeg = checkNegative();
  // Don't add the resource if either of the input constraints aren't met.
  if ( (empty($invalidCoor)) and (empty($invalidNeg)) ) {
    createResource();
    createPrimaryEsf();
    createAdditionalEsf();
    createCapabilities();
    $added = true;
  }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Add Resource</title>
<!-- <link rel="stylesheet" type="text/css" href="style.css" /> -->
<script type="text/javascript">
function appendCaps() {
    var str = document.getElementById("newcap");
    var select = document.getElementById("caps");
    var option = document.createElement("option");
    option.text = str.value;
    option.value = str.value;
    option.selected = true;

    select.add(option);
    document.getElementById("newcap").value = '';
}
</script>
</head>
<body>
<h1>New Resource Info</h1>
<?php
    print "<h3>Owner: {$_SESSION['name']}</h3>";       
?>

<form action="addresource.php" method="post">
    <label>Resource Name</label>
    <input type="text" name="rname" required><br/><br/>
    <label>Primary ESF</label>
    <select name="primary">
    <?php
        $query = "SELECT * FROM Functions";
        $result = mysql_query($query);
        while ($row = mysql_fetch_array($result)) {
          print "<option value='" . $row['ESFId'] . "'>(" . $row['ESFId'] . ") " . $row['ESFDescription'] . "</option>";
        }
    ?>
    </select><br/><br/>
    <label>Additional ESFs</label>
    <select name="additional[]" multiple>
    <?php
        $query = "SELECT * FROM Functions";
        $result = mysql_query($query);
        while ($row = mysql_fetch_array($result)) {
          print "<option value='" . $row['ESFId'] . "'>(" . $row['ESFId'] . ") " . $row['ESFDescription'] . "</option>";
        }
    ?>
    </select><br/><br/>
    <label>Model</label>
    <input type="text" name="model"><br/><br/>

    <label>Capabilities</label>
    <select name="caps[]" id="caps" multiple>
    </select><br/><br/>
    <input type="text" name="newcap" id="newcap">
    <button onclick="appendCaps()">Add</button>
    
    <h3>Home Location</h3>
    <?php
        if (!empty($invalidCoor)) {
          print "<p class='error'>Error: Invalid " . $invalidCoor . " value.</p>";
          unset($invalidCoor);
        }
        if (!empty($invalidNeg)) {
          print "<p class='error'>Error: Invalid " . $invalidNeg . " value for cost.</p>";
          unset($invalidNeg);
        }
    ?>
    <label>Latitude</label>
    <input type="number" name="lat" step="0.000001" required>
    <label>Longitude</label>
    <input type="number" name="long" step="0.000001" required>

    <h3>Cost</h3>
    <label>$</label>
    <input type="number" name="cost" step="0.01" required>
    <label>per</label>
    <select name="period">
    <?php
        $query = "SELECT * FROM PayPeriods";
        $result = mysql_query($query);
        while ($row = mysql_fetch_array($result)) {
          print "<option value='" . $row['TimePeriod'] . "'>" . $row['TimePeriod'] . "</option>";
        }
    ?>
    </select><br/><br/>
    <button type="reset" onclick="location.href='menu.php'">Cancel</button>
    <button type="submit">Save</button>
    <?php
        if (isset($added)) {
          print "<p class='error'>Added resource with ID [" . $_SESSION['rid'] . "].</p>";
          unset($added);
        }
    ?>
</form>

</body>
</html>
