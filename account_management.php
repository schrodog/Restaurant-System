<?php

$username = $_POST["username"];
$password = $_POST["password"];

$servername="localhost";
$username = "user1";
$password = "123456";
$dbname = "myDBPDO";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($operation=="update"){
        $sql = "update MyGuests set firstname=?, lastname=?, email=? where id=?;";
        $stmt = $conn->prepare($sql);
        // echo $valueList;
        foreach ($valueList as $value) {
            $tmp = $value;
            $id = array_shift($tmp);
            array_push($tmp, $id);
            // print_r($tmp);
            $stmt->execute($tmp);
        }
        echo "successfully updated " . $stmt->rowCount() . " rows";

    } else if ($operation=="insert"){
        $stmt = $conn->prepare("insert into MyGuests (id, firstname, lastname, email)
            values (:id, :firstname, :lastname, :email)");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':firstname', $fn);
        $stmt->bindParam(':lastname', $ln);
        $stmt->bindParam(':email', $email);

        foreach ($valueList as $value){
            $id =  $value[0];
            $fn = $value[1];
            $ln = $value[2];
            $email = $value[3];
            $stmt->execute();
        }

        echo "success insert";
    }

} catch (PDOException $e){
    echo $e->getMessage();
}
$conn = null;


?>


<!-- set password for 'user13'@'localhost'='456'; -->

<!-- change password
$sql = "set password for '$targetUser'@'localhost' = '$newpwd';"; -->

<!-- NEW user
for staff
CREATE USER 'user2'@'localhost' IDENTIFIED BY '123';
GRANT SELECT, UPDATE ON Restaurant.masterorder TO 'user2'@'localhost';
GRANT SELECT, UPDATE ON Restaurant.menu TO 'user2'@'localhost';
GRANT SELECT, UPDATE ON Restaurant.`order` TO 'user2'@'localhost';
GRANT SELECT ON Restaurant.report TO 'user2'@'localhost';
GRANT SELECT, UPDATE ON Restaurant.`table` TO 'user2'@'localhost';
GRANT UPDATE ON Restaurant.staff TO 'user2'@'localhost';
GRANT UPDATE ON mysql.user TO 'user2'@'localhost';
FLUSH PRIVILEGES; -->

<!-- for administrator
CREATE USER 'user_admin1'@'localhost' IDENTIFIED BY '123';
GRANT ALL ON *.* TO 'user_admin1'@'localhost' WITH GRANT OPTION;
GRANT CREATE USER ON *.* TO 'user_admin1'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES; -->

<!-- delete user
DROP USER 'user11'@'localhost';
FLUSH PRIVILEGES;
-->
<!-- GRANT UPDATE ON mysql.user TO 'user12'@'localhost'; -->

