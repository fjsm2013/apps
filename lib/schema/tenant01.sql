-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: froshlav_8
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
-- Table structure for table `categoriaservicio`
--

DROP TABLE IF EXISTS `categoriaservicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoriaservicio` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Detalle` text COLLATE utf8mb4_unicode_ci,
  `Estado` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriaservicio`
--

LOCK TABLES `categoriaservicio` WRITE;
/*!40000 ALTER TABLE `categoriaservicio` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoriaservicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoriaservicios`
--

DROP TABLE IF EXISTS `categoriaservicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoriaservicios` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Detalle` text COLLATE utf8mb4_unicode_ci,
  `Estado` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriaservicios`
--

LOCK TABLES `categoriaservicios` WRITE;
/*!40000 ALTER TABLE `categoriaservicios` DISABLE KEYS */;
INSERT INTO `categoriaservicios` VALUES (1,'Lavado Básico','Lavado exterior básico',1),(2,'Lavado Completo','Lavado exterior e interior',1),(3,'Encerado','Aplicación de cera protectora',1),(4,'Aspirado','Limpieza interior con aspiradora',1),(5,'Detallado','Limpieza profunda y detallada',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoriavehiculo`
--

LOCK TABLES `categoriavehiculo` WRITE;
/*!40000 ALTER TABLE `categoriavehiculo` DISABLE KEYS */;
INSERT INTO `categoriavehiculo` VALUES (1,'Sedan/Hatch Back','',1,0),(2,'SUV compacto','fas fa-car-side',1,2),(3,'Pick Up','fas fa-truck-monster',1,4),(7,'SUV Grande','fas fa-car-side',1,3),(8,'MiniBus','fas fa-shuttle-van',1,5),(9,'Buses','',1,6),(10,'Moto','',1,7);
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
  `NombreCompleto` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Cedula` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Empresa` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Correo` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Telefono` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Direccion` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Distrito` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Canton` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Provincia` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Pais` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT 'CR',
  `IVA` int DEFAULT '13',
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint DEFAULT '1',
  `FechaRegistro` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Cedula_UNIQUE` (`Cedula`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (5,'Katherine','503640054','Katherine Monestel SEO','kmonestel@gmail.com','83239015','2107 NW 79 AVE DUAL D9154','Guatuso','Guatuso','Alajuela','CR',13,'2026-01-10 11:27:45',1,NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id_empresa` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('activo','inactivo','pendiente') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_empresa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (8,'Interpal','activo','2026-01-09 19:50:38');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
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
  `Descuento` decimal(10,2) DEFAULT '0.00',
  `Impuesto` decimal(10,2) DEFAULT '0.13',
  `Estado` tinyint DEFAULT '1',
  `FechaIngreso` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `FechaProceso` timestamp NULL DEFAULT NULL,
  `FechaTerminado` timestamp NULL DEFAULT NULL,
  `FechaCierre` timestamp NULL DEFAULT NULL,
  `FacturaNo` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Categoria` int DEFAULT '0',
  `TipoServicio` int DEFAULT '1',
  `VehiculoID` int DEFAULT '0',
  `ServiciosJSON` json DEFAULT NULL,
  `UpdatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Observaciones` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`ID`),
  KEY `main` (`ID`,`ClienteID`,`Estado`,`Categoria`,`VehiculoID`,`TipoServicio`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordenes`
--

LOCK TABLES `ordenes` WRITE;
/*!40000 ALTER TABLE `ordenes` DISABLE KEYS */;
INSERT INTO `ordenes` VALUES (10,5,9040.00,0.00,1040.00,1,'2026-01-10 17:31:49',NULL,NULL,NULL,NULL,1,1,1,'[{\"id\": 11, \"nombre\": \"\", \"precio\": 4000}, {\"id\": 12, \"nombre\": \"\", \"precio\": 4000}]','2026-01-10 17:31:49','Nueva Orden');
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
  `Descripcion` text COLLATE utf8mb4_unicode_ci,
  `Precio` double NOT NULL DEFAULT '0',
  `Descuento` decimal(10,0) NOT NULL DEFAULT '0',
  `Impuesto` decimal(10,0) NOT NULL DEFAULT '13',
  `PackageID` tinyint NOT NULL DEFAULT '0',
  `DateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uk_servicio_categoria` (`ServicioID`,`TipoCategoriaID`),
  KEY `idx_servicio` (`ServicioID`),
  KEY `idx_categoria` (`TipoCategoriaID`)
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `precios`
--

LOCK TABLES `precios` WRITE;
/*!40000 ALTER TABLE `precios` DISABLE KEYS */;
INSERT INTO `precios` VALUES (233,1,11,NULL,4000,0,13,0,'2026-01-10 17:21:54'),(234,2,11,NULL,5000,0,13,0,'2026-01-10 17:21:54'),(235,3,11,NULL,7000,0,13,0,'2026-01-10 17:21:54'),(236,7,11,NULL,6000,0,13,0,'2026-01-10 17:21:54'),(237,8,11,NULL,8000,0,13,0,'2026-01-10 17:21:54'),(238,9,11,NULL,10000,0,13,0,'2026-01-10 17:21:54'),(239,10,11,NULL,2000,0,13,0,'2026-01-10 17:21:54'),(247,1,12,NULL,4000,0,13,0,'2026-01-10 17:23:26'),(248,2,12,NULL,4000,0,13,0,'2026-01-10 17:23:26'),(249,3,12,NULL,4000,0,13,0,'2026-01-10 17:23:26'),(250,7,12,NULL,4000,0,13,0,'2026-01-10 17:23:26'),(251,8,12,NULL,4000,0,13,0,'2026-01-10 17:23:26'),(252,9,12,NULL,6000,0,13,0,'2026-01-10 17:23:26'),(253,10,12,NULL,2000,0,13,0,'2026-01-10 17:23:26'),(254,1,13,NULL,3000,0,13,0,'2026-01-10 17:24:10'),(255,2,13,NULL,3000,0,13,0,'2026-01-10 17:24:10'),(256,3,13,NULL,3000,0,13,0,'2026-01-10 17:24:10'),(257,7,13,NULL,3000,0,13,0,'2026-01-10 17:24:10'),(258,8,13,NULL,0,0,13,0,'2026-01-10 17:24:10'),(259,9,13,NULL,0,0,13,0,'2026-01-10 17:24:10'),(260,10,13,NULL,0,0,13,0,'2026-01-10 17:24:10');
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
  `Descripcion` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CategoriaServicioID` tinyint DEFAULT '1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicios`
--

LOCK TABLES `servicios` WRITE;
/*!40000 ALTER TABLE `servicios` DISABLE KEYS */;
INSERT INTO `servicios` VALUES (11,'Lavado Exteriores',1),(12,'Limpieza Interior',1),(13,'Lavado Chasis',1);
/*!40000 ALTER TABLE `servicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permiso` tinyint(1) DEFAULT '1',
  `estado` enum('activo','inactivo','suspendido') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `idx_empresa` (`id_empresa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
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
  `Marca` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Modelo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Year` int DEFAULT NULL,
  `Placa` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `CategoriaVehiculo` int DEFAULT NULL,
  `Color` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Observaciones` text COLLATE utf8mb4_unicode_ci,
  `FechaRegistro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `placa_unique` (`Placa`),
  KEY `idx_cliente` (`ClienteID`),
  KEY `idx_categoria` (`CategoriaVehiculo`),
  KEY `idx_placa` (`Placa`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vehiculos`
--

LOCK TABLES `vehiculos` WRITE;
/*!40000 ALTER TABLE `vehiculos` DISABLE KEYS */;
INSERT INTO `vehiculos` VALUES (1,5,'Nissan','Sentra',2019,'BSM791',1,'Gris',NULL,'2026-01-10 17:28:04',1);
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

-- Dump completed on 2026-01-10 11:37:33
