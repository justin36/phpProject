<?php

$pid = $_POST['school'];
echo $pid;
if ($pid == 'All') {
    # Get Total number of students
    $conn = mysqli_connect('localhost', 'root', '', 'g3t02');

    if (!$conn) {
        // code...
        exit('Could Not Connect' .
            mysqli_connect_error($conn));
    }

    $pQuery = "Select count(*) from student";
    $stmt = mysqli_prepare($conn, $pQuery);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count1);

    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    # Get Double Major Students
    $conn = mysqli_connect('localhost', 'root', '', 'g3t02');

    if (!$conn) {
        // code...
        exit('Could Not Connect' .
            mysqli_connect_error($conn));
    }

    $pQuery = "Select count(*) from double_degree";
    $stmt = mysqli_prepare($conn, $pQuery);

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count2);

    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

} else {

    # Get total number of students
    $conn = mysqli_connect('localhost', 'root', '', 'g3t02');

    if (!$conn) {
        // code...
        exit('Could Not Connect' .
            mysqli_connect_error($conn));
    }

    $pQuery = "Select count(*) from student
                where pid = ?";

    $stmt = mysqli_prepare($conn, $pQuery);

    mysqli_stmt_bind_param($stmt, 's', $pid);

    $pid = $_POST['school'];
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $count1);

    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    # Get total number of students
    $conn = mysqli_connect('localhost', 'root', '', 'g3t02');

    if (!$conn) {
        // code...
        exit('Could Not Connect' .
            mysqli_connect_error($conn));
    }

    $pQuery = "Select count(*) from double_degree
                where pid = ?";

    $stmt = mysqli_prepare($conn, $pQuery);

    mysqli_stmt_bind_param($stmt, 's', $pid);

    $pid = $_POST['school'];
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $count2);

    mysqli_stmt_fetch($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>mainResult(0406)4</title>
    <link rel="stylesheet" href="../Q8/styles.css" type="text/css">
</head>
<body>

<h1>Student Demongraphy</h1>
<?php
$school = $_POST['school'];
echo "<h2>$school</h2>";

function getPercentage($num1, $num2) {
    $percentage = $num2 / $num1;
    $percentage = round($percentage * 100) ;

    return $percentage . "%";
}

function makeArray($num1, $num2) {
    $arr1 = array();
    array_push($arr1, $num1, $num2);

    return $arr1;
}

?>


<table border="1">
    <tr>
        <th>Total Number of Students</th>
        <th>Double Degree</th>
        <th>Percentage</th>
    </tr>
    <tr>
        <?php

        echo "<td> $count1</td>";
        echo "<td> $count2</td>";
        echo "<td>" . getPercentage($count1, $count2) . "</td>"?>
    </tr>

</table>

</body>
</html>
