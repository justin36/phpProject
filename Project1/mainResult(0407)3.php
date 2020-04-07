<?php

$conn = mysqli_connect('localhost', 'root', '', 'g3t02');

if (!$conn) {
    // code...
    exit('Could Not Connect' .
        mysqli_connect_error($conn));
}

$pQuery = "Select email from student";
$stmt = mysqli_prepare($conn, $pQuery);

mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $email);

$strToArr1 = [];

while (mysqli_stmt_fetch($stmt)) {
    echo $email;
    $findDot = strpos($email, '.');
//    echo "<br>";
//    echo $findDot;
//    echo gettype($findDot);

    $subEmail = substr($email, $findDot+1, 4);

    $intValue = intval($subEmail);

    echo "<br>";
    echo $intValue;
    echo "<br>";
}

echo $arr1Length;
echo $strToArr1;

mysqli_stmt_close($stmt);
mysqli_close($conn);


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>mainResult(0406)4</title>
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
        <th>Double Degree</th>
        <th>Percentage</th>
    </tr>
    <tr>
        <?php

        echo "<td> $email</td>";
        echo "<td> $count2</td>";
        echo "<td>" . getPercentage($email, $count2) . "</td>" ?>
    </tr>

</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
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

</body>
</html>
