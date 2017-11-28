<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Menu Management</title>

  <!-- Bootstrap core CSS -->
  <script src="lib/jquery-3.2.1.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
  <script src="js/table-sort.js"></script>
  <link href="custom_css/menu-management.css" rel="stylesheet" type="text/css" />
</head>


<body>
  <!-- Navigation -->

  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="icon-bar">
      <a class="active" href="main_menu-manager.php"><i class="fa fa-home"></i></a>
    </div>
    <ul class="title" >Food Menu</p></ul>
    <button type="button" id="logoutBtn" class="btn btn-warning"><img src="icon/log-out.svg"> Logout </button>
  </nav>
  <!-- Page Content -->
  <div class="container-fluid">
    <!-- Menu table -->
    <br>
    <div class="search-block">
      <input type="text" id="searchCode" placeholder="Food Code" class="no_focus">
      <input type="text" id="searchName" placeholder="Food Name" class="no_focus">
      <input type="text" id="searchPrice1" placeholder="Price" class="no_focus price">-
      <input type="text" id="searchPrice2" placeholder="Price" class="no_focus price">
      <input type="text" id="searchQuantity1" placeholder="Quanity" class="no_focus quan">-
      <input type="text" id="searchQuantity2" placeholder="Quanity" class="no_focus quan">
      <!-- <button class="btn btn-outline-success" type="submit">Search</button> -->
    </div>

    <div class="row justify-content-md-center" id="right-part">  <!--   justify-content-md-center -->

         <table id="mainTable" class="table table-striped">
             <thead><tr>
               <th onclick="columnSort(0)" value="0">Code</th>
                <th onclick="columnSort(1)" value="0">Food Name</th>
                <th onclick="columnSort(2)" value="0">Price</th>
                <th onclick="columnSort(3)" value="0">Quantity</th>
                <th onclick="columnSort(4)" value="0">Category</th>
                <th></th>
            </tr></thead>
            <tbody>
      <?php

        class TableRows extends RecursiveIteratorIterator {
            private static $index=0;
            function __construct($it){
                parent::__construct($it, self::LEAVES_ONLY);
            }
            function current(){
                if (self::$index==0){
                  $str = "<td class='no_focus'>";
                } else {
                  $str = "<td>";
                }
                self::$index++;
                return $str.parent::current()."</td>";
            }
            function beginChildren(){
                self::$index=0;
                echo "<tr>";
            }
            function endChildren(){
                echo "<td class='no_focus'><button type='button' class='delBtn btn' style='background-color:transparent'><img src='icon/delete.png'></button></td></tr>\n";
                //style='background-color:transparent' <img src='icon/key.png'/>
            }
        }

        $servername="localhost";
        $username =$_SESSION["Username"];
        $password = $_SESSION["Password"];
        $dbname = "Restaurant";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            if (!isset($_SESSION["Category"])){
              $filter_category = "Burger";
            } else {
              $filter_category = $_SESSION["Category"];
            }
            $sql = "select FoodID, FoodName, Price, Quantity, Category from `menu` where Category='$filter_category'";

            // echo $sql;

            $stmt = $conn->prepare($sql);
            $stmt->execute();
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
              </tbody></table>

      </div> <!-- end right-part -->



    <div id="type-navigation">
      <!-- <div class="row"> -->
        <div id="typeBtnList"></div>
        <!-- <a class="btn btn-primary" id="button1"><p >Burger</p></a>
      </div>
    </div> -->
    </div>
</div>

<footer class="footer">
    <div class="container">
        <button class="btn btn-info" id="addBtn">New Food</button>
        <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
    </div>
</footer>

<div id="newFoodModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">New Food</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <div class="modal-item-list">
      <label>New food code*:</label>
      <input type="text" class="form-control no_focus" id="foodCode">
    </div>
    <div class="modal-item-list">
      <label>Enter food name:</label>
      <input type="text" class="form-control no_focus" id="foodName">
    </div>
    <div class="modal-item-list">
      <label>Enter price:</label>
      <input type="text" class="form-control no_focus" id="price">
    </div>
    <div class="modal-item-list">
      <label>Enter quantity:</label>
      <input type="text" class="form-control no_focus" id="quantity">
    </div>
    <div class="modal-item-list">
      <label>Enter category*:</label>
      <input type="text" class="form-control no_focus" id="category">
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div></div></div>

<div id="deleteModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">Delete row</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <div class="modal-item-list">
      <p>Are you sure to delete this row?</p>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div></div></div>


<script>
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
        $("#typeBtnList").append('<button class="btn btn-primary type-btn" ><p>'+value+'</p></button>');
      });
    });
  }
}
refresh_buttons();
</script>
<script src="js/menu-management-input.js"></script>
<script src="js/menu-management-editableTable.js"></script>

<script>$('#mainTable').editableTableWidget().numericInputExample();</script>
<script src="js/logout.js"></script>
</body>
</html>