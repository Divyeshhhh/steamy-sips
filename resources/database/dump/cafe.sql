-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: cafe
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `administrator` (
  `user_id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `is_superadmin` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `admin_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `check_job_title_length` CHECK (char_length(`job_title`) > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administrator`
--

LOCK TABLES `administrator` WRITE;
/*!40000 ALTER TABLE `administrator` DISABLE KEYS */;
/*!40000 ALTER TABLE `administrator` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client`
--

DROP TABLE IF EXISTS `client`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `client` (
  `user_id` int(11) NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  CONSTRAINT `client_fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `check_street_length` CHECK (char_length(`street`) > 0),
  CONSTRAINT `check_city_length` CHECK (char_length(`city`) > 0),
  CONSTRAINT `check_district_valid` CHECK (`district` in ('Moka','Port Louis','Flacq','Curepipe','Black River','Savanne','Grand Port','Rivi├¿re du Rempart','Pamplemousses','Mahebourg','Plaines Wilhems'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client`
--

LOCK TABLES `client` WRITE;
/*!40000 ALTER TABLE `client` DISABLE KEYS */;
/*!40000 ALTER TABLE `client` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(50) NOT NULL CHECK (`status` in ('pending','cancelled','completed')),
  `created_date` date NOT NULL,
  `pickup_date` date DEFAULT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `district` varchar(255) DEFAULT NULL CHECK (`district` in ('Moka','Port Louis','Flacq','Curepipe','Black River','Savanne','Grand Port','Rivi├¿re du Rempart','Pamplemousses','Mahebourg','Plaines Wilhems')),
  `total_price` decimal(10,2) NOT NULL CHECK (`total_price` >= 0),
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `order_fk` (`user_id`),
  CONSTRAINT `order_fk` FOREIGN KEY (`user_id`) REFERENCES `client` (`user_id`),
  CONSTRAINT `check_street_length` CHECK (char_length(`street`) > 0),
  CONSTRAINT `check_city_length` CHECK (char_length(`city`) > 0)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
INSERT INTO `order` VALUES (1,'pending','2024-02-17','2024-02-18','123 Main St','Port-louis','Moka',50.00,4),(2,'cancelled','2024-02-18','2024-02-19','456 Elm St','Port-louis','Moka',75.00,4);
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_product`
--

DROP TABLE IF EXISTS `order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_product` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL CHECK (`quantity` > 0),
  `cup_size` varchar(50) NOT NULL,
  `milk_type` varchar(50) NOT NULL,
  PRIMARY KEY (`order_id`,`product_id`),
  KEY `order_product_2fk` (`product_id`),
  CONSTRAINT `order_product_1fk` FOREIGN KEY (`order_id`) REFERENCES `order` (`order_id`),
  CONSTRAINT `order_product_2fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  CONSTRAINT `check_cup_size` CHECK (`cup_size` in ('small','medium','large')),
  CONSTRAINT `check_milk_type` CHECK (`milk_type` in ('almond','coconut','oat','soy'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_product`
--

LOCK TABLES `order_product` WRITE;
/*!40000 ALTER TABLE `order_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `calories` int(11) DEFAULT NULL CHECK (`calories` >= 0),
  `stock_level` int(11) DEFAULT NULL CHECK (`stock_level` >= 0),
  `img_url` varchar(255) NOT NULL,
  `img_alt_text` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL CHECK (char_length(`description`) > 0),
  PRIMARY KEY (`product_id`),
  CONSTRAINT `check_img_url_format` CHECK (`img_url` like '%.png' or `img_url` like '%.jpeg' or `img_url` like '%.avif'),
  CONSTRAINT `check_img_alt_text_length` CHECK (char_length(`img_alt_text`) between 5 and 150),
  CONSTRAINT `check_category_length` CHECK (char_length(`category`) > 0)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'Espresso',5,100,'espresso.png','Espresso Image','Espresso',2.99,'A strong and concentrated coffee drink.'),(2,'Cappuccino',120,75,'cappuccino.jpeg','Cappuccino Image','Cappuccino',4.99,'An Italian coffee drink made with espresso, hot milk, and steamed milk foam.'),(3,'Latte',150,60,'latte.png','Latte Image','Latte',3.99,'A coffee drink made with espresso and steamed milk.'),(4,'Americano',5,80,'americano.avif','Americano Image','Americano',3.49,'A coffee drink prepared by diluting espresso with hot water.'),(5,'Mocha',200,70,'mocha.jpeg','Mocha Image','Mocha',4.49,'A chocolate-flavored variant of a latte, often with whipped cream on top.');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `review` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `rating` int(11) NOT NULL,
  `date` date NOT NULL,
  `text` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `parent_review_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `review_1fk` (`user_id`),
  KEY `review_2fk` (`product_id`),
  KEY `review_3fk` (`parent_review_id`),
  CONSTRAINT `review_1fk` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  CONSTRAINT `review_2fk` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`),
  CONSTRAINT `review_3fk` FOREIGN KEY (`parent_review_id`) REFERENCES `review` (`review_id`),
  CONSTRAINT `check_rating` CHECK (`rating` between 1 and 5)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES (1,5,'2024-02-17','Excellent coffee, loved the taste and aroma!',1,1,NULL),(2,4,'2024-02-18','Great service, but the coffee was a bit too bitter for my liking.',2,1,NULL),(3,3,'2024-02-19','I agree with the previous review, the coffee was indeed excellent!',3,1,1);
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(320) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_no` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `unique_email` (`email`),
  CONSTRAINT `email_format` CHECK (`email` like '%@%.%'),
  CONSTRAINT `password_length` CHECK (char_length(`password`) > 8),
  CONSTRAINT `phone_number_length` CHECK (char_length(`phone_no`) > 6)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-02-17 19:23:27
