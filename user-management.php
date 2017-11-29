<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, shrink-to-fit=no">
    <!-- <meta name="description" content="">
    <meta name="author" content=""> -->

    <title>Restaurant System</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="custom_css/user-management.css" rel="stylesheet" type="text/css" />

    <script src="lib/jquery-3.2.1.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script> -->
    <!-- <script src="lib/bootstrap.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <script src="js/user-management-editabletable.js"></script>
    <script src="js/user-management-input.js"></script>
    <script src="js/table-sort.js"></script>

</head>

<body>

<header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <a class="navbar-brand mr-auto" href="main_menu-manager.php">Home</a>

    <form class="form-inline"> <!-- mt-2 mt-md-0 -->
      <input id="search" class="form-control mr-sm-2 no_focus" type="text" placeholder="Search by username" aria-label=" Search">
      <!-- <button class="btn btn-outline-success" type="submit">Search</button> -->
    </form>
    <button type="button" id="logoutBtn" class="btn btn-warning"><img src="icon/log-out.svg"> Logout </button>
  </nav>
</header>
<h2>User Management</h2>


<div class="container">
  <div class="hero-unit">
     <table id="mainTable" class="table table-striped">
         <thead><tr>
             <th onclick="columnSort(0)" value="0">ID</th>
              <th onclick="columnSort(1)" value="0">Firstname</th>
              <th onclick="columnSort(2)" value="0">Lastname</th>
              <th onclick="columnSort(3)" value="0">Age</th>
              <th onclick="columnSort(4)" value="0">Username</th>
              <th onclick="columnSort(5)" value="0">Contact Number</th>
              <th onclick="columnSort(6)" value="0">Position</th>
              <th onclick="columnSort(7)" value="0">Gender</th>
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
        if (self::$index==0 or self::$index==4){
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
        echo "<td class='no_focus'><button type='button' class='keyBtn btn' style='background-color:transparent'><img src='icon/key.png'/></button></td>
        <td class='no_focus'><button type='button' class='delBtn btn' style='background-color:transparent'><img src='icon/delete.png'></button></td></tr>\n";
        // echo "</tr>\n";
    }
}

$servername="localhost";
$username = $_SESSION["Username"];
$password =  $_SESSION["Password"];
$dbname = "Restaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select StaffID, Firstname, LastName, Age, Username, `ContactNumber`, Position, Gender from staff";

    $stmt = $conn->prepare($sql);
    $stmt->execute();   // $stmt = PDOStatement class

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); // return associated array
    // create html table
    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll() )) as $k=>$v){
        echo $v;
    }

    // echo "success";

} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;
// echo "</table>";

?>
</tbody></table></div></div>

<footer class="footer">
    <div class="container">
        <button class="btn btn-info" id="addBtn">Add User</button>
        <button type="button" class="btn btn-primary" id="updateBtn">Update</button>
        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">dialog</button><br> -->
    </div>
</footer>

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

<div id="accountModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">Manage Account</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <button type="button" class="btn btn-default" data-dismiss="modal" id="newPwd">New Password</button>
    <button type="button" class="btn btn-default" data-dismiss="modal" id="changeUser">Change Username</button>
    <button type="button" class="btn btn-default" data-dismiss="modal" id="viewPwd">View password</button>
  </div>
</div></div></div>

<div id="newPasswordModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">New Password</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <label for="pwd">Please enter new password:</label>
    <input type="password" class="form-control no_focus" id="pwd1">
    <!-- <label for="pwd">Re-enter new password:</label>
    <input type="password" class="form-control no_focus" id="pwd2"> -->
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div></div></div>

<div id="viewPasswordModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">Current Password</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <p id="currentPwd" style="font-size: 20px"></p>
  </div>
</div></div></div>

<div id="changeUserModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">Change Username</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <label>Please enter new username:</label>
    <input type="text" class="form-control no_focus" id="username">
    <label>Choose privilege type: </label>
    <div class="dropdown">
      <button onclick="showDropdown2()" class="dropbtn dropdown-toggle btn btn-primary" id="privType2">User</button>
      <div id="privList2" class="dropdown-content">
        <a>User</a><a>Administrator</a>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div></div></div>

<div id="addUserModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content">
  <div class="modal-header">
    <h4 class="modal-title">Add User</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
  </div>
  <div class="modal-body">
    <div class="modal-item-list">
      <label>Enter username:</label>
      <input type="text" class="form-control no_focus" id="username">
    </div>
    <div class="modal-item-list">
      <label for="pwd">Enter password:</label>
      <input type="password" class="form-control no_focus" id="pwd">
    </div>
    <div class="modal-item-list" id="dropdown-group">
    <label>Choose privilege type:</label>
    <div class="dropdown">
     <button onclick="showDropdown()" class="dropbtn dropdown-toggle btn btn-primary" id="privType">User</button>
     <div id="privList" class="dropdown-content">
       <a>User</a><a>Administrator</a>
     </div>
   </div></div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-primary" id="OK" data-dismiss="modal">OK</button>
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
  </div>
</div></div></div>

<script>
$('#mainTable').editableTableWidget().numericInputExample();
</script>
<script src="js/logout.js"></script>

</body>
</html>
