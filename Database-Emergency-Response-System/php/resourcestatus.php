<?php

/* connect to database */
$connect = mysql_connect("localhost:3306", "root", "team66");
if (!$connect) {
    die("Failed to connect to database");
}
mysql_select_db("erms") or die( "Unable to select database");
session_start();
if (empty($_SESSION['username'])) {
    header('Location: login.php');
}
$query = "SELECT Name FROM User WHERE Username = '{$_SESSION['username']}'";
unset($result);
$result = mysql_query($query);
$fname = "me";
if ($result) {
    $row = mysql_fetch_array($result);
    $fname=$row['Name'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ERMS Resource Status</title>
    <!-- <link rel="stylesheet" type="text/css" href="style.css" /> -->
</head>

<body>

<div id="main_container">

    <div class="center_content">

        <div class="center_left">
            <div class="title_name"><h1> Resource Status </h1></div>
            <div class="features">

                <div class="profile_section">

                    <!-- First Table -->
                    <h3>Resources in use</h3>


                    <?php
                    $query = "SELECT Deploys.IncidentID as incID, Resources.ResourceID as rid, Resources.RscName as RName, Incidents.Description as Des, ".
                        "User.Name as UName, Deploys.StartDate as SDate, Deploys.ReturnDate as RDate ".
                        "FROM Deploys ".
                        "INNER JOIN Incidents ON Incidents.IncidentID = Deploys.IncidentID ".
                        "INNER JOIN Resources ON Resources.ResourceID = Deploys.ResourceID ".
                        "INNER JOIN User ON Resources.Username = User.Username ".
                        "WHERE Deploys.Active = 1 AND Incidents.Username = '{$_SESSION['username']}'";
                    $result = mysql_query($query);
                    if(!$result){
                        print "Could not retrieve deployed data";
                        exit();
                    }
                    if (mysql_num_rows($result) == 0) {
                        print "No resources currently in use by $fname";
                    } else {
                        print "<table width='80%' border='1'>";
                        print "<tr>";
                        print "<td class='heading'>ID</td>";
                        print "<td class='heading'>Resource Name</td>";
                        print "<td class='heading'>Incident</td>";
                        print "<td class='heading'>Owner</td>";
                        print "<td class='heading'>Start Date</td>";
                        print "<td class='heading'>Return by</td>";
                        print "<td class='heading'>Action</td>";
                        print "</tr>";
                        while ($row = mysql_fetch_array($result)){
                            print "<tr>";
                            print "<td>{$row['rid']}</td>";
                            print "<td>{$row['RName']}</td>";
                            print "<td>{$row['Des']}</td>";
                            print "<td>{$row['UName']}</td>";
                            print "<td>{$row['SDate']}</td>";
                            print "<td>{$row['RDate']}</td>";
                            print "<td>";
                            print "<form action='returnResource.php' method = 'POST'>";
                            print "<input type='hidden' value='{$row['rid']}' name='rscid' />";
                            print "<input type='hidden' value='{$row['incID']}' name='incid' />";
                            print "<input type='hidden' value='{$row['RDate']}' name='rdate' />";
                            print "<input type='hidden' value='resourcestatus.php' name='ret' />";
                            print "<button style='display:inline-block' type='submit'>Return</button>";
                            if($row['RDate']<date('Y-m-d')){
                                print "<p style='color:red;display:inline-block;margin-left:5px'>*Past due*</p>";
                            }
                            print "</form>";
                            print "</td>";
                            print "</tr>";
                        }
                        print "</table>";
                    }
                    ?>
                    <!-- end table 1 -->

                    <!-- Second Table -->
                    <?php
                    print "<h3>Resources Requested by $fname</h3>";
                    ?>


                    <?php
                    $query2 = "SELECT Requests.IncidentID as incID, Requests.ResourceID as rid, Resources.RscName as RName, Incidents.Description as Des, ".
                        "User.Name as UName, Requests.ReturnDate as RDate ".
                        "FROM Requests ".
                        "INNER JOIN Incidents ON Incidents.IncidentID = Requests.IncidentID ".
                        "INNER JOIN Resources ON Resources.ResourceID = Requests.ResourceID ".
                        "INNER JOIN User ON Resources.Username = User.Username ".
                        "WHERE Incidents.Username = '{$_SESSION['username']}'".
                        "AND (Requests.IncidentID, Requests.ResourceID) NOT IN (SELECT IncidentID, ResourceID FROM deploys)";
                    $result2 = mysql_query($query2);
                    if(!$result2){
                        print "Could not retrieve request data";
                        exit();
                    }
                    if (mysql_num_rows($result2) == 0) {
                        print "No resources currently requested by $fname";
                    } else {
                        print "<table width='80%' border='1'>";
                        print "<tr>";
                        print "<td class='heading'>ID</td>";
                        print "<td class='heading'>Resource Name</td>";
                        print "<td class='heading'>Incident</td>";
                        print "<td class='heading'>Owner</td>";
                        print "<td class='heading'>Return by</td>";
                        print "<td class='heading'>Action</td>";
                        print "</tr>";
                        while ($row = mysql_fetch_array($result2)){
                            print "<tr>";
                            print "<td>{$row['rid']}</td>";
                            print "<td>{$row['RName']}</td>";
                            print "<td>{$row['Des']}</td>";
                            print "<td>{$row['UName']}</td>";
                            print "<td>{$row['RDate']}</td>";
                            print "<td>";
                            print "<form action='deleteRequest.php' method = 'POST'>";
                            print "<input type='hidden' value='{$row['rid']}' name='rscid' />";
                            print "<input type='hidden' value='{$row['incID']}' name='incid' />";
                            print "<input type='hidden' value='resourcestatus.php' name='ret' />";
                            print "<button type='submit'>Cancel</button>";
                            print "</form>";
                            print "</td>";
                            print "</tr>";
                        }
                        print "</table>";
                    }
                    ?>
                    <!-- end table 2 -->

                    <!-- Third Table -->
                    <?php
                    print "<h3>Resources Requests received by $fname</h3>";
                    ?>

                    <?php
                    $query3 = "SELECT Requests.IncidentID as iid, Requests.ResourceID as rid, Resources.RscName as RName, Incidents.Description as Des, ".
                        "User.Name as UName, Requests.ReturnDate as RDate, Resources.RscStatus as rscs ".
                        "FROM Requests ".
                        "INNER JOIN Incidents ON Incidents.IncidentID = Requests.IncidentID ".
                        "INNER JOIN Resources ON Resources.ResourceID = Requests.ResourceID ".
                        "INNER JOIN User ON Incidents.Username = User.Username ".
                        "WHERE Resources.Username = '{$_SESSION['username']}'".
                        "AND (Requests.IncidentID, Requests.ResourceID) NOT IN (SELECT IncidentID, ResourceID FROM deploys)";
                    $result3 = mysql_query($query3);
                    if(!$result3){
                        print "Could not retrieve request data";
                        exit();
                    }
                    if (mysql_num_rows($result3) == 0) {
                        print "No resources requests received by $fname";
                    } else {
                        print "<table width='80%' border='1'>";
                        print "<tr>";
                        print "<td class='heading'>ID</td>";
                        print "<td class='heading'>Resource Name</td>";
                        print "<td class='heading'>Incident</td>";
                        print "<td class='heading'>Requested by</td>";
                        print "<td class='heading'>Return by</td>";
                        print "<td class='heading'>Action</td>";
                        print "</tr>";
                        while ($row = mysql_fetch_array($result3)){
                            print "<tr>";
                            print "<td>{$row['rid']}</td>";
                            print "<td>{$row['RName']}</td>";
                            print "<td>{$row['Des']}</td>";
                            print "<td>{$row['UName']}</td>";
                            print "<td>{$row['RDate']}</td>";
                            print "<td>";
                            if($row['rscs'] == "Available"){
                                print "<form action='deployResource.php' method = 'POST' style='display:inline-block'>";
                                print "<input type='hidden' value='{$row['rid']}' name='rscid' />";
                                print "<input type='hidden' value='{$row['iid']}' name='incid' />";
                                print "<input type='hidden' value='{$row['RDate']}' name='rdate' />";
                                print "<input type='hidden' value='resourcestatus.php' name='ret' />";
                                print "<button type='submit'>Deploy</button>";
                                print "</form>";
                            }
                            print "<form action='deleteRequest.php' method = 'POST' style='display:inline-block'>";
                            print "<input type='hidden' value='{$row['rid']}' name='rscid' />";
                            print "<input type='hidden' value='{$row['iid']}' name='incid' />";
                            print "<input type='hidden' value='resourcestatus.php' name='ret' />";
                            print "<button type='submit'>Reject</button>";
                            print "</form>";
                            print "</td>";
                            print "</tr>";
                        }
                        print "</table>";
                    }
                    ?>
                    <!-- end table 3 -->

                    <!-- Fourth Table -->
                    <h3>Repairs Scheduled</h3>

                    <?php
                    $query4 = "SELECT Repair.ResourceID as rid, Resources.RscName as RName, Repair.StartDate as SDate, Repair.NumDays as nd, ".
                        " Repair.Started as started FROM repair ".
                        "INNER JOIN resources ON resources.ResourceID = repair.ResourceID ".
                        "WHERE resources.Username = '{$_SESSION['username']}' ".
                        "AND (DATE_ADD(Repair.StartDate, INTERVAL Repair.NumDays DAY) > CURDATE() ".
                        "OR Repair.Started = 0)";
                    $result4 = mysql_query($query4);
                    if(!$result4){
                        print "Could not retrieve repair data";
                        exit();
                    }
                    if (mysql_num_rows($result4) == 0) {
                        print "<p>No repairs in progress</p><br/>";
                    }else{
                        print "<table width='80%' border='1'>";
                        print "<tr>";
                        print "<td class='heading'>ID</td>";
                        print "<td class='heading'>Resource Name</td>";
                        print "<td class='heading'>Start On</td>";
                        print "<td class='heading'>Ready By</td>";
                        print "<td class='heading'>Action</td>";
                        print "</tr>";
                        while ($row = mysql_fetch_array($result4)){
                            print "<tr>";
                            print "<td>{$row['rid']}</td>";
                            print "<td>{$row['RName']}</td>";
                            $SDate = strtotime($row['SDate']);
                            $today = strtotime(date("Y-m-d"));
                            $return = $SDate + (60*60*24*$row['nd']);
                            print "<td>".date("d M Y",$SDate)."</td>";
                            print "<td>".date("d M Y",$return)."</td>";
                            print "<td>";
                            if($SDate > $today || !$row['started']){
                                print "<form action='cancelRepair.php' method = 'POST'>";
                                print "<input type='hidden' value='{$row['rid']}' name='rscid' />";
                                print "<input type='hidden' value='".date("Ymd",$SDate)."' name='sdate' />";
                                print "<input type='hidden' value='resourcestatus.php' name='ret' />";
                                print "<button style='display:inline-block' type='submit'>Cancel</button>";
                                if($SDate < $today && !$row['started']){
                                    print "<p style='color:red;display:inline-block;margin-left:5px'> *Not yet in repair; awaiting late return*</p>";
                                }
                                print "</form>";
                            }
                            else {
                                print "Repair is in progress";
                            }
                            print "</td>";
                            print "</tr>";
                        }
                        print "</table><br/>";
                    }
                    ?>
                    <!-- end table 4 -->


                </div>

            </div>

            <div class="menu">
                <button type="submit" onclick=location.href='menu.php'>Menu</button>
                <button type="submit" onclick=location.href='logout.php'>Logout</button>
            </div>

        </div>

        <div class="clear"></div>

    </div>


</div>

</body>
</html>
