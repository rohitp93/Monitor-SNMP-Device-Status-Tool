<!DOCTYPE html>
<html>
<head>
<h2 align = "center">Details</h2>
<meta http-equiv="refresh" content="31"/>
</head>

<?php
	include "db.php";

	$conn = mysqli_connect($host, $username, $password,$database);

	if (!$conn)
	{
	   die("Connection failed: " . mysqli_connect_error());
	}
	?>
	<br><br><table style = "width: 80%; text-align: center;">	
	<tr>
	<th  style = "text-align: center;">Sent</th>
	<th  style = "text-align: center;">Lost</th>
	<th  style = "text-align: center;">Last Recorded Uptime</th>
	<th  style = "text-align: center;">Last Updated(Web Server Time)</th>
	</tr>
	<tr><?php $id = $_GET['var']; ?>
	<td><?php $result = mysqli_query($conn,"SELECT * FROM Uptime WHERE id = '$id'"); while ($row = mysqli_fetch_array($result)) {echo $row["Sent"]; ?></td>
	<td><?php echo $row["TLost"]; ?></td>
	<td><?php echo $row["Uptime"]; }?></td>
	<td><?php $current_date = date('d/m/Y H:i:s'); echo $current_date; ?>
	</tr>

	
