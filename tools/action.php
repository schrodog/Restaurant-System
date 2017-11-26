<?php


								$servername = "localhost";
								$username = "user1";
								$password = "123456";
								$dbname = "Restaurant";



								// Create connection
								$conn = new mysqli($servername,$username,$password,$dbname);
								// Check connection
								if ($conn->connect_error) {
									die("Connection failed: " . $conn->connect_error);
								}

								$abc = $_POST['foodid'];
								$table = $_POST['tableid'];

								//$foodid = $conn->real_escape_string($_POST['foodid']);
								//$foodname = $conn->real_escape_string($_REQUEST['foodname']);
								//$price = $conn->real_escape_string($_REQUEST['price']);

								//for ($i = 0; $i <count($abc); $i++) {
								print_r ($table."<br>");
								print_r ($abc[0]."<br>".$abc[1]."<br>");

								//$sql = "INSERT INTO `order` (FoodID) VALUES ('$abc[0]')";
								//$sql .= "; ";
								//$sql .= "INSERT INTO `order` (FoodID) VALUES ('$abc[1]')";

								if (!$conn->multi_query($sql)) {
									echo "Multi query failed: (" . $conn->errno . ") " . $conn->error;
								}

								do {
									if ($res = $conn->store_result()) {
										var_dump($res->fetch_all(MYSQLI_ASSOC));
										$res->free();
									}
								} while ($conn->more_results() && $conn->next_result());



								//}unset($abc);


								$conn->close();
						?>