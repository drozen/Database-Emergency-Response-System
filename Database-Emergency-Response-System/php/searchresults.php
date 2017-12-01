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
    function errHandler($msg) {
        print "<p class='error'>[Error creating " . $msg . "]: " . mysql_error() . "</p>";
        exit();
    }

    function storeCoordinates($incident) {
        $query = "SELECT RADIANS(Longitude) AS incidentLong, RADIANS(Latitude) AS incidentLat " .
            "FROM Incidents " .
            "WHERE IncidentID = " . $incident . ";";
        unset($result);
        $result = mysql_query($query);
        if (!$result) {
            print "<p class='error'>Error: Incident could not be found. Contact system admin.</p>";
            exit();
        }
        $row = mysql_fetch_array($result);
        $_SESSION['long'] = $row['incidentLong'];
        $_SESSION['lat'] = $row['incidentLat'];
    }
    // Retrieve longitude and latitude in radians.
    if ($_POST['incident'] != 0) {
        storeCoordinates($_POST['incident']);
    }
    // build query
    $query2 = "SELECT Resources.ResourceID as rid, Resources.RscName as rname, User.Name as uname, Resources.Username as ruser, ".
        "CONCAT('$',Resources.Cost,'/', Resources.TimePeriod) as cost, Resources.RscStatus as rscs, ".
        "CASE ".
        "WHEN Resources.RscStatus = 'Available' THEN CURDATE() ".
        "ELSE nad2.nextDate ".
        "END as available ";
    if ($_POST['incident'] != 0) {
        $query2 .= ", @lat1 := RADIANS(Resources.Latitude), @dla := @lat1 - {$_SESSION['lat']}, ".
            "@dlo := RADIANS(Resources.Longitude) - {$_SESSION['long']}, ".
            " @a := SIN(@dla/2) * SIN(@dla/2) + COS(@lat1) * COS({$_SESSION['lat']}) * SIN(@dlo/2) * SIN(@dlo/2), ".
            "@c := 2 * ATAN2( SQRT(@a), SQRT(1-@a) ), ".
            "ROUND(@distance := 6371 * @c, 1) AS totalDist";
    }
    $query2 .= " FROM Resources INNER JOIN User on Resources.Username = User.Username ".
        "LEFT OUTER JOIN (SELECT ResourceID, MAX(nextAvail) as nextDate FROM ".
        "(SELECT ResourceID, DATE_ADD(StartDate,INTERVAL NumDays DAY) as nextAvail ".
        "FROM Repair ".
        "UNION ".
        "SELECT ResourceID, ReturnDate as nextAvail FROM Deploys) as nad GROUP BY ResourceID) as nad2 ".
        "ON Resources.ResourceID = nad2.ResourceID ";
    $strQ = "WHERE";
    if (!empty($_POST['keyword'])) {
        $query2 .= "WHERE (Resources.RscName LIKE '%{$_POST['keyword']}%' OR Resources.Model LIKE '%{$_POST['keyword']}%' ".
            "OR Resources.ResourceID IN (SELECT ResourceID FROM Capabilities WHERE Capability LIKE '%{$_POST['keyword']}%')) ";
        $strQ = "AND";
    }
    if ($_POST['esf']!=0) {
        $query2 .= "$strQ Resources.ResourceID IN (SELECT ResourceID FROM PrimaryESF ".
            "WHERE ESFId = {$_POST['esf']} ".
            "UNION ".
            "SELECT ResourceID FROM ResourceESFs ".
            "WHERE ESFId = {$_POST['esf']}) ";
        if($strQ == "WHERE"){
            $strQ = "AND";
        }
    }
    if($_POST['distance']!=0){
        $query2 .= "HAVING totalDist <= {$_POST['distance']} ";
    }
    $query2 .= "ORDER BY ";
    if($_POST['incident'] != 0){
        $query2 .= "totalDist, ";
    }
    $query2 .="available, rname";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Search Results</title>

    <h1>Search Results</h1>

    <?php
    $result2 = mysql_query($query2);
    if (!$result2) {
        print "<p class='error'>Error: test</p>";
        exit();
    }
    if (mysql_num_rows($result2) == 0) {
        print "<p>No resources match criteria.</p>";
    } else {
        print "<table width='100%' border='1'>";
        print "<tr>";
        print "<th>ID</th>";
        print "<th>Name</th>";
        print "<th>Owner</th>";
        print "<th>Cost</th>";
        print "<th>Status</th>";
        print "<th>Next Available</th>";
        // If the user selected an incident be sure to add the following two columns.
        if ($_POST['incident'] != 0) {
            print "<th>Distance</th>";
            print "<th>Action</th>";
        }
        print "</tr>";
        while ($row = mysql_fetch_array($result2)) {
            print "<tr>";
            print "<td>{$row['rid']}</td>";
            print "<td>{$row['rname']}</td>";
            print "<td>{$row['uname']}</td>";
            print "<td>{$row['cost']}</td>";
            print "<td>{$row['rscs']}</td>";
            print "<td>{$row['available']}</td>";
            // Incident has been used for search criteria, so a resource can be requested or deployed.
            if ($_POST['incident'] != 0) {
                print "<td>{$row['totalDist']} km</td>";
                print "<td>";
                if ($row['rscs'] != "In Repair") {
                    // The resource is owned by the searching user.
                    if ($row['ruser'] == $_SESSION['username']) {
                        // If we own it, and it's available, it can be deployed immediately.
                        if ($row['rscs'] == "Available") {
                            print "<form action='makerequest.php' method = 'POST' style='display:inline-block'>";
                            print "<input type='hidden' value='{$row['rid']}' name='rscid' />";
                            print "<input type='hidden' value='{$_POST['incident']}' name='incid' />";
                            print "<input type='hidden' value='deploy' name='ret' />";
                            print "<button type='submit'>Deploy</button>";
                            print "</form>";
                        }
                        print "<form action='repairDays.php' method = 'POST' style='display:inline-block'>";
                        print "<input type='hidden' value='{$row['rid']}' name='rscid' />";
                        print "<input type='hidden' value='{$row['rname']}' name='rname' />";
                        print "<input type='hidden' value='resourcestatus.php' name='ret' />";
                        print "<button type='submit'>Repair</button>";
                        print "</form>";
                    } else {
                        print "<form action='makerequest.php' method = 'POST' style='display:inline-block'>";
                        print "<input type='hidden' value='{$row['rid']}' name='rscid' />";
                        print "<input type='hidden' value='{$_POST['incident']}' name='incid' />";
                        print "<input type='hidden' value='resourcestatus.php' name='ret' />";
                        print "<button type='submit'>Request</button>";
                        print "</form>";
                    }
                }
                print "</td>";
            }
            print "</tr>";
        }
        print "</table>";
    }
    ?>

    <br/><br/>
    <button type="reset" onclick="location.href='menu.php'">Close</button>
    <button type="reset" onclick="location.href='searchresources.php'">Return to Search Resources</button>

</head>
<body>
</body>
</html>
