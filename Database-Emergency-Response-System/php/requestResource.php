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
    
    function isValidDate($date_str) {
      $date_arr = date_parse($date_str);
      if ( !checkdate($date_arr['month'], $date_arr['day'], $date_arr['year']) ) {
        $invalidDate = $date_str;
        return $invalidDate;
      }
    }
    
    $invalidDate = isValidDate($_POST['rdate']);
    if (empty($invalidDate)) {
      $query = "INSERT INTO Requests (IncidentID, ResourceID, ReturnDate) " .
          "VALUES ({$_POST['incid']}, {$_POST['rscid']}, '{$_POST['rdate']}')";
      $result = mysql_query($query);
      if (!$result) {
          print "could not insert request";
          exit();
      } else {
          if($_POST['ret']=='deploy'){
              print "<html>";
              print "<body onload='document.dply.submit()'>";
              print "<form action='deployResource.php' method = 'POST' name ='dply'>";
              print "<input type='hidden' value='{$_POST['rscid']}' name='rscid' />";
              print "<input type='hidden' value='{$_POST['incid']}' name='incid' />";
              print "<input type='hidden' value='{$_POST['rdate']}' name='rdate' />";
              print "<input type='hidden' value='resourcestatus.php' name='ret' />";
              print "</form>";
              print "</body>";
              print "</html>";
          } else {
              header("Location: {$_POST['ret']}");
          }
      }
    }
    else {
      print "<p class='error'>Error: " . $invalidDate . " is not a valid date value.</p>";
      print "<div class='menu'>";
      print "<button type='submit' onclick=location.href='menu.php'>Menu</button>";
      print "<button type='submit' onclick=location.href='logout.php'>Logout</button>";
      print "</div>";
      unset($invalidDate);
    }

?>
