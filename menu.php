<?php
// Starting session
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php
  echo "<meta name='data-masterOrderID' content='".$_SESSION["MasterOrderID"]."'>";
  ?>

  <title>Order System</title>

  <script src="lib/jquery-3.2.1.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <script src="lib/jquery.dataTables.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link href="custom_css/menu.css" rel="stylesheet" type="text/css" />
</head>


<body data-spy="scroll" data-target="#myScrollspy" data-offset="15">
  <!-- Navigation -->

  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="icon-bar">
      <?php 
      if ($_SESSION["Privilege"]=="Administrator"){
        echo '<a class="active" href="main_menu-manager.php"><i class="fa fa-home"></i></a>';
      } else {
        echo '<a class="active" href="main_menu-user.php"><i class="fa fa-home"></i></a>';
      }
       ?>
    </div>
    <ul class="title" >Food Menu</p></ul>
    <button type="button" class="btn btn-warning"><img src="icon/log-out.svg"> Logout </button>
  </nav>
  <!-- Page Content -->
  <div class="container-fluid">
    <!-- Menu table -->
    <br><br>

    <div class="row justify-content-md-center" id="right-part">
        <table class="table table-dark table-hover" id="mainTable">
          <thead>
            <tr>
              <th>Code</th>
              <th>Food Name</th>
              <th>Price ($)</th>
              <th>Quantity</th>
              <th class="test">Category</th>
            </tr>
          </thead>
          <tbody id="menu">
            <?php

            $servername = "localhost";
            $username =$_SESSION["Username"];
            $password = $_SESSION["Password"];
            $dbname = "Restaurant";

            // Create connection
            $conn = new mysqli($servername,$username,$password,$dbname);
            // Check connection
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            if (isset($_SESSION["Category"])) {
              $category = $_SESSION["Category"];
            } else {
              $category = 'Burger';
            }

            $sql = "SELECT FoodID, FoodName, Price, Quantity, Category FROM menu where category='$category'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              // output data of each row
              while($row = $result->fetch_assoc()) {
                echo '<tr> <td>'.$row['FoodID'].'</td><td>'.$row["FoodName"].'</td><td>'.$row["Price"].'</td><td>'.$row["Quantity"].'</td><td>'.$row["Category"].'</td></tr>';
              }
            } else {
              echo "0 results";
            }
            $conn->close();
            ?>

          </tbody>
        </table>
    </div>

    <div id="type-navigation">
      <div class="row" id="typeBtnList">
          <!-- <a class="btn btn-primary" id="button1"><p >Burger</p></a>
          <a class="btn btn-primary" id="button2"><p >Pizza</p></a>
          <a class="btn btn-primary" id="button3"><p >Chicken</p></a>
          <a class="btn btn-primary" id="button4"><p >Sides</p></a>
          <a class="btn btn-primary" id="button5"><p >Drinks</p></a>
          <a class="btn btn-primary" id="button6"><p >Dessert</p></a> -->
      </div>
    </div>

    <!-- Order List -->
    <div class="row" id="orderBlock">
        <div class="card" id="order-list">
          <div class="card-header"><h4>Order List</h4></div>
          <form class="form-inline" action="./action.php" method="POST">
            <div class="card-block">
              <?php
              echo '<p type="tableID" name="tableid">Table No: '.$_SESSION["TableNo"].'</p>';
               ?>
              <input type="hidden" name="foodname"/>
              <input type="hidden" name="quantity"/>
              <input type="hidden" name="price" readonly/>
            </div>
            <br>
            <div class="card-footer">
              <div class="text-muted"><h4>Total</h4></div>
              <div id="total"></div>
            </div>

          </div>
          <button type="button" class="btn btn-info" name="save" id="saveOrderBtn">Save</button>
        </form>
    </div>

  </div>

<script>
//Search bar AND Side bar
$(document).ready(function () {
  var table = $('.table').DataTable();
});
//   $('#button1').on('click',function(){
//     table.search("Burger").draw();
//   });

function refresh_buttons(){
$.ajax({
  type     : "POST",
  url      : "tools/select_category.php",
  success  : function(data) {
    set_buttons(JSON.parse(data));
  }
}).fail(function(xhr, status, error){
	alert(error);
});

function set_buttons(data){
  $("#typeBtnList").empty();
  data.forEach(function(row){
    row.forEach(function(value){
      // var str = "'"+value+"'";
      // $("#typeBtnList").append('<button class="btn btn-primary type-btn" onclick="changeType('+str+')"><p>'+value+'</p></button>');
      $("#typeBtnList").append('<a class="btn btn-primary type-btn" ><p>'+value+'</p></a>');
    });
  });
}
}
refresh_buttons();

</script>
<script src="js/menu.js"></script>
</body>
</html>