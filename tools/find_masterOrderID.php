<?php
session_start();
/** select all category type **/

$servername="localhost";
$username = "user1";
$password = "123456";
$dbname = "Restaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT  from menu;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_NUM);

    echo json_encode($stmt->fetchAll());

} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;

?>

