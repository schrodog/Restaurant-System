<?php
session_start();

$servername = "localhost";
$username = $_SESSION["Username"];
$password = $_SESSION["Password"];
$dbname = "Restaurant";

// Create connection
$conn = new mysqli($servername,$username,$password,$dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION["Category"])) {
  $category = $_SESSION["Category"];
} else {
  $category = 'Burger';
}

$sql = "SELECT FoodID, FoodName, Price, Quantity, Category FROM menu where category='$category'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo '<tr> <td>'.$row['FoodID'].'</td><td>'.$row["FoodName"].'</td><td>'.$row["Price"].'</td><td>'.$row["Quantity"].'</td><td>'.$row["Category"].'</td></tr>';
  }
} else {
  echo "0 results";
}
$conn->close();
?>