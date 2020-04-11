<html>    
    <head>
    <title>Information</title>
    </head>   

    <body>     
        <?php 
            //Step 1: Connect to the database server, to a specific database
            $conn = mysqli_connect('localhost', 'root', '', 'g3t02');
            if (!$conn)
            {  exit( 'Could not connect:'  
                        .mysqli_connect_error($conn)); 	
            }  
            
            //Step 2: Prepare Statement
            $pQuery = "select sid
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
            order by sid";

            $stmt = mysqli_prepare($conn,$pQuery);
            mysqli_stmt_execute($stmt);

            //Step 4a: Bind result variables
            mysqli_stmt_bind_result($stmt,$sid_r);

            //Step 4b: Fetch Values � results
            ?>      

            <form method="post" action="question5.php">          
                Please select your student ID  
                
                <select name="sid">
                        <option value="" selected>-- Select Your ID --</option>
                    <?php 
                while (mysqli_stmt_fetch($stmt)) { ?>

                    <option value= "<?php echo $sid_r; ?>"> <?php echo $sid_r; ?></option>

                <?php }?>

                </select> 
                <input type="submit" value="Get student and course information " />
            </form> 
                       

            <?php
            //Step 5a: Close Statement
            mysqli_stmt_close($stmt);

            //Step 5b: Close the Connection
            mysqli_close($conn);
            ?>
     
      
    </body> 
</html> 