-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: froshlav_11
-- ------------------------------------------------------
-- Server version	8.3.0

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
-- Table structure for table `categoria_servicios`
--

DROP TABLE IF EXISTS `categoria_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria_servicios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_unique` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria_servicios`
--

LOCK TABLES `categoria_servicios` WRITE;
/*!40000 ALTER TABLE `categoria_servicios` DISABLE KEYS */;
INSERT INTO `categoria_servicios` VALUES (1,'Lavado Básico','Lavado exterior básico','activo','2026-01-17 15:34:26'),(2,'Lavado Completo','Lavado exterior e interior','activo','2026-01-17 15:34:26'),(3,'Encerado','Aplicación de cera protectora','activo','2026-01-17 15:34:26'),(4,'Aspirado','Limpieza interior con aspiradora','activo','2026-01-17 15:34:26'),(5,'Detallado','Limpieza profunda y detallada','activo','2026-01-17 15:34:26');
/*!40000 ALTER TABLE `categoria_servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoriaservicios`
--

DROP TABLE IF EXISTS `categoriaservicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoriaservicios` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Detalle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Estado` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriaservicios`
--

LOCK TABLES `categoriaservicios` WRITE;
/*!40000 ALTER TABLE `categoriaservicios` DISABLE KEYS */;
INSERT INTO `categoriaservicios` VALUES (1,'Lavado Básico','Lavado exterior básico',1),(2,'Lavado Completo','Lavado exterior e interior',1),(3,'Encerado','Aplicación de cera protectora',1),(4,'Aspirado','Limpieza interior con aspiradora',1),(5,'Detallado','Limpieza profunda y detallada',1),(6,'Lavado Básico','Lavado exterior básico',1),(7,'Lavado Completo','Lavado exterior e interior',1),(8,'Encerado','Aplicación de cera protectora',1),(9,'Aspirado','Limpieza interior con aspiradora',1),(10,'Detallado','Limpieza profunda y detallada',1);
/*!40000 ALTER TABLE `categoriaservicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoriavehiculo`
--

DROP TABLE IF EXISTS `categoriavehiculo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoriavehiculo` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `TipoVehiculo` varchar(45) DEFAULT NULL,
  `Imagen` varchar(80) NOT NULL,
  `Estado` tinyint unsigned NOT NULL DEFAULT '1',
  `OrdenClasificacion` tinyint unsigned DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriavehiculo`
--

LOCK TABLES `categoriavehiculo` WRITE;
/*!40000 ALTER TABLE `categoriavehiculo` DISABLE KEYS */;
INSERT INTO `categoriavehiculo` VALUES (14,'Sedán','',1,0),(15,'SUV','',1,0),(16,'Pickup','',1,0),(18,'Motocicleta','',1,0),(19,'Camión','',1,0);
/*!40000 ALTER TABLE `categoriavehiculo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `NombreCompleto` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Cedula` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Empresa` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Correo` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Telefono` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Direccion` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Distrito` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Canton` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Provincia` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Pais` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'CR',
  `IVA` int DEFAULT '13',
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint DEFAULT '1',
  `FechaRegistro` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Cedula_UNIQUE` (`Cedula`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (7,'Katherine','503640054','Mail Technologies Inc.','kmonestel@gmail.com','6666666666','2107 NW 79 AVE DUAL D9154','Doral','Doral','Florida','CR',13,'2026-01-20 12:27:30',1,NULL),(8,'Frederick Saavedra',NULL,'Interpal','myinterpal@gmail.com','6666666666','2km norte de plaza de depoortes Cabanga','Guatuso','Guatuso','Alajuela','CR',13,'2026-01-20 12:29:11',1,NULL),(9,'JAVIER V','55555555555','INTETEC','amidestino@gmail.com','6666666666','2107 NW 79 AVE DUAL D9154','Doral','Doral','Florida','CR',13,'2026-01-21 13:11:31',1,NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion_empresa`
--

DROP TABLE IF EXISTS `configuracion_empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion_empresa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eslogan` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci,
  `hora_apertura` time DEFAULT '08:00:00',
  `hora_cierre` time DEFAULT '18:00:00',
  `dias_laborales` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',
  `capacidad_maxima` int DEFAULT '50',
  `tiempo_promedio` int DEFAULT '30',
  `moneda` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'CRC',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion_empresa`
--

LOCK TABLES `configuracion_empresa` WRITE;
/*!40000 ALTER TABLE `configuracion_empresa` DISABLE KEYS */;
INSERT INTO `configuracion_empresa` VALUES (1,'Marketing','Best Ever','555555555','kmonestel@gmail.com','Guatuso, Costa Rica','08:00:00','18:00:00','Lunes,Martes,Miércoles,Jueves,Viernes,Sábado',50,30,'CRC','2026-01-17 15:54:23','2026-01-17 15:54:23');
/*!40000 ALTER TABLE `configuracion_empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion_sistema`
--

DROP TABLE IF EXISTS `configuracion_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion_sistema` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` text COLLATE utf8mb4_unicode_ci,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave_unique` (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion_sistema`
--

LOCK TABLES `configuracion_sistema` WRITE;
/*!40000 ALTER TABLE `configuracion_sistema` DISABLE KEYS */;
INSERT INTO `configuracion_sistema` VALUES (1,'usuarios_configurados','1',NULL,'2026-01-17 17:06:54','2026-01-17 17:06:54'),(2,'wizard_completed','2026-01-17 11:06:54',NULL,'2026-01-17 17:06:54','2026-01-17 17:06:54');
/*!40000 ALTER TABLE `configuracion_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id_empresa` int NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('activo','inactivo','pendiente') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_empresa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (11,'Marketing','activo','2026-01-17 15:28:31');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orden_servicios`
--

DROP TABLE IF EXISTS `orden_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_servicios` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `OrdenID` int NOT NULL,
  `ServicioID` int DEFAULT NULL,
  `Descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `ServicioPersonalizado` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Cantidad` int DEFAULT '1',
  `Subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `OrdenID` (`OrdenID`)
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orden_servicios`
--

LOCK TABLES `orden_servicios` WRITE;
/*!40000 ALTER TABLE `orden_servicios` DISABLE KEYS */;
INSERT INTO `orden_servicios` VALUES (30,19,1,NULL,4000.00,NULL,1,0.00),(31,19,2,NULL,4000.00,NULL,1,0.00),(32,19,3,NULL,3000.00,NULL,1,0.00),(33,20,1,NULL,4000.00,NULL,1,0.00),(34,20,2,NULL,4000.00,NULL,1,0.00),(35,20,40,NULL,2000.00,NULL,1,0.00),(36,21,1,NULL,4000.00,NULL,1,0.00),(37,21,2,NULL,4000.00,NULL,1,0.00),(38,21,3,NULL,3000.00,NULL,1,0.00),(39,22,1,NULL,4000.00,NULL,1,0.00),(40,22,2,NULL,4000.00,NULL,1,0.00),(114,27,NULL,NULL,6000.00,'Lavado Exteriores',1,6000.00),(115,27,NULL,NULL,2000.00,'Lavado Chasis',1,2000.00),(118,27,NULL,NULL,10000.00,'NUEVO SERVICIO',1,10000.00),(119,25,NULL,NULL,10000.00,'Lavado exterior NEUVO',1,10000.00),(120,25,NULL,NULL,30000.00,'Lavado Chasis',1,30000.00),(121,28,1,NULL,5000.00,'Lavado Exteriores',1,5000.00),(122,28,2,NULL,4000.00,'Limpieza Interior',1,4000.00),(123,28,3,NULL,3000.00,'Lavado Chasis',1,3000.00),(124,28,40,NULL,2000.00,'Encerado',1,2000.00),(125,28,41,NULL,15000.00,'Pulido de Vidrios',1,15000.00),(126,28,NULL,NULL,30000.00,'njnjnin',1,30000.00);
/*!40000 ALTER TABLE `orden_servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordenes`
--

DROP TABLE IF EXISTS `ordenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordenes` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `ClienteID` int DEFAULT NULL,
  `Monto` decimal(10,2) DEFAULT '0.00',
  `Subtotal` decimal(10,2) DEFAULT '0.00',
  `Descuento` decimal(10,2) DEFAULT '0.00',
  `Impuesto` decimal(10,2) DEFAULT '0.13',
  `Estado` tinyint DEFAULT '1',
  `FechaIngreso` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `FechaProceso` timestamp NULL DEFAULT NULL,
  `FechaTerminado` timestamp NULL DEFAULT NULL,
  `FechaCierre` timestamp NULL DEFAULT NULL,
  `FacturaNo` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Categoria` int DEFAULT '0',
  `TipoServicio` int DEFAULT '1',
  `VehiculoID` int DEFAULT '0',
  `ServiciosJSON` json DEFAULT NULL,
  `UpdatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`),
  KEY `main` (`ID`,`ClienteID`,`Estado`,`Categoria`,`VehiculoID`,`TipoServicio`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordenes`
--

LOCK TABLES `ordenes` WRITE;
/*!40000 ALTER TABLE `ordenes` DISABLE KEYS */;
INSERT INTO `ordenes` VALUES (19,7,12430.00,0.00,0.00,1430.00,4,'2026-01-20 18:27:55','2026-01-20 18:29:45','2026-01-20 19:17:38','2026-01-21 19:21:40',NULL,15,1,3,'[{\"id\": 1, \"nombre\": \"\", \"precio\": 4000}, {\"id\": 2, \"nombre\": \"\", \"precio\": 4000}, {\"id\": 3, \"nombre\": \"\", \"precio\": 3000}]','2026-01-21 19:21:40',''),(20,8,11300.00,0.00,0.00,1300.00,4,'2026-01-20 18:29:21','2026-01-20 18:57:11','2026-01-21 19:19:38','2026-01-21 19:20:03',NULL,15,1,4,'[{\"id\": 1, \"nombre\": \"\", \"precio\": 4000}, {\"id\": 2, \"nombre\": \"\", \"precio\": 4000}, {\"id\": 40, \"nombre\": \"\", \"precio\": 2000}]','2026-01-21 19:20:03',''),(21,9,12430.00,0.00,0.00,1430.00,4,'2026-01-21 19:14:39','2026-01-21 19:24:24','2026-01-21 19:25:53','2026-01-21 19:27:44',NULL,15,1,3,'[{\"id\": 1, \"nombre\": \"\", \"precio\": 4000}, {\"id\": 2, \"nombre\": \"\", \"precio\": 4000}, {\"id\": 3, \"nombre\": \"\", \"precio\": 3000}]','2026-01-21 19:27:44',''),(22,9,9040.00,0.00,0.00,1040.00,4,'2026-01-21 19:15:28','2026-01-21 19:17:08','2026-01-21 19:22:12','2026-01-21 19:23:13',NULL,15,1,3,'[{\"id\": 1, \"nombre\": \"\", \"precio\": 4000}, {\"id\": 2, \"nombre\": \"\", \"precio\": 4000}]','2026-01-21 19:23:13',''),(23,9,12430.00,0.00,0.00,1430.00,3,'2026-01-21 19:29:54','2026-01-21 19:30:15','2026-01-21 19:33:26',NULL,NULL,15,1,3,'[{\"id\": 1, \"nombre\": \"\", \"precio\": 4000}, {\"id\": 2, \"nombre\": \"\", \"precio\": 4000}]','2026-01-24 16:45:42',''),(25,9,22600.00,0.00,20000.00,1170.00,4,'2026-01-21 19:44:30','2026-01-21 19:45:18','2026-01-24 18:54:48','2026-01-24 18:55:52',NULL,15,1,3,'[{\"id\": 119, \"nombre\": \"Lavado exterior NEUVO\", \"precio\": 10000, \"personalizado\": true}, {\"id\": 120, \"nombre\": \"Lavado Chasis\", \"precio\": 30000, \"personalizado\": true}]','2026-01-24 18:55:52',''),(26,9,76840.00,0.00,0.00,8840.00,2,'2026-01-21 20:07:58','2026-01-21 20:14:43',NULL,NULL,NULL,15,1,3,'[{\"id\": 1, \"nombre\": \"\", \"precio\": 4000}, {\"id\": 2, \"nombre\": \"\", \"precio\": 4000}]','2026-01-24 16:45:42',''),(27,8,16950.00,0.00,3000.00,4940.00,4,'2026-01-24 16:49:03','2026-01-24 18:25:37','2026-01-24 18:25:50','2026-01-24 18:40:48',NULL,15,1,4,'[{\"id\": 114, \"nombre\": \"Lavado Exteriores\", \"precio\": 6000, \"personalizado\": true}, {\"id\": 115, \"nombre\": \"Lavado Chasis\", \"precio\": 2000, \"personalizado\": true}, {\"id\": 118, \"nombre\": \"NUEVO SERVICIO\", \"precio\": 10000, \"personalizado\": true}]','2026-01-24 18:40:48','TEST2025'),(28,8,32770.00,0.00,30000.00,4940.00,4,'2026-01-24 18:58:36','2026-01-24 19:01:35','2026-01-24 19:02:19','2026-01-24 19:03:10',NULL,15,1,4,'[{\"id\": 121, \"nombre\": \"Lavado Exteriores\", \"precio\": 5000, \"personalizado\": true}, {\"id\": 122, \"nombre\": \"Limpieza Interior\", \"precio\": 4000, \"personalizado\": true}, {\"id\": 123, \"nombre\": \"Lavado Chasis\", \"precio\": 3000, \"personalizado\": true}, {\"id\": 124, \"nombre\": \"Encerado\", \"precio\": 2000, \"personalizado\": true}, {\"id\": 125, \"nombre\": \"Pulido de Vidrios\", \"precio\": 15000, \"personalizado\": true}, {\"id\": 126, \"nombre\": \"njnjnin\", \"precio\": 30000, \"personalizado\": true}]','2026-01-24 19:03:10','TEST');
/*!40000 ALTER TABLE `ordenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `precios`
--

DROP TABLE IF EXISTS `precios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `precios` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `TipoCategoriaID` int unsigned NOT NULL,
  `ServicioID` int unsigned NOT NULL,
  `Descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `Precio` double NOT NULL DEFAULT '0',
  `Descuento` decimal(10,0) NOT NULL DEFAULT '0',
  `Impuesto` decimal(10,0) NOT NULL DEFAULT '13',
  `PackageID` tinyint NOT NULL DEFAULT '0',
  `DateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uk_servicio_categoria` (`ServicioID`,`TipoCategoriaID`),
  KEY `idx_servicio` (`ServicioID`),
  KEY `idx_categoria` (`TipoCategoriaID`)
) ENGINE=InnoDB AUTO_INCREMENT=316 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `precios`
--

LOCK TABLES `precios` WRITE;
/*!40000 ALTER TABLE `precios` DISABLE KEYS */;
INSERT INTO `precios` VALUES (261,14,1,NULL,4000,0,13,0,'2026-01-17 16:44:43'),(262,15,1,NULL,4000,0,13,0,'2026-01-17 16:44:43'),(263,16,1,NULL,4000,0,13,0,'2026-01-17 16:44:43'),(264,17,1,NULL,4000,0,13,0,'2026-01-17 16:44:43'),(265,18,1,NULL,4000,0,13,0,'2026-01-17 16:44:43'),(266,19,1,NULL,5000,0,13,0,'2026-01-17 16:44:43'),(267,14,2,NULL,4000,0,13,0,'2026-01-17 16:44:43'),(268,15,2,NULL,4000,0,13,0,'2026-01-17 16:44:43'),(269,16,2,NULL,4500,0,13,0,'2026-01-17 16:44:43'),(270,17,2,NULL,4500,0,13,0,'2026-01-17 16:44:43'),(271,18,2,NULL,4500,0,13,0,'2026-01-17 16:44:43'),(272,19,2,NULL,4500,0,13,0,'2026-01-17 16:44:43'),(273,14,3,NULL,3000,0,13,0,'2026-01-17 16:44:43'),(274,15,3,NULL,3000,0,13,0,'2026-01-17 16:44:43'),(275,16,3,NULL,3000,0,13,0,'2026-01-17 16:44:43'),(276,17,3,NULL,3000,0,13,0,'2026-01-17 16:44:43'),(277,18,3,NULL,3000,0,13,0,'2026-01-17 16:44:43'),(278,19,3,NULL,3000,0,13,0,'2026-01-17 16:44:43'),(279,14,40,NULL,2000,0,13,0,'2026-01-17 16:44:43'),(280,15,40,NULL,2000,0,13,0,'2026-01-17 16:44:43'),(281,16,40,NULL,2000,0,13,0,'2026-01-17 16:44:43'),(282,17,40,NULL,2000,0,13,0,'2026-01-17 16:44:43'),(283,18,40,NULL,2000,0,13,0,'2026-01-17 16:44:43'),(284,19,40,NULL,2000,0,13,0,'2026-01-17 16:44:43'),(285,14,41,NULL,25000,0,13,0,'2026-01-17 16:44:43'),(286,15,41,NULL,25000,0,13,0,'2026-01-17 16:44:43'),(287,16,41,NULL,25000,0,13,0,'2026-01-17 16:44:43'),(288,17,41,NULL,25000,0,13,0,'2026-01-17 16:44:43'),(289,18,41,NULL,25000,0,13,0,'2026-01-17 16:44:43'),(290,19,41,NULL,25000,0,13,0,'2026-01-17 16:44:43');
/*!40000 ALTER TABLE `precios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicios`
--

DROP TABLE IF EXISTS `servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicios` (
  `ID` int unsigned NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `Detalles` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `CategoriaServicioID` tinyint DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios`
--

LOCK TABLES `servicios` WRITE;
/*!40000 ALTER TABLE `servicios` DISABLE KEYS */;
INSERT INTO `servicios` VALUES (1,'Lavado Exterior','Lavado de la carrocería externa',1),(2,'Limpieza Interior','Limpieza completa del interior del vehículo',1),(3,'Lavado Chasis','Limpieza del chasis y bajos del vehículo',1),(40,'Encerado','Aplicación de cera protectora',1),(41,'Pulido de Vidrios','Pulido y limpieza especializada de vidrios',1);
/*!40000 ALTER TABLE `servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vehiculos` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `ClienteID` int NOT NULL,
  `Marca` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Modelo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Year` int DEFAULT NULL,
  `Placa` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `CategoriaVehiculo` int DEFAULT NULL,
  `Color` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `FechaRegistro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `placa_unique` (`Placa`),
  KEY `idx_cliente` (`ClienteID`),
  KEY `idx_categoria` (`CategoriaVehiculo`),
  KEY `idx_placa` (`Placa`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehiculos`
--

LOCK TABLES `vehiculos` WRITE;
/*!40000 ALTER TABLE `vehiculos` DISABLE KEYS */;
INSERT INTO `vehiculos` VALUES (3,9,'Chevrolet','Suburban',2025,'BSM791',15,'Negro',NULL,'2026-01-20 18:27:55',1),(4,8,'Cadillac','SV',2026,'KFJ182',15,'Negro',NULL,'2026-01-20 18:29:21',1);
/*!40000 ALTER TABLE `vehiculos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-24 13:06:55
