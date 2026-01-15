-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: frosh_lavacar
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
-- Table structure for table `access_logs`
--

DROP TABLE IF EXISTS `access_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `access_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `action` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `success` tinyint(1) DEFAULT '1',
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `access_logs`
--

LOCK TABLES `access_logs` WRITE;
/*!40000 ALTER TABLE `access_logs` DISABLE KEYS */;
INSERT INTO `access_logs` VALUES (68,2,'logout','system','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36',1,'2025-12-30 19:12:25');
/*!40000 ALTER TABLE `access_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `administradores`
--

DROP TABLE IF EXISTS `administradores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `administradores` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('superadmin','admin','soporte') COLLATE utf8mb4_unicode_ci DEFAULT 'admin',
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `administradores`
--

LOCK TABLES `administradores` WRITE;
/*!40000 ALTER TABLE `administradores` DISABLE KEYS */;
INSERT INTO `administradores` VALUES (1,'Administrador','admin@lavacar.com','$2y$10$YourHashedPasswordHere','admin','activo',NULL,'2025-12-20 16:16:00'),(2,'Javier Saavedra','myinterpal@gmail.com','$2y$10$PMa7jScvb10xPS8FckwXN.qvrjZw1zZZD02LMRxdT90myGS/NlucS','superadmin','activo','2025-12-27 07:52:03','2025-12-20 16:37:15');
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
  `servidor_host` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `servidor_puerto` int DEFAULT '3306',
  `nombre_db` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuario_db` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contrasena_encriptada` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_servidor` enum('shared','dedicated','premium') COLLATE utf8mb4_unicode_ci DEFAULT 'shared',
  `version_mysql` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '8.0',
  `fecha_creacion_db` timestamp NULL DEFAULT NULL,
  `ultimo_backup` timestamp NULL DEFAULT NULL,
  `tamano_actual_mb` decimal(10,2) DEFAULT '0.00',
  `estado` enum('activa','pendiente','inactiva') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  PRIMARY KEY (`id_config`),
  UNIQUE KEY `id_empresa` (`id_empresa`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_bases_datos`
--

LOCK TABLES `config_bases_datos` WRITE;
/*!40000 ALTER TABLE `config_bases_datos` DISABLE KEYS */;
INSERT INTO `config_bases_datos` VALUES (1,3,NULL,3306,'cars',NULL,NULL,'shared','8.0',NULL,NULL,0.00,'activa');
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
  `nombre_sistema` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Lavacar Admin',
  `email_soporte` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'soporte@lavacar.com',
  `telefono_soporte` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT '+593 999999999',
  `dias_gracias` int DEFAULT '30',
  `limite_vehiculos_gratis` int DEFAULT '10',
  `estado_sistema` enum('activo','mantenimiento') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moneda_default` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
  `idioma_default` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT 'es',
  `zona_horaria` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'America/Guayaquil',
  `notificaciones_email` tinyint(1) DEFAULT '1',
  `notificaciones_whatsapp` tinyint(1) DEFAULT '0',
  `backup_automatico` tinyint(1) DEFAULT '1',
  `frecuencia_backup` enum('diario','semanal','mensual') COLLATE utf8mb4_unicode_ci DEFAULT 'semanal',
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `configuracion_sistema`
--

LOCK TABLES `configuracion_sistema` WRITE;
/*!40000 ALTER TABLE `configuracion_sistema` DISABLE KEYS */;
INSERT INTO `configuracion_sistema` VALUES (1,'FROSH Admin','soporte@lavacar.com','+593 999999999',30,10,'activo',NULL,'USD','es','America/Costa Rica',1,0,1,'semanal','2025-12-22 20:14:02');
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `consumo_recursos`
--

LOCK TABLES `consumo_recursos` WRITE;
/*!40000 ALTER TABLE `consumo_recursos` DISABLE KEYS */;
/*!40000 ALTER TABLE `consumo_recursos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_requests`
--

DROP TABLE IF EXISTS `demo_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demo_requests` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(150) NOT NULL,
  `empresa` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `tipo_negocio` varchar(100) NOT NULL,
  `vehiculos_diarios` varchar(50) NOT NULL,
  `mensaje` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_requests`
--

LOCK TABLES `demo_requests` WRITE;
/*!40000 ALTER TABLE `demo_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id_empresa` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruc_identificacion` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pais` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre_base_datos` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo','pendiente') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_empresa`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nombre_base_datos` (`nombre_base_datos`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (2,'Lavacar Guatuso','000000000000','55555555555','info@lavacarguatuso.com','Costa Rica','Guatuso','froshlav_2','activo','2025-12-20 16:47:05','2025-12-24 18:12:11'),(3,'URBANO CARWASH','00000000000','5555555555','contact@carcarecenter.com','Costa Rica','Puerto Jimenez','froshlav_3','activo','2025-12-20 16:47:05','2025-12-24 18:12:11'),(4,'Mega Wash Center','1234567890004',NULL,'admin@megawash.com','Colombia','Bogotá','froshlav_4','activo','2025-12-20 16:47:05','2025-12-24 18:12:11'),(10,'FSM','1111111111111','+5066666666666','myinterpal@gmail.com','Costa Rica','Doral','froshlav_10','activo','2026-01-13 18:38:48','2026-01-13 18:52:43'),(9,'Nueva01','1111111111111','+5066666666666','myinterpal01@gmail.com','Costa Rica','Doral','froshlav_1768328886','pendiente','2026-01-13 18:28:06','2026-01-13 18:38:42'),(8,'Interpal S.A','310010000','+50683239015','jsaavedra@docusend.biz','Costa Rica','Doral','froshlav_8','activo','2026-01-09 19:06:54','2026-01-13 18:05:54');
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
  `motivo_cambio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_cambio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `id_usuario_cambio` int DEFAULT NULL,
  `precio_anterior` decimal(10,2) DEFAULT NULL,
  `precio_nuevo` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id_historial`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_plan_anterior` (`id_plan_anterior`),
  KEY `id_plan_nuevo` (`id_plan_nuevo`),
  KEY `id_usuario_cambio` (`id_usuario_cambio`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_planes`
--

LOCK TABLES `historial_planes` WRITE;
/*!40000 ALTER TABLE `historial_planes` DISABLE KEYS */;
/*!40000 ALTER TABLE `historial_planes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`),
  KEY `idx_token` (`token`),
  KEY `idx_expires` (`expires_at`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES (31,7,'9c62d62e5ba406938c7c4a664cca996c06721fee08fbdf4edbb2887e1322cb24','2025-12-30 15:36:02',0,'2025-12-30 20:36:02'),(32,8,'7df5df849b3c73e35a107982177f9f9d4808cca3444b41008c7b2733c5174586','2026-01-13 13:13:59',1,'2026-01-13 18:13:59'),(34,10,'3c3d1f1fd22899b6bc1a92f8509d8822effef129cfdbcc6e0b64204f5fe42c72','2026-01-13 15:39:53',0,'2026-01-13 20:39:53');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `token_hash` (`token_hash`),
  KEY `expires_at` (`expires_at`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES (27,7,'d5d4ab97258d80d6a1b85558af5985b32614365d47cd9128f099a0ef8da40efd','2025-12-31 09:32:37','2025-12-31 08:32:37',1);
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `planes`
--

DROP TABLE IF EXISTS `planes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `planes` (
  `id_plan` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `precio_mensual` decimal(10,2) NOT NULL,
  `precio_anual` decimal(10,2) DEFAULT NULL,
  `moneda` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
  `max_vehiculos` int DEFAULT NULL,
  `max_usuarios` int DEFAULT '1',
  `max_sucursales` int DEFAULT '1',
  `tipo_base_datos` enum('compartida','dedicada') COLLATE utf8mb4_unicode_ci DEFAULT 'compartida',
  `limite_almacenamiento_gb` int DEFAULT '1',
  `incluye_soporte_prioritario` tinyint(1) DEFAULT '0',
  `incluye_api_acceso` tinyint(1) DEFAULT '0',
  `incluye_reportes_avanzados` tinyint(1) DEFAULT '0',
  `estado` enum('activo','inactivo') COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_plan`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `planes`
--

LOCK TABLES `planes` WRITE;
/*!40000 ALTER TABLE `planes` DISABLE KEYS */;
INSERT INTO `planes` VALUES (1,'Bronce','Plan básico para pequeños negocios',29.99,299.99,'USD',50,2,1,'compartida',1,0,0,0,'activo','2025-12-20 16:16:00'),(2,'Plata','Plan para negocios en crecimiento',79.99,799.99,'USD',200,5,1,'compartida',5,0,0,0,'activo','2025-12-20 16:16:00'),(3,'Oro','Plan premium para empresas',199.99,1999.99,'USD',1000,20,1,'compartida',20,0,0,0,'activo','2025-12-20 16:16:00');
/*!40000 ALTER TABLE `planes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(90) DEFAULT '0',
  `Reportes` tinyint DEFAULT '0',
  `Usuarios` tinyint DEFAULT '0',
  `Clientes` tinyint DEFAULT '0',
  `Ordenes` tinyint DEFAULT '0',
  `Actividad` tinyint DEFAULT '0',
  `Financiero` tinyint DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrador',1,1,1,1,1,1),(2,'Asistente',0,0,1,1,1,0),(3,'Operador',0,0,0,1,1,0);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sistema_logs`
--

DROP TABLE IF EXISTS `sistema_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sistema_logs` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('login','empresa_creada','empresa_actualizada','suscripcion_cambiada','suscripcion_renovada','suscripcion_estado','admin_creado','admin_estado','admin_password_reset','bd_creada','bd_password_reset','bd_backup','config_actualizada','logs_limpiados','backup_sistema','error') COLLATE utf8mb4_unicode_ci DEFAULT 'login',
  `id_admin` int DEFAULT NULL,
  `id_empresa` int DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `id_admin` (`id_admin`),
  KEY `id_empresa` (`id_empresa`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;



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
  `ciclo_facturacion` enum('mensual','anual') COLLATE utf8mb4_unicode_ci DEFAULT 'mensual',
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `fecha_proximo_pago` date DEFAULT NULL,
  `estado` enum('activa','pendiente','cancelada','vencida') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `metodo_pago` enum('tarjeta','transferencia','paypal') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precio_actual` decimal(10,2) NOT NULL,
  `renovacion_automatica` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_suscripcion`),
  KEY `id_empresa` (`id_empresa`),
  KEY `id_plan` (`id_plan`),
  KEY `idx_fecha_fin` (`fecha_fin`),
  KEY `idx_estado` (`estado`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suscripciones`
--

LOCK TABLES `suscripciones` WRITE;
/*!40000 ALTER TABLE `suscripciones` DISABLE KEYS */;
INSERT INTO `suscripciones` VALUES (7,10,1,'mensual','2026-01-13','2026-02-12',NULL,'activa',NULL,0.00,1),(2,2,2,'mensual','2025-12-20','2026-01-20',NULL,'activa',NULL,79.99,1),(3,3,1,'mensual','2025-12-20','2026-01-20',NULL,'activa',NULL,29.99,1),(4,4,1,'mensual','2025-12-20','2026-01-20',NULL,'activa',NULL,29.99,1),(6,8,1,'mensual','2026-01-09','2026-02-08',NULL,'activa',NULL,0.00,1);
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
  `referencia_pago` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `moneda` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT 'USD',
  `fecha_pago` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_vencimiento` date DEFAULT NULL,
  `estado_pago` enum('completado','pendiente','fallido','reembolsado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `metodo_pago` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datos_facturacion` json DEFAULT NULL,
  PRIMARY KEY (`id_transaccion`),
  UNIQUE KEY `referencia_pago` (`referencia_pago`),
  KEY `id_suscripcion` (`id_suscripcion`),
  KEY `idx_referencia` (`referencia_pago`),
  KEY `idx_fecha_pago` (`fecha_pago`),
  KEY `idx_estado_pago` (`estado_pago`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transacciones`
--

LOCK TABLES `transacciones` WRITE;
/*!40000 ALTER TABLE `transacciones` DISABLE KEYS */;
INSERT INTO `transacciones` VALUES (1,1,NULL,199.99,'USD','2025-12-19 06:00:00',NULL,'completado','tarjeta',NULL),(2,2,NULL,79.99,'USD','2025-12-18 06:00:00',NULL,'completado','tarjeta',NULL),(3,3,NULL,29.99,'USD','2025-12-17 06:00:00',NULL,'completado','tarjeta',NULL);
/*!40000 ALTER TABLE `transacciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `empresa_id` int DEFAULT '0',
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` int DEFAULT '1',
  `department` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `department_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_users_department` (`department_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (7,3,'jsaavedra','myinterpal@gmail.com','$2y$10$zFjg6nvcgVbVDcb/FpnXkeov7XM41/hky4lGJ7jRnvtayiArN3UpG','Javier','Saavedra',1,'Gerencia',1,'2025-12-29 19:38:11','2025-12-29 19:30:26','2025-12-31 14:33:17',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
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
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permiso` tinyint(1) DEFAULT '1',
  `mfa_habilitado` tinyint(1) DEFAULT '0',
  `intentos_fallidos` int DEFAULT '0',
  `bloqueado_hasta` datetime DEFAULT NULL,
  `ultimo_login` datetime DEFAULT NULL,
  `ultimo_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('activo','inactivo','suspendido') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuarioscol` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint DEFAULT '1',
  `remember_token` tinyint DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_empresa` (`email`,`id_empresa`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `idx_empresa` (`id_empresa`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,3,'Frederick','amidestino@gmail.com','jsaavedra','$2y$10$KjCD2B9b8UbsWzbXb9HNGu/iKsMuCcXSpMeSE9s21.GfImzb/GJMG',1,0,3,NULL,NULL,NULL,'activo','2025-12-22 20:33:58','2025-12-24 16:26:22',NULL,1,0),(7,3,'Javier Saavedra','myinterpal01@gmail.com','','$2y$10$zFjg6nvcgVbVDcb/FpnXkeov7XM41/hky4lGJ7jRnvtayiArN3UpG',1,0,0,NULL,'2026-01-09 12:36:27','::1','activo','2025-12-23 21:03:29','2026-01-13 18:27:35',NULL,1,0),(8,8,'Javier Saavedra','jsaavedra@docusend.biz','javiersaavedra','$2y$10$sN89wFYaEePZPwCD4ytZYeIRjNfE6nn34EARfHV99Cj3GAn2uZbAi',1,0,0,NULL,'2026-01-13 12:17:53','::1','activo','2026-01-09 19:13:32','2026-01-13 18:17:53',NULL,1,0),(9,8,'Omar Monestel','omar@amidestino.com','omar','$2y$10$nDTZ1WbAbeZjCxxrMWovteMFgigTRCKNC4IcXE0heV4IeuTPeT6o2',2,0,0,NULL,NULL,NULL,'activo','2026-01-09 20:04:14','2026-01-13 17:58:42',NULL,1,0),(10,10,'Frederick Saavedra','myinterpal@gmail.com','fsaavedra','$2y$10$XwiEWLfxjnWtzPQmReEpoO2SAMaTg/RXqYeaD9MZ2ZDHH8qnfusGi',1,0,0,NULL,'2026-01-14 10:40:56','::1','activo','2026-01-13 18:39:42','2026-01-14 16:40:56',NULL,1,0);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-14 13:34:20
