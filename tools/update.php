<?php
/*** compulsory: (target_table, operation)
    update: headerList, valueList , idList, idName
    insertEmpty: idName
    insert: headerList, valueList
    delete: idName, valueList (only 1 [])
***/

$operation = $_POST["operation"];
$target_table = $_POST["target_table"];
if(isset($_POST["valueList"])) { $valueList = $_POST["valueList"]; }
if(isset($_POST["headerList"])) { $headerList = $_POST["headerList"]; }
if(isset($_POST["idList"])) { $idList = $_POST["idList"]; }
if(isset($_POST["idName"])) { $idName = $_POST["idName"]; }


$servername="localhost";
$username = "user1";
$password = "123456";
$dbname = "Restaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($operation=="update"){
        // $sql = "update User set FName=?, LName=?, Age=?, Username=?, `Contact Number`=?, Position=? where id=?;";
        $columns = "`".implode("`=?, `", $headerList)."`";
        $sql = "UPDATE `$target_table` SET ".$columns."=? WHERE $idName=?;";
        if(isset($_POST["override"])) { $sql = "SET SQL_SAFE_UPDATES=0; SET FOREIGN_KEY_CHECKS=0;".$sql." SET SQL_SAFE_UPDATES=1; SET FOREIGN_KEY_CHECKS=1;"; }

        $stmt = $conn->prepare($sql);
        echo $sql;
        foreach ($valueList as $index=>$value) {
            $value2 = array_map(function($val){
              return $val === "" ? NULL : $val;
            }, $value); // convert empty value to NULL to avoid error
            array_push($value2, strval($idList[$index]));
            print_r($value2);
            $stmt->execute($value2);
        }
        if(isset($_POST["getLastID"])) {
          $last_id = $stmt->lastInsertID();
        }
        // echo "successfully updated " . $stmt->rowCount() . " rows";

    } else if ($operation=="insertEmpty"){
        $sql = "INSERT INTO `$target_table` (`$idName`) VALUES (NULL)";
        // echo $sql;
        $conn->exec($sql);

        // echo $valueList." success insert";

    } else if ($operation=="insert"){

      $columns = "`".implode("`, `", $headerList )."`";

      foreach ($valueList as $row) {
        $values = array_map(function($val){
          return $val === "" ? NULL : $val;
        }, $row);
        $value2 = "'".implode("', '", $values)."'";
        $sql = "INSERT INTO `$target_table` ($columns) VALUES ($value2)";
        // echo $sql;
        $conn->exec($sql);
      }
      if(isset($_POST["getLastID"])) {
        $last_id = $conn->lastInsertID();
        echo $last_id;
      }
    }

    else if ($operation == "delete"){
        $values = implode(", ", $valueList);
        $sql = "delete from `$target_table` where `$idName` in ('$values'); ";
        if(isset($_POST["override"])) { $sql = "SET SQL_SAFE_UPDATES=0; SET FOREIGN_KEY_CHECKS=0; ".$sql." SET SQL_SAFE_UPDATES=1; SET FOREIGN_KEY_CHECKS=1;"; }

        $conn->exec($sql);
        echo $sql;
        // echo $valueList." deleted successfully";
    }

} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;

?>

