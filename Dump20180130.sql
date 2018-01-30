-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: sp_web
-- ------------------------------------------------------
-- Server version	5.7.19-log

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
-- Table structure for table `prispevky`
--

DROP TABLE IF EXISTS `prispevky`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prispevky` (
  `uzivatelske_jmeno` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nazev` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `autori` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `cesta` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `abstract` longtext COLLATE utf8mb4_bin NOT NULL,
  `schvalen` tinyint(1) NOT NULL DEFAULT '0',
  `recenzovan` tinyint(1) DEFAULT NULL,
  `celkove_hodnoceni` varchar(30) COLLATE utf8mb4_bin DEFAULT NULL,
  PRIMARY KEY (`nazev`),
  UNIQUE KEY `ID_UNIQUE` (`ID`),
  KEY `uzivatelske_jmeno_idx` (`uzivatelske_jmeno`),
  CONSTRAINT `fk_uzivatelske_jmeno` FOREIGN KEY (`uzivatelske_jmeno`) REFERENCES `uzivatele` (`uzivatelske_jmeno`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prispevky`
--

LOCK TABLES `prispevky` WRITE;
/*!40000 ALTER TABLE `prispevky` DISABLE KEYS */;
INSERT INTO `prispevky` VALUES ('uživatel',9,'Dokumentace SP, WEB','Přemysl Kouba','upload/uživatel/Dokumentace_WEB.pdf','Dokumentace k semestrální práci pro předmět Webové aplikace na FAV/KIV.',1,1,'3.133'),('uživatel',11,'Dokumentace SP, WEB, aktualizováno','Přemysl Kouba','upload/uživatel/Dokumentace_WEB_akt.pdf','Novější verze s doplněnými funkcemi.',0,0,'hodnotí se');
/*!40000 ALTER TABLE `prispevky` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recenze`
--

DROP TABLE IF EXISTS `recenze`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recenze` (
  `nazev` varchar(120) COLLATE utf8mb4_bin NOT NULL,
  `recenzent` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `hotovo` tinyint(4) DEFAULT NULL,
  `poznamka` longtext COLLATE utf8mb4_bin,
  `horiginalita` smallint(6) DEFAULT NULL,
  `htema` smallint(6) DEFAULT NULL,
  `htechkvalita` smallint(6) DEFAULT NULL,
  `hjazykkvalita` smallint(6) DEFAULT NULL,
  `hdoporuceni` smallint(6) DEFAULT NULL,
  `celkove_hodnoceni` double DEFAULT NULL,
  `ID` int(11) NOT NULL,
  PRIMARY KEY (`nazev`,`recenzent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recenze`
--

LOCK TABLES `recenze` WRITE;
/*!40000 ALTER TABLE `recenze` DISABLE KEYS */;
INSERT INTO `recenze` VALUES ('Dokumentace SP, WEB','recenzent1',1,'Super. Jsem hodný recenzent',1,1,1,2,2,1.4,9),('Dokumentace SP, WEB','recenzent2',1,'Už jsem viděl i horší. Jsem střední recenzent.',2,3,3,4,4,3.2,9),('Dokumentace SP, WEB','recenzent3',1,'Aspoň, že k tomu něco napsal. Ale je to odpad. Jsem zlý recenzent.',5,5,5,4,5,4.8,9),('Dokumentace SP, WEB, aktualizováno','Nováček',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11),('Dokumentace SP, WEB, aktualizováno','recenzent1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11),('Dokumentace SP, WEB, aktualizováno','recenzent3',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11);
/*!40000 ALTER TABLE `recenze` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uzivatele`
--

DROP TABLE IF EXISTS `uzivatele`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uzivatele` (
  `uzivatelske_jmeno` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `jmeno` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `prijmeni` varchar(45) COLLATE utf8mb4_bin NOT NULL,
  `typ` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `heslo` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`uzivatelske_jmeno`),
  UNIQUE KEY `uzivatelske_jmeno_UNIQUE` (`uzivatelske_jmeno`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uzivatele`
--

LOCK TABLES `uzivatele` WRITE;
/*!40000 ALTER TABLE `uzivatele` DISABLE KEYS */;
INSERT INTO `uzivatele` VALUES ('Nováček','Nový','Nováček','recenzent','a'),('aadmin','aadmin','aadmin','admin','a'),('admin','Admin','Admin','admin','admin'),('recenzent1','Hodný','Recenzent','recenzent','a'),('recenzent2','Střední','Recenzent','recenzent','a'),('recenzent3','Zlý','Recenzent','recenzent','a'),('uživatel','Přemysl','Kouba','uzivatel','a');
/*!40000 ALTER TABLE `uzivatele` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-01-30  9:17:02
