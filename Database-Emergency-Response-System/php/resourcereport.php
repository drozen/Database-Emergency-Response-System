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

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>ERMS Resource Report</title>
		<!-- <link rel="stylesheet" type="text/css" href="style.css" /> -->
	</head>

	<body>

		<div id="main_container">

			<div class="center_content">

				<div class="center_left">
					<div class="title_name"><h1>Resource Report by Primary Emergency Support Function</h1></div>
					<div class="features">

						<div class="profile_section">

							<table width="80%" border="1">
								<tr>
									<td class="heading"><strong>#</strong></td>
									<td class="heading"><strong>Primary Emergency Support Function</strong></td>
									<td class="heading"><strong>Total Resources</strong></td>
									<td class="heading"><strong>Resources in Use</strong></td>
								</tr>

								<?php

								$query = "SELECT F.ESFId as ESF_NO, ESFDescription, COALESCE(A.In_use_count ,0) as In_use_c,".
                                         " COALESCE(B. Esf_count ,0) as Esf_res_count".
                                         " FROM Functions F".
                                         " LEFT OUTER JOIN ".
                                         "  (SELECT PE.ESFId, count(*) as In_use_count".
                                         "   FROM Resources R, PrimaryESF  PE".
                                         "   WHERE R.Username = '{$_SESSION['username']}'".
                                         "   AND R.RscStatus = 'In Use'".
                                         "   AND R.ResourceID = PE.ResourceID".
                                         "   GROUP BY ESFId) as A".
                                         "   ON F.ESFId = A.ESFId".
                                         " LEFT OUTER JOIN ".
                                         "  (SELECT PE1.ESFId, count(*) as Esf_count".
                                         "   FROM Resources R1, PrimaryESF  PE1".
                                         "   WHERE R1.Username = '{$_SESSION['username']}'".
                                         "   AND R1.ResourceID = PE1.ResourceID".
                                         "   GROUP BY ESFId) as B".
                                         "   ON F.ESFId = B.ESFId".
                                         "   ORDER BY F.ESFId";

								$result = mysql_query($query);
								if (!$result) {
									print "<p class='error'>Error: " . mysql_error() . "</p>";
									exit();
								}

								while ($row = mysql_fetch_array($result)){
									print "<tr>";
									print "<td>{$row['ESF_NO']}</td>";
								    print "<td>{$row['ESFDescription']}</td>";
									print "<td>{$row['Esf_res_count']}</td>";
									print "<td>{$row['In_use_c']}</td>";
									print "</tr>";
								}



								$query1 = "SELECT  count(*) as Tot_res_count".
                                         " FROM Resources ".
                                         " WHERE Username = '{$_SESSION['username']}'";
							    $result1 = mysql_query($query1);
							    if (!$result1) {
									print "<p class='error'>Error: " . mysql_error() . "</p>";
									exit();
		   						}
		   					    $row1 = mysql_fetch_array($result1);
							    print "<tr>";
								print "<td> </td>";
							    print "<td><strong>TOTALS</strong></td>";
								print "<td><strong>{$row1['Tot_res_count']}</strong></td>";


								$query2 = "SELECT  count(*) as Tot_in_use_count".
                                         " FROM Resources ".
                                         " WHERE Username = '{$_SESSION['username']}'".
                                         " AND RscStatus = 'In Use'";
                                $result2 = mysql_query($query2);
							    if (!$result2) {
								    print "<p class='error'>Error: " . mysql_error() . "</p>";
								    exit();
    							}
    							$row2 = mysql_fetch_array($result2);
                                print "<td><strong>{$row2['Tot_in_use_count']}</strong></td>";
                                print "</tr>";


								?>

							</table>


						</div>

					 </div>

                              <br/>
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
