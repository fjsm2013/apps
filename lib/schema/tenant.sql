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

--
-- Dumping data for table `categoriaservicios`
--

LOCK TABLES `categoriaservicios` WRITE;
/*!40000 ALTER TABLE `categoriaservicios` DISABLE KEYS */;
INSERT INTO `categoriaservicios` VALUES (1,'Lavado Básico','Lavado exterior básico',1),(2,'Lavado Completo','Lavado exterior e interior',1),(3,'Encerado','Aplicación de cera protectora',1),(4,'Aspirado','Limpieza interior con aspiradora',1),(5,'Detallado','Limpieza profunda y detallada',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


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
-- Table structure for table `orden_servicios`
--

DROP TABLE IF EXISTS `orden_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orden_servicios` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `OrdenID` int NOT NULL,
  `ServicioID` int NOT NULL,
  `Descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `Cantidad` int DEFAULT '1',
  `Subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `OrdenID` (`OrdenID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;


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
INSERT INTO `servicios` VALUES (1,'Lavado Exteriores',1),(2,'Limpieza Interior',1),(3,'Lavado Chasis',1);
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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

