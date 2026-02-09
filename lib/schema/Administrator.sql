
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

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (2,'Lavacar Guatuso','000000000000','55555555555','info@lavacarguatuso.com','Costa Rica','Guatuso','froshlav_2','activo','2025-12-20 16:47:05','2025-12-24 18:12:11'),(3,'URBANO CARWASH','00000000000','5555555555','contact@carcarecenter.com','Costa Rica','Puerto Jimenez','froshlav_3','activo','2025-12-20 16:47:05','2025-12-24 18:12:11'),(4,'Mega Wash Center','1234567890004',NULL,'admin@megawash.com','Colombia','Bogotá','froshlav_4','activo','2025-12-20 16:47:05','2025-12-24 18:12:11'),(10,'FSM','1111111111111','+5066666666666','myinterpal@gmail.com','Costa Rica','Doral','froshlav_10','activo','2026-01-13 18:38:48','2026-01-13 18:52:43'),(9,'Nueva01','1111111111111','+5066666666666','myinterpal01@gmail.com','Costa Rica','Doral','froshlav_1768328886','pendiente','2026-01-13 18:28:06','2026-01-13 18:38:42'),(8,'Interpal S.A','310010000','+50683239015','jsaavedra@docusend.biz','Costa Rica','Doral','froshlav_8','activo','2026-01-09 19:06:54','2026-01-13 18:05:54');

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
