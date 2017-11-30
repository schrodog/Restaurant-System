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
  function drawGraph(oriValues, oriLabels){

    var backgroundPlatte = ['rgba(255, 99, 132, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)','rgba(75, 192, 192, 0.2)','rgba(153, 102, 255, 0.2)','rgba(255, 159, 64, 0.2)'];
    var borderPlatte = ['rgba(255,99,132,1)','rgba(54, 162, 235, 1)','rgba(255, 206, 86, 1)','rgba(75, 192, 192, 1)','rgba(153, 102, 255, 1)','rgba(255, 159, 64, 1)'];
    var values = oriValues.map(function(item){
      return parseInt(item,10);
    });
    var valueCount = values.length;
    var background = backgroundPlatte.slice(0,valueCount);
    var border = borderPlatte.slice(0,valueCount);

    // console.log('label:'+oriLabels);
    // console.log('back:'+background);

    var ctx = $("#myChart");
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        labels: oriLabels,
        datasets: [{
          label: 'counts',
          data: values,
          backgroundColor: background,
          borderColor: border,
          borderWidth: 1
        }]
      },
      options: {
        pieceLabel: {
          render: function(args){
            return args.label+': '+args.value
          },
          fontSize: 24,
          overlap: true
        }
      }
    });
  }
  </script>

	<style>
		canvas {
			width: auto;
			height: 100%;
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

<div class="container">
	<canvas id="myChart"></canvas>
</div>

<?php
session_start();

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

    $sql = "SELECT menu.Category , count(menu.Category) as `count`
        FROM `order`,masterorder, menu
        where `order`.masterorderID=masterorder.MasterOrderID and
        `order`.foodID=menu.foodID and
        masterorder.`CheckOut Date` between '$startDate' and '$endDate'
        group by menu.Category;";

    // echo $sql;
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $tmp1 = array();
    $tmp2 = array();
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    foreach(new RecursiveArrayIterator($stmt->fetchAll() ) as $k=>$v){
        array_push($tmp1, $v['Category']);
        array_push($tmp2, $v['count']);
    }
    // print_r($tmp1);
    // print_r($tmp2);
    echo "<script>drawGraph(".json_encode($tmp2).",".json_encode($tmp1).")</script>";

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
	    	link.download = "order_1.png";
	}, false);
	document.body.appendChild(link);
</script>

</body>
</html>