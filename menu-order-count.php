<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, shrink-to-fit=no">

    <title>Restaurant System</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <link href="custom_css/menu-order-count.css" rel="stylesheet" type="text/css" />

    <script src="lib/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script src="js/menuOrderCount-input.js"></script>
    <script src="js/table-sort.js"></script>

</head>

<body>
<h2>Menu Order Count</h2>

<?php 
session_start();
$dateRange = $_SESSION["dateRange"];
$startDate = $dateRange[0]."-".$dateRange[1]."-".$dateRange[2];
$endDate = $dateRange[3]."-".$dateRange[4]."-".$dateRange[5];

echo "<p class='date'>Time range: <span class='text-primary'>$startDate</span> - <span class='text-success'>$endDate</span></p>";
?>

<header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <!-- <a class="navbar-brand mr-auto" href="index.php">Back</a> -->
    <button type="button" class="btn btn-warning" onclick="window.open('order-graph.php','_blank')">View As Graph</button>
  </nav>
</header>

</div>

<div class="container">
  <div class="hero-unit">
     <table id="mainTable" class="table table-striped">
       <thead><tr>
         <th onclick='columnSort(0)' value='0'>Code</th>
         <th onclick='columnSort(1)' value='0'>Food Name</th>
         <th onclick='columnSort(2)' value='0'>Count</th>
        </tr></thead>
        <tbody>
<?php

class TableRows extends RecursiveIteratorIterator {
    function __construct($it){
        parent::__construct($it, self::LEAVES_ONLY);
    }
    function current(){
        $str = "<td>";
        return $str.parent::current()."</td>";
    }
    function beginChildren(){
        echo "<tr>";
    }
    function endChildren(){
        echo "</tr>\n";
    }
}

$servername="localhost";
$username = "user1";
$password = "123456";
$dbname = "Restaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    
    $sql = "SELECT `order`.foodID , menu.foodName , count(`order`.OrderID) as `count`
            FROM `order`,masterorder, menu
            where `order`.masterorderID=masterorder.MasterOrderID and
            `order`.foodID=menu.foodID and
            masterorder.`CheckOut Date` between '$startDate' and '$endDate'
            group by `order`.foodID;";

    // echo $sql;
    $stmt = $conn->prepare($sql);
    $stmt->execute();   // $stmt = PDOStatement class

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); // return associated array
    // create html table
    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll() )) as $k=>$v){
        echo $v;
    }

} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;
// echo "</table>";

?>
</tbody>
</table>
</div>
</div>

<!-- <footer class="footer">
    <div class="container">
        
        <button type="button" class="btn btn-primary" id="viewOrderBtn">View order count</button>
    </div>
</footer> -->

<!-- <div id="deleteModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Warning</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p>Are you sure to delete this row?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
-->
<!-- <script type='text/javascript'>calcTotal();</script> -->

<script>
  

</script>

</body>
</html>
