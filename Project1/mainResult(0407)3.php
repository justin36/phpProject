<?php

include 'connectionMaker.php';

$conn = get_connection();

if (!$conn) {
    // code...
    exit('Could Not Connect' .
        mysqli_connect_error($conn));
}

$pQuery = "Select email from student";
$stmt = mysqli_prepare($conn, $pQuery);

mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $email);


$student_info = array();

while (mysqli_stmt_fetch($stmt)) {

    $findDot = strpos($email, '.');
    $subEmail = substr($email, $findDot+1, 4);
    $intValue = intval($subEmail);

    array_push($student_info,
        array("email" => $email, "year"=>$intValue));

}

$student_group = array(
);

for ($i = 0; $i < count($student_info) ; $i+= 1){
    $year = $student_info[$i]['year'];
    if(!array_key_exists($year, $student_group)){
        $student_group[$year] = 1;
    }else{
        $student_group[$year] += 1;
    }
}

$chart_data = array("x" => array(), "y" => array());
ksort($student_group);

foreach($student_group as $key => $value){
    array_push($chart_data['x'], $key);
    array_push($chart_data['y'], $value);
//    echo $value ;
}
// {x:[2014, 2015], y:[250, 240 ...]}

$arr1 = json_encode($chart_data["x"]);
$arr2 = json_encode($chart_data["y"]);

mysqli_stmt_close($stmt);
mysqli_close($conn);


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>mainResult(0407)3</title>
    <link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>

<h1>Student Demongraphy</h1>
<?php

function getPercentage($num1, $num2)
{
    $percentage = $num2 / $num1;
    $percentage = round($percentage * 100);

    return $percentage . "%";
}

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js"></script>
<div style="position:absolute; top:60px; left:10px; width:600px; height:600px;">
<canvas id="myChart" width="100" height="100"></canvas>
<script type="text/javascript">

    var ctx = document.getElementById('myChart').getContext('2d');

    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo $arr1 ?>,
            datasets: [{
                label: '# of students',
                data: <?php echo $arr2 ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    // 'rgba(54, 162, 235, 0.2)',
                    // 'rgba(255, 206, 86, 0.2)',
                    // 'rgba(75, 192, 192, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    // 'rgba(54, 162, 235, 1)',
                    // 'rgba(255, 206, 86, 1)',
                    // 'rgba(75, 192, 192, 1)',
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
