<?php
session_start();
// $privilege = "Administrator";
$privilege = "User";
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Table Management</title>

  <!-- Bootstrap core CSS -->
  <script src="lib/jquery-3.2.1.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> -->
  <script src="js/table-sort.js"></script>
  <link href="custom_css/table-management.css" rel="stylesheet" type="text/css" />

<?php
if ($privilege=="User") {
  echo "<style>
  #mainTable tr{
    cursor: pointer;
    line-height: 2.2;
  }</style>";
}
?>

</head>


<body>
  <!-- Navigation -->

  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="icon-bar">
      <a class="active" href="index.html"><i class="fa fa-home"></i></a>
    </div>
    <ul class="title" >Table Menu</p></ul>
    <button type="button" class="btn btn-warning"><img src="icon/log-out.svg"> Logout </button>
  </nav>
  <!-- Page Content -->
  <div class="container-fluid">
    <!-- Menu table -->
    <br>
    <!-- <div class="search-block">
      <input type="text" id="searchCode" placeholder="Food Code" class="no_focus">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </div> -->

         <table id="mainTable" class="table table-striped justify-content-md-center">
             <thead><tr>
               <th onclick="columnSort(0)" value="0">Table No</th>
                <th onclick="columnSort(1)" value="0">Num of seats</th>
                <th onclick="columnSort(2)" value="0">Availability</th>
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
                global $privilege;
                if ($privilege=="Administrator"){
                  echo "<td class='no_focus'><button type='button' class='goToBtn btn'>Go to table</button></td>
                  <td class='no_focus'><button type='button' class='changeTableBtn btn'>Change Table No</button></td>
                  <td class='no_focus'><button type='button' class='delBtn btn' style='background-color:transparent'><img src='icon/delete.png'></button></td></tr>";
                  //style='background-color:transparent' <img src='icon/key.png'/>
                } else {
                  echo "</tr>";
                }
            }
        }

        $servername="localhost";
        $username = "user1";
        $password = "123456";
        $dbname = "Restaurant";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "select TableNo, NumOfSeat, Available from `table`";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); // return associated array
            foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll() )) as $k=>$v){
                echo $v;
            }
        } catch (PDOException $e){
            echo $e->getMessage();
        }
        $conn = null;
      ?>
              </tbody></table>

</div>

<?php
if ($privilege=="Administrator"){
  echo '<footer class="footer">
  <div class="container">
  <button class="btn btn-info" id="addBtn">New Table</button>
  <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
  </div>
  </footer>';
}
?>

<div id="newTableModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">New Table</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <div class="modal-item-list">
      <label>New table no*: </label>
      <input type="text" class="form-control no_focus" id="tableno">
    </div>
    <div class="modal-item-list">
      <label>Enter number of seats: </label>
      <input type="text" class="form-control no_focus" id="numOfSeats">
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div></div></div>

<div id="changeNoModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">Change Table Number</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <div class="modal-item-list">
      <label>New table no: </label>
      <input type="text" class="form-control no_focus" id="newTableNo">
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
      <p>Are you sure to delete this table?</p>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div></div></div>

<div id="assignNewModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">New Order</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <div class="modal-item-list">
      <p>Are you sure to assign customer to this table?</p>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div></div></div>

<script>

</script>
<script src="js/table-management-input.js"></script>
<script src="js/table-management-editableTable.js"></script>

<?php
if ($privilege=="Administrator"){
  echo "<script>$('#mainTable').editableTableWidget().numericInputExample();</script>";
  echo "<script>
  $('.goToBtn').click(function(){
    var thisRow = $(this).parent().parent();
    var tableNo = thisRow.find('td').first().text();
    var available = thisRow.find('td:nth-child(3)').text();
    GoToTable(tableNo, available);
  });
  </script>";
} elseif ($privilege=="User") {
  echo "<script>
  $('#mainTable tbody tr').click(function(){
    var thisRow = $(this);
    var tableNo = thisRow.find('td').first().text();
    var available = thisRow.find('td:nth-child(3)').text();
    GoToTable(tableNo, available);
  });
  </script>";
}
?>

</body>
</html>