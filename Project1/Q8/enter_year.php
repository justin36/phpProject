<?php

$pid = $_POST['school'];
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
function makeArray($num1, $num2)
{
    $arr1 = array();
    array_push($arr1, $num1, $num2);

    return $arr1;
}

$json1[] = $count1;
$json1[] = $count2;

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Double Degree</title>
    <link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>

<h1>Student Demongraphy</h1>
<?php
$school = $_POST['school'];
echo "<h2>$school</h2>";

function getPercentage($num1, $num2)
{
    $percentage = $num2 / $num1;
    $percentage = round($percentage * 100);

    return $percentage . "%";
}

?>


<table border="1">
    <tr>
        <th>Total Number of Students</th>
        <th>Double Degree Students</th>
        <th>Percentage</th>
    </tr>
    <tr>
        <?php

        echo "<td> $count1</td>";
        echo "<td> $count2</td>";
        echo "<td>" . getPercentage($count1, $count2) . "</td>" ?>
    </tr>

</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>

<div style="position:absolute; top:200px; left:10px; width:600px; height:600px;">
<canvas id="myChart" width="100" height="100"></canvas>
<script type="text/javascript">
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Students', 'Double Degree Students'],
            datasets: [{
                label: '# of Students',
                data: <?php echo json_encode($json1)?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>
</div>
</body>
</html>
