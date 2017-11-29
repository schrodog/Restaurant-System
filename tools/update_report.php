<?php
/** update report up to last n days (including today) **/
session_start();
// error_reporting(E_ALL);
// ini_set('display_errors', 'on');

$servername="localhost";
$username = $_SESSION["Username"];
$password =  $_SESSION["Password"];
$dbname = "Restaurant";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CALL update_report(".$_POST["days"].",".$_SESSION["StaffID"].");";
    $conn->exec($sql);
    

} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;

?>

