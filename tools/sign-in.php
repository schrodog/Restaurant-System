<?php
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 'on');

$_SESSION["Username"] = $_POST["inputName"];
$_SESSION["Password"] = $_POST["inputPassword"];

$servername='localhost';
$username = $_POST["inputName"];
$password = $_POST["inputPassword"];
$dbname = "Restaurant";

$auth=0;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select * from `order` limit 1";
    $conn->exec($sql);

} catch (PDOException $e){
  $auth=0;
}

// check if the person have admin privilege to see all staff
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select * from `staff` limit 1";
    $conn->exec($sql);
    $auth = 1;
} catch (PDOException $e){
    $auth = 2;
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select StaffID from `staff` where UserName='$username'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $_SESSION["StaffID"] = (($stmt->fetchAll())[0])["StaffID"] ;
    
} catch (PDOException $e){
}
// echo $auth;


// get staffID
if ($error==2 ) {
  echo '<script type="text/javascript">
  alert("Wrong username or password!");
  window.location = "../index.php";
  </script>';
  exit;
}
// Admin
else if ($auth==1){
  // echo '1';
  $_SESSION["Privilege"]="Administrator";
  echo '<script type="text/javascript">
  window.location = "../main_menu-manager.php";
  </script>';
} else if ($auth==2) {
  // echo '2';

  // normal user
  $_SESSION["Privilege"]="User";
  echo '<script type="text/javascript">
  window.location = "../main_menu-user.php";
  </script>';
}

?>