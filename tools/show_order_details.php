<?php
session_start();
$servername="localhost";
$username = $_SESSION["Username"];
$password = $_SESSION["Password"];
$dbname = "Restaurant";

$masterOrderID = $_SESSION["masterOrderID"];

class TableRows2 extends RecursiveIteratorIterator {
    function __construct($it){
        parent::__construct($it, self::LEAVES_ONLY);
    }
    function current(){
        $str = "<td>";
        return $str.parent::current()."</td>";
    }
    function beginChildren(){
        echo "<tr>";
    }
    function endChildren(){
        echo "</tr>";
    }
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT OrderID,`order`.Quantity,menu.FoodName,`order`.price
            FROM `order`,menu
            where `order`.FoodID=menu.FoodID and MasterOrderID=$masterOrderID;";

    // echo $sql;
    $stmt = $conn->prepare($sql);
    $stmt->execute();   // $stmt = PDOStatement class

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); // return associated array
    // create html table
    foreach(new TableRows2(new RecursiveArrayIterator($stmt->fetchAll() )) as $k=>$v){
        echo $v;
    }
} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;

echo "<script>removeFile()</script>";
?>