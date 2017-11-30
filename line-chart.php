<!DOCTYPE html>
<html leng='en'>
<head>
	<meta charset="utf-8">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, shrink-to-fit=yes"> -->
  <title>Order Graph</title>
	<!-- <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script> -->
  <script src="lib/jquery-3.2.1.min.js"></script>
	<script src="lib/Chart.js"></script>
	<script src="lib/Chart.PieceLabel.js"></script>

<script>
function drawGraph(date_labels, incomeValue, countValue){

  var values1 = incomeValue.map(function(item){
    return parseInt(item,10);
  });
  var values2 = countValue.map(function(item){
    return parseInt(item,10);
  });
  var ctx = $("#myChart");
  var font_size = 24;
  var myChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: date_labels,
      datasets: [{
        data: values1,
        label: 'income',
        yAxisID: 'A',
        borderWidth: 1,
        backgroundColor: 'rgba(255,255,0,0.2)',
        fontSize: font_size
      }, {
        data: values2,
        label: 'count',
        yAxisID: 'B',
        borderWidth: 3,
        borderColor: 'rgba(0,255,0,1)',
        backgroundColor: 'rgba(0,0,0,0)',
        fontSize: font_size
      }]
    },
    options: {
      scales: {
        yAxes: [{
          id: 'A',
          position: 'left',
          ticks: {
            // beginAtZero: true,
            fontSize: font_size
          },
          scaleLabel:{
            display: true,
            labelString: 'Income',
            fontSize: font_size
          }
        }, {
          id: 'B',
          position: 'right',
          scaleLabel:{
            display: true,
            labelString: 'Count',
            fontSize: font_size
          },
          ticks: {
            beginAtZero: true,
            fontSize: font_size
          }
        }],
        xAxes: [{
          ticks: {
            fontSize: font_size
          }
        }]
      }
    }
  });
}
</script>

<style>
	canvas {
		width: auto;
		height: 100%;
		/*background-color: blue;*/
	}
	a {
    background: #69c;
    color: #fff;
    padding: 5px 10px;
		cursor: pointer;
	}
</style>

</head>
<body>

<!-- <a type="button" id="save-graph">Save</a> -->
<div class="container">
	<canvas id="myChart"></canvas>
</div>

<?php
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 'on');
$servername = "localhost";
$username = $_SESSION["Username"];
$password = $_SESSION["Password"];
$dbname = "Restaurant";

$dateRange = $_SESSION["dateRange"];
$startDate = $dateRange[0]."-".$dateRange[1]."-".$dateRange[2];
$endDate = $dateRange[3]."-".$dateRange[4]."-".$dateRange[5];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$operation = $_SESSION["operation"];
		// echo 'op='.$operation;
		if ($operation=="1"){
	    $sql = "SELECT `Count` , Income, `Date` FROM report
							where `Date` between '$startDate' and '$endDate';";
		} else if ($operation=="2"){
			$sql = "select SUM(`Count`) as `Count`, SUM(Income) as Income, month(date) as mon, year(date) as yr from report
							where date<='$endDate' and date>='$startDate'
							group by year(date),month(date);";
		} else if($operation=="3"){
			$sql = "select SUM(`Count`) as `Count`, SUM(Income) as Income, year(date) as `Date` from report
							where date<='$endDate' and date>='$startDate'
							group by year(date);";
		}
    // echo $sql;
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $tmp1 = array();
		$tmp2 = array();
    $tmp3 = array();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(new RecursiveArrayIterator($stmt->fetchAll() ) as $k=>$v){
			if ($operation==1 || $operation==3){
				array_push($tmp1, $v['Count']);
				array_push($tmp2, $v['Income']);
				array_push($tmp3, $v['Date']);
			} else {
				array_push($tmp1, $v['Count']);
				array_push($tmp2, $v['Income']);
				array_push($tmp3, $v['mon']."-".$v['yr'] );
			}
    }
    // print_r($tmp1);
    // print_r($tmp2);
    echo "<script>drawGraph(".json_encode($tmp3).",".json_encode($tmp2).",".json_encode($tmp1).")</script>";

} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;

?>

<script>
var srcCanvas = document.querySelector('canvas');

var link = document.createElement('a');
    link.innerHTML = 'download image';
		link.addEventListener('click', function(ev) {
			var destinationCanvas = document.createElement("canvas");
			destinationCanvas.width = srcCanvas.width;
			destinationCanvas.height = srcCanvas.height;

			var destCtx = destinationCanvas.getContext('2d');
			//create a rectangle with the desired color
			destCtx.fillStyle = "#FFFFFF";
			destCtx.fillRect(0,0,srcCanvas.width,srcCanvas.height);
			//draw the original canvas onto the destination canvas
			destCtx.drawImage(srcCanvas, 0, 0);
			link.href = destinationCanvas.toDataURL();
    	link.download = "chart_1.png";
}, false);
document.body.appendChild(link);
</script>

</body>
</html>