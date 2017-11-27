<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Order System</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  <!-- <link href="user-man.css" rel="stylesheet" type="text/css" /> -->
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

  <script src="lib/jquery-3.2.1.min.js"></script>
  <script src="lib/jquery.dataTables.min.js"></script>
  <link href="custom_css/order.css" rel="stylesheet" type="text/css">


</head>
<body>
  <!-- Navigation -->
  <?php
  session_start();
  ?>

  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="icon-bar">
      <a class="active" href="index.html"><i class="fa fa-home"></i></a>
    </div>
    <a type="button" class="btn btn-info active" href="table-management.php">Table</a>

    <ul class="title" >Order Review</p></ul>
    <button type="button" class="btn btn-warning"><img src="icon/log-out.svg"> Logout </button>
  </nav>
  <!-- Page Content -->
  <div class="container-fluid">
    <!-- Menu table -->

    <!-- <form class="form-inline top-info" action="./searchorderid.php" METHOD="POST">
    <input type="text" name="orderno" id="orderno" placeholder="Search Order Number">
  </form> -->

  <!-- <form class="form-inline top-info" action="./searchtableid.php" METHOD="POST">
  <div class="form-group">
  <input type="text" class="form-control mr-sm-2 no_focus" id="tableno" name="orderno1">
  <input type="submit" value="abc"/>
</div>
</form> -->
<?php
echo "MasterOrderID:".$_SESSION["MasterOrderID"]."<br>" ;
echo "tableNo:".$_SESSION["TableNo"];
?>
<div class="row  justify-content-md-center">
    <table class="table table-hover table-striped" id="mainTable">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Quantity</th>
          <th>Food ID</th>
          <th>Food Name</th>
          <th>Price($)</th>
        </tr>
      </thead>
      <tbody id="order">
        <?php

        $servername = "localhost";
        $username = "user1";
        $password = "123456";
        $dbname = "Restaurant";

        // Create connection
        $conn = new mysqli($servername,$username,$password,$dbname);
        // Check connection
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }


        $sql = "SELECT orderid, `order`.quantity, `order`.FoodID,`menu`.FoodName, `order`.price FROM `order`,`menu` where masterorderid='".$_SESSION["MasterOrderID"]."' and menu.FoodID=`order`.FoodID";
        echo $sql;
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          // output data of each row
          while($row = $result->fetch_assoc()) {
            //echo "{$row["price"]}";
            echo "<tr><td>{$row["orderid"]} </td><td>{$row["quantity"]}</td><td>{$row["FoodID"]}</td><td>{$row["FoodName"]}</td><td>{$row["price"]}</td>
            <td class='no_focus btn_container'><button type='button' class='delBtn btn' style='background-color:transparent'><img src='icon/delete.png'></button></td></tr>";
          }
        }  else {
          echo "No result found.";
        }

        $conn->close();
        ?>
      </tbody>
    </table>
</div></div>

<!-- <form class="form-inline" action="./calltotal.php" METHOD="POST"> -->
<!-- </form> -->

<div class="form-group total-calc">
    <h4>Total: </h4>
    <input type="total" class="form-control mr-sm-2 no_focus" type="text" name="total" id="total" readonly>
</div>
<div class="change-group">
  <div class="form-group">
    <h4>Customer Paid: </h4>
    <input type="paid" class="form-control mr-sm-2 no_focus" type="text" id="paid">
  </div>
  <div class="form-group">
    <h4>Change: </h4>
    <input type="change" class="form-control mr-sm-2 no_focus" type="text" id="change" readonly>
  </div>
</div>

<div class="row btn-list">
  <div class="col-sm-3" id="#newOrderBtn">
    <a class="btn btn-primary" href="menu.php"><p >New Order</p></a>
  </div>
  <div class="col-sm-3" id="confirmOrderBtn">
    <a class="btn btn-primary"><p >Confirm Order</p></a>
  </div>
  <!-- <div class="col-sm-3" id="completeBtn">
    <a class="btn btn-primary"><p >Complete</p></a>
  </div> -->
</div>

<div id="deleteModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
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
</div></div></div>


<script src="js/order-input.js"></script>

</body>
</html>