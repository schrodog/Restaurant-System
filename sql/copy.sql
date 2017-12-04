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

LOAD DATA LOCAL INFILE '~/Programming/php/Restaurant-System/sql/staff.csv'
INTO TABLE `staff`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;



LOAD DATA LOCAL INFILE '~/Programming/php/Restaurant-System/sql/menu.csv'
INTO TABLE `menu`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

SET foreign_key_checks = 0;
DELETE FROM `report`;
ALTER TABLE `report` AUTO_INCREMENT = 1;
DELETE FROM `masterorder`;
ALTER TABLE `masterorder` AUTO_INCREMENT = 1;
DELETE FROM `order`;
ALTER TABLE `order` AUTO_INCREMENT = 1;
DELETE FROM `table`;
ALTER TABLE `table` AUTO_INCREMENT = 1;

LOAD DATA LOCAL INFILE '~/Programming/php/Restaurant-System/sql/table.csv'
INTO TABLE `table`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Programming/php/Restaurant-System/sql/report.csv'
INTO TABLE `report`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Programming/php/Restaurant-System/sql/masterorder.csv'
INTO TABLE `masterorder`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Programming/php/Restaurant-System/sql/order.csv'
INTO TABLE `order`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

