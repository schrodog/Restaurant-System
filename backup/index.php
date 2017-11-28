<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <title>Tiny editable jQuery Bootstrap spreadsheet from MindMup</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- <meta name="keywords" content="opensource jquery bootstrap editable table spreadsheet" />
    <meta name="description" content="This tiny jQuery bootstrap plugin turns any table into an editable spreadsheet" /> -->
    <!-- <link rel="apple-touch-icon" href="https://d1g6a398qq2djm.cloudfront.net/img/apple-touch-icon.png" /> -->
    <!-- <link rel="shortcut icon" href="https://d1g6a398qq2djm.cloudfront.net/img/favicon.ico" /> -->
    <link href="external/google-code-prettify/prettify.css" rel="stylesheet">
    <!-- <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.no-icons.min.css" rel="stylesheet"> -->
    <!-- <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-responsive.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link href="//netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script> -->
    <script src="external/google-code-prettify/prettify.js"></script>
    <link href="index.css" rel="stylesheet">
    <script src="mindmup-editabletable.js"></script>
    <script src="numeric-input-example.js"></script>
    <script src="table-sort.js"></script>
    <!-- <script src="operation.js"></script> -->
</head>
<body>

<div class="container">
  <div class="hero-unit">
     <table id="mainTable" class="table table-striped">
         <thead><tr><th onclick="columnSort(0)" value="0">ID <span id="asc"></span></th>
             <th onclick="columnSort(1)" value="0">Firstname <span id="asc"></span></th>
             <th onclick="columnSort(2)" value="0">Lastname <span id="asc"></th>
             <th onclick="columnSort(3)" value="0">Email <span id="asc"></th></tr>
         </thead>
         <tbody>
<?php

// echo "<table style='border: solid 1px black;'>";
// echo "<tr><th>Id</th><th>Firstname</th><th>Lastname</th><th>Email</th></tr>";

class TableRows extends RecursiveIteratorIterator {
    function __construct($it){
        parent::__construct($it, self::LEAVES_ONLY);
    }
    function current(){
        return "<td>".parent::current()."</td>";
    }
    function beginChildren(){
        echo "<tr>";
    }
    function endChildren(){
        echo "</tr>"."\n";
    }
}

$servername="localhost";
$username = "user1";
$password = "123456";
$dbname = "myDBPDO";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("select id,firstname, lastname, email from MyGuests");
    $stmt->execute();   // $stmt = PDOStatement class

    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); // return associated array
    // create html table
    foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll() )) as $k=>$v){
        echo $v;
    }

    // echo "success";

} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;
// echo "</table>";

?>
</tbody>
<!-- <tfoot><tr><th><strong>TOTAL</strong></th><th></th><th></th><th></th></tr></thead> -->
</table>
</div>
</div>
<button type="button" id="updateBtn">Update</button><br>
<button type="button" id="addBtn">Add Row</button><br>
<button type="button" id="delBtn">Delete Row</button>
<datalist id="productName">
    <option value="Drink">Drink</option>
    <option value="Sides">Sides</option>
    <option value="Noodle">Noodle</option>
</datalist>

<script>
  $('#mainTable').editableTableWidget().numericInputExample().find('td:first').focus();
  // $('#textAreaEditor').editableTableWidget({editor: $('<textarea>')});
  // window.prettyPrint && prettyPrint();
</script>

</body>
</html>
