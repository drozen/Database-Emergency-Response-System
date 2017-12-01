<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Schedule Repair</title>
    <!-- <link rel="stylesheet" type="text/css" href="style.css" /> -->
</head>

<body>
<?php
  print "<h1>Schedule Repair for {$_POST['rname']}</h1>";
  print "<form action='schedulerepair.php' method = 'POST'>";
    print "<input type='hidden' value='{$_POST['rscid']}' name='rscid' />";
    print "<input type='hidden' value='{$_POST['ret']}' name='ret' />";
?>
    <label>Number of days: </label>
    <input type='number' min="1" max="999" name='ndays' required><br/><br/>
    <button type='submit'>Submit Repair</button></br><br/>
  </form>

<div class="menu">
    <button type="submit" onclick=location.href='menu.php'>Menu</button>
    <button type="submit" onclick=location.href='logout.php'>Logout</button>
</div>

</body>
</html>
