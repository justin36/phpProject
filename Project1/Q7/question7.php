<html> 
    <body> 
        <?php 
        //Step 1: Connect to the database server, to a specific database
        $conn = mysqli_connect('localhost:3308', 'root', '', 'g3t02');
        if (!$conn)
        {  exit( 'Could not connect:'  
                   .mysqli_connect_error($conn)); 	
        }

        //Step 2: Prepare Statement
        $pQuery = "select prog_name,homesch_name,secsch_name,totalcourses,core,electives,firstdegreestudents,seconddegreestudents from programme
 
                   left join (select pid,count(cid) as totalcourses from programme_courses group by pid) as allcourses on programme.pid=allcourses.pid 

                   left join (select pid,count(cid) as core from programme_courses where is_core=1 group by pid) as corecourses on programme.pid=corecourses.pid

                   left join (select pid,count(cid) as electives from programme_courses where is_core=0 group by pid) as electivecourses on programme.pid=electivecourses.pid

                   left join (select Firstdegree.pid ,firstdegreestudents,seconddegreestudents from (select pid, count(sid) as firstdegreestudents from student group by pid) as Firstdegree 

                   left join (select pid,count(sid) as seconddegreestudents from double_degree group by pid) as Seconddegree
on Firstdegree.pid=Seconddegree.pid group by Firstdegree.pid) as studentcount on programme.pid=studentcount.pid
                   where programme
.pid=? ";

        $stmt = mysqli_prepare($conn, $pQuery); 

        //Step 2b: Bind parameters
        mysqli_stmt_bind_param($stmt,'s',$programme_id);

        //Step 3: Perform the query (execute statement)
        if (isset($_POST['pid']))
            {
                $programme_id = $_POST['pid'];
            }

        mysqli_stmt_execute($stmt);

        //Step 4a: Bind result variables
        mysqli_stmt_bind_result($stmt,$prog_name,$homesch_name,$secsch_name,$totalcourses, $core, $electives,$firstdegreestudents,$seconddegreestudents);

        //Step 4b: Fetch Values � results
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

        <h2>Programme and Popular electives</h2>
        <p>Programme information: </p>
        <table id="t01"> 

        <?php while (mysqli_stmt_fetch($stmt)) {
            if ($secsch_name != null) { ?>
                <tr>         
                    <th> Programme Name </th>         
                    <th> Host School </th> 
                    <th> Secondary School </th>
                    <th> No. of Core courses </th>
                    <th> No. of Elective courses </th>  
                    <th> No.of students chosen First Degree </th> 
                    <th> No.of students chosen Second Degree</th> 
      
                </tr>

                <tr>
                    <td><?php echo $prog_name;?></td>
                    <td><?php echo $homesch_name;?></td>
                    <td><?php echo $secsch_name;?></td>
                    <td><?php echo $core;?></td>
                    <td><?php echo $electives;?></td>
                    <td><?php echo $firstdegreestudents;?></td>
                    <td><?php echo $seconddegreestudents;?></td>
                </tr> 
        <?php }
        else {?>
                <tr>         
                    <th> Programme Name </th>         
                    <th> Host School </th> 
                    <th> No. of Core courses </th>
                    <th> No. of Elective courses </th>  
                    <th> No.of students chosen First Degree </th> 
                    <th> No.of students chosen Second Degree</th>    
                </tr>
                <tr>
                    <td><?php echo $prog_name;?></td>
                    <td><?php echo $homesch_name;?></td>
                    <td><?php echo $core;?></td>
                    <td><?php echo $electives;?></td>
                    <td><?php echo $firstdegreestudents;?></td>
                    <td><?php echo $seconddegreestudents;?></td>
                </tr> 
        <?php }?>
            </table>
        <?php }?>

        <?php

        //Step 2: Prepare Statement
        $pQuery = "select cid,title,P from (select distinct supply.cid ,c.title, D/S as P,DENSE_RANK() OVER( ORDER BY D/S DESC) popularity_rank 

                   from (select cid, sum(capacity) as S from section where cid in (select cid from programme_courses p where is_core=0 ) group by cid) as supply

                   ,(select cid, count(sid) as D from bidding where cid in (select cid from programme_courses p where is_core=0 ) group by cid) as demand 
                   
, course c
where supply.cid = demand.cid and supply.cid =c.cid and c.pid =?) as temp where popularity_rank<=3";

        $stmt = mysqli_prepare($conn, $pQuery); 

        //Step 2b: Bind parameters
        mysqli_stmt_bind_param($stmt,'s',$programme_id);

        //Step 3: Perform the query (execute statement)
        if (isset($_POST['pid']))
            {
                $programme_id = $_POST['pid'];
            }

        mysqli_stmt_execute($stmt);

        //Step 4a: Bind result variables
        mysqli_stmt_bind_result($stmt,$cid,$title,$P);

        //Step 4b: Fetch Values � results
        ?>
        <p>Popular Electives:  </p>
        <table id="t01"> 
        <?php while (mysqli_stmt_fetch($stmt)) { 
            if ($cid != null) {?>
                <tr>         
                <th> Course ID </th>         
                <th> Course Name </th> 
                <th> popularity Score </th>

            </tr>
            <tr>
                <td><?php echo $cid;?></td>
                <td><?php echo $title;?></td>
                <td><?php echo $P;?></td>

            </tr> 
            <?php }

            else { ?>
                <tr>         
                <th> Course ID </th>         
                <th> Course Name </th> 
                <th> popularity Score </th>

            </tr>
            <tr>
                <td><?php echo $cid;?></td>
                <td><?php echo $title;?></td>
                <td><?php echo $P;?></td>

            </tr> 
            <?php }}?>
            </table>


        <?php
        //Step 5a: Close Statement
        mysqli_stmt_close($stmt);

        //Step 5b: Close the Connection
        mysqli_close($conn);
        ?>
    </body> 
</html> 