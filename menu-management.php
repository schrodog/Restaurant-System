<?php
// Starting session
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Order System</title>

  <!-- Bootstrap core CSS -->
  <script src="lib/jquery-3.2.1.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Custom styles for this template -->

  <!-- <link href="user-man.css" rel="stylesheet" type="text/css" /> -->
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- <script src="jquery.dataTables.min.js"></script> -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="js/menu-management-editableTable.js"></script>
  <script src="js/menu-management-input.js"></script>
  <script src="js/table-sort.js"></script>
  <link href="menu-management.css" rel="stylesheet" type="text/css" />
</head>


<body data-spy="scroll" data-target="#myScrollspy" data-offset="15">
  <!-- Navigation -->

  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="icon-bar">
      <a class="active" href="index.html"><i class="fa fa-home"></i></a>
    </div>
    <ul class="title" >Food Menu</p></ul>
    <button type="button" class="btn btn-warning"><img src="icon/log-out.svg"> Logout </button>
  </nav>
  <!-- Page Content -->
  <div class="container-fluid">
    <!-- Menu table -->
    <br><br>

    <div class="row justify-content-md-center" id="right-part">  <!--   justify-content-md-center -->
      <!-- <div class="col-6"> -->
        <table class="table table-dark table-hover">
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
            $username = "user1";
            $password = "123456";
            $dbname = "Restaurant";

            // Create connection
            $conn = new mysqli($servername,$username,$password,$dbname);
            // Check connection
            if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT FoodID, FoodName, Price, Quantity, Category FROM menu";
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
      <!-- </div> -->
    </div>

    <div id="type-navigation">
      <div class="row">
        <!-- <div class="col"> -->
          <a class="btn btn-primary" id="button1"><p >Burger</p></a>
        <!-- </div> -->
        <!-- <div class="col"> -->
          <a class="btn btn-primary" id="button2"><p >Pizza</p></a>
        <!-- </div> -->
        <!-- <div class="col"> -->
          <a class="btn btn-primary" id="button3"><p >Chicken</p></a>
        <!-- </div> -->
        <!-- <div class="col"> -->
          <a class="btn btn-primary" id="button4"><p >Sides</p></a>
        <!-- </div> -->
        <!-- <div class="col"> -->
          <a class="btn btn-primary" id="button5"><p >Drinks</p></a>
        <!-- </div> -->
        <!-- <div class="col"> -->
          <a class="btn btn-primary" id="button6"><p >Dessert</p></a>
        <!-- </div> -->
      </div>
    </div>

    <!-- Order List -->
    <div class="row" id="orderBlock">
      <!-- <div class="col-lg-6"> -->
        <div class="card" id="order-list">
          <div class="card-header"><h4>Order List</h4></div>
          <!-- Insert ordered items -->
          <form class="form-inline" action="./action.php" method="POST">
            <div class="card-block">
              <input type="tableID" class="form-control" type="text" placeholder="Table No." name="tableid" required >
              <!--<input type="hidden" name="foodid"/>-->
              <input type="hidden" name="foodname"/>
              <input type="hidden" name="price"/>
            </div>

            <div class="card-footer">
              <div class="text-muted"><h4>Total</h4></div>
              <div id="total"></div>
            </div>

          </div>
          <input type= "submit" class="btn btn-info" name="save" value="save"></a>
        </form>
      <!-- </div> -->
    </div>

  </div>

  <script>
  //Search bar AND Side bar
  $(document).ready(function () {
    var table = $('.table').DataTable();
    $('#button1').on('click',function(){
      table.search("Burger").draw();
    });
    $('#button2').on('click',function(){
      table.search("Pizza").draw();
    });
    $('#button3').on('click',function(){
      table.search("Chicken").draw();
    });
    $('#button4').on('click',function(){
      table.search("Sides").draw();
    });
    $('#button5').on('click',function(){
      table.search("Drinks").draw();
    });
    $('#button6').on('click',function(){
      table.search("Dessert").draw();
    });
  });

  //Mini-order summary
  $("#menu tr").click(function() {
    var tableData = $(this).children("td").map(function() {
      return $(this).text();
    }).get();
    // console.log(tableData);
    $('#order-list .card-block').append('<p class="row" name="'+ '" id="summary"></p>')+
    $('#order-list .card-block p:last-child').append('<span class="col-6" name="foodname"> ' + tableData[1]+
    '</span><br /><input class="col" name="foodid[]" value='+ tableData[0]+'>' +
    '<input class="col" name="price" class="price-order" value='+ Number(tableData[2])+'>');
    var sum = 0;

    $(".card-block").find("input[name ='price']").each(function(){
      sum += Number($(this).val());
      $('#total').text('$' + sum.toFixed(2));
    });
    console.log('sum: ' + sum);

  });


  </script>
</body>
</html>