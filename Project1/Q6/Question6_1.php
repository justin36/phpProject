<html>
	<body>
		<?php

		$sid = $_POST['student'];
		$bid_datetime = $_POST['Date'];
		if ($bid_datetime < '2018-11-07 10:00:00'){
			//Step 1: Connect to the database
			$conn = mysqli_connect('localhost', 'root', '', 'g3t02');
			if (!$conn) 
			{	exit('Could Not Connect' .
				mysqli_connect_error($conn));
			}
			
			//Step 2: Prepare Statement	
			$pQuery = "select sid, stu_name, init_edollars, ifnull(succbidding, 0) as succbidding, ifnull(pendbidding, 0) as pendbidding from(
						select s.sid, stu_name, init_edollars,
						(select sum(edollars) from bidding where rid='0' and outcome = '1' and bidding.sid = s.sid and bid_datetime <= ?) as succbidding,
						(select sum(edollars) from bidding where bidding.sid = s.sid and bid_datetime <= ?) as pendbidding
						from bidding b right outer join student s on b.sid = s.sid) as temp
						where sid = ?
						group by sid";
							
			$stmt = mysqli_prepare($conn, $pQuery);
			mysqli_stmt_bind_param($stmt, 'sss', $bid_datetime, $bid_datetime, $sid);
			$sid = $_POST['student'];
			$bid_datetime = $_POST['Date'];
			mysqli_stmt_execute($stmt);
			
			//Step 4a: Bind result variables	
			mysqli_stmt_bind_result($stmt, $sid_s, $stu_name_s, $initial_edollars_s, $succbidding_s, $pendbidding_s);
		?>
			
			<style>
			table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
			}
			th, td {
			padding: 15px;
			}
			table#t01 tr:nth-child(even) {
			background-color: #eee;
			}
			table#t01 tr:nth-child(odd) {
			background-color: #fff;
			}
			table#t01 th {
			background-color: #f1f1c1;
			color: black;
			}
			
			</style>
			
			<h2>Student Edollars Infomation</h2>
			<p>Here is your information: </p>
			<table id="t01">
			
			<?php while(mysqli_stmt_fetch($stmt)) { ?>
				<tr>
					<th> Student ID </th>
					<th> Student Name </th>
					<th> Initial Account </th>
					<th> Successful Bid Amount </th>
					<th> Pending Bid Amount </th>
				</tr>
				
				<tr>
					<td><?php echo $sid_s; ?></td>
					<td><?php echo $stu_name_s; ?></td>
					<td><?php echo $initial_edollars_s; ?></td>
					<td><?php echo $succbidding_s; ?></td>
					<td><?php echo $pendbidding_s; ?></td>
				</tr>
			</table>
		<?php } ?>

		<?php
			mysqli_stmt_close($stmt);
			mysqli_close($conn);
				
		} else {
			$sid = $_POST['student'];
			$bid_datetime = $_POST['Date'];
			$conn = mysqli_connect('localhost', 'root', '', 'g3t02');
			if (!$conn) 
			{	exit('Could Not Connect' .
				mysqli_connect_error($conn));
			}
			
			$pQuery = "select sid, stu_name, init_edollars, ifnull(succbidding, 0) as succbidding, ifnull(pendbidding, 0) as pendbidding from(
						select s.sid, stu_name, init_edollars,
						(select sum(edollars) from bidding where rid = '1' and outcome = '1' and bidding.sid = s.sid and bid_datetime <= ?) as succbidding,
						(select sum(edollars) from bidding where rid = '2' and bidding.sid = s.sid and bid_datetime <= ?) as pendbidding
						from bidding b right outer join student s on b.sid = s.sid) as temp
						where sid = ?
						group by sid";
						
						
			$stmt = mysqli_prepare($conn, $pQuery);
			mysqli_stmt_bind_param($stmt, 'sss', $bid_datetime, $bid_datetime, $sid);
			$sid = $_POST['student'];
			$bid_datetime = $_POST['Date'];
			mysqli_stmt_execute($stmt);
			
			//Step 4a: Bind result variables	
			mysqli_stmt_bind_result($stmt, $sid_s, $stu_name_s, $initial_edollars_s, $succbidding_s, $pendbidding_s);
			
		?>
			
			<style>
			table, th, td {
			border: 1px solid black;
			border-collapse: collapse;
			}
			th, td {
			padding: 15px;
			}
			table#t01 tr:nth-child(even) {
			background-color: #eee;
			}
			table#t01 tr:nth-child(odd) {
			background-color: #fff;
			}
			table#t01 th {
			background-color: #f1f1c1;
			color: black;
			}
			
			</style>
			
			
			<h2>Student Edollars Infomation</h2>
			<p>Here is your information: </p>
			<table id="t01">
			
			<?php while(mysqli_stmt_fetch($stmt)) { ?>
				<tr>
					<th> Student ID </th>
					<th> Student Name </th>
					<th> Initial Account </th>
					<th> Successful Bid Amount </th>
					<th> Pending Bid Amount </th>
				</tr>
				
				<tr>
					<td><?php echo $sid_s; ?></td>
					<td><?php echo $stu_name_s; ?></td>
					<td><?php echo $initial_edollars_s; ?></td>
					<td><?php echo $succbidding_s; ?></td>
					<td><?php echo $pendbidding_s; ?></td>
				</tr>
			</table>
		<?php } ?>


		<?php
			mysqli_stmt_close($stmt);
			mysqli_close($conn);
			
		}
		?>
	</body> 
</html>