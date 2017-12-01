<?php

/* connect to database */
$connect = mysql_connect("localhost:3306", "root", "team66");
if (!$connect) {
    die("Failed to connect to database");
}
mysql_select_db("erms") or die("Unable to select database");

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$query =
    "SELECT Name, JobTitle, HireDate, HQLocation, Jurisdiction, PopSize " .
    "FROM User " .
    "LEFT OUTER JOIN Individual ON User.Username = Individual.Username " .
    "LEFT OUTER JOIN Company ON User.Username = Company.Username " .
    "LEFT OUTER JOIN GovtAgency ON User.Username = GovtAgency.Username " .
    "LEFT OUTER JOIN Municipality ON User.Username = Municipality.Username " .
    "WHERE User.Username = '{$_SESSION['username']}' ";

$result = mysql_query($query);
if (!$result) {
    print "<p class='error'>Error: User could not be found. Contact system admin.</p>";
    exit();
}

$row = mysql_fetch_array($result);
if (!$row) {
    print "<p>Error: No data returned from database.  Administrator login NOT supported.</p>";
    print "<a href='logout.php'>Logout</a>";
    exit();
}

unset($result);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ERMS Menu</title>
</head>
<body>

<h2>ERMS</h2>
<h3>Main Menu</h3>
<div class="top_right">

    <div class="profile_section">

        <table width="80%">

            <tr>
                <td class="item_label">Name:</td>
                <td><?php print $row['Name']; ?></td>
            </tr>

            <?php
            if ($row['JobTitle'] != NULL) {
                print  "<tr>";
                print  "<td class=\"item_label\">Job Title:</td>";
                print  "<td>" . $row['JobTitle'] . "</td>";
                print  "</tr>";
                if ($row['HireDate'] != NULL) {
                    print  "<tr>";
                    print  "<td class=\"item_label\">Hire Date:</td>";
                    print  "<td>" . $row['HireDate'] . "</td>";
                    print  "</tr>";
                }

            } elseif ($row['HQLocation'] != NULL) {
                print  "<tr>";
                print  "<td class=\"item_label\">Headquarters Location:</td>";
                print  "<td>" . $row['HQLocation'] . "</td>";
                print  "</tr>";
            } elseif ($row['Jurisdiction'] != NULL) {
                print  "<tr>";
                print  "<td class=\"item_label\">Jurisdiction:</td>";
                print  "<td>" . $row['Jurisdiction'] . "</td>";
                print  "</tr>";
            } elseif ($row['PopSize'] != NULL) {
                print  "<tr>";
                print  "<td class=\"item_label\">Population:</td>";
                print  "<td>" . $row['PopSize'] . "</td>";
                print  "</tr>";
            } else {
                print  "<tr>";
                print  "User not classified: please consult system admin.";
                print  "</tr>";
            } ?>

        </table>

    </div>

    <br/><a href="addresource.php">Add Resource</a><br/><br/>
    <a href="addincident.php">Add Emergency Incident</a><br/><br/>
    <a href="searchresources.php">Search Resources</a><br/><br/>
    <a href="resourcestatus.php">Resource Status</a><br/><br/>
    <a href="resourcereport.php">Resource Report</a><br/><br/>
    <a href="logout.php">Exit</a>
    <br />
    <br />
    <br />
    <a href="returnRepairs.php">Return Repairs</a>

</div>

</body>
</html>
