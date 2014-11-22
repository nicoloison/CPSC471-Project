-- MySQL dump 10.15  Distrib 10.0.14-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: recipedb
-- ------------------------------------------------------
-- Server version	10.0.14-MariaDB-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cookbook`
--

DROP TABLE IF EXISTS `cookbook`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cookbook` (
  `name` varchar(255) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `description` text,
  `rating` float(2,1) DEFAULT NULL,
  PRIMARY KEY (`name`,`author_name`),
  KEY `author_name` (`author_name`),
  CONSTRAINT `cookbook_ibfk_1` FOREIGN KEY (`author_name`) REFERENCES `users` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cookbook`
--

LOCK TABLES `cookbook` WRITE;
/*!40000 ALTER TABLE `cookbook` DISABLE KEYS */;
INSERT INTO `cookbook` VALUES ('Liam\'s Cookbook','lmitchell','Liam\'s favourite recipes!',NULL),('Nicole\'s Cookbook','nloison','Nicole\'s favourite recipes!',NULL);
/*!40000 ALTER TABLE `cookbook` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cookbook_recipe`
--

DROP TABLE IF EXISTS `cookbook_recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cookbook_recipe` (
  `cookbook_name` varchar(255) NOT NULL,
  `cookbook_author` varchar(255) NOT NULL,
  `recipe_name` varchar(255) NOT NULL,
  `recipe_author` varchar(255) NOT NULL,
  PRIMARY KEY (`cookbook_name`,`cookbook_author`,`recipe_name`,`recipe_author`),
  KEY `recipe_name` (`recipe_name`,`recipe_author`),
  CONSTRAINT `cookbook_recipe_ibfk_1` FOREIGN KEY (`cookbook_name`, `cookbook_author`) REFERENCES `cookbook` (`name`, `author_name`),
  CONSTRAINT `cookbook_recipe_ibfk_2` FOREIGN KEY (`recipe_name`, `recipe_author`) REFERENCES `recipe` (`name`, `author_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cookbook_recipe`
--

LOCK TABLES `cookbook_recipe` WRITE;
/*!40000 ALTER TABLE `cookbook_recipe` DISABLE KEYS */;
INSERT INTO `cookbook_recipe` VALUES ('Liam\'s Cookbook','lmitchell','mac and cheese','nloison'),('Liam\'s Cookbook','lmitchell','shepherd\'s pie','lmitchell'),('Nicole\'s Cookbook','nloison','shepherd\'s pie','lmitchell');
/*!40000 ALTER TABLE `cookbook_recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dietary_restrictions`
--

DROP TABLE IF EXISTS `dietary_restrictions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dietary_restrictions` (
  `name` varchar(255) NOT NULL,
  `ingredient` varchar(255) NOT NULL,
  PRIMARY KEY (`name`),
  KEY `ingredient` (`ingredient`),
  CONSTRAINT `dietary_restrictions_ibfk_1` FOREIGN KEY (`ingredient`) REFERENCES `ingredient` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dietary_restrictions`
--

LOCK TABLES `dietary_restrictions` WRITE;
/*!40000 ALTER TABLE `dietary_restrictions` DISABLE KEYS */;
/*!40000 ALTER TABLE `dietary_restrictions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ingredient` (
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ingredient`
--

LOCK TABLES `ingredient` WRITE;
/*!40000 ALTER TABLE `ingredient` DISABLE KEYS */;
INSERT INTO `ingredient` VALUES ('cheese'),('corn'),('ground beef'),('macaroni'),('peas'),('potatoes'),('steak');
/*!40000 ALTER TABLE `ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipe` (
  `name` varchar(255) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `instructions` text NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `prep_time` float(4,1) DEFAULT NULL,
  `portions` int(2) DEFAULT NULL,
  `rating` float(2,1) DEFAULT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`name`,`author_name`),
  KEY `author_name` (`author_name`),
  CONSTRAINT `recipe_ibfk_1` FOREIGN KEY (`author_name`) REFERENCES `users` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe`
--

LOCK TABLES `recipe` WRITE;
/*!40000 ALTER TABLE `recipe` DISABLE KEYS */;
INSERT INTO `recipe` VALUES ('mac and cheese','nloison','melt cheese and cook noodles','pics/mac.png',45.0,4,NULL,'melted cheese and cooked macaronis'),('shepherd\'s pie','lmitchell','cook it for like an hour or whatever','pics/pie.png',55.0,8,NULL,'potatoes and meat and some corn and stuff!'),('steak dinner','adjuric','grill em!','pics/steak.png',25.0,4,NULL,'grilled meat!');
/*!40000 ALTER TABLE `recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe_ingredient`
--

DROP TABLE IF EXISTS `recipe_ingredient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipe_ingredient` (
  `recipe_name` varchar(255) NOT NULL,
  `ingredient_name` varchar(255) NOT NULL,
  `amount` float(6,2) NOT NULL,
  `amount_units` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`recipe_name`,`ingredient_name`),
  KEY `ingredient_name` (`ingredient_name`),
  CONSTRAINT `recipe_ingredient_ibfk_1` FOREIGN KEY (`recipe_name`) REFERENCES `recipe` (`name`),
  CONSTRAINT `recipe_ingredient_ibfk_2` FOREIGN KEY (`ingredient_name`) REFERENCES `ingredient` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe_ingredient`
--

LOCK TABLES `recipe_ingredient` WRITE;
/*!40000 ALTER TABLE `recipe_ingredient` DISABLE KEYS */;
INSERT INTO `recipe_ingredient` VALUES ('mac and cheese','cheese',3.00,'cups'),('mac and cheese','macaroni',8.00,'cups'),('shepherd\'s pie','cheese',1.00,'cups'),('shepherd\'s pie','corn',1.00,'cups'),('shepherd\'s pie','peas',1.00,'cups'),('shepherd\'s pie','potatoes',8.00,NULL),('steak dinner','steak',4.00,NULL);
/*!40000 ALTER TABLE `recipe_ingredient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_cookbook_ratings`
--

DROP TABLE IF EXISTS `user_cookbook_ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_cookbook_ratings` (
  `user_name` varchar(255) NOT NULL,
  `cookbook_name` varchar(255) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `rating` int(1) NOT NULL,
  PRIMARY KEY (`user_name`,`cookbook_name`,`author_name`),
  KEY `cookbook_name` (`cookbook_name`,`author_name`),
  CONSTRAINT `user_cookbook_ratings_ibfk_1` FOREIGN KEY (`cookbook_name`, `author_name`) REFERENCES `cookbook` (`name`, `author_name`),
  CONSTRAINT `user_cookbook_ratings_ibfk_2` FOREIGN KEY (`user_name`) REFERENCES `users` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_cookbook_ratings`
--

LOCK TABLES `user_cookbook_ratings` WRITE;
/*!40000 ALTER TABLE `user_cookbook_ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_cookbook_ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_recipe_ratings`
--

DROP TABLE IF EXISTS `user_recipe_ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_recipe_ratings` (
  `user_name` varchar(255) NOT NULL,
  `recipe_name` varchar(255) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `rating` int(1) NOT NULL,
  PRIMARY KEY (`user_name`,`recipe_name`,`author_name`),
  KEY `recipe_name` (`recipe_name`,`author_name`),
  CONSTRAINT `user_recipe_ratings_ibfk_1` FOREIGN KEY (`recipe_name`, `author_name`) REFERENCES `recipe` (`name`, `author_name`),
  CONSTRAINT `user_recipe_ratings_ibfk_2` FOREIGN KEY (`user_name`) REFERENCES `users` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_recipe_ratings`
--

LOCK TABLES `user_recipe_ratings` WRITE;
/*!40000 ALTER TABLE `user_recipe_ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_recipe_ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('adjuric','99999999'),('lmitchell','12345678'),('nloison','87654321');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-11-21 19:48:05
