<?php
session_start();
$nameArray = $_POST["name"];
if (isset($_POST["value"])) { $valueArray = $_POST["value"]; }
if(isset($_POST["unset"])) {
  $del=1; // enable unset session variable
} else {
  $del=0;
}

if ($del==0){
  foreach ($valueArray as $key => $value) {
    $_SESSION[$nameArray[$key]] = $value;
    echo $_SESSION[$nameArray[$key]];
  }
} else {
  foreach ($nameArray as $name){
    unset($_SESSION[$name]);
  }
}

?>