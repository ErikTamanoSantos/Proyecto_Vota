-- MySQL dump 10.13  Distrib 8.0.35, for Linux (x86_64)
--
-- Host: localhost    Database: project_vota
-- ------------------------------------------------------
-- Server version	8.0.35-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Answers`
--

DROP TABLE IF EXISTS `Answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Answers` (
  `ID` int NOT NULL,
  `Text` varchar(255) DEFAULT NULL,
  `PollID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Answers_Poll` (`PollID`),
  CONSTRAINT `Answers_Poll` FOREIGN KEY (`PollID`) REFERENCES `Polls` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Answers`
--

LOCK TABLES `Answers` WRITE;
/*!40000 ALTER TABLE `Answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `Answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Poll_Admins`
--

DROP TABLE IF EXISTS `Poll_Admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Poll_Admins` (
  `UserID` int DEFAULT NULL,
  `PollID` int DEFAULT NULL,
  UNIQUE KEY `Poll_Admins_unique` (`UserID`,`PollID`),
  KEY `Poll_Admins_Poll` (`PollID`),
  CONSTRAINT `Poll_Admins_Admin` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`),
  CONSTRAINT `Poll_Admins_Poll` FOREIGN KEY (`PollID`) REFERENCES `Polls` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Poll_Admins`
--

LOCK TABLES `Poll_Admins` WRITE;
/*!40000 ALTER TABLE `Poll_Admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `Poll_Admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Poll_InvitedUsers`
--

DROP TABLE IF EXISTS `Poll_InvitedUsers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Poll_InvitedUsers` (
  `UserID` int DEFAULT NULL,
  `PollID` int DEFAULT NULL,
  UNIQUE KEY `Poll_InvitedUsers_unique` (`UserID`,`PollID`),
  KEY `Poll_InvitedUsers_Poll` (`PollID`),
  CONSTRAINT `Poll_InvitedUsers_Poll` FOREIGN KEY (`PollID`) REFERENCES `Polls` (`ID`),
  CONSTRAINT `Poll_InvitedUsers_User` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Poll_InvitedUsers`
--

LOCK TABLES `Poll_InvitedUsers` WRITE;
/*!40000 ALTER TABLE `Poll_InvitedUsers` DISABLE KEYS */;
/*!40000 ALTER TABLE `Poll_InvitedUsers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Polls`
--

DROP TABLE IF EXISTS `Polls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Polls` (
  `ID` int NOT NULL,
  `CreationDate` datetime DEFAULT NULL,
  `StartDate` datetime DEFAULT NULL,
  `EndDate` datetime DEFAULT NULL,
  `State` enum('active','blocked','not_begun','finished') DEFAULT NULL,
  `QuestionVisibility` enum('public','private','hidden') DEFAULT NULL,
  `ResultsVisibility` enum('public','private','hidden') DEFAULT NULL,
  `ImagePath` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL,
  `CreatorID` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `FK_PollCreator` (`CreatorID`),
  CONSTRAINT `FK_PollCreator` FOREIGN KEY (`CreatorID`) REFERENCES `Users` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Polls`
--

LOCK TABLES `Polls` WRITE;
/*!40000 ALTER TABLE `Polls` DISABLE KEYS */;
/*!40000 ALTER TABLE `Polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User_Vote`
--

DROP TABLE IF EXISTS `User_Vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `User_Vote` (
  `UserID` int DEFAULT NULL,
  `AnswerID` int DEFAULT NULL,
  UNIQUE KEY `User_Vote_unique` (`UserID`,`AnswerID`),
  KEY `User_Vote_Answer` (`AnswerID`),
  CONSTRAINT `User_Vote_Answer` FOREIGN KEY (`AnswerID`) REFERENCES `Answers` (`ID`),
  CONSTRAINT `User_Vote_User` FOREIGN KEY (`UserID`) REFERENCES `Users` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User_Vote`
--

LOCK TABLES `User_Vote` WRITE;
/*!40000 ALTER TABLE `User_Vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `User_Vote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Users` (
  `ID` int NOT NULL,
  `Username` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Phone` varchar(255) DEFAULT NULL,
  `Country` varchar(255) DEFAULT NULL,
  `City` varchar(255) DEFAULT NULL,
  `PostalCode` int DEFAULT NULL,
  `IsAuthenticated` tinyint(1) DEFAULT NULL,
  `ValidationToken` int DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-01-18 19:26:37
