<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'on');

$_SESSION["Username"] = $_POST["inputName"];
$_SESSION["Password"] = $_POST["inputPassword"];

$servername='localhost';
$username = $_POST["inputName"];
$password = $_POST["inputPassword"];
$dbname = "Restaurant";

$error=0;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select * from `order` limit 1";
    $conn->exec($sql);

} catch (PDOException $e){
  // echo '0';
  $error=2;
    // echo $e->getMessage();

    // exit;
}


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "select * from `staff` limit 10";
    $conn->exec($sql);
    $auth = 1;
} catch (PDOException $e){

    // echo $e->getMessage();
    // echo "no admin<br>";
    $auth = 2;
}
// Admin

if ($error==2 ) {
  echo '<script type="text/javascript">
  alert("Wrong username or password!");
  window.location = "../index.php";
  </script>';
}

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