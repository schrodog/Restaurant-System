<?php
// menu.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'on');

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
  $username = $_SESSION["Username"];
  $password = $_SESSION["Password"];
  $dbname = "Restaurant";
  if ( isset($_POST["searchPrice1"])) {$price1 = $_POST["searchPrice1"];}
  if ( isset($_POST["searchPrice2"])) {$price2 = $_POST["searchPrice2"];}
  if ( isset($_POST["searchQuantity1"])) {$quan1 = $_POST["searchQuantity1"];}
  if ( isset($_POST["searchQuantity2"])) {$quan2 = $_POST["searchQuantity2"];}

  try {
      $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "select FoodID, FoodName, Price, Quantity, Category from `menu` where ";
      // $query = array();
      if (isset($_POST["foodCode"])){
        //  array_push($query, "FoodID like '%"$_POST["foodCode"]"%'");
        $sql = $sql."FoodID like '%".$_POST["foodCode"]."%'";
      }
      else if (isset($_POST["searchName"])){
        $sql = $sql."FoodName like '%".$_POST["searchName"]."%'";
      }
      else if ( isset($price1) || isset($price2)  ){
        $tmp = array();
        if (isset( $price1 )) { array_push($tmp, "Price>=$price1"); }
        if (isset( $price2 )) { array_push($tmp, "Price<=$price2"); }
        $sql = $sql.implode(' and ', $tmp);
        // echo $sql;
      }
      else if ( isset($quan1) || isset($quan2)  ){
        $tmp = array();
        if (isset( $quan1 )) { array_push($tmp, "Quantity>=$quan1"); }
        if (isset( $quan2 )) { array_push($tmp, "Quantity<=$quan2"); }
        $sql = $sql.implode(' and ', $tmp);
      }
      else {
        if (!isset($_SESSION["Category"])){
          $filter_category = "Burger";
        } else {
          $filter_category = $_SESSION["Category"];
        }
        $sql = "select FoodID, FoodName, Price, Quantity, Category from `menu` where Category='$filter_category'";
      }


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