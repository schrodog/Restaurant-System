<?php
session_start();
// $servername="localhost";
// $username = "user1";
// $password = "123456";
// $dbname = "Restaurant";

if (isset($_SESSION["masterOrderID"])) {$masterOrderID = $_SESSION["masterOrderID"];}

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
    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll() )) as $k=>$v){
        echo $v;
    }
} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;

echo "<script>removeFile()</script>";
?>