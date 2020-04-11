<html>    
    <head>
    <title>Information</title>
    </head>   

    <body>     
        <?php 
            //Step 1: Connect to the database server, to a specific database
            $conn = mysqli_connect('localhost:3308', 'root', '', 'g3t02');
            if (!$conn)
            {  exit( 'Could not connect:'  
                        .mysqli_connect_error($conn)); 	
            }  
            
            //Step 2: Prepare Statement
            $pQuery = "select pid from programme";

            $stmt = mysqli_prepare($conn,$pQuery);
            mysqli_stmt_execute($stmt);

            //Step 4a: Bind result variables
            mysqli_stmt_bind_result($stmt,$pid_r);

            //Step 4b: Fetch Values � results
            ?>      

            <form method="post" action="question7.php">          
                Please select your student ID  
                
                <select name="pid">
                        <option value="" selected>-- Select Your ID --</option>
                    <?php 
                while (mysqli_stmt_fetch($stmt)) { ?>

                    <option value= "<?php echo $pid_r; ?>"> <?php echo $pid_r; ?></option>

                <?php }?>

                </select> 
                <input type="submit" value="Get Programme information and popular elective Courses " />
            </form> 
                       

            <?php
            //Step 5a: Close Statement
            mysqli_stmt_close($stmt);

            //Step 5b: Close the Connection
            mysqli_close($conn);
            ?>
     
      
    </body> 
</html> 