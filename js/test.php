<?php
session_start();

  class TableRows extends RecursiveIteratorIterator {
      private static $index=0;
      function __construct($it){
          parent::__construct($it, self::LEAVES_ONLY);
      }
      function current(){
          if (self::$index==0){
            $str = "<td class='no_focus'>";
          } else {
            $str = "<td>";
          }
          self::$index++;
          return $str.parent::current()."</td>";
      }
      function beginChildren(){
          self::$index=0;
          echo "<tr>";
      }
      function endChildren(){
          echo "<td class='no_focus'><button type='button' class='delBtn btn' style='background-color:transparent'><img src='icon/delete.png'></button></td></tr>\n";
          //style='background-color:transparent' <img src='icon/key.png'/>
      }
  }

  $servername="localhost";
  $username = "user1";
  $password = "123456";
  $dbname = "Restaurant";

  try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      if (!isset($_SESSION["Category"])){
        $filter_category = "Burger";
      } else {
        $filter_category = $_SESSION["Category"];
      }
      $sql = "select FoodID, FoodName, Price, Quantity, Category from `menu` where Category='$filter_category'";

      // echo $sql;
      
      $stmt = $conn->prepare($sql);
      $stmt->execute();  
      $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); // return associated array
      // create html table
      foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll() )) as $k=>$v){
          echo $v;
      }

  } catch (PDOException $e){
      echo $e->getMessage();
  }
  $conn = null;

  
echo "<script>$('#mainTable').editableTableWidget().numericInputExample();</script>";
?>