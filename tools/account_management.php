<?php

$operation = $_POST["operation"];
if (isset($_POST["username"])){ $username = $_POST["username"]; }
if (isset($_POST["password"])){ $password = $_POST["password"]; }
if (isset($_POST["privilege"])){ $privilege = $_POST["privilege"]; }
if (isset($_POST["newName"])){ $newName = $_POST["newName"]; }
if (isset($_POST["newPwd"])){ $newPwd = $_POST["newPwd"]; }

$servername="localhost";
$username1 = $_SESSION["Username"];
$password1 = $_SESSION["Password"];
$dbname = "Restaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username1, $password1);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($operation=="add_user"){
        if ($privilege=="User"){
          $sql="
          CREATE USER '$username'@'localhost' IDENTIFIED BY '$password';
          GRANT ALL ON Restaurant.masterorder TO '$username'@'localhost';
          GRANT SELECT, UPDATE(quantity) ON Restaurant.menu TO '$username'@'localhost';
          GRANT ALL ON Restaurant.`order` TO '$username'@'localhost';
          GRANT ALL ON Restaurant.report TO '$username'@'localhost';
          GRANT SELECT, UPDATE(Available) ON Restaurant.`table` TO '$username'@'localhost';
          GRANT SELECT(StaffID,password), UPDATE(PassWord) ON Restaurant.staff TO '$username'@'localhost';
          FLUSH PRIVILEGES; ";
          $conn->exec($sql);

        } elseif ($privilege=="Administrator") {
          $sql = "CREATE USER '$username'@'localhost' IDENTIFIED BY '$password';
          GRANT ALL ON *.* TO '$username'@'localhost' WITH GRANT OPTION;
          GRANT CREATE USER ON *.* TO '$username'@'localhost' WITH GRANT OPTION;
          FLUSH PRIVILEGES;";
          $conn->exec($sql);
        }

    }
    elseif ($operation=="delete_user"){
        $sql = "DROP USER '$username'@'localhost'; FLUSH PRIVILEGES;";
        $conn->exec($sql);
    }
    elseif ($operation=="change_username") {
      $sql = "UPDATE mysql.user SET user='$newName' where user='$username';";
      $conn->exec($sql);
    }
    elseif ($operation=="change_password") {
      $sql = "set password for '$username'@'localhost'= '$newPwd'; ";
      $conn->exec($sql);
    }
    elseif ($operation=="view_password") {
      $sql = "SELECT PassWord from staff where UserName='$username'; ";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $result=(($stmt->fetchAll())[0])['PassWord'];
      echo $result;
    }

} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;


?>

