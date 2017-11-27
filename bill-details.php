<?php
session_start();

$reportID = $_SESSION["reportID"];
$billDate = $_SESSION["billDate"];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, shrink-to-fit=no">

    <title>Restaurant System</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <!-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script> -->
  <link href="custom_css/menu-order-count.css" rel="stylesheet" type="text/css" />
    <link href="custom_css/bill-details.css" rel="stylesheet" type="text/css" />

    <script src="lib/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <!-- <script src="menuOrderCount-input.js"></script> -->
    <script src="js/table-sort.js"></script>
<script>
function removeFile(){
  $(".modal-dialog td:nth-child(5)").remove();
}
</script>

</head>

<body>
<h2>Daily Bill Details</h2>
<?php
echo "<div class='info'>
<p>Date: $billDate</p><p>Report ID: $reportID</p></div>";

?>

<?php
// $dateRange = $_SESSION["dateRange"];
//
// echo "<p class='date'>Time range: <span class='text-primary'>$startDate</span> - <span class='text-success'>$endDate</span></p>";
?>

<!-- <header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <button type="button" class="btn btn-warning" onclick="window.open('order-graph.php','_blank')">View As Graph</button>
  </nav>
</header> -->

</div>

<div class="container">
  <div class="hero-unit">
     <table id="mainTable" class="table table-striped">
       <thead><tr>
         <th onclick='columnSort(0)' value='0'>MasterOrder ID</th>
         <th onclick='columnSort(1)' value='0'>Price</th>
         <th onclick='columnSort(2)' value='0'>Payment</th>
         <th onclick='columnSort(3)' value='0'>Change</th>
         <th onclick='columnSort(4)' value='0'>StaffID</th>
         <th onclick='columnSort(5)' value='0'>TableNo</th>
         <th onclick='columnSort(6)' value='0'>CheckOut Time</th>
         <th></th>
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
        echo "<td class='bill-file'><img src='icon/file.png'/></td></tr>\n";
    }
}

$servername="localhost";
$username = $_SESSION["Username"];
$password = $_SESSION["Password"];
$dbname = "Restaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT MasterOrderID,Price,Payment,`Change`,StaffID,TableNo,`CheckOut Time` FROM masterorder where ReportID=$reportID;";

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

?>
</tbody></table></div></div>

<!-- <footer class="footer">
    <div class="container">

        <button type="button" class="btn btn-primary" id="viewOrderBtn">View order count</button>
    </div>
</footer> -->

<div id="orderModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Order Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

        <div class="container">
          <div class="hero-unit">
             <table id="mainTable" class="table table-striped">
               <thead><tr>
                 <th>Order ID</th>
                 <th>Quantity</th>
                 <th>Food Name</th>
                 <th>Price</th>
                </tr></thead>
              <tbody>
<?php

// $servername="localhost";
// $username = "user1";
// $password = "123456";
// $dbname = "Restaurant";

if (isset($_SESSION["masterOrderID"])) {$masterOrderID = $_SESSION["masterOrderID"];}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT OrderID,`order`.Quantity,menu.FoodName,`order`.price
            FROM `order`,menu
            where `order`.FoodID=menu.FoodID and MasterOrderID=$masterOrderID;";

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

echo "<script>removeFile()</script>";
?>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
      </div> -->
    </div>
  </div>
</div>

<script>
$("#mainTable .bill-file").click(function(){
  var thisrow = $(this).parent();
  var masterID = thisrow.find("td").first().text();
  // console.log(masterID);
  $.ajax({
    type: "POST",
    url: "tools/save_session.php",
    data: {"name": ["masterOrderID"] , "value":[masterID] },
    success: function(data, txt, jqxhr){
      console.log(masterID);
      // window.location.href= 'bill-details.php';
      $("#orderModal").modal('show');
    }
  }).fail(function(xhr, status, error){
    alert(error);
  });
});


</script>

</body>
</html>
