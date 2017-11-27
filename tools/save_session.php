<?php
session_start();
$nameArray = $_POST["name"];
$valueArray = $_POST["value"];

foreach ($valueArray as $key => $value) {
  $_SESSION[$nameArray[$key]] = $value;
  echo $_SESSION[$nameArray[$key]];
}

?>