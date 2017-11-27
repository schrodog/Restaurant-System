<?php 
session_start();
$_SESSION["Username"] = $_POST["inputName"];
$_SESSION["Password"] = $_POST["inputPassword"];

$servername='localhost';
$username = $_POST["inputName"];
$password = $_POST["inputPassword"];
$dbname = "Restaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e){
    // echo $e->getMessage();
    echo '<script type="text/javascript">
    alert("Wrong username or password!");
    window.location = "../index.php";
    </script>';
    exit;
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "select * from `staff` limit 10";
    $conn->exec($sql);
    $error=0;
} catch (PDOException $e){
    // echo $e->getMessage();
    // echo "no admin<br>";
    $error=1;
}
if ($error==0){
  // Admin
  $_SESSION["Privilege"]="Administrator";
  echo '<script type="text/javascript">
  window.location = "../main_menu-manager.php";
  </script>';
} else  {
  // normal user
  $_SESSION["Privilege"]="User";
  echo '<script type="text/javascript">
  window.location = "../main_menu-user.php";
  </script>';
}
// echo $_SESSION["Privilege"];

?>