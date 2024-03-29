-- MariaDB dump 10.19  Distrib 10.5.15-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: gulasch.fritz.box    Database: wichteln
-- ------------------------------------------------------
-- Server version	10.5.17-MariaDB-log

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
-- Table structure for table `teilnehmer`
--

DROP TABLE IF EXISTS `teilnehmer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `teilnehmer` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `dname` varchar(50) DEFAULT NULL,
  `wishlist` text DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `interesse` text DEFAULT NULL,
  `favs` text DEFAULT NULL,
  `notlike` text DEFAULT NULL,
  `email` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teilnehmer`
--

LOCK TABLES `teilnehmer` WRITE;
/*!40000 ALTER TABLE `teilnehmer` DISABLE KEYS */;
/*!40000 ALTER TABLE `teilnehmer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zuweisungen`
--

DROP TABLE IF EXISTS `zuweisungen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `zuweisungen` (
  `teilnehmer` varchar(255) NOT NULL,
  `wichtel` varchar(255) DEFAULT NULL,
  `trackingid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`teilnehmer`),
  UNIQUE KEY `wichtel` (`wichtel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zuweisungen`
--

LOCK TABLES `zuweisungen` WRITE;
/*!40000 ALTER TABLE `zuweisungen` DISABLE KEYS */;
/*!40000 ALTER TABLE `zuweisungen` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-25 20:10:09
