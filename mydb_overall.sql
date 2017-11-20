CREATE DATABASE IF NOT EXISTS Restaurant;
USE Restaurant;

--
-- Table structure for table `staff`
--
DROP TABLE IF EXISTS `staff`;
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
DROP TABLE IF EXISTS `table`;
CREATE TABLE `table` (
  `TableNo` varchar(11) NOT NULL,
  `NumOfSeat` int(11) DEFAULT NULL,
  `Available` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`TableNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `menu`
--
DROP TABLE IF EXISTS `menu`;
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
DROP TABLE IF EXISTS `report`;
CREATE TABLE `report` (
  `ReportID` int(11) NOT NULL AUTO_INCREMENT,
  `Income` int(11) DEFAULT NULL,
  `Date` DATE DEFAULT NULL,
  `Count` int(11) DEFAULT NULL,
  `StaffID` int(11) DEFAULT NULL,
  PRIMARY KEY (`ReportID`),
  KEY `fk_Report_Manager1_idx` (`StaffID`),
  CONSTRAINT `fk_Report_Manager1` FOREIGN KEY (`StaffID`) REFERENCES `staff` (`StaffID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `masterorder`
--
DROP TABLE IF EXISTS `masterorder`;
CREATE TABLE `masterorder` (
  `MasterOrderID` int(11) NOT NULL AUTO_INCREMENT,
  `Price` int(11) DEFAULT NULL,
  `Payment` int(11) DEFAULT NULL,
  `Change` int(11) DEFAULT NULL,
  `StaffID` int(11) DEFAULT NULL,
  `ReportID` int(11) DEFAULT NULL,
  `TableNo` varchar(11) NOT NULL,
  `CheckOut Time` TIME(1) DEFAULT NULL,
  `CheckOut Date` DATE NOT NULL,
  PRIMARY KEY (`MasterOrderID`),
  KEY `fk_MasterOrder_Waiter1_idx` (`StaffID`),
  KEY `fk_MasterOrder_Table1_idx` (`TableNo`),
  CONSTRAINT `fk_MasterOrder_Table1` FOREIGN KEY (`TableNo`) REFERENCES `table` (`TableNo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_MasterOrder_Waiter1` FOREIGN KEY (`StaffID`) REFERENCES `staff` (`StaffID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_MasterOrder_Report1` FOREIGN KEY (`ReportID`) REFERENCES `report` (`ReportID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `order`
--
DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `OrderID` int(11) NOT NULL AUTO_INCREMENT,
  `Quantity` int(11) DEFAULT NULL,
  `MasterOrderID` int(11) NOT NULL,
  `FoodID` varchar(11) NOT NULL,
  `Price` int(11) DEFAULT NULL,
  PRIMARY KEY (`OrderID`),
  KEY `fk_Order_MasterOrder1_idx` (`MasterOrderID`),
  KEY `fk_Order_Menu1_idx` (`FoodID`),
  CONSTRAINT `fk_Order_MasterOrder1` FOREIGN KEY (`MasterOrderID`) REFERENCES `masterorder` (`MasterOrderID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Order_Menu1` FOREIGN KEY (`FoodID`) REFERENCES `menu` (`FoodID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- insert data --

LOAD DATA LOCAL INFILE '~/Downloads/staff.csv'
INTO TABLE `staff`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Downloads/table.csv'
INTO TABLE `table`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Downloads/menu.csv'
INTO TABLE `menu`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Downloads/report.csv'
INTO TABLE `report`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Downloads/masterorder.csv'
INTO TABLE `masterorder`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;

LOAD DATA LOCAL INFILE '~/Downloads/order.csv'
INTO TABLE `order`
FIELDS TERMINATED BY ','
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS;








