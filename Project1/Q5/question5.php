<html> 
    <body> 
        <?php 
        //Step 1: Connect to the database server, to a specific database
        $conn = mysqli_connect('localhost', 'root', '', 'g3t02');
        if (!$conn)
        {  exit( 'Could not connect:'  
                   .mysqli_connect_error($conn)); 	
        }

        //Step 2: Prepare Statement
        $pQuery = "select stu_name,email,first_degree_name,first_degree_id, second_degree_name, second_degree_id,final_edollor
            from (
            select temp3.sid,stu_name,email,first_degree_name,first_degree_id, second_degree_name, second_degree_id, COALESCE((init_edollars-edollor_spend),init_edollars) as final_edollor from(
            select sid,stu_name,email,init_edollars, first_degree_name,first_degree_id, prog_name as second_degree_name, second_degree_id from(
            select sid,stu_name,email,init_edollars,first_degree_id, second_degree_id,prog_name as first_degree_name from
            (select s.sid,stu_name,email,init_edollars, s.pid as first_degree_id, dd.pid as second_degree_id 
            from student s left outer join double_degree dd 
            on s.sid=dd.sid) as temp
            inner join programme
            on temp.first_degree_id=programme.pid) as temp2
            left outer join programme
            on temp2.second_degree_id=programme.pid) as temp3
            left outer join 
            (select sid,sum(edollars) as edollor_spend from bidding
            where outcome=1
            group by sid) as edollor_spend
            on temp3.sid = edollor_spend.sid) as temp4
            where sid=? ";

        $stmt = mysqli_prepare($conn, $pQuery); 

        //Step 2b: Bind parameters
        mysqli_stmt_bind_param($stmt,'s',$student_id);

        //Step 3: Perform the query (execute statement)
        if (isset($_POST['sid']))
            {
                $student_id = $_POST['sid'];
            }

        mysqli_stmt_execute($stmt);

        //Step 4a: Bind result variables
        mysqli_stmt_bind_result($stmt,$stu_name,$email,$first_degree_name,$first_degree_id, $second_degree_name, $second_degree_id,$final_edollor);

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

        <h2>Student and Course Information</h2>
        <p>Here is your information: </p>
        <table id="t01"> 

        <?php while (mysqli_stmt_fetch($stmt)) {
            if ($second_degree_id != null) { ?>
                <tr>         
                    <th> Student Name </th>         
                    <th> Email </th> 
                    <th> First Degree Name </th>
                    <th> First Degree ID </th>  
                    <th> Second Degree Name </th> 
                    <th> Second Degree ID </th> 
                    <th> Final Edollor </th>      
                </tr>

                <tr>
                    <td><?php echo $stu_name;?></td>
                    <td><?php echo $email;?></td>
                    <td><?php echo $first_degree_name;?></td>
                    <td><?php echo $first_degree_id;?></td>
                    <td><?php echo $second_degree_name;?></td>
                    <td><?php echo $second_degree_id;?></td>
                    <td><?php echo $final_edollor;?></td>
                </tr> 
        <?php }
        else {?>
                <tr>         
                    <th> Student Name </th>         
                    <th> Email </th> 
                    <th> First Degree Name </th>
                    <th> First Degree ID </th>  
                    <th> Final Edollor </th>      
                </tr>
                <tr>
                    <td><?php echo $stu_name;?></td>
                    <td><?php echo $email;?></td>
                    <td><?php echo $first_degree_name;?></td>
                    <td><?php echo $first_degree_id;?></td>
                    <td><?php echo $final_edollor;?></td>
                </tr> 
        <?php }?>
            </table>
        <?php }?>

        <?php

        //Step 2: Prepare Statement
        $pQuery = "select cid,course_name,sno,edollars,is_core_of_first_degree,second_degree_id,is_core_of_second from (
            select temp9.cid,course_name,sno,sid,edollars,first_degree_id,is_core_of_first_degree,second_degree_id,COALESCE(is_core,0) as is_core_of_second from (
            select temp8.cid,course_name,sno,sid,edollars,first_degree_id,COALESCE(is_core,0) as is_core_of_first_degree,second_degree_id from (
            select cid,course_name,sno,temp6.sid,edollars,first_degree_id,second_degree_id from (
            select temp5.cid,title as course_name,sno,sid,edollars from (
            select cid,sno,sid,edollars from bidding
            where outcome=1) as temp5 
            inner join course c 
            on temp5.cid=c.cid
            order by sid) as temp6
            inner join (
            select s.sid, s.pid as first_degree_id, dd.pid as second_degree_id 
            from student s left outer join double_degree dd 
            on s.sid=dd.sid) as temp7
            on temp6.sid=temp7.sid) as temp8
            left outer join programme_courses pc
            on temp8.first_degree_id=pc.pid and temp8.cid=pc.cid) temp9
            left outer join programme_courses pc
            on temp9.second_degree_id=pc.pid and temp9.cid=pc.cid) as temp10
            where sid=?";

        $stmt = mysqli_prepare($conn, $pQuery); 

        //Step 2b: Bind parameters
        mysqli_stmt_bind_param($stmt,'s',$student_id);

        //Step 3: Perform the query (execute statement)
        if (isset($_POST['sid']))
            {
                $student_id = $_POST['sid'];
            }

        mysqli_stmt_execute($stmt);

        //Step 4a: Bind result variables
        mysqli_stmt_bind_result($stmt,$cid,$course_name,$sno,$edollars,$is_core_of_first_degree,$second_degree_id,$is_core_of_second);

        //Step 4b: Fetch Values � results
        ?>
        <p>You are successfully enrolled:  </p>
        <table id="t01"> 

        <?php while (mysqli_stmt_fetch($stmt)) { 
                    if ($second_degree_id != null) {?>
                    <tr>         
                        <th> Course ID </th>         
                        <th> Course Name </th> 
                        <th> Section Number </th>
                        <th> Edolloar Spend </th>   
                        <th> Is Core Course for First Degree </th>
                        <th> Is Core Course for Second Degree </th>     
                    </tr>
                    <tr>
                        <td><?php echo $cid;?></td>
                        <td><?php echo $course_name;?></td>
                        <td><?php echo $sno;?></td>
                        <td><?php echo $edollars;?></td>
                        <td><?php echo $is_core_of_first_degree;?></td>
                        <td><?php echo $is_core_of_second;?></td>
                    </tr> 
                    <?php }

                    else { ?>
                    <tr>         
                        <th> Course ID </th>         
                        <th> Course Name </th> 
                        <th> Section Number </th>
                        <th> Edolloar Spend </th>   
                        <th> Is Core Course for First Degree </th>    
                    </tr>
                    <tr>
                        <td><?php echo $cid;?></td>
                        <td><?php echo $course_name;?></td>
                        <td><?php echo $sno;?></td>
                        <td><?php echo $edollars;?></td>
                        <td><?php echo $is_core_of_first_degree;?></td>
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