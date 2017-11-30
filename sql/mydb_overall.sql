CREATE DATABASE IF NOT EXISTS Restaurant;
USE Restaurant;

--
-- Table structure for table `staff`
--
-- DROP TABLE IF EXISTS `staff`;
CREATE TABLE `staff` (
  `StaffID` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(45) DEFAULT NULL,
  `LastName` varchar(45) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `UserName` varchar(45) DEFAULT NULL,
  `PassWord` varchar(45) DEFAULT NULL,
  `ContactNumber` int(11) DEFAULT NULL,
  `Position` varchar(45) DEFAULT NULL,
  `Gender` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`StaffID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `table`
--
-- DROP TABLE IF EXISTS `table`;
CREATE TABLE `table` (
  `TableNo` varchar(11) NOT NULL,
  `NumOfSeat` int(11) DEFAULT NULL,
  `Available` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`TableNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `menu`
--
-- DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `FoodID` varchar(11) NOT NULL,
  `FoodName` varchar(45) DEFAULT NULL,
  `Price` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Category` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`FoodID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `report`
--
-- DROP TABLE IF EXISTS `report`;
CREATE TABLE `report` (
  `ReportID` int(11) NOT NULL AUTO_INCREMENT,
  `Income` int(11) DEFAULT NULL,
  `Date` DATE DEFAULT NULL,
  `Count` int(11) DEFAULT NULL,
  `StaffID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ReportID`),
  KEY `fk_Report_Manager1_idx` (`StaffID`),
  CONSTRAINT `fk_Report_Manager1` FOREIGN KEY (`StaffID`) REFERENCES `staff` (`StaffID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `masterorder`
--
-- DROP TABLE IF EXISTS `masterorder`;
CREATE TABLE `masterorder` (
  `MasterOrderID` int(11) NOT NULL AUTO_INCREMENT,
  `Price` int(11) DEFAULT NULL,
  `Payment` int(11) DEFAULT NULL,
  `Change` int(11) DEFAULT NULL,
  `StaffID` int(11) DEFAULT NULL,
  `ReportID` int(11) DEFAULT NULL,
  `TableNo` varchar(11) DEFAULT NULL,
  `CheckOut Time` TIME(1) DEFAULT NULL,
  `CheckOut Date` DATE NOT NULL DEFAULT '1000-01-01',
  PRIMARY KEY (`MasterOrderID`),
  KEY `fk_MasterOrder_Waiter1_idx` (`StaffID`),
  KEY `fk_MasterOrder_Table1_idx` (`TableNo`),
  CONSTRAINT `fk_MasterOrder_Table1` FOREIGN KEY (`TableNo`) REFERENCES `table` (`TableNo`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_MasterOrder_Waiter1` FOREIGN KEY (`StaffID`) REFERENCES `staff` (`StaffID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_MasterOrder_Report1` FOREIGN KEY (`ReportID`) REFERENCES `report` (`ReportID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `order`
--
-- DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `Quantity` int(11) DEFAULT NULL,
  `MasterOrderID` int(11) DEFAULT NULL,
  `FoodID` varchar(11) DEFAULT NULL,
  `Price` int(11) DEFAULT NULL,
  PRIMARY KEY (`OrderID`),
  KEY `fk_Order_MasterOrder1_idx` (`MasterOrderID`),
  KEY `fk_Order_Menu1_idx` (`FoodID`),
  CONSTRAINT `fk_Order_MasterOrder1` FOREIGN KEY (`MasterOrderID`) REFERENCES `masterorder` (`MasterOrderID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_Order_Menu1` FOREIGN KEY (`FoodID`) REFERENCES `menu` (`FoodID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- insert data --

LOAD DATA LOCAL INFILE '~/Documents/Courses/Database/Project/staff.csv'
INTO TABLE `staff`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Documents/Courses/Database/Project/table.csv'
INTO TABLE `table`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Documents/Courses/Database/Project/menu.csv'
INTO TABLE `menu`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Documents/Courses/Database/Project/report.csv'
INTO TABLE `report`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Documents/Courses/Database/Project/masterorder.csv'
INTO TABLE `masterorder`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Documents/Courses/Database/Project/order.csv'
INTO TABLE `order`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

-- force delete old data --
SET foreign_key_checks = 0;
DELETE FROM `report`;
ALTER TABLE `report` AUTO_INCREMENT = 1;
DELETE FROM `masterorder`;
ALTER TABLE `masterorder` AUTO_INCREMENT = 1;
DELETE FROM `order`;
ALTER TABLE `order` AUTO_INCREMENT = 1;

-- User --
CREATE USER '$username'@'localhost' IDENTIFIED BY '$password';
GRANT ALL ON Restaurant.masterorder TO '$username'@'localhost';
GRANT SELECT, UPDATE(quantity) ON Restaurant.menu TO '$username'@'localhost';
GRANT ALL ON Restaurant.`order` TO '$username'@'localhost';
GRANT ALL ON Restaurant.report TO '$username'@'localhost';
GRANT SELECT, UPDATE(Available) ON Restaurant.`table` TO '$username'@'localhost';
GRANT SELECT(StaffID,password,UserName), UPDATE(PassWord) ON Restaurant.staff TO '$username'@'localhost';
FLUSH PRIVILEGES;

-- Administrator --
CREATE USER '$username'@'localhost' IDENTIFIED BY '$password';
GRANT ALL ON *.* TO '$username'@'localhost' WITH GRANT OPTION;
GRANT CREATE USER ON *.* TO '$username'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;

-- stored procedure for report update --
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_report`(p1 int, staffID int(11))
BEGIN
  declare income int;
  declare n int;
  declare date_var date;
  declare i int default 0;

  set i=0;
  while i<p1 do
    select sum(price), count(*), `CheckOut Date`
    into income,n, date_var
    from masterorder
    where `CheckOut Date` = date_add(curdate(), interval -p1+i+1 day);

    if (select exists(select 1 from `report` where `Date`=date_var))=1 then
  	  update report set `Count`=n, `Date`=date_var, `Income`=income, `StaffID`=staffID where `Date`=date_var;
  	else
  		insert into report (`Count`,`Date`,`Income`,`StaffID`) VALUES (n,date_var,income, staffID);
    end if;
    set i=i+1;
  end while;
END
