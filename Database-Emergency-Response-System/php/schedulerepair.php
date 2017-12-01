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

	$query1 = "SELECT RscStatus FROM Resources WHERE ResourceID = {$_POST['rscid']}";
	$result1 = mysql_query($query1);
	if(!$result1){
		print "error finding resource status";
		exit();
	} else {
		$row = mysql_fetch_array($result1);
		if($row['RscStatus']=='In Use'){
			$query2 = "SELECT ReturnDate as rdate FROM Deploys WHERE ResourceID = {$_POST['rscid']} AND Active = 1";
			$result2 = mysql_query($query2);
			if(!$result2){
				print "Error getting return date from deploys";
				exit();
			} else{
				$row2 = mysql_fetch_array($result2);
				$query3 = "INSERT INTO Repair(ResourceID,StartDate,NumDays,Started) ".
							"VALUES({$_POST['rscid']},'{$row2['rdate']}',{$_POST['ndays']},0)";
				$result3 = mysql_query($query3);
				if(!$result3){
                                        print "<p class='error'>Error scheduling repair:  resource either in-use, or other repair is pending.</p><br/>";
                                        print "<button type='submit' onclick=location.href='menu.php'>Menu</button>";
                                        print "<button type='submit' onclick=location.href='logout.php'>Logout</button>";
					exit();
				} else {
					header("Location: {$_POST['ret']}");
				}
			}
		} else {
			$query4 = "INSERT INTO Repair(ResourceID,StartDate,NumDays,Started) ".
						"VALUES({$_POST['rscid']},CURDATE(),{$_POST['ndays']},1)";
			$result4 = mysql_query($query4);
			if(!$result4){
				print "Error scheduling repair of available resource";
				exit();
			} else {
				$query5 = "UPDATE Resources SET RscStatus = 'In Repair' WHERE ResourceID = {$_POST['rscid']}";
				$result5 = mysql_query($query5);
				if(!$query5){
					print "Error updating resource status";
					exit();
				}
				header("Location: {$_POST['ret']}");
			}

		}
	}

?>
