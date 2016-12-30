<!DOCTYPE html>
<html>
<head>
<h2 align = "center">SysUpTime of DEVICES</h2>
<meta http-equiv="refresh" content="31"/>
<title>Assignment 4</title>
</head>

<?php
	include "db.php";

	$conn = mysqli_connect($host, $username, $password, $database, $port);

	if (!$conn)
	{
	   die("Connection failed: " . mysqli_connect_error());
	}
	
	mysqli_select_db($conn,"$database");	

	$result = mysqli_query($conn,"SELECT * FROM Uptime");        
?>
<div>
<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 2px solid black;
}
</style>
<table style = "width: 80%; text-align: center;" align="center" border=1>
<tr>
<th  style = "text-align: center;">IP</th>
<th  style = "text-align: center;">PORT</th>
<th  style = "text-align: center;">COMMUNITY</th>
<th  style = "text-align: center;">UpTime(D, HH:MM:SS.MS)</th>
<th  style = "text-align: center;">STATUS</th>
</tr>

<?php

$first = "FFEEEE";
$last = "FF0000";

while($row = mysqli_fetch_array($result)) 
{

if ($row['Lost']==0)
{
?>
<tr><?php $id = $row["id"]; ?>
<td><a href="data.php?var=<?php echo "$id";?>"><?php echo $row["IP"]; ?></td></a>
<td><?php echo $row["PORT"]; ?></td>
<td><?php echo $row["COMMUNITY"]; ?></td>
<td><?php echo $row["Uptime"]; ?></td>
<td bgcolor="#FFFFFF"></td>
</tr>
<?php
}

elseif ($row['Lost']==1)
{
?>
<tr><?php $id = $row["id"]; ?>
<td><a href="data.php?var=<?php echo "$id";?>"><?php echo $row["IP"]; ?></td></a>
<td><?php echo $row["PORT"]; ?></td>
<td><?php echo $row["COMMUNITY"]; ?></td>
<td><?php echo $row["Uptime"]; ?></td>
<td bgcolor="#FFEEEE"></td>
</tr>
<?php
}

elseif ($row['Lost']>=30)
{
?>	
<tr><?php $id = $row["id"]; ?>
<td><a href="data.php?var=<?php echo "$id";?>"><?php echo $row["IP"]; ?></td></a>
<td><?php echo $row["PORT"]; ?></td>
<td><?php echo $row["COMMUNITY"]; ?></td>
<td><?php echo $row["Uptime"]; ?></td>
<td bgcolor="#FF0000"></td>
</tr>
<?php
}

else
{
?>
<tr><?php $id = $row["id"]; ?>
<td><a href="data.php?var=<?php echo "$id";?>"><?php echo $row["IP"]; ?></td></a>
<td><?php echo $row["PORT"]; ?></td>
<td><?php echo $row["COMMUNITY"]; ?></td>
<td><?php echo $row["Uptime"]; ?></td>
<?php 
$color = dechex(hexdec($first) - ($row['Lost'] * 2056)); 
$red = "#"."$color";
?>
<td bgcolor="<?php echo $red?>">
<?php
}
}
?>

</table>
</div>
<br><br><br><footer><center>Rohit Pothuraju</center></footer>
</html>
