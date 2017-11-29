<?php
session_start();

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
      $sql = "set SQL_SAFE_UPDATES = 0;
      UPDATE mysql.user SET user='$newName' where user='$username';
      FLUSH PRIVILEGES;";
      if ($privilege=="User"){
        $sql = $sql."
        GRANT ALL ON Restaurant.masterorder TO '$newName'@'localhost';
        GRANT SELECT, UPDATE(quantity) ON Restaurant.menu TO '$newName'@'localhost';
        GRANT ALL ON Restaurant.`order` TO '$newName'@'localhost';
        GRANT ALL ON Restaurant.report TO '$newName'@'localhost';
        GRANT SELECT, UPDATE(Available) ON Restaurant.`table` TO '$newName'@'localhost';
        GRANT SELECT(StaffID,password), UPDATE(PassWord) ON Restaurant.staff TO '$newName'@'localhost';
        DROP USER '$username'@'localhost';
        set SQL_SAFE_UPDATES = 1;
        FLUSH PRIVILEGES; ";
      } elseif ($privilege=="Administrator") {
        $sql = $sql."
        GRANT ALL ON *.* TO '$newName'@'localhost' WITH GRANT OPTION;
        GRANT CREATE USER ON *.* TO '$newName'@'localhost' WITH GRANT OPTION;
        DROP USER '$username'@'localhost';
        set SQL_SAFE_UPDATES = 1;
        FLUSH PRIVILEGES; ";
      }
      $conn->exec($sql);
    }
    elseif ($operation=="change_password") {
      $sql = "set password for '$username'@'localhost'= '$newPwd';  FLUSH PRIVILEGES;";
      $conn->exec($sql);
    }
    elseif ($operation=="view_password") {
      $sql = "SELECT PassWord from staff where `UserName`='$username';  FLUSH PRIVILEGES;";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $result=(($stmt->fetchAll())[0])['PassWord'];
      echo $result;
      // echo $sql;
    }

} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;


?>

