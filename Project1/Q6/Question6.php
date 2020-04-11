<?php
$mysqli = NEW MySQLi('localhost', 'root', '', 'g3t02');

$resultSet = $mysqli->query("SELECT sid FROM student");

$color1 = "lightblue";
$color2 = "teal";
$color = $color1;
?>


<form action='Question6_1.php' method='post'>
	<select name="student">
	<option value="" selected>-- Select Departments -- </option>
	<?php
	while ($rows = $resultSet->fetch_assoc())
	{
		$color == $color1 ? $color = $color2 : $color = $color1;
		
		$stu_id = $rows['sid'];
		echo "<option value='$stu_id' style='background:$color;'>
		$stu_id</option>";
	}
	?>
	</select>
		
	Date: <input type="text" name="Date">
	<input type="submit" name="" value="check">
</form>