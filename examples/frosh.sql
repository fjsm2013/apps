-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: frosh
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
-- Table structure for table `administradores`
--

DROP TABLE IF EXISTS `administradores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administradores` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('superadmin','admin','soporte') DEFAULT 'admin',
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administradores`
--

LOCK TABLES `administradores` WRITE;
/*!40000 ALTER TABLE `administradores` DISABLE KEYS */;
INSERT INTO `administradores` VALUES (1,'Administrador','admin@lavacar.com','$2y$10$YourHashedPasswordHere','superadmin','activo',NULL,'2025-12-20 13:55:46'),(2,'Javier Saavedra Villanueva','myinterpal@gmail.com','$2y$10$PMa7jScvb10xPS8FckwXN.qvrjZw1zZZD02LMRxdT90myGS/NlucS','admin','activo','2025-12-20 09:11:15','2025-12-20 15:10:11');
/*!40000 ALTER TABLE `administradores` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`fmorgan`@`%`*/ /*!50003 TRIGGER `prevent_last_superadmin_delete` BEFORE DELETE ON `administradores` FOR EACH ROW BEGIN
    DECLARE superadmin_count INT;
    
    IF OLD.rol = 'superadmin' THEN
        SELECT COUNT(*) INTO superadmin_count 
        FROM administradores 
        WHERE rol = 'superadmin' AND estado = 'activo';
        
        IF superadmin_count <= 1 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede eliminar el último superadministrador activo';
        END IF;
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `config_bases_datos`
--

DROP TABLE IF EXISTS `config_bases_datos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `config_bases_datos` (
  `id_config` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `servidor_host` varchar(100) DEFAULT NULL,
  `servidor_puerto` int DEFAULT '3306',
  `nombre_db` varchar(50) DEFAULT NULL,
  `usuario_db` varchar(50) DEFAULT NULL,
  `contrasena_encriptada` varchar(255) DEFAULT NULL,
  `tipo_servidor` enum('shared','dedicated','premium') DEFAULT 'shared',
  `version_mysql` varchar(20) DEFAULT '8.0',
  `fecha_creacion_db` timestamp NULL DEFAULT NULL,
  `ultimo_backup` timestamp NULL DEFAULT NULL,
  `tamano_actual_mb` decimal(10,2) DEFAULT '0.00',
  `estado` enum('activa','pendiente','inactiva') DEFAULT 'pendiente',
  PRIMARY KEY (`id_config`),
  UNIQUE KEY `id_empresa` (`id_empresa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_bases_datos`
--

LOCK TABLES `config_bases_datos` WRITE;
/*!40000 ALTER TABLE `config_bases_datos` DISABLE KEYS */;
/*!40000 ALTER TABLE `config_bases_datos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `configuracion_sistema`
--

DROP TABLE IF EXISTS `configuracion_sistema`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `configuracion_sistema` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_sistema` varchar(100) DEFAULT 'Lavacar Admin',
  `email_soporte` varchar(100) DEFAULT 'soporte@lavacar.com',
  `telefono_soporte` varchar(20) DEFAULT '+593 999999999',
  `dias_gracias` int DEFAULT '30',
  `limite_vehiculos_gratis` int DEFAULT '10',
  `estado_sistema` enum('activo','mantenimiento') DEFAULT 'activo',
  `logo_url` varchar(255) DEFAULT NULL,
  `moneda_default` varchar(3) DEFAULT 'USD',
  `idioma_default` varchar(5) DEFAULT 'es',
  `zona_horaria` varchar(50) DEFAULT 'America/Guayaquil',
  `notificaciones_email` tinyint(1) DEFAULT '1',
  `notificaciones_whatsapp` tinyint(1) DEFAULT '0',
  `backup_automatico` tinyint(1) DEFAULT '1',
  `frecuencia_backup` enum('diario','semanal','mensual') DEFAULT 'semanal',
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion_sistema`
--

LOCK TABLES `configuracion_sistema` WRITE;
/*!40000 ALTER TABLE `configuracion_sistema` DISABLE KEYS */;
INSERT INTO `configuracion_sistema` VALUES (1,'Lavacar Admin','soporte@lavacar.com','+593 999999999',30,10,'activo',NULL,'USD','es','America/Guayaquil',1,0,1,'semanal','2025-12-20 14:56:11');
/*!40000 ALTER TABLE `configuracion_sistema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `consumo_recursos`
--

DROP TABLE IF EXISTS `consumo_recursos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `consumo_recursos` (
  `id_consumo` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `fecha` date NOT NULL,
  `vehiculos_registrados` int DEFAULT '0',
  `usuarios_activos` int DEFAULT '0',
  `transacciones_dia` int DEFAULT '0',
  `almacenamiento_usado_mb` decimal(10,2) DEFAULT '0.00',
  `limite_vehiculos` int DEFAULT NULL,
  `limite_almacenamiento_mb` int DEFAULT NULL,
  PRIMARY KEY (`id_consumo`),
  UNIQUE KEY `uniq_empresa_fecha` (`id_empresa`,`fecha`),
  KEY `idx_fecha_empresa` (`fecha`,`id_empresa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consumo_recursos`
--

LOCK TABLES `consumo_recursos` WRITE;
/*!40000 ALTER TABLE `consumo_recursos` DISABLE KEYS */;
/*!40000 ALTER TABLE `consumo_recursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id_empresa` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `ruc_identificacion` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `nombre_base_datos` varchar(50) DEFAULT NULL,
  `host_base_datos` varchar(100) DEFAULT NULL,
  `puerto_base_datos` int DEFAULT '3306',
  `estado` enum('activo','inactivo','pendiente') DEFAULT 'pendiente',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_empresa`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nombre_base_datos` (`nombre_base_datos`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (1,'Katherine','503640054','83239015','kmonestel@gmail.com','Costa Rica','Guatuso','lavacar_katherine_1766244106',NULL,3306,'activo','2025-12-20 15:21:46','2025-12-20 15:21:46');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`fmorgan`@`%`*/ /*!50003 TRIGGER `empresas_before_update` BEFORE UPDATE ON `empresas` FOR EACH ROW BEGIN
    SET NEW.fecha_actualizacion = NOW();
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `historial_planes`
--

DROP TABLE IF EXISTS `historial_planes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_planes` (
  `id_historial` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `id_plan_anterior` int DEFAULT NULL,
  `id_plan_nuevo` int NOT NULL,
  `motivo_cambio` varchar(255) DEFAULT NULL,
  `fecha_cambio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario_cambio` int DEFAULT NULL,
  `precio_anterior` decimal(10,2) DEFAULT NULL,
  `precio_nuevo` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_historial`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_plan_anterior` (`id_plan_anterior`),
  KEY `id_plan_nuevo` (`id_plan_nuevo`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_planes`
--

LOCK TABLES `historial_planes` WRITE;
/*!40000 ALTER TABLE `historial_planes` DISABLE KEYS */;
INSERT INTO `historial_planes` VALUES (1,1,NULL,1,'Asignar Plan','2025-12-20 15:23:23',2,NULL,29.99);
/*!40000 ALTER TABLE `historial_planes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `planes`
--

DROP TABLE IF EXISTS `planes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `planes` (
  `id_plan` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text,
  `precio_mensual` decimal(10,2) NOT NULL,
  `precio_anual` decimal(10,2) DEFAULT NULL,
  `moneda` varchar(3) DEFAULT 'USD',
  `max_vehiculos` int DEFAULT NULL,
  `max_usuarios` int DEFAULT '1',
  `max_sucursales` int DEFAULT '1',
  `incluye_soporte_prioritario` tinyint(1) DEFAULT '0',
  `incluye_api_acceso` tinyint(1) DEFAULT '0',
  `incluye_reportes_avanzados` tinyint(1) DEFAULT '0',
  `tipo_base_datos` enum('compartida','dedicada') DEFAULT 'compartida',
  `limite_almacenamiento_gb` int DEFAULT '1',
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_plan`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `planes`
--

LOCK TABLES `planes` WRITE;
/*!40000 ALTER TABLE `planes` DISABLE KEYS */;
INSERT INTO `planes` VALUES (1,'Esencial','Plan básico para pequeños negocios',19.99,19.99,'USD',50,2,1,0,0,0,'compartida',1,'activo','2025-12-20 13:47:49'),(2,'Avanzado','Plan para negocios en crecimiento',69.99,699.99,'USD',200,5,3,0,0,1,'compartida',5,'activo','2025-12-20 13:47:49'),(3,'Master','Plan premium para empresas',199.99,1999.99,'USD',1000,20,10,1,1,1,'dedicada',20,'activo','2025-12-20 13:47:49');
/*!40000 ALTER TABLE `planes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sistema_logs`
--

DROP TABLE IF EXISTS `sistema_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sistema_logs` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('login','empresa_creada','empresa_actualizada','suscripcion_cambiada','error') DEFAULT 'login',
  `id_admin` int DEFAULT NULL,
  `id_empresa` int DEFAULT NULL,
  `descripcion` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `id_admin` (`id_admin`),
  KEY `id_empresa` (`id_empresa`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sistema_logs`
--

LOCK TABLES `sistema_logs` WRITE;
/*!40000 ALTER TABLE `sistema_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `sistema_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suscripciones`
--

DROP TABLE IF EXISTS `suscripciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suscripciones` (
  `id_suscripcion` int NOT NULL AUTO_INCREMENT,
  `id_empresa` int NOT NULL,
  `id_plan` int NOT NULL,
  `ciclo_facturacion` enum('mensual','anual') DEFAULT 'mensual',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `fecha_proximo_pago` date DEFAULT NULL,
  `estado` enum('activa','pendiente','cancelada','vencida') DEFAULT 'pendiente',
  `metodo_pago` enum('tarjeta','transferencia','paypal') DEFAULT NULL,
  `precio_actual` decimal(10,2) NOT NULL,
  `renovacion_automatica` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_suscripcion`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_plan` (`id_plan`),
  KEY `idx_fecha_fin` (`fecha_fin`),
  KEY `idx_estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suscripciones`
--

LOCK TABLES `suscripciones` WRITE;
/*!40000 ALTER TABLE `suscripciones` DISABLE KEYS */;
INSERT INTO `suscripciones` VALUES (1,1,1,'mensual','2025-12-20','2026-01-20',NULL,'activa',NULL,29.99,1);
/*!40000 ALTER TABLE `suscripciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transacciones`
--

DROP TABLE IF EXISTS `transacciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transacciones` (
  `id_transaccion` int NOT NULL AUTO_INCREMENT,
  `id_suscripcion` int NOT NULL,
  `referencia_pago` varchar(100) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `moneda` varchar(3) DEFAULT 'USD',
  `fecha_pago` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_vencimiento` date DEFAULT NULL,
  `estado_pago` enum('completado','pendiente','fallido','reembolsado') DEFAULT 'pendiente',
  `metodo_pago` varchar(50) DEFAULT NULL,
  `datos_facturacion` json DEFAULT NULL,
  PRIMARY KEY (`id_transaccion`),
  UNIQUE KEY `referencia_pago` (`referencia_pago`),
  KEY `id_suscripcion` (`id_suscripcion`),
  KEY `idx_referencia` (`referencia_pago`),
  KEY `idx_fecha_pago` (`fecha_pago`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transacciones`
--

LOCK TABLES `transacciones` WRITE;
/*!40000 ALTER TABLE `transacciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `transacciones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-07 15:06:24
