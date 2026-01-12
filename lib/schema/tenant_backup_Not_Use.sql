
SET NAMES utf8mb4;

  ------------------------------------
  – Table structure for table barrio
  ------------------------------------

DROP TABLE IF EXISTS barrio;

CREATE TABLE barrio ( id int NOT NULL, codigoProvincia int DEFAULT NULL,
codigoCanton int DEFAULT NULL, codigoDistrito int DEFAULT NULL, codigo
int DEFAULT NULL, barrio varchar(80) DEFAULT NULL, PRIMARY KEY (id) )
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  ---------------------------------
  – Dumping data for table barrio
  ---------------------------------

INSERT INTO barrio VALUES
(1,1,1,1,1,‘Amón’),(2,1,1,1,2,‘Aranjuez’),(3,1,1,1,3,‘California
(parte)’),(4,1,1,1,4,‘Carmen’),(5,1,1,1,5,‘Empalme’),(6,1,1,1,6,‘Escalante’),(7,1,1,1,7,‘Otoya.’),(8,1,1,2,1,‘Bajos
de la
Unión’),(9,1,1,2,2,‘Claret’),(10,1,1,2,3,‘Cocacola’),(11,1,1,2,4,‘Iglesias
Flores’),(12,1,1,2,5,‘Mantica’),(13,1,1,2,6,‘México’),(14,1,1,2,7,‘Paso
de la
Vaca’),(15,1,1,2,8,‘Pitahaya.’),(16,1,1,3,1,‘Almendares’),(17,1,1,3,2,‘Ángeles’),(18,1,1,3,3,‘Bolívar’),(19,1,1,3,4,‘Carit’),(20,1,1,3,5,‘Colón
(parte)’),(21,1,1,3,6,‘Corazón de Jesús’),(22,1,1,3,7,‘Cristo
Rey’),(23,1,1,3,8,‘Cuba’),(24,1,1,3,9,‘Dolorosa
(parte)’),(25,1,1,3,10,‘Merced’),(26,1,1,3,11,‘Pacífico
(parte)’),(27,1,1,3,12,‘Pinos’),(28,1,1,3,13,‘Salubridad’),(29,1,1,3,14,‘San
Bosco’),(30,1,1,3,15,‘San Francisco’),(31,1,1,3,16,‘Santa
Lucía’),(32,1,1,3,17,‘Silos.’),(33,1,1,4,1,‘Bellavista’),(34,1,1,4,2,‘California
(parte)’),(35,1,1,4,3,‘Carlos María Jiménez’),(36,1,1,4,4,‘Dolorosa
(parte)’),(37,1,1,4,5,‘Dos Pinos’),(38,1,1,4,6,‘Francisco Peralta
(parte)’),(39,1,1,4,7,‘González Lahmann’),(40,1,1,4,8,‘González
Víquez’),(41,1,1,4,9,‘Güell’),(42,1,1,4,10,‘La
Cruz’),(43,1,1,4,11,‘Lomas de
Ocloro’),(44,1,1,4,12,‘Luján’),(45,1,1,4,13,‘Milflor’),(46,1,1,4,14,‘Naciones
Unidas’),(47,1,1,4,15,‘Pacífico (parte)’),(48,1,1,4,16,‘San Cayetano
(parte)’),(49,1,1,4,17,‘Soledad’),(50,1,1,4,18,‘Tabacalera’),(51,1,1,4,19,‘Vasconia.’),(52,1,1,5,1,‘Alborada’),(53,1,1,5,2,‘Calderón
Muñoz’),(54,1,1,5,3,‘Cerrito’),(55,1,1,5,4,‘Córdoba’),(56,1,1,5,5,‘Gloria’),(57,1,1,5,6,‘Jardín’),(58,1,1,5,7,‘Luisas’),(59,1,1,5,8,‘Mangos’),(60,1,1,5,9,‘Montealegre’),(61,1,1,5,10,‘Moreno
Cañas’),(62,1,1,5,11,‘Quesada Durán’),(63,1,1,5,12,‘San
Dimas’),(64,1,1,5,13,‘San Gerardo
(parte)’),(65,1,1,5,14,‘Trébol’),(66,1,1,5,15,‘Ujarrás’),(67,1,1,5,16,‘Vista
Hermosa’),(68,1,1,5,17,‘Yoses Sur’),(69,1,1,5,18,‘Zapote
(centro).’),(70,1,1,6,1,‘Ahogados
(parte)’),(71,1,1,6,2,‘Bosque’),(72,1,1,6,3,‘Cabañas’),(73,1,1,6,4,‘Camelias’),(74,1,1,6,5,‘Coopeguaria’),(75,1,1,6,6,‘Faro’),(76,1,1,6,7,‘Fátima’),(77,1,1,6,8,‘Hispano’),(78,1,1,6,9,‘I
Griega’),(79,1,1,6,10,‘Lincoln’),(80,1,1,6,11,‘Lomas de San
Francisco’),(81,1,1,6,12,‘Maalot’),(82,1,1,6,13,‘Méndez’),(83,1,1,6,14,‘Pacífica’),(84,1,1,6,15,‘San
Francisco de Dos Ríos
(centro)’),(85,1,1,6,16,‘Sauces’),(86,1,1,6,17,‘Saucitos’),(87,1,1,6,18,‘Zurquí.’),(88,1,1,7,1,‘Alborada’),(89,1,1,7,2,‘Ánimas’),(90,1,1,7,3,‘Árboles’),(91,1,1,7,4,‘Bajos
del Torres’),(92,1,1,7,5,‘Carranza’),(93,1,1,7,6,‘Corazón de
Jesús’),(94,1,1,7,7,‘Cristal’),(95,1,1,7,8,‘Carvajal
Castro’),(96,1,1,7,9,‘Jardines de Autopista’),(97,1,1,7,10,‘La
Caja’),(98,1,1,7,11,‘La
Carpio’),(99,1,1,7,12,‘Magnolia’),(100,1,1,7,13,‘Marimil’),(101,1,1,7,14,‘Monserrat’),(102,1,1,7,15,‘Peregrina’),(103,1,1,7,16,‘Robledal’),(104,1,1,7,17,‘Rossiter
Carballo’),(105,1,1,7,18,‘Santander’),(106,1,1,7,19,‘Saturno’),(107,1,1,7,20,‘Uruca
(centro)’),(108,1,1,7,21,‘Vuelta del
Virilla.’),(109,1,1,8,1,‘Américas’),(110,1,1,8,2,‘Bajo Cañada
(parte)’),(111,1,1,8,3,‘Balcón Verde’),(112,1,1,8,4,‘Colón
(parte)’),(113,1,1,8,5,‘Del
Pino’),(114,1,1,8,6,‘Holanda’),(115,1,1,8,7,‘La Luisa’),(116,1,1,8,8,‘La
Salle’),(117,1,1,8,9,‘Lomalinda’),(118,1,1,8,10,‘Morenos’),(119,1,1,8,11,‘Niza’),(120,1,1,8,12,‘Rancho
Luna’),(121,1,1,8,13,‘Rohrmoser
(parte)’),(122,1,1,8,14,‘Roma’),(123,1,1,8,15,‘Sabana’),(124,1,1,8,16,‘Tovar.’),(125,1,1,9,1,‘Alfa’),(126,1,1,9,2,‘Asturias’),(127,1,1,9,3,‘Asunción’),(128,1,1,9,4,‘Bribrí’),(129,1,1,9,5,‘Favorita
Norte’),(130,1,1,9,6,‘Favorita
Sur’),(131,1,1,9,7,‘Galicia’),(132,1,1,9,8,‘Geroma’),(133,1,1,9,9,‘Hispania’),(134,1,1,9,10,‘Libertad’),(135,1,1,9,11,‘Lomas
del Río’),(136,1,1,9,12,‘Llanos del Sol’),(137,1,1,9,13,‘María
Reina’),(138,1,1,9,14,‘Metrópolis’),(139,1,1,9,15,‘Navarra’),(140,1,1,9,16,‘Pavas
(centro)’),(141,1,1,9,17,‘Pueblo Nuevo’),(142,1,1,9,18,‘Residencial del
Oeste’),(143,1,1,9,19,‘Rincón Grande’),(144,1,1,9,20,‘Rohrmoser
(parte)’),(145,1,1,9,21,‘Rotonda’),(146,1,1,9,22,‘San
Pedro’),(147,1,1,9,23,‘Santa Bárbara’),(148,1,1,9,24,‘Santa
Catalina’),(149,1,1,9,25,‘Santa
Fé’),(150,1,1,9,26,‘Triángulo’),(151,1,1,9,27,‘Villa
Esperanza.’),(152,1,1,10,1,‘Bajo Cañada
(parte)’),(153,1,1,10,2,‘Belgrano’),(154,1,1,10,3,‘Hatillo
Centro’),(155,1,1,10,4,‘Hatillo 1’),(156,1,1,10,5,‘Hatillo
2’),(157,1,1,10,6,‘Hatillo 3’),(158,1,1,10,7,‘Hatillo
4’),(159,1,1,10,8,‘Hatillo 5’),(160,1,1,10,9,‘Hatillo
6’),(161,1,1,10,10,‘Hatillo 7’),(162,1,1,10,11,‘Hatillo
8’),(163,1,1,10,12,‘Quince de Setiembre’),(164,1,1,10,13,‘Sagrada
Familia’),(165,1,1,10,14,‘Tiribí’),(166,1,1,10,15,‘Topacio’),(167,1,1,10,16,‘Veinticinco
de Julio’),(168,1,1,10,17,‘Vivienda en
Marcha.’),(169,1,1,11,1,‘Bengala’),(170,1,1,11,2,‘Bilbao’),(171,1,1,11,3,‘Cañada
del
Sur’),(172,1,1,11,4,‘Carmen’),(173,1,1,11,5,‘Cascajal’),(174,1,1,11,6,‘Cerro
Azul’),(175,1,1,11,7,‘Colombari’),(176,1,1,11,8,‘Domingo
Savio’),(177,1,1,11,9,‘Guacamaya’),(178,1,1,11,10,‘Jazmín’),(179,1,1,11,11,‘Hogar
Propio’),(180,1,1,11,12,‘Kennedy’),(181,1,1,11,13,‘López
Mateos’),(182,1,1,11,14,‘Luna
Park’),(183,1,1,11,15,‘Martínez’),(184,1,1,11,16,‘Mojados’),(185,1,1,11,17,‘Mongito’),(186,1,1,11,18,‘Monte
Azúl’),(187,1,1,11,19,‘Musmanni’),(188,1,1,11,20,‘Paso
Ancho’),(189,1,1,11,21,‘Presidentes’),(190,1,1,11,22,‘San Cayetano
(parte)’),(191,1,1,11,23,‘San Martín’),(192,1,1,11,24,‘San Sebastián
(centro)’),(193,1,1,11,25,‘Santa
Rosa’),(194,1,1,11,26,‘Seminario’),(195,1,1,11,27,‘Sorobarú.’),(196,1,2,1,1,‘Alto
Carrizal’),(197,1,2,1,2,‘Carrizal
(parte)’),(198,1,2,1,3,‘Faroles’),(199,1,2,1,4,‘Guapinol’),(200,1,2,1,5,‘Hulera’),(201,1,2,1,6,‘Itabas’),(202,1,2,1,7,‘Jaboncillo’),(203,1,2,1,8,‘Profesores
(parte).’),(204,1,2,2,1,‘Avellana’),(205,1,2,2,2,‘Bebedero’),(206,1,2,2,3,‘Belo
Horizonte (parte)’),(207,1,2,2,4,‘Carrizal
(parte)’),(208,1,2,2,5,‘Curío’),(209,1,2,2,6,‘Chirca’),(210,1,2,2,7,‘Chiverral’),(211,1,2,2,8,‘Entierrillo’),(212,1,2,2,9,‘Filtros’),(213,1,2,2,10,‘Guayabos’),(214,1,2,2,11,‘Hojablanca’),(215,1,2,2,12,‘Juan
Santana’),(216,1,2,2,13,‘Lajas’),(217,1,2,2,14,‘Masilla’),(218,1,2,2,15,‘Muta’),(219,1,2,2,16,‘Pedrero’),(220,1,2,2,17,‘Perú’),(221,1,2,2,18,‘Profesores
(parte)’),(222,1,2,2,19,‘Sabanillas’),(223,1,2,2,20,‘Salitrillos’),(224,1,2,2,21,‘Santa
Eduvigis’),(225,1,2,2,22,‘Santa
Teresa’),(226,1,2,2,23,‘Tejarcillos’),(227,1,2,2,24,‘Torrotillo’),(228,1,2,2,25,‘Vista
de
Oro.’),(229,1,2,3,1,‘Anonos’),(230,1,2,3,2,‘Ayala’),(231,1,2,3,3,‘Bajo
Anonos’),(232,1,2,3,4,‘Bajo Palomas’),(233,1,2,3,5,‘Belo Horizonte
(parte)’),(234,1,2,3,6,‘Betina’),(235,1,2,3,7,‘Ceiba’),(236,1,2,3,8,‘Facio
Castro’),(237,1,2,3,9,‘Guachipelín’),(238,1,2,3,10,‘Herrera’),(239,1,2,3,11,‘Laureles’),(240,1,2,3,12,‘León’),(241,1,2,3,13,‘Loma
Real’),(242,1,2,3,14,‘Matapalo’),(243,1,2,3,15,‘Maynard’),(244,1,2,3,16,‘Mirador’),(245,1,2,3,17,‘Miravalles’),(246,1,2,3,18,‘Palermo’),(247,1,2,3,19,‘Palma
de
Mallorca’),(248,1,2,3,20,‘Pinar’),(249,1,2,3,21,‘Primavera’),(250,1,2,3,22,‘Quesada’),(251,1,2,3,23,‘Real
de Pereira (parte)’),(252,1,2,3,24,‘Santa
Marta’),(253,1,2,3,25,‘Tena’),(254,1,2,3,26,‘Trejos
Montealegre’),(255,1,2,3,27,‘Vista
Alegre.’),(256,1,3,1,1,‘Altamira’),(257,1,3,1,2,‘Bellavista’),(258,1,3,1,3,‘Calle
Fallas’),(259,1,3,1,4,‘Camaquirí’),(260,1,3,1,5,‘Capilla’),(261,1,3,1,6,‘Centro
de Amigos’),(262,1,3,1,7,‘Cerámica’),(263,1,3,1,8,‘Colonia del
Sur’),(264,1,3,1,9,‘Contadores’),(265,1,3,1,10,‘Cruce’),(266,1,3,1,11,‘Cucubres’),(267,1,3,1,12,‘Dorados’),(268,1,3,1,13,‘Florita’),(269,1,3,1,14,‘Fortuna’),(270,1,3,1,15,‘Jardín’),(271,1,3,1,16,‘Loto’),(272,1,3,1,17,‘Metrópoli’),(273,1,3,1,18,‘Monseñor
Sanabria’),(274,1,3,1,19,‘Monteclaro’),(275,1,3,1,20,‘Palogrande’),(276,1,3,1,21,‘Pinos’),(277,1,3,1,22,‘Retoños’),(278,1,3,1,23,‘Río
Jorco’),(279,1,3,1,24,‘Sabara’),(280,1,3,1,25,‘San Esteban
Rey’),(281,1,3,1,26,‘San Jerónimo’),(282,1,3,1,27,‘San
José’),(283,1,3,1,28,‘San
Roque’),(284,1,3,1,29,‘Tauros’),(285,1,3,1,30,‘Torremolinos’),(286,1,3,1,31,‘Venecia’),(287,1,3,1,32,‘Vista
Verde.’),(288,1,3,2,1,‘Ángeles’),(289,1,3,2,2,‘Capri’),(290,1,3,2,3,‘Damas
Israelitas’),(291,1,3,2,4,‘Girasol’),(292,1,3,2,5,‘Higuito’),(293,1,3,2,6,‘Lindavista’),(294,1,3,2,7,‘Lomas
de
Jorco’),(295,1,3,2,8,‘Llano’),(296,1,3,2,9,‘Meseguer’),(297,1,3,2,10,‘Olivos’),(298,1,3,2,11,‘Orquídeas’),(299,1,3,2,12,‘Peñascal’),(300,1,3,2,13,‘Rinconada’),(301,1,3,2,14,‘Rodillal’),(302,1,3,2,15,‘Sabanilla’),(303,1,3,2,16,‘San
Martín’),(304,1,3,2,17,‘Santa
Eduvigis’),(305,1,3,2,18,‘Valverde.’),(306,1,3,2,19,‘Alto
Alumbre’),(307,1,3,2,20,‘Hoyo’),(308,1,3,2,21,‘Jericó’),(309,1,3,2,22,‘Manzano’),(310,1,3,2,23,‘Pacaya’),(311,1,3,2,24,‘Roblar’),(312,1,3,2,25,‘Ticalpes
(parte)’),(313,1,3,2,26,‘Calle
Naranjos.’),(314,1,3,3,1,‘Calabacitas’),(315,1,3,3,2,‘Calle
Común’),(316,1,3,3,3,‘Cruz
Roja’),(317,1,3,3,4,‘Itaipú’),(318,1,3,3,5,‘Máquinas’),(319,1,3,3,6,‘Mota’),(320,1,3,3,7,‘Novedades’),(321,1,3,3,8,‘Pedrito
Monge’),(322,1,3,3,9,‘Río’),(323,1,3,3,10,‘Robles.’),(324,1,3,4,1,‘Alpino’),(325,1,3,4,2,‘Arco
Iris’),(326,1,3,4,3,‘Bambú’),(327,1,3,4,4,‘Berlay’),(328,1,3,4,5,‘Guaria’),(329,1,3,4,6,‘Huaso
(parte)’),(330,1,3,4,7,‘Juncales’),(331,1,3,4,8,‘Lajas’),(332,1,3,4,9,‘Macarena’),(333,1,3,4,10,‘Maiquetía’),(334,1,3,4,11,‘Méndez.’),(335,1,3,5,1,‘Acacias’),(336,1,3,5,2,‘Calle
Amador’),(337,1,3,5,3,‘Constancia’),(338,1,3,5,4,‘Churuca’),(339,1,3,5,5,‘Huetares’),(340,1,3,5,6,‘Plazoleta’),(341,1,3,5,7,‘Pueblo
Nuevo’),(342,1,3,5,8,‘Río
Damas’),(343,1,3,5,9,‘Rotondas’),(344,1,3,5,10,‘Solar.’),(345,1,3,6,1,‘Bajos
de Tarrazú’),(346,1,3,6,2,‘Bustamante’),(347,1,3,6,3,‘Santa Elena
(parte)’),(348,1,3,6,4,‘Violeta.’),(349,1,3,7,1,‘Aguacate’),(350,1,3,7,2,‘Balneario’),(351,1,3,7,3,‘Don
Bosco’),(352,1,3,7,4,‘Guatuso’),(353,1,3,7,5,‘Güízaro’),(354,1,3,7,6,‘Jerusalén’),(355,1,3,7,7,‘Lince’),(356,1,3,7,8,‘Mesas’),(357,1,3,7,9,‘Quebrada
Honda’),(358,1,3,7,10,‘Ticalpes (parte).’),(359,1,3,8,1,‘Empalme
(parte)’),(360,1,3,8,2,‘Lucha (parte)’),(361,1,3,8,3,‘San Cristóbal
Sur’),(362,1,3,8,4,‘Sierra.’),(363,1,3,9,1,‘Bajo
Tigre’),(364,1,3,9,2,‘Chirogres’),(365,1,3,9,3,‘Guadarrama’),(366,1,3,9,4,‘Joya’),(367,1,3,9,5,‘La
Fila (parte)’),(368,1,3,9,6,‘Llano bonito’),(369,1,3,9,7,‘Quebrada
Honda’),(370,1,3,9,8,‘Trinidad
(parte).’),(371,1,3,10,1,‘Cajita’),(372,1,3,10,2,‘Dorado’),(373,1,3,10,3,‘Dos
Cercas’),(374,1,3,10,4,‘Fomentera’),(375,1,3,10,5,‘Nuestra Señora de la
Esperanza’),(376,1,3,10,6,‘San
Lorenzo.’),(377,1,3,11,1,‘Ángeles’),(378,1,3,11,2,‘Autofores’),(379,1,3,11,3,‘Balboa’),(380,1,3,11,4,‘Coopelot’),(381,1,3,11,5,‘Gardenia’),(382,1,3,11,6,‘Higuerones’),(383,1,3,11,7,‘Leo’),(384,1,3,11,8,‘Mónaco’),(385,1,3,11,9,‘Sagitario’),(386,1,3,11,10,‘Santa
Cecilia’),(387,1,3,11,11,‘Tejar
(parte)’),(388,1,3,11,12,‘Treviso’),(389,1,3,11,13,‘Unidas’),(390,1,3,11,14,‘Valencia’),(391,1,3,11,15,‘Vizcaya.’),(392,1,3,12,1,‘Cartonera’),(393,1,3,12,2,‘Claveles’),(394,1,3,12,3,‘Damasco’),(395,1,3,12,4,‘Diamante’),(396,1,3,12,5,‘Esmeraldas’),(397,1,3,12,6,‘Fortuna’),(398,1,3,12,7,‘Fortunita’),(399,1,3,12,8,‘Porvenir’),(400,1,3,12,9,‘Raya’),(401,1,3,12,10,‘Riberalta’),(402,1,3,12,11,‘Villanueva.’),(403,1,3,13,1,‘Letras’),(404,1,3,13,2,‘Balcón
Verde.’),(405,1,4,1,1,‘Ángeles’),(406,1,4,1,0,‘Bajo
Badilla’),(407,1,4,1,2,‘Bajo Moras’),(408,1,4,1,0,‘Buenos
Aires’),(409,1,4,1,3,‘Cañales Abajo’),(410,1,4,1,0,‘Corazón de
María’),(411,1,4,1,4,‘Cañales
Arriba’),(412,1,4,1,0,‘Jarasal’),(413,1,4,1,5,‘Carit’),(414,1,4,1,0,‘Junquillo
Arriba’),(415,1,4,1,6,‘Charcón (parte)’),(416,1,4,1,0,‘Pueblo
Nuevo’),(417,1,4,1,7,‘Cirrí’),(418,1,4,1,0,‘San
Isidro.’),(419,1,4,1,8,‘Junquillo
Abajo’),(420,1,4,1,9,‘Pozos’),(421,1,4,1,10,‘San
Francisco’),(422,1,4,1,11,‘San
Martín’),(423,1,4,1,12,‘Zapote.’),(424,1,4,2,1,‘Alto
Palma’),(425,1,4,2,2,‘Bajo Lanas’),(426,1,4,2,3,‘Bajo
Legua’),(427,1,4,2,4,‘Bajo Legüita’),(428,1,4,2,5,‘Bajo
Quesada’),(429,1,4,2,6,‘Bocana’),(430,1,4,2,7,‘Carmona’),(431,1,4,2,8,‘Cerbatana’),(432,1,4,2,9,‘Charquillos’),(433,1,4,2,10,‘Jilgueral’),(434,1,4,2,11,‘Llano
Grande’),(435,1,4,2,12,‘Mercedes
Norte’),(436,1,4,2,13,‘Potenciana’),(437,1,4,2,14,‘Quebrada
Honda’),(438,1,4,2,15,‘Quivel’),(439,1,4,2,16,‘Rancho
Largo’),(440,1,4,2,17,‘Salitrales’),(441,1,4,2,18,‘Santa
Marta’),(442,1,4,2,19,‘Túfares’),(443,1,4,2,20,‘Tulín’),(444,1,4,2,21,‘Víbora’),(445,1,4,2,22,‘Zapotal.’),(446,1,4,3,1,‘Alto
Barbacoas’),(447,1,4,3,2,‘Bajo
Burgos’),(448,1,4,3,3,‘Cortezal’),(449,1,4,3,4,‘Guatuso’),(450,1,4,3,5,‘Piedades’),(451,1,4,3,6,‘San
Juan.’),(452,1,4,4,1,‘Cacao’),(453,1,4,4,2,‘Cuesta
Mora’),(454,1,4,4,3,‘Grifo
Bajo’),(455,1,4,4,4,‘Poró’),(456,1,4,4,5,‘Pueblo
Nuevo’),(457,1,4,4,6,‘Salitrillo.’),(458,1,4,5,1,‘Bijagual’),(459,1,4,5,2,‘Floralia’),(460,1,4,5,3,‘Punta
de Lanza’),(461,1,4,5,4,‘San Rafael Abajo.’),(462,1,4,6,1,‘Alto
Cebadilla’),(463,1,4,6,2,‘Bajo
Chacones’),(464,1,4,6,3,‘Copalar’),(465,1,4,6,4,‘Pedernal’),(466,1,4,6,5,‘Polca’),(467,1,4,6,6,‘Sabanas.’),(468,1,4,7,1,‘Bajo
Guevara’),(469,1,4,7,2,‘Planta’),(470,1,4,7,3,‘Rinconada.’),(471,1,4,8,1,‘Bajo
Herrera’),(472,1,4,8,2,‘Calle Herrera’),(473,1,4,8,3,‘Cruce
Guanacaste’),(474,1,4,8,4,‘Charcón
(parte)’),(475,1,4,8,5,‘Estero’),(476,1,4,8,6,‘Río
Viejo’),(477,1,4,8,7,‘Salitral’),(478,1,4,8,8,‘Tinamaste.’),(479,1,4,9,1,‘Alto
Concepción’),(480,1,4,9,2,‘Alto Pérez
Astúa’),(481,1,4,9,3,‘Ángeles’),(482,1,4,9,4,‘Angostura
(parte)’),(483,1,4,9,5,‘Arenal’),(484,1,4,9,6,‘Bajo
Chires’),(485,1,4,9,7,‘Bajo de Guarumal’),(486,1,4,9,8,‘Bajo el
Rey’),(487,1,4,9,9,‘Bajo
Vega’),(488,1,4,9,10,‘Cerdas’),(489,1,4,9,11,‘Fila
Aguacate’),(490,1,4,9,12,‘Gamalotillo 1
(Colonia)’),(491,1,4,9,13,‘Gamalotillo 2
(Gamalotillo)’),(492,1,4,9,14,‘Gamalotillo 3 (Tierra
Fértil)’),(493,1,4,9,15,‘Gloria’),(494,1,4,9,16,‘Guarumal’),(495,1,4,9,17,‘Guarumalito’),(496,1,4,9,18,‘Mastatal’),(497,1,4,9,19,‘Pericos’),(498,1,4,9,20,‘Río
Negro (parte)’),(499,1,4,9,21,‘San Miguel’),(500,1,4,9,22,‘San
Vicente’),(501,1,4,9,23,‘Santa Rosa’),(502,1,4,9,24,‘Vista de
Mar’),(503,1,4,9,25,‘Zapatón.’),(504,1,5,1,1,‘Corea’),(505,1,5,1,2,‘I
Griega’),(506,1,5,1,3,‘Las Tres Marías’),(507,1,5,1,4,‘Santa
Cecilia’),(508,1,5,1,5,‘Rodeo’),(509,1,5,1,6,‘Sitio.’),(510,1,5,1,7,‘Alto
Pastora’),(511,1,5,1,8,‘Bajo Canet’),(512,1,5,1,9,‘Bajo San
Juan’),(513,1,5,1,10,‘Canet’),(514,1,5,1,11,‘Cedral
(parte)’),(515,1,5,1,12,‘Guadalupe’),(516,1,5,1,13,‘Llano
Piedra’),(517,1,5,1,14,‘San Cayetano’),(518,1,5,1,15,‘San
Guillermo’),(519,1,5,1,16,‘Sabana (parte)’),(520,1,5,1,17,‘San
Pedro.’),(521,1,5,2,1,‘Alto Guarumal’),(522,1,5,2,2,‘Alto
Portal’),(523,1,5,2,3,‘Alto
Zapotal’),(524,1,5,2,4,‘Ardilla’),(525,1,5,2,5,‘Bajo Quebrada
Honda’),(526,1,5,2,6,‘Bajo Reyes’),(527,1,5,2,7,‘Bajo
Zapotal’),(528,1,5,2,8,‘Cerro
Nara’),(529,1,5,2,9,‘Concepción’),(530,1,5,2,10,‘Chilamate’),(531,1,5,2,11,‘Delicias’),(532,1,5,2,12,‘Esperanza’),(533,1,5,2,13,‘Esquipulas’),(534,1,5,2,14,‘La
Pacaya’),(535,1,5,2,15,‘Las Pavas’),(536,1,5,2,16,‘Mata de
Caña’),(537,1,5,2,17,‘Miramar’),(538,1,5,2,18,‘Nápoles’),(539,1,5,2,19,‘Naranjillo’),(540,1,5,2,20,‘Palma’),(541,1,5,2,21,‘Quebrada
Arroyo’),(542,1,5,2,22,‘Rodeo’),(543,1,5,2,23,‘Sabana
(parte)’),(544,1,5,2,24,‘Salado’),(545,1,5,2,25,‘San
Bernardo’),(546,1,5,2,26,‘San Francisco’),(547,1,5,2,27,‘San
Martín’),(548,1,5,2,28,‘Santa Cecilia’),(549,1,5,2,29,‘Santa
Marta’),(550,1,5,2,30,‘Santa
Rosa’),(551,1,5,2,31,‘Zapotal.’),(552,1,5,3,1,‘Alto
Chiral’),(553,1,5,3,2,‘San Juan (Alto San Juan)’),(554,1,5,3,3,‘Bajo
Jénaro’),(555,1,5,3,4,‘Bajo San
José’),(556,1,5,3,5,‘Jamaica’),(557,1,5,3,6,‘Quebrada Seca (Santa
Ana)’),(558,1,5,3,7,‘San Jerónimo’),(559,1,5,3,8,‘San
Josecito.’),(560,1,6,1,1,‘Guatuso’),(561,1,6,1,2,‘Mirador.’),(562,1,6,1,3,‘Alfonso
XIII’),(563,1,6,1,4,‘Barro’),(564,1,6,1,5,‘Cinco Esquinas
(parte)’),(565,1,6,1,6,‘Corazón de
Jesús’),(566,1,6,1,7,‘Guapinol’),(567,1,6,1,8,‘Las
Mercedes’),(568,1,6,1,9,‘Lomas de
Aserrí’),(569,1,6,1,10,‘Lourdes’),(570,1,6,1,11,‘María
Auxiliadora’),(571,1,6,1,12,‘Mesas’),(572,1,6,1,13,‘Poás’),(573,1,6,1,14,‘Santa
Rita’),(574,1,6,1,15,‘Sáurez’),(575,1,6,1,16,‘Tres
Marías’),(576,1,6,1,17,‘Vereda.’),(577,1,6,2,1,‘Bajos de
Praga’),(578,1,6,2,2,‘Máquina
Vieja’),(579,1,6,2,3,‘Tigre.’),(580,1,6,3,1,‘Calvario’),(581,1,6,3,2,‘Ceiba
Alta (parte)’),(582,1,6,3,3,‘Jocotal’),(583,1,6,3,4,‘Legua de
Naranjo’),(584,1,6,3,5,‘Mangos’),(585,1,6,3,6,‘Meseta’),(586,1,6,3,7,‘Monte
Redondo’),(587,1,6,3,8,‘Ojo de
Agua’),(588,1,6,3,9,‘Rosalía’),(589,1,6,3,10,‘Uruca.’),(590,1,6,4,1,‘La
Fila (parte)’),(591,1,6,4,0,‘Pueblo
Nuevo.’),(592,1,6,4,2,‘Limonal’),(593,1,6,4,3,‘Los
Solano’),(594,1,6,4,4,‘Rancho
Grande’),(595,1,6,4,5,‘Salitral’),(596,1,6,4,6,‘Tranquerillas’),(597,1,6,4,7,‘Trinidad
(parte)’),(598,1,6,4,8,‘Villanueva.’),(599,1,6,5,1,‘Alto
Buenavista’),(600,1,6,5,2,‘Altos del Aguacate’),(601,1,6,5,3,‘Bajo
Bijagual’),(602,1,6,5,4,‘Bajo Máquinas’),(603,1,6,5,5,‘Bajo Venegas
(parte)’),(604,1,6,5,6,‘Carmen (parte).’),(605,1,6,6,1,‘Ojo de Agua
(parte)’),(606,1,6,6,2,‘Portuguez.’),(607,1,6,7,1,‘Cinco Esquinas
(parte)’),(608,1,6,7,2,‘Santa
Teresita.’),(609,1,6,7,3,‘Cerro’),(610,1,6,7,4,‘Cuesta
Grande’),(611,1,6,7,5,‘Guinealillo’),(612,1,6,7,6,‘Huaso
(parte)’),(613,1,6,7,7,‘Lagunillas’),(614,1,6,7,8,‘Palo
Blanco’),(615,1,6,7,9,‘Quebradas’),(616,1,6,7,10,‘Rincón.’),(617,1,7,1,1,‘Alhambra’),(618,1,7,1,0,‘Cabriola’),(619,1,7,1,2,‘Brasil’),(620,1,7,1,0,‘Cedral’),(621,1,7,1,3,‘Carreras’),(622,1,7,1,0,‘Cuesta
Achiotal’),(623,1,7,1,4,‘Colonia del Prado’),(624,1,7,1,0,‘Llano
León’),(625,1,7,1,5,‘Llano
Limón’),(626,1,7,1,0,‘Michoacán’),(627,1,7,1,6,‘Nuevo
Brasil’),(628,1,7,1,0,‘Quebrada
Honda’),(629,1,7,1,7,‘Piñal’),(630,1,7,1,0,‘Rodeo’),(631,1,7,1,8,‘San
Bosco’),(632,1,7,1,0,‘Santísima Trinidad’),(633,1,7,1,9,‘San
Vicente’),(634,1,7,1,0,‘Ticufres.’),(635,1,7,1,10,‘Tablera.’),(636,1,7,2,1,‘Bajo
Claras’),(637,1,7,2,2,‘Bajo
Morado’),(638,1,7,2,3,‘Corrogres’),(639,1,7,2,4,‘Monte
Negro.’),(640,1,7,3,1,‘Bajo Bustamante’),(641,1,7,3,2,‘Bajo
Lima’),(642,1,7,3,3,‘Bajo
Loaiza’),(643,1,7,3,4,‘Cañas’),(644,1,7,3,5,‘Corralar’),(645,1,7,3,6,‘Morado’),(646,1,7,3,7,‘Piedras
Blancas’),(647,1,7,3,8,‘Salto.’),(648,1,7,4,1,‘Chile’),(649,1,7,4,2,‘Danta’),(650,1,7,4,3,‘Palma’),(651,1,7,4,4,‘Quebrada
Grande.’),(652,1,7,5,1,‘Cordel’),(653,1,7,5,2,‘Chucás’),(654,1,7,5,3,‘Jateo’),(655,1,7,5,4,‘Llano
Grande’),(656,1,7,5,5,‘Monte Frío
(Potrerillos).’),(657,1,7,6,1,‘Pito.’),(658,1,7,7,1,‘San
Juan’),(659,1,7,7,2,‘San Martín’),(660,1,7,7,3,‘Quebrada
Honda.’),(661,1,8,1,1,‘Árboles’),(662,1,8,1,2,‘Colonia del
Río’),(663,1,8,1,3,‘El Alto
(parte)’),(664,1,8,1,4,‘Fátima’),(665,1,8,1,5,‘Independencia’),(666,1,8,1,6,‘Jardín’),(667,1,8,1,7,‘Magnolia’),(668,1,8,1,8,‘Maravilla’),(669,1,8,1,9,‘Margarita’),(670,1,8,1,10,‘Minerva’),(671,1,8,1,11,‘Moreno
Cañas’),(672,1,8,1,12,‘Orquídea’),(673,1,8,1,13,‘Pilar
Jiménez’),(674,1,8,1,14,‘Rothe’),(675,1,8,1,15,‘San
Gerardo’),(676,1,8,1,16,‘Santa Cecilia’),(677,1,8,1,17,‘Santa
Eduvigis’),(678,1,8,1,18,‘Santo
Cristo’),(679,1,8,1,19,‘Unión’),(680,1,8,1,20,‘Yurusti.’),(681,1,8,2,1,‘Carlos
María Ulloa’),(682,1,8,2,2,‘San Francisco
(centro)’),(683,1,8,2,3,‘Tournón.’),(684,1,8,3,1,‘Calle Blancos
(centro)’),(685,1,8,3,2,‘Ciprés’),(686,1,8,3,3,‘Encanto’),(687,1,8,3,4,‘Esquivel
Bonilla’),(688,1,8,3,5,‘Montelimar’),(689,1,8,3,6,‘Pinos’),(690,1,8,3,7,‘San
Antonio’),(691,1,8,3,8,‘San Gabriel’),(692,1,8,3,9,‘Santo
Tomás’),(693,1,8,3,10,‘Volio.’),(694,1,8,4,1,‘Bruncas’),(695,1,8,4,2,‘Carmen’),(696,1,8,4,3,‘Claraval’),(697,1,8,4,4,‘Cruz’),(698,1,8,4,5,‘Cuesta
Grande (parte)’),(699,1,8,4,6,‘Estéfana
(parte)’),(700,1,8,4,7,‘Hortensias’),(701,1,8,4,8,‘Jaboncillal’),(702,1,8,4,9,‘Jardines
de la
Paz’),(703,1,8,4,10,‘Lourdes’),(704,1,8,4,11,‘Praderas’),(705,1,8,4,12,‘Tepeyac’),(706,1,8,4,13,‘Térraba’),(707,1,8,4,14,‘Villalta’),(708,1,8,4,15,‘Villaverde’),(709,1,8,4,16,‘Vista
del Valle.’),(710,1,8,5,1,‘Ángeles’),(711,1,8,5,2,‘El Alto
(parte)’),(712,1,8,5,3,‘Floresta’),(713,1,8,5,4,‘Korobó’),(714,1,8,5,5,‘La
Mora’),(715,1,8,5,6,‘Morita’),(716,1,8,5,7,‘Mozotal’),(717,1,8,5,8,‘Nazareno’),(718,1,8,5,9,‘Orquídea’),(719,1,8,5,10,‘Rodrigo
Facio’),(720,1,8,5,11,‘Santa Clara
(parte)’),(721,1,8,5,12,‘Setillal’),(722,1,8,5,13,‘Vista del
Monte.’),(723,1,8,6,1,‘Mirador.’),(724,1,8,6,2,‘Corralillo’),(725,1,8,6,3,‘Guayabillos’),(726,1,8,6,4,‘Isla’),(727,1,8,6,5,‘San
Miguel’),(728,1,8,6,0,‘Vista de Mar.’),(729,1,8,7,1,‘Ana
Frank’),(730,1,8,7,2,‘Castores’),(731,1,8,7,3,‘Cuadros’),(732,1,8,7,4,‘Don
Carlos’),(733,1,8,7,5,‘El Alto (parte)’),(734,1,8,7,6,‘Flor de
Liz’),(735,1,8,7,7,‘Kurú’),(736,1,8,7,8,‘Lomas de
Tepeyac’),(737,1,8,7,9,‘Lupita’),(738,1,8,7,10,‘Montesol’),(739,1,8,7,11,‘Nogales’),(740,1,8,7,12,‘Pueblo’),(741,1,8,7,13,‘Violetas.’),(742,1,9,1,1,‘Aguas
Lindas’),(743,1,9,1,2,‘Cabañas’),(744,1,9,1,3,‘Casa
Blanca’),(745,1,9,1,4,‘Lajas
(parte)’),(746,1,9,1,5,‘Obando’),(747,1,9,1,6,‘Paso Machete
(parte)’),(748,1,9,1,7,‘San
Rafael.’),(749,1,9,1,8,‘Corrogres’),(750,1,9,1,9,‘Pilas.’),(751,1,9,2,1,‘Paso
Machete (parte)’),(752,1,9,2,2,‘Robalillo.’),(753,1,9,2,3,‘Alto
Raicero’),(754,1,9,2,4,‘Chirracal’),(755,1,9,2,5,‘Matinilla’),(756,1,9,2,6,‘Pabellón’),(757,1,9,2,7,‘Perico.’),(758,1,9,3,1,‘Alto
Palomas’),(759,1,9,3,2,‘Concepción’),(760,1,9,3,3,‘Cuevas’),(761,1,9,3,4,‘Chispa’),(762,1,9,3,5,‘Gavilanes’),(763,1,9,3,6,‘Honduras’),(764,1,9,3,7,‘Lajas
(parte)’),(765,1,9,3,8,‘Lindora’),(766,1,9,3,9,‘Manantial’),(767,1,9,3,10,‘Real
de Pereira (parte)’),(768,1,9,3,11,‘Valle del
sol.’),(769,1,9,4,1,‘Chimba’),(770,1,9,4,2,‘Guapinol’),(771,1,9,4,3,‘Mata
Grande’),(772,1,9,4,4,‘Mina’),(773,1,9,4,5,‘Paso Machete
(parte)’),(774,1,9,4,6,‘Río
Uruca.’),(775,1,9,5,1,‘Caraña’),(776,1,9,5,2,‘Cebadilla’),(777,1,9,5,3,‘Finquitas’),(778,1,9,5,4,‘Montaña
del Sol’),(779,1,9,5,5,‘Rincón San
Marcos’),(780,1,9,5,6,‘Triunfo.’),(781,1,9,6,1,‘Canjel’),(782,1,9,6,2,‘Copey.’),(783,1,10,1,1,‘Bellavista’),(784,1,10,1,2,‘Chorotega’),(785,1,10,1,3,‘Lagunilla’),(786,1,10,1,4,‘Macha’),(787,1,10,1,5,‘Madrigal.’),(788,1,10,2,1,‘Aguilar
Machado’),(789,1,10,2,2,‘Cochea’),(790,1,10,2,3,‘El
Alto’),(791,1,10,2,4,‘Faro del
Suroeste’),(792,1,10,2,5,‘Filtros’),(793,1,10,2,6,‘Pueblo
Escondido’),(794,1,10,2,7,‘Vistas de
Alajuelita.’),(795,1,10,3,1,‘Caracas’),(796,1,10,3,2,‘Cascabela’),(797,1,10,3,3,‘Llano’),(798,1,10,3,4,‘Piedra
de
fuego.’),(799,1,10,3,5,‘Lámparas.’),(800,1,10,4,1,‘Almendros’),(801,1,10,4,2,‘Boca
del Monte’),(802,1,10,4,3,‘Chirivico’),(803,1,10,4,4,‘Monte
Alto’),(804,1,10,4,5,‘Once de
Abril’),(805,1,10,4,6,‘Progreso’),(806,1,10,4,7,‘Tejar
(parte)’),(807,1,10,4,8,‘Vista de San José’),(808,1,10,4,9,‘Vista
Real.’),(809,1,10,5,1,‘Aurora’),(810,1,10,5,2,‘Corina
Rodríguez’),(811,1,10,5,3,‘Esquipulas Dos’),(812,1,10,5,4,‘La
Guápil’),(813,1,10,5,5,‘Peralta’),(814,1,10,5,6,‘Verbena.’),(815,1,10,5,7,‘Palo
Campano.’),(816,1,11,1,1,‘Alamos’),(817,1,11,1,2,‘Alpes’),(818,1,11,1,3,‘Arias’),(819,1,11,1,4,‘Brisa’),(820,1,11,1,5,‘Cedros’),(821,1,11,1,6,‘Corazón
de Jesús’),(822,1,11,1,7,‘Durazno’),(823,1,11,1,8,‘Girasoles
(parte)’),(824,1,11,1,9,‘Huacas’),(825,1,11,1,10,‘Magnolias’),(826,1,11,1,11,‘Mercedes’),(827,1,11,1,12,‘Monte
Azul’),(828,1,11,1,13,‘San Francisco’),(829,1,11,1,14,‘San
Martín’),(830,1,11,1,15,‘Villa
Solidarista.’),(831,1,11,2,1,‘Fanguillo’),(832,1,11,2,2,‘I
Griega’),(833,1,11,2,3,‘Loma
Bonita’),(834,1,11,2,4,‘Nubes’),(835,1,11,2,5,‘Patio de
Agua’),(836,1,11,2,6,‘Villa
Emaus.’),(837,1,11,3,1,‘Calera’),(838,1,11,3,2,‘Carmen’),(839,1,11,3,3,‘Gemelas’),(840,1,11,3,4,‘Josué’),(841,1,11,3,5,‘Manzanos’),(842,1,11,3,6,‘Murtal’),(843,1,11,3,7,‘Sitrae’),(844,1,11,3,8,‘Valle
Felíz.’),(845,1,11,3,9,‘Alto Palma
(parte)’),(846,1,11,3,10,‘Platanares’),(847,1,11,3,11,‘Rodeo
(parte).’),(848,1,11,4,1,‘Cornelia’),(849,1,11,4,2,‘Girasoles
(parte)’),(850,1,11,4,3,‘Horizontes’),(851,1,11,4,4,‘Irazú’),(852,1,11,4,5,‘Jardines’),(853,1,11,4,6,‘Labrador’),(854,1,11,4,7,‘Patalillo’),(855,1,11,4,8,‘Santa
Marta’),(856,1,11,4,9,‘Trapiche’),(857,1,11,4,10,‘Villalinda’),(858,1,11,4,11,‘Villanova.’),(859,1,11,5,1,‘Avilés’),(860,1,11,5,2,‘Cerro
Indio’),(861,1,11,5,3,‘Guaba’),(862,1,11,5,4,‘Rojizo’),(863,1,11,5,5,‘Sinaí.’),(864,1,11,5,6,‘Canoa’),(865,1,11,5,7,‘Cascajal’),(866,1,11,5,8,‘Choco’),(867,1,11,5,9,‘Isla’),(868,1,11,5,10,‘Monserrat’),(869,1,11,5,11,‘Patillos’),(870,1,11,5,12,‘Rodeo
(parte)’),(871,1,11,5,13,‘Santa Rita de Casia’),(872,1,11,5,14,‘Tierras
Morenas’),(873,1,11,5,15,‘Vegas de
Cajón’),(874,1,11,5,16,‘Venita.’),(875,1,12,1,1,‘Abarca’),(876,1,12,1,2,‘Corral’),(877,1,12,1,3,‘María
Auxiliadora’),(878,1,12,1,4,‘Ortiga’),(879,1,12,1,5,‘Pozos’),(880,1,12,1,6,‘San
Martín (San Gerardo)’),(881,1,12,1,7,‘San
Luis’),(882,1,12,1,8,‘Turrujal’),(883,1,12,1,9,‘Vereda.’),(884,1,12,1,10,‘Aguablanca
(parte)’),(885,1,12,1,11,‘Alto Escalera’),(886,1,12,1,12,‘Alto Los
Mora’),(887,1,12,1,13,‘Ángeles’),(888,1,12,1,14,‘Chirraca
(parte)’),(889,1,12,1,15,‘Esperanza’),(890,1,12,1,16,‘Potrerillos’),(891,1,12,1,17,‘Resbalón’),(892,1,12,1,18,‘Tablazo.’),(893,1,12,2,1,‘Alto
Sierra’),(894,1,12,2,2,‘Alto Vigía’),(895,1,12,2,3,‘Bajo
Arias’),(896,1,12,2,4,‘Bajo Bermúdez’),(897,1,12,2,5,‘Bajo
Calvo’),(898,1,12,2,6,‘Bajo Cárdenas’),(899,1,12,2,7,‘Bajo
Moras’),(900,1,12,2,8,‘Coyolar’),(901,1,12,2,9,‘Hondonada’),(902,1,12,2,10,‘La
Cruz’),(903,1,12,2,11,‘Lagunillas
(parte)’),(904,1,12,2,12,‘Ococa’),(905,1,12,2,13,‘Toledo’),(906,1,12,2,14,‘Zapote.’),(907,1,12,3,1,‘San
Pablo’),(908,1,12,3,2,‘Agua Blanca (parte)’),(909,1,12,3,3,‘Bajo
Cerdas’),(910,1,12,3,4,‘Bajos de
Jorco’),(911,1,12,3,5,‘Bolívar’),(912,1,12,3,6,‘Cañadas’),(913,1,12,3,7,‘Caragral’),(914,1,12,3,8,‘Corazón
de Jesús’),(915,1,12,3,9,‘Charcalillo’),(916,1,12,3,10,‘Chirraca
(parte)’),(917,1,12,3,11,‘Fila’),(918,1,12,3,12,‘Jaular’),(919,1,12,3,13,‘Lagunillas
(parte)’),(920,1,12,3,14,‘La Mina’),(921,1,12,3,15,‘La
Pita’),(922,1,12,3,16,‘Los
Monge’),(923,1,12,3,17,‘Playa’),(924,1,12,3,18,‘San
Pablo’),(925,1,12,3,19,‘Sevilla.’),(926,1,12,4,1,‘Bajo Los
Cruces’),(927,1,12,4,2,‘Ceiba Alta (parte)’),(928,1,12,4,3,‘Ceiba
Baja’),(929,1,12,4,4,‘Ceiba
Este’),(930,1,12,4,5,‘Escuadra’),(931,1,12,4,6,‘Gravilias’),(932,1,12,4,7,‘Lindavista’),(933,1,12,4,8,‘Llano
Bonito’),(934,1,12,4,9,‘Mesa’),(935,1,12,4,10,‘Naranjal’),(936,1,12,4,11,‘Perpetuo
Socorro’),(937,1,12,4,12,‘Tejar’),(938,1,12,4,13,‘Tiquires.’),(939,1,12,5,1,‘Alto
Parritón’),(940,1,12,5,2,‘Bajo Palma’),(941,1,12,5,3,‘Bajo
Pérez’),(942,1,12,5,4,‘Bijagual’),(943,1,12,5,5,‘Breñón’),(944,1,12,5,6,‘Caspirola’),(945,1,12,5,7,‘Colorado’),(946,1,12,5,8,‘Cuesta
Aguacate’),(947,1,12,5,9,‘Limas’),(948,1,12,5,10,‘Parritón’),(949,1,12,5,11,‘Plomo’),(950,1,12,5,12,‘Sabanas’),(951,1,12,5,13,‘San
Jerónimo’),(952,1,12,5,14,‘Soledad’),(953,1,12,5,15,‘Téruel’),(954,1,12,5,16,‘Tiquiritos’),(955,1,12,5,17,‘Uruca’),(956,1,12,5,18,‘Zoncuano.’),(957,1,13,1,1,‘Acacias’),(958,1,13,1,2,‘Arboleda’),(959,1,13,1,3,‘Asturias’),(960,1,13,1,4,‘Estudiantes’),(961,1,13,1,5,‘Florida’),(962,1,13,1,6,‘González
Truque’),(963,1,13,1,7,‘Jesús Jiménez
Zamora’),(964,1,13,1,8,‘Lindavista’),(965,1,13,1,9,‘Rosas’),(966,1,13,1,10,‘San
Jerónimo’),(967,1,13,1,11,‘Santa
Eduvigis’),(968,1,13,1,12,‘Valle’),(969,1,13,1,13,‘Versalles’),(970,1,13,1,14,‘Villafranca’),(971,1,13,1,15,‘Virginia.’),(972,1,13,2,1,‘Bajo
Piuses’),(973,1,13,2,2,‘Copey’),(974,1,13,2,3,‘Leiva
Urcuyo’),(975,1,13,2,4,‘Lilas’),(976,1,13,2,5,‘Lomas del
Pinar’),(977,1,13,2,6,‘Montecarlo’),(978,1,13,2,7,‘Santa
Teresa.’),(979,1,13,3,1,‘Apolo’),(980,1,13,3,2,‘Dalia’),(981,1,13,3,3,‘Estancia’),(982,1,13,3,4,‘Fletcher’),(983,1,13,3,5,‘Franjo’),(984,1,13,3,6,‘Jardines
de Tibás’),(985,1,13,3,7,‘Jardines La
Trinidad’),(986,1,13,3,8,‘Monterreal’),(987,1,13,3,9,‘Palmeras’),(988,1,13,3,10,‘Santa
Mónica’),(989,1,13,3,11,‘Talamanca’),(990,1,13,3,12,‘Vergel.’),(991,1,13,4,1,‘Doña
Fabiola’),(992,1,13,4,2,‘Garabito.’),(993,1,13,5,1,‘Anselmo
Alvarado’),(994,1,13,5,2,‘Balsa’),(995,1,13,5,3,‘Cuatro
Reinas’),(996,1,13,5,4,‘Orquídeas’),(997,1,13,5,5,‘Rey’),(998,1,13,5,6,‘San
Agustín.’),(999,1,14,1,1,‘Alondra’),(1000,1,14,1,2,‘Americano’),(1001,1,14,1,3,‘Américas’),(1002,1,14,1,4,‘Bajo
Isla’),(1003,1,14,1,5,‘Bajo Varelas’),(1004,1,14,1,6,‘Barro de
Olla’),(1005,1,14,1,7,‘Caragua’),(1006,1,14,1,8,‘Carmen’),(1007,1,14,1,9,‘Colegios’),(1008,1,14,1,10,‘Colegios
Norte’),(1009,1,14,1,11,‘Chaves’),(1010,1,14,1,12,‘El Alto
(parte)’),(1011,1,14,1,13,‘Flor’),(1012,1,14,1,14,‘Florencia’),(1013,1,14,1,15,‘Guaria’),(1014,1,14,1,16,‘Guaria
Oriental’),(1015,1,14,1,17,‘Isla’),(1016,1,14,1,18,‘Jardines de
Moravia’),(1017,1,14,1,19,‘La
Casa’),(1018,1,14,1,20,‘Ladrillera’),(1019,1,14,1,21,‘Robles’),(1020,1,14,1,22,‘Romeral’),(1021,1,14,1,23,‘Sagrado
Corazón’),(1022,1,14,1,24,‘San Blas’),(1023,1,14,1,25,‘San
Jorge’),(1024,1,14,1,26,‘San Martín’),(1025,1,14,1,27,‘San
Rafael’),(1026,1,14,1,28,‘Santa Clara (parte)’),(1027,1,14,1,29,‘Santo
Tomás’),(1028,1,14,1,30,‘Saprissa.’),(1029,1,14,2,1,‘Alto Palma
(parte)’),(1030,1,14,2,2,‘Platanares’),(1031,1,14,2,3,‘Tornillal’),(1032,1,14,2,4,‘Torre.’),(1033,1,14,3,1,‘Altos
de
Trinidad’),(1034,1,14,3,2,‘Cipreses’),(1035,1,14,3,3,‘Colonia’),(1036,1,14,3,4,‘Moral’),(1037,1,14,3,5,‘Níspero’),(1038,1,14,3,6,‘Rosal’),(1039,1,14,3,7,‘Ruano’),(1040,1,14,3,8,‘Sitios’),(1041,1,14,3,9,‘Tanques’),(1042,1,14,3,10,‘Virilla.’),(1043,1,15,1,1,‘Alhambra’),(1044,1,15,1,2,‘Azáleas’),(1045,1,15,1,3,‘Carmiol’),(1046,1,15,1,4,‘Cedral’),(1047,1,15,1,5,‘Dent
(parte)’),(1048,1,15,1,6,‘Francisco Peralta
(parte)’),(1049,1,15,1,7,‘Fuentes’),(1050,1,15,1,8,‘Granja’),(1051,1,15,1,9,‘Kezia’),(1052,1,15,1,10,‘Lourdes’),(1053,1,15,1,11,‘Monterrey’),(1054,1,15,1,12,‘Nadori’),(1055,1,15,1,13,‘Oriente’),(1056,1,15,1,14,‘Pinto’),(1057,1,15,1,15,‘Prados
del Este’),(1058,1,15,1,16,‘Roosevelt’),(1059,1,15,1,17,‘San Gerardo
(parte)’),(1060,1,15,1,18,‘Santa
Marta’),(1061,1,15,1,19,‘Saprissa’),(1062,1,15,1,20,‘Vargas
Araya’),(1063,1,15,1,21,‘Yoses.’),(1064,1,15,2,1,‘Arboleda’),(1065,1,15,2,2,‘Bloquera’),(1066,1,15,2,3,‘Cedros’),(1067,1,15,2,4,‘El
Cristo
(parte)’),(1068,1,15,2,5,‘Españolita’),(1069,1,15,2,6,‘Luciana’),(1070,1,15,2,7,‘Marsella’),(1071,1,15,2,8,‘Maravilla’),(1072,1,15,2,9,‘Rodeo’),(1073,1,15,2,10,‘Rosales’),(1074,1,15,2,11,‘San
Marino.’),(1075,1,15,3,1,‘Alma
Máter’),(1076,1,15,3,2,‘Damiana’),(1077,1,15,3,3,‘Dent
(parte)’),(1078,1,15,3,4,‘Guaymí’),(1079,1,15,3,5,‘Paso
Hondo’),(1080,1,15,3,6,‘Paulina.’),(1081,1,15,4,1,‘Alameda’),(1082,1,15,4,2,‘Andrómeda’),(1083,1,15,4,3,‘Begonia’),(1084,1,15,4,4,‘Cuesta
Grande (parte)’),(1085,1,15,4,5,‘El Cristo
(parte)’),(1086,1,15,4,6,‘Estéfana
(parte)’),(1087,1,15,4,7,‘Europa’),(1088,1,15,4,8,‘Liburgia’),(1089,1,15,4,9,‘Mansiones
(parte)’),(1090,1,15,4,10,‘Maruz’),(1091,1,15,4,11,‘Salitrillos.’),(1092,1,16,1,1,‘Alto
Poró’),(1093,1,16,1,2,‘Bolsón’),(1094,1,16,1,3,‘Purires.’),(1095,1,16,2,1,‘Alto
Limón’),(1096,1,16,2,2,‘Florecilla’),(1097,1,16,2,3,‘Limón’),(1098,1,16,2,4,‘Palmar’),(1099,1,16,2,5,‘Pita
Villa Colina.’),(1100,1,16,3,1,‘Bajo Laguna’),(1101,1,16,3,2,‘El
Barro’),(1102,1,16,3,3,‘Llano Bonito (San Juan de
Mata)’),(1103,1,16,3,4,‘Molenillo’),(1104,1,16,3,5,‘Paso
Agres’),(1105,1,16,3,6,‘Surtubal’),(1106,1,16,3,7,‘Tronco
Negro.’),(1107,1,16,4,1,‘Pital’),(1108,1,16,4,2,‘Potenciana
Arriba’),(1109,1,16,4,3,‘Quebrada Azul’),(1110,1,16,4,4,‘San
Francisco’),(1111,1,16,4,5,‘San Rafael.’),(1112,1,16,5,1,‘Alto
Espavel’),(1113,1,16,5,2,‘Angostura
(parte)’),(1114,1,16,5,3,‘Bijagualito’),(1115,1,16,5,4,‘Bola’),(1116,1,16,5,5,‘Carara’),(1117,1,16,5,6,‘Cima’),(1118,1,16,5,7,‘Delicias’),(1119,1,16,5,8,‘El
Sur’),(1120,1,16,5,9,‘Esperanza’),(1121,1,16,5,10,‘Fila
Negra’),(1122,1,16,5,11,‘Galán’),(1123,1,16,5,12,‘La
Trampa’),(1124,1,16,5,13,‘Lajas’),(1125,1,16,5,14,‘Mata de
Platano’),(1126,1,16,5,15,‘Montelimar’),(1127,1,16,5,16,‘Pacayal’),(1128,1,16,5,17,‘Pavona’),(1129,1,16,5,18,‘Quina’),(1130,1,16,5,19,‘Río
Negro’),(1131,1,16,5,20,‘Río Seco’),(1132,1,16,5,21,‘San
Francísco’),(1133,1,16,5,22,‘San Gabriel’),(1134,1,16,5,23,‘San
Isidro’),(1135,1,16,5,24,‘San
Jerónimo’),(1136,1,16,5,25,‘Tulín.’),(1137,1,17,1,1,‘Bandera’),(1138,1,17,1,2,‘Higueronal’),(1139,1,17,1,3,‘Llano’),(1140,1,17,1,4,‘Nubes’),(1141,1,17,1,5,‘San
Rafael.’),(1142,1,17,1,6,‘Botella’),(1143,1,17,1,7,‘Cedral’),(1144,1,17,1,8,‘Guaria’),(1145,1,17,1,9,‘Naranjo’),(1146,1,17,1,10,‘Quebrada
Grande (parte)’),(1147,1,17,1,11,‘Reyes’),(1148,1,17,1,12,‘San
Joaquín’),(1149,1,17,1,13,‘San
Lucas’),(1150,1,17,1,14,‘Sukia’),(1151,1,17,1,15,‘Vapor.’),(1152,1,17,2,1,‘Cabeceras
de Tarrazú’),(1153,1,17,2,2,‘Chonta
(parte)’),(1154,1,17,2,3,‘Quebradillas.’),(1155,1,17,3,1,‘Bernardo
Ureña.’),(1156,1,17,3,2,‘Alto Cañazo’),(1157,1,17,3,3,‘Alto
indio’),(1158,1,17,3,4,‘Alto Miramar’),(1159,1,17,3,5,‘Cañón
(parte)’),(1160,1,17,3,6,‘Cima’),(1161,1,17,3,7,‘Cruce
Chinchilla’),(1162,1,17,3,8,‘Florida’),(1163,1,17,3,9,‘Garrafa’),(1164,1,17,3,10,‘Jaboncillo’),(1165,1,17,3,11,‘Madreselva’),(1166,1,17,3,12,‘Ojo
de Agua (parte)’),(1167,1,17,3,13,‘Paso Macho
(parte)’),(1168,1,17,3,14,‘Pedregoso’),(1169,1,17,3,15,‘Providencia’),(1170,1,17,3,16,‘Quebrada
Grande (parte)’),(1171,1,17,3,17,‘Quebrador’),(1172,1,17,3,18,‘Río
Blanco’),(1173,1,17,3,19,‘Salsipuedes (parte)’),(1174,1,17,3,20,‘San
Carlos’),(1175,1,17,3,21,‘San
Gerardo’),(1176,1,17,3,22,‘Trinidad’),(1177,1,17,3,23,‘Vueltas.’),(1178,1,18,1,1,‘Ahogados
(parte)’),(1179,1,18,1,2,‘Aromático’),(1180,1,18,1,3,‘Cipreses’),(1181,1,18,1,4,‘Chapultepec’),(1182,1,18,1,5,‘Dorados’),(1183,1,18,1,6,‘Guayabos’),(1184,1,18,1,7,‘Hacienda
Vieja’),(1185,1,18,1,8,‘Hogar’),(1186,1,18,1,9,‘José María
Zeledón’),(1187,1,18,1,10,‘Laguna’),(1188,1,18,1,11,‘La
Lía’),(1189,1,18,1,12,‘Mallorca’),(1190,1,18,1,13,‘María
Auxiliadora’),(1191,1,18,1,14,‘Miramontes’),(1192,1,18,1,15,‘Nopalera’),(1193,1,18,1,16,‘Plaza
del Sol’),(1194,1,18,1,17,‘Prado’),(1195,1,18,1,18,‘San
José’),(1196,1,18,1,19,‘Santa
Cecilia’),(1197,1,18,1,20,‘Tacaco.’),(1198,1,18,2,1,‘Biarquiria’),(1199,1,18,2,2,‘Eucalipto’),(1200,1,18,2,3,‘Freses’),(1201,1,18,2,4,‘Granadilla
Norte’),(1202,1,18,2,5,‘Granadilla Sur’),(1203,1,18,2,6,‘Montaña Rusa
(parte).’),(1204,1,18,3,1,‘Araucauria (parte)’),(1205,1,18,3,2,‘Lomas de
Ayarco’),(1206,1,18,3,3,‘Pinares.’),(1207,1,18,4,1,‘Colina’),(1208,1,18,4,2,‘Lomas
de San Pancracio’),(1209,1,18,4,3,‘Ponderosa’),(1210,1,18,4,4,‘Quince de
Agosto.’),(1211,1,19,1,1,‘Aeropuerto’),(1212,1,19,1,2,‘Alto
Alonso’),(1213,1,19,1,3,‘Boruca’),(1214,1,19,1,4,‘Boston’),(1215,1,19,1,5,‘Cementerio’),(1216,1,19,1,6,‘Cooperativa’),(1217,1,19,1,7,‘Cristo
Rey’),(1218,1,19,1,8,‘Doce de
Marzo’),(1219,1,19,1,9,‘Dorotea’),(1220,1,19,1,10,‘Durán
Picado’),(1221,1,19,1,11,‘España’),(1222,1,19,1,12,‘Estadio’),(1223,1,19,1,13,‘Evans
Gordon
Wilson’),(1224,1,19,1,14,‘González’),(1225,1,19,1,15,‘Hospital’),(1226,1,19,1,16,‘Hoyón’),(1227,1,19,1,17,‘I
Griega’),(1228,1,19,1,18,‘Las Américas’),(1229,1,19,1,19,‘Lomas de
Cocorí’),(1230,1,19,1,20,‘Luis
Monge’),(1231,1,19,1,21,‘Morazán’),(1232,1,19,1,22,‘Pavones’),(1233,1,19,1,23,‘Pedregoso’),(1234,1,19,1,24,‘Pocito’),(1235,1,19,1,25,‘Prado’),(1236,1,19,1,26,‘Romero’),(1237,1,19,1,27,‘Sagrada
Familia’),(1238,1,19,1,28,‘San Andrés’),(1239,1,19,1,29,‘San
Luis’),(1240,1,19,1,30,‘San Rafael Sur’),(1241,1,19,1,31,‘San
Vicente’),(1242,1,19,1,32,‘Santa
Cecilia’),(1243,1,19,1,33,‘Sinaí’),(1244,1,19,1,34,‘Tierra
Prometida’),(1245,1,19,1,35,‘Tormenta’),(1246,1,19,1,36,‘Unesco’),(1247,1,19,1,37,‘Valverde.’),(1248,1,19,1,38,‘Alto
Ceibo’),(1249,1,19,1,39,‘Alto Huacas’),(1250,1,19,1,40,‘Alto
Sajaral’),(1251,1,19,1,41,‘Alto San Juan’),(1252,1,19,1,42,‘Alto
Tumbas’),(1253,1,19,1,43,‘Angostura’),(1254,1,19,1,44,‘Bajo
Ceibo’),(1255,1,19,1,45,‘Bajo Esperanzas’),(1256,1,19,1,46,‘Bajo
Mora’),(1257,1,19,1,47,‘Bijaguales’),(1258,1,19,1,48,‘Bocana’),(1259,1,19,1,49,‘Bonita’),(1260,1,19,1,50,‘Ceibo’),(1261,1,19,1,51,‘Ceniza’),(1262,1,19,1,52,‘Dorado’),(1263,1,19,1,53,‘Esperanzas’),(1264,1,19,1,54,‘Guadalupe’),(1265,1,19,1,55,‘Guaria’),(1266,1,19,1,56,‘Higuerones’),(1267,1,19,1,57,‘Jilguero’),(1268,1,19,1,58,‘Jilguero
Sur’),(1269,1,19,1,59,‘Los Guayabos’),(1270,1,19,1,60,‘María
Auxiliadora’),(1271,1,19,1,61,‘Miravalles’),(1272,1,19,1,62,‘Morete’),(1273,1,19,1,63,‘Ojo
de Agua’),(1274,1,19,1,64,‘Ocho de
Diciembre’),(1275,1,19,1,65,‘Pacuarito’),(1276,1,19,1,66,‘Palma’),(1277,1,19,1,67,‘Paso
Beita’),(1278,1,19,1,68,‘Paso Lagarto’),(1279,1,19,1,69,‘Quebrada Honda
(parte)’),(1280,1,19,1,70,‘Quebrada
Vueltas’),(1281,1,19,1,71,‘Quebradas’),(1282,1,19,1,72,‘Roble’),(1283,1,19,1,73,‘Rosario’),(1284,1,19,1,74,‘San
Agustín’),(1285,1,19,1,75,‘San Jorge’),(1286,1,19,1,76,‘San Juan de
Miramar’),(1287,1,19,1,77,‘San Lorenzo’),(1288,1,19,1,78,‘San Rafael
Norte’),(1289,1,19,1,79,‘Santa Fé’),(1290,1,19,1,80,‘Santa
Marta’),(1291,1,19,1,81,‘Suiza
(parte)’),(1292,1,19,1,82,‘Tajo’),(1293,1,19,1,83,‘Toledo’),(1294,1,19,1,84,‘Tronconales’),(1295,1,19,1,85,‘Tuis’),(1296,1,19,1,86,‘Villanueva.’),(1297,1,19,2,1,‘Arepa’),(1298,1,19,2,2,‘Carmen’),(1299,1,19,2,3,‘Chanchos’),(1300,1,19,2,4,‘Hermosa’),(1301,1,19,2,5,‘Linda’),(1302,1,19,2,6,‘Linda
Arriba’),(1303,1,19,2,7,‘Miraflores’),(1304,1,19,2,8,‘Paraíso de la
Tierra’),(1305,1,19,2,9,‘Peñas Blancas’),(1306,1,19,2,10,‘Quizarrá
(parte)’),(1307,1,19,2,11,‘San Blas’),(1308,1,19,2,12,‘San
Luis’),(1309,1,19,2,13,‘Santa Cruz’),(1310,1,19,2,14,‘Santa
Elena’),(1311,1,19,2,15,‘Trinidad.’),(1312,1,19,3,1,‘Alto
Brisas’),(1313,1,19,3,2,‘Ángeles’),(1314,1,19,3,3,‘Aurora’),(1315,1,19,3,4,‘Chiles’),(1316,1,19,3,5,‘Crematorio’),(1317,1,19,3,6,‘Daniel
Flores’),(1318,1,19,3,7,‘Laboratorio’),(1319,1,19,3,8,‘Los
Pinos’),(1320,1,19,3,9,‘Loma
Verde’),(1321,1,19,3,10,‘Lourdes’),(1322,1,19,3,11,‘Rosas’),(1323,1,19,3,12,‘Rosa
Iris’),(1324,1,19,3,13,‘San Francisco’),(1325,1,19,3,14,‘Santa
Margarita’),(1326,1,19,3,15,‘Trocha’),(1327,1,19,3,16,‘Villa
Ligia.’),(1328,1,19,3,17,‘Aguas Buenas (parte)’),(1329,1,19,3,18,‘Bajos
de Pacuar’),(1330,1,19,3,19,‘Concepción
(parte)’),(1331,1,19,3,20,‘Corazón de Jesús’),(1332,1,19,3,21,‘Juntas de
Pacuar’),(1333,1,19,3,22,‘Paso Bote’),(1334,1,19,3,23,‘Patio de
Agua’),(1335,1,19,3,24,‘Peje’),(1336,1,19,3,25,‘Percal’),(1337,1,19,3,26,‘Pinar
del Río’),(1338,1,19,3,27,‘Quebrada Honda
(parte)’),(1339,1,19,3,28,‘Repunta’),(1340,1,19,3,29,‘Reyes’),(1341,1,19,3,30,‘Ribera’),(1342,1,19,3,31,‘Suiza
(parte).’),(1343,1,19,4,1,‘Linda
Vista’),(1344,1,19,4,2,‘Lourdes.’),(1345,1,19,4,3,‘Alaska’),(1346,1,19,4,4,‘Altamira’),(1347,1,19,4,5,‘Alto
Jaular’),(1348,1,19,4,6,‘Ángeles’),(1349,1,19,4,7,‘Boquete’),(1350,1,19,4,8,‘Buenavista’),(1351,1,19,4,9,‘Canaán’),(1352,1,19,4,10,‘Chimirol’),(1353,1,19,4,11,‘Chispa’),(1354,1,19,4,12,‘Chuma’),(1355,1,19,4,13,‘División
(parte)’),(1356,1,19,4,14,‘Guadalupe’),(1357,1,19,4,15,‘Herradura’),(1358,1,19,4,16,‘La
Bambú’),(1359,1,19,4,17,‘Los
Monges’),(1360,1,19,4,18,‘Monterrey’),(1361,1,19,4,19,‘Palmital’),(1362,1,19,4,20,‘Piedra
Alta’),(1363,1,19,4,21,‘Playa
Quesada’),(1364,1,19,4,22,‘Playas’),(1365,1,19,4,23,‘Pueblo
Nuevo’),(1366,1,19,4,24,‘Río Blanco’),(1367,1,19,4,25,‘San
Antonio’),(1368,1,19,4,26,‘San Gerardo’),(1369,1,19,4,27,‘San
José’),(1370,1,19,4,28,‘San Juan Norte’),(1371,1,19,4,29,‘Siberia
(parte)’),(1372,1,19,4,30,‘Tirrá’),(1373,1,19,4,31,‘Zapotal.’),(1374,1,19,5,1,‘Cruz
Roja.’),(1375,1,19,5,2,‘Arenilla (Junta)’),(1376,1,19,5,3,‘Alto
Calderón’),(1377,1,19,5,4,‘Cedral
(parte)’),(1378,1,19,5,5,‘Fátima’),(1379,1,19,5,6,‘Fortuna’),(1380,1,19,5,7,‘Guaria’),(1381,1,19,5,8,‘Laguna’),(1382,1,19,5,9,‘Rinconada
Vega’),(1383,1,19,5,10,‘San Jerónimo’),(1384,1,19,5,11,‘San
Juan’),(1385,1,19,5,12,‘San Juancito’),(1386,1,19,5,13,‘San
Rafael’),(1387,1,19,5,14,‘Santa Ana’),(1388,1,19,5,15,‘Santo
Domingo’),(1389,1,19,5,16,‘Tambor’),(1390,1,19,5,17,‘Unión’),(1391,1,19,5,18,‘Zapotal.’),(1392,1,19,6,1,‘Aguas
Buenas (parte)’),(1393,1,19,6,2,‘Bajo Bonitas’),(1394,1,19,6,3,‘Bajo
Espinoza’),(1395,1,19,6,4,‘Bolivia’),(1396,1,19,6,5,‘Bonitas’),(1397,1,19,6,6,‘Buenos
Aires’),(1398,1,19,6,7,‘Concepción (parte)’),(1399,1,19,6,8,‘Cristo
Rey’),(1400,1,19,6,9,‘La
Sierra’),(1401,1,19,6,10,‘Lourdes’),(1402,1,19,6,11,‘Mastatal’),(1403,1,19,6,12,‘Mollejoncito’),(1404,1,19,6,13,‘Mollejones’),(1405,1,19,6,14,‘Naranjos’),(1406,1,19,6,15,‘Oratorio’),(1407,1,19,6,16,‘San
Carlos’),(1408,1,19,6,17,‘San Pablito’),(1409,1,19,6,18,‘San
Pablo’),(1410,1,19,6,19,‘San Roque
(parte)’),(1411,1,19,6,20,‘Socorro’),(1412,1,19,6,21,‘Surtubal
(parte)’),(1413,1,19,6,22,‘Villa Argentina’),(1414,1,19,6,23,‘Villa
Flor’),(1415,1,19,6,24,‘Vista de
Mar.’),(1416,1,19,7,1,‘Achiotal’),(1417,1,19,7,2,‘Aguila’),(1418,1,19,7,3,‘Alto
Trinidad (Puñal)’),(1419,1,19,7,4,‘Bajo Caliente’),(1420,1,19,7,5,‘Bajo
Minas’),(1421,1,19,7,6,‘Barrionuevo’),(1422,1,19,7,7,‘Bellavista’),(1423,1,19,7,8,‘Calientillo’),(1424,1,19,7,9,‘Corralillo’),(1425,1,19,7,10,‘China
Kichá’),(1426,1,19,7,11,‘Delicias’),(1427,1,19,7,12,‘Desamparados’),(1428,1,19,7,13,‘El
Progreso’),(1429,1,19,7,14,‘Gibre’),(1430,1,19,7,15,‘Guadalupe’),(1431,1,19,7,16,‘Las
Cruces’),(1432,1,19,7,17,‘Mesas’),(1433,1,19,7,18,‘Minas’),(1434,1,19,7,19,‘Montezuma’),(1435,1,19,7,20,‘Paraiso’),(1436,1,19,7,21,‘San
Antonio’),(1437,1,19,7,22,‘San Gabriel’),(1438,1,19,7,23,‘San
Marcos’),(1439,1,19,7,24,‘San Martín’),(1440,1,19,7,25,‘San
Miguel’),(1441,1,19,7,26,‘San Roque (parte)’),(1442,1,19,7,27,‘Santa
Cecilia’),(1443,1,19,7,28,‘Santa Fe’),(1444,1,19,7,29,‘Santa
Luisa’),(1445,1,19,7,30,‘Surtubal
(parte)’),(1446,1,19,7,31,‘Trinidad’),(1447,1,19,7,32,‘Veracruz’),(1448,1,19,7,33,‘Zapote.’),(1449,1,19,8,1,‘Cedral
(parte)’),(1450,1,19,8,2,‘El
Quemado’),(1451,1,19,8,3,‘Gloria’),(1452,1,19,8,4,‘Las
Brisas’),(1453,1,19,8,5,‘Las
Vegas’),(1454,1,19,8,6,‘Mercedes’),(1455,1,19,8,7,‘Montecarlo’),(1456,1,19,8,8,‘Navajuelar’),(1457,1,19,8,9,‘Nubes’),(1458,1,19,8,10,‘Paraíso’),(1459,1,19,8,11,‘Pilar’),(1460,1,19,8,12,‘Pueblo
Nuevo’),(1461,1,19,8,13,‘Quizarrá
(parte)’),(1462,1,19,8,14,‘Salitrales’),(1463,1,19,8,15,‘San
Francisco’),(1464,1,19,8,16,‘San Ignacio’),(1465,1,19,8,17,‘San
Pedrito’),(1466,1,19,8,18,‘Santa María’),(1467,1,19,8,19,‘Santa
Teresa.’),(1468,1,19,9,1,‘Alfombra’),(1469,1,19,9,2,‘Alto
Perla’),(1470,1,19,9,3,‘Bajos’),(1471,1,19,9,4,‘Bajos de
Zapotal’),(1472,1,19,9,5,‘Barú’),(1473,1,19,9,6,‘Barucito’),(1474,1,19,9,7,‘Cacao’),(1475,1,19,9,8,‘Camarones’),(1476,1,19,9,9,‘Cañablanca’),(1477,1,19,9,10,‘Ceiba’),(1478,1,19,9,11,‘Chontales’),(1479,1,19,9,12,‘Farallas’),(1480,1,19,9,13,‘Florida’),(1481,1,19,9,14,‘San
Juan de Dios
(Guabo)’),(1482,1,19,9,15,‘Líbano’),(1483,1,19,9,16,‘Magnolia’),(1484,1,19,9,17,‘Pozos’),(1485,1,19,9,18,‘Reina’),(1486,1,19,9,19,‘San
Marcos’),(1487,1,19,9,20,‘San Salvador’),(1488,1,19,9,21,‘Santa
Juana’),(1489,1,19,9,22,‘Santo Cristo’),(1490,1,19,9,23,‘Tinamaste (San
Cristobal)’),(1491,1,19,9,24,‘Torito’),(1492,1,19,9,25,‘Tres Piedras
(parte)’),(1493,1,19,9,26,‘Tumbas’),(1494,1,19,9,27,‘Villabonita Vista
Mar.’),(1495,1,19,10,1,‘Alto
Mena’),(1496,1,19,10,2,‘Brujo’),(1497,1,19,10,3,‘Calle Los
Mora’),(1498,1,19,10,4,‘Chiricano’),(1499,1,19,10,5,‘Gloria’),(1500,1,19,10,6,‘Loma
Guacal’),(1501,1,19,10,7,‘Llano’),(1502,1,19,10,8,‘Paraíso’),(1503,1,19,10,9,‘Providencia
(Parte)’),(1504,1,19,10,10,‘Purruja’),(1505,1,19,10,11,‘Río
Nuevo’),(1506,1,19,10,12,‘San Antonio’),(1507,1,19,10,13,‘Santa
Marta’),(1508,1,19,10,14,‘Savegre Abajo’),(1509,1,19,10,15,‘Viento
Fresco.’),(1510,1,19,11,1,‘Alto Macho
Mora’),(1511,1,19,11,2,‘Ángeles’),(1512,1,19,11,3,‘Berlín’),(1513,1,19,11,4,‘Chanchera’),(1514,1,19,11,5,‘División
(parte)’),(1515,1,19,11,6,‘Fortuna’),(1516,1,19,11,7,‘Hortensia’),(1517,1,19,11,8,‘La
Ese’),(1518,1,19,11,9,‘La
Piedra’),(1519,1,19,11,10,‘Lira’),(1520,1,19,11,11,‘Matasanos’),(1521,1,19,11,12,‘Palma’),(1522,1,19,11,13,‘Pedregosito’),(1523,1,19,11,14,‘Providencia
(Parte)’),(1524,1,19,11,15,‘San Ramón Norte’),(1525,1,19,11,16,‘Santa
Eduvigis’),(1526,1,19,11,17,‘Santo Tomás’),(1527,1,19,11,18,‘Siberia
(parte)’),(1528,1,19,11,19,‘Valencia.’),(1529,1,20,1,1,‘Estadio’),(1530,1,20,1,2,‘La
Clara’),(1531,1,20,1,3,‘La Virgen’),(1532,1,20,1,4,‘Los
Ángeles’),(1533,1,20,1,5,‘Sagrada
Familia.’),(1534,1,20,1,6,‘Abejonal’),(1535,1,20,1,7,‘Carrizales’),(1536,1,20,1,8,‘Los
Navarro’),(1537,1,20,1,9,‘Montes de
Oro’),(1538,1,20,1,10,‘Rosario.’),(1539,1,20,2,1,‘Angostura
(parte)’),(1540,1,20,2,2,‘Bajo
Gamboa’),(1541,1,20,2,3,‘Higuerón’),(1542,1,20,2,4,‘Llano
Grande’),(1543,1,20,2,5,‘Ojo de Agua
(parte)’),(1544,1,20,2,6,‘Rastrojales.’),(1545,1,20,3,1,‘Bajo
Mora’),(1546,1,20,3,2,‘Bajo Venegas
(parte)’),(1547,1,20,3,3,‘Concepción’),(1548,1,20,3,4,‘San
Francisco’),(1549,1,20,3,5,‘San Luis’),(1550,1,20,3,6,‘San Rafael
Abajo’),(1551,1,20,3,7,‘Santa Juana’),(1552,1,20,3,8,‘Santa Rosa
(parte).’),(1553,1,20,4,1,‘Alto Carrizal’),(1554,1,20,4,2,‘Loma de la
Altura’),(1555,1,20,4,3,‘Santa Rosa
(parte)’),(1556,1,20,4,4,‘Trinidad.’),(1557,1,20,5,1,‘Cedral
(parte)’),(1558,1,20,5,2,‘Lucha (parte)’),(1559,1,20,5,3,‘San
Martín’),(1560,1,20,5,4,‘Rincón Gamboa.’),(1561,1,20,6,1,‘Angostura
(parte)’),(1562,1,20,6,2,‘Cuesta.’),(1563,2,1,1,1,‘Acequia Grande
(parte)’),(1564,2,1,1,2,‘Agonía’),(1565,2,1,1,3,‘Arroyo’),(1566,2,1,1,4,‘Bajo
Cornizal’),(1567,2,1,1,5,‘Brasil
(parte)’),(1568,2,1,1,6,‘Cafetal’),(1569,2,1,1,7,‘Canoas’),(1570,2,1,1,8,‘Carmen’),(1571,2,1,1,9,‘Cementerio’),(1572,2,1,1,10,‘Concepción’),(1573,2,1,1,11,‘Ciruelas’),(1574,2,1,1,12,‘Corazón
de Jesús’),(1575,2,1,1,13,‘Cristo Rey’),(1576,2,1,1,14,‘Gregorio José
Ramírez’),(1577,2,1,1,15,‘Guadalupe’),(1578,2,1,1,16,‘Higuerones’),(1579,2,1,1,17,‘Hospital’),(1580,2,1,1,18,‘Llano’),(1581,2,1,1,19,‘Llobet’),(1582,2,1,1,20,‘Molinos’),(1583,2,1,1,21,‘Montecillos
(parte)’),(1584,2,1,1,22,‘Montenegro’),(1585,2,1,1,23,‘Monserrat
(parte)’),(1586,2,1,1,24,‘Paso
Flores’),(1587,2,1,1,25,‘Providencia’),(1588,2,1,1,26,‘Retiro’),(1589,2,1,1,27,‘San
Luis’),(1590,2,1,1,28,‘Tomás
Guardia’),(1591,2,1,1,29,‘Tropicana’),(1592,2,1,1,30,‘Villabonita
(parte)’),(1593,2,1,1,31,‘Villahermosa.’),(1594,2,1,2,1,‘Amistad’),(1595,2,1,2,2,‘Botánica’),(1596,2,1,2,3,‘Copablanca’),(1597,2,1,2,4,‘Flores’),(1598,2,1,2,5,‘Jardines’),(1599,2,1,2,6,‘Jocote’),(1600,2,1,2,7,‘Juan
Santamaría’),(1601,2,1,2,8,‘Lagunilla’),(1602,2,1,2,9,‘Mandarina
(parte)’),(1603,2,1,2,10,‘Maravilla (parte)’),(1604,2,1,2,11,‘Montesol
(parte)’),(1605,2,1,2,12,‘Pueblo Nuevo’),(1606,2,1,2,13,‘San
Rafael’),(1607,2,1,2,14,‘Santa
Rita’),(1608,2,1,2,15,‘Tigra’),(1609,2,1,2,16,‘Torre’),(1610,2,1,2,17,‘Trinidad’),(1611,2,1,2,18,‘Tuetal
Sur.’),(1612,2,1,2,19,‘Coyol’),(1613,2,1,2,20,‘Flores.’),(1614,2,1,3,1,‘Alto
Pavas’),(1615,2,1,3,2,‘Bambú’),(1616,2,1,3,3,‘Cinco
Esquinas’),(1617,2,1,3,4,‘Concordia’),(1618,2,1,3,5,‘Domingas’),(1619,2,1,3,6,‘El
Plan.’),(1620,2,1,4,1,‘Acequia Grande (parte)’),(1621,2,1,4,2,‘Bajo
Monge’),(1622,2,1,4,3,‘Cañada’),(1623,2,1,4,4,‘Montecillos
(parte)’),(1624,2,1,4,5,‘Monserrat (parte)’),(1625,2,1,4,6,‘Puente Arena
(parte)’),(1626,2,1,4,7,‘Tejar’),(1627,2,1,4,8,‘Vegas’),(1628,2,1,4,9,‘Villabonita
(parte).’),(1629,2,1,4,10,‘Ciruelas’),(1630,2,1,4,11,‘Roble’),(1631,2,1,4,12,‘Sánchez.’),(1632,2,1,5,1,‘Bajo
Tejar’),(1633,2,1,5,2,‘Cacao’),(1634,2,1,5,3,‘Cañada’),(1635,2,1,5,4,‘Coco’),(1636,2,1,5,5,‘El
Bajo’),(1637,2,1,5,6,‘Hacienda Los Reyes’),(1638,2,1,5,7,‘Nuestro
Amo’),(1639,2,1,5,8,‘Rincón Chiquito’),(1640,2,1,5,9,‘Rincón de
Herrera’),(1641,2,1,5,10,‘Rincón de
Monge’),(1642,2,1,5,11,‘Ventanas’),(1643,2,1,5,12,‘Vueltas.’),(1644,2,1,6,1,‘Barrios
(Alajuela): Aguilar’),(1645,2,1,6,2,‘Ceiba’),(1646,2,1,6,3,‘San
Martín.’),(1647,2,1,6,4,‘Alto
Pilas’),(1648,2,1,6,5,‘Buríos’),(1649,2,1,6,6,‘Carbonal’),(1650,2,1,6,7,‘Cerrillal’),(1651,2,1,6,8,‘Dulce
Nombre’),(1652,2,1,6,9,‘Espino
(parte)’),(1653,2,1,6,10,‘Itiquís’),(1654,2,1,6,11,‘Laguna’),(1655,2,1,6,12,‘Loría’),(1656,2,1,6,13,‘Maravilla
(parte)’),(1657,2,1,6,14,‘Montaña’),(1658,2,1,6,15,‘Pilas’),(1659,2,1,6,16,‘Potrerillos’),(1660,2,1,6,17,‘San
Jerónimo’),(1661,2,1,6,18,‘San
Rafael’),(1662,2,1,6,19,‘Tacacorí’),(1663,2,1,6,20,‘Tuetal Norte
(parte)’),(1664,2,1,6,21,‘Villas de la Ceiba.’),(1665,2,1,7,1,‘Alto del
Desengaño’),(1666,2,1,7,2,‘Ángeles’),(1667,2,1,7,3,‘Bajo Santa
Bárbara’),(1668,2,1,7,4,‘Cerro’),(1669,2,1,7,5,‘Doka’),(1670,2,1,7,6,‘Espino
(parte)’),(1671,2,1,7,7,‘Fraijanes’),(1672,2,1,7,8,‘Lajas’),(1673,2,1,7,9,‘Poasito’),(1674,2,1,7,10,‘San
Luis’),(1675,2,1,7,11,‘San Rafael’),(1676,2,1,7,12,‘Vargas
(parte).’),(1677,2,1,8,1,‘La
Paz’),(1678,2,1,8,2,‘Perla.’),(1679,2,1,8,3,‘Cañada’),(1680,2,1,8,4,‘Ojo
de
Agua’),(1681,2,1,8,5,‘Paires’),(1682,2,1,8,6,‘Potrerillos’),(1683,2,1,8,7,‘Rincón
Venegas.’),(1684,2,1,9,1,‘Ángeles’),(1685,2,1,9,2,‘Bajo Cañas
(parte)’),(1686,2,1,9,3,‘Bajo La
Sorda’),(1687,2,1,9,4,‘Cacique’),(1688,2,1,9,5,‘California’),(1689,2,1,9,6,‘Cañas
(parte)’),(1690,2,1,9,7,‘Guayabo’),(1691,2,1,9,8,‘Monserrat
(parte)’),(1692,2,1,9,9,‘Puente Arena
(parte)’),(1693,2,1,9,10,‘Villalobos’),(1694,2,1,9,11,‘Víquez.’),(1695,2,1,10,1,‘Bajo
Cañas (parte)’),(1696,2,1,10,2,‘Bellavista’),(1697,2,1,10,3,‘Brasil
(parte)’),(1698,2,1,10,4,‘Calicanto’),(1699,2,1,10,5,‘Cañas
(parte)’),(1700,2,1,10,6,‘Erizo’),(1701,2,1,10,7,‘Mojón’),(1702,2,1,10,8,‘Pasito’),(1703,2,1,10,9,‘Rincón’),(1704,2,1,10,10,‘Rosales
(parte)’),(1705,2,1,10,11,‘Targuases’),(1706,2,1,10,12,‘Tres
Piedras.’),(1707,2,1,11,1,‘Bajo
Pita’),(1708,2,1,11,2,‘Granja’),(1709,2,1,11,3,‘Morera’),(1710,2,1,11,4,‘San
Martín’),(1711,2,1,11,5,‘Santa
Rita’),(1712,2,1,11,6,‘Turrucareña’),(1713,2,1,11,7,‘Villacares.’),(1714,2,1,11,8,‘Bajo
San Miguel’),(1715,2,1,11,9,‘Candelaria’),(1716,2,1,11,10,‘Carrera
Buena’),(1717,2,1,11,11,‘Cebadilla’),(1718,2,1,11,12,‘Cerrillos (San
Miguel)’),(1719,2,1,11,13,‘Conejo’),(1720,2,1,11,14,‘Juntas’),(1721,2,1,11,15,‘Siquiares’),(1722,2,1,11,16,‘Tamarindo.’),(1723,2,1,12,1,‘Cacao’),(1724,2,1,12,2,‘Calle
Liles’),(1725,2,1,12,3,‘González’),(1726,2,1,12,4,‘Quebradas’),(1727,2,1,12,5,‘Rincón
Cacao’),(1728,2,1,12,6,‘Tuetal Norte (parte)’),(1729,2,1,12,7,‘Vargas
(parte).’),(1730,2,1,13,1,‘Animas’),(1731,2,1,13,2,‘Cuesta
Colorada’),(1732,2,1,13,3,‘Copeyal’),(1733,2,1,13,4,‘Horcones’),(1734,2,1,13,5,‘Lagos
del Coyol’),(1735,2,1,13,6,‘Llanos’),(1736,2,1,13,7,‘Mandarina
(parte)’),(1737,2,1,13,8,‘Manolos’),(1738,2,1,13,9,‘Mina’),(1739,2,1,13,10,‘Montesol
(parte)’),(1740,2,1,13,11,‘Monticel’),(1741,2,1,13,12,‘Saltillo.’),(1742,2,1,14,1,‘Bajo
Latas’),(1743,2,1,14,2,‘Cariblanco’),(1744,2,1,14,3,‘Corazón de
Jesús’),(1745,2,1,14,4,‘Isla Bonita’),(1746,2,1,14,5,‘Nueva
Cinchona’),(1747,2,1,14,6,‘San
Antonio’),(1748,2,1,14,7,‘Paraíso’),(1749,2,1,14,8,‘Punta
Mala’),(1750,2,1,14,9,‘Ujarrás’),(1751,2,1,14,10,‘Virgen del Socorro
(parte).’),(1752,2,2,1,1,‘Bajo
Ladrillera’),(1753,2,2,1,2,‘Cachera’),(1754,2,2,1,3,‘Lisímaco
Chavarría’),(1755,2,2,1,4,‘Sabana (parte)’),(1756,2,2,1,5,‘San
José’),(1757,2,2,1,6,‘Tremedal
(parte).’),(1758,2,2,2,1,‘Arias’),(1759,2,2,2,2,‘Montserrat.’),(1760,2,2,2,3,‘Alto
Santiago’),(1761,2,2,2,4,‘Alto
Salas’),(1762,2,2,2,5,‘Angostura’),(1763,2,2,2,6,‘Balboa’),(1764,2,2,2,7,‘Cambronero’),(1765,2,2,2,8,‘Constancia’),(1766,2,2,2,9,‘Cuesta
del Toro’),(1767,2,2,2,10,‘Empalme’),(1768,2,2,2,11,‘La
Ese’),(1769,2,2,2,12,‘León’),(1770,2,2,2,13,‘Magallanes’),(1771,2,2,2,14,‘Moncada’),(1772,2,2,2,15,‘Río
Jesús.’),(1773,2,2,3,1,‘Americana’),(1774,2,2,3,2,‘Bajo
Tajos’),(1775,2,2,3,3,‘Belén’),(1776,2,2,3,4,‘Cipreses’),(1777,2,2,3,5,‘Lirios’),(1778,2,2,3,6,‘Llamarón’),(1779,2,2,3,7,‘Pueblo
Nuevo’),(1780,2,2,3,8,‘Tanque’),(1781,2,2,3,9,‘Tejar’),(1782,2,2,3,10,‘Vicente
Badilla.’),(1783,2,2,3,11,‘Juntas
(parte).’),(1784,2,2,4,1,‘Copán.’),(1785,2,2,4,2,‘Araya’),(1786,2,2,4,3,‘Bajo
Matamoros
(parte)’),(1787,2,2,4,4,‘Bolívar’),(1788,2,2,4,5,‘Campos’),(1789,2,2,4,6,‘Esperanza’),(1790,2,2,4,7,‘La
Paz’),(1791,2,2,4,8,‘Lomas’),(1792,2,2,4,9,‘Piedades
Noroeste.’),(1793,2,2,5,1,‘Bajo
Barranca’),(1794,2,2,5,2,‘Barranca’),(1795,2,2,5,3,‘Bureal’),(1796,2,2,5,4,‘Carmen’),(1797,2,2,5,5,‘Chassoul’),(1798,2,2,5,6,‘Guaria’),(1799,2,2,5,7,‘Nagatac’),(1800,2,2,5,8,‘Palma’),(1801,2,2,5,9,‘Potrerillos’),(1802,2,2,5,10,‘Quebradillas’),(1803,2,2,5,11,‘Salvador’),(1804,2,2,5,12,‘San
Bosco’),(1805,2,2,5,13,‘San Francisco’),(1806,2,2,5,14,‘San
Miguel’),(1807,2,2,5,15,‘Sardinal’),(1808,2,2,5,16,‘Socorro.’),(1809,2,2,6,1,‘Tres
Marías’),(1810,2,2,6,2,‘Unión.’),(1811,2,2,6,3,‘Alto
Llano’),(1812,2,2,6,4,‘Berlín’),(1813,2,2,6,5,‘Calera’),(1814,2,2,6,6,‘Chavarría’),(1815,2,2,6,7,‘Llano
Brenes’),(1816,2,2,6,8,‘Orlich’),(1817,2,2,6,9,‘Orozco’),(1818,2,2,6,10,‘Pata
de Gallo’),(1819,2,2,6,11,‘Rincón de Mora’),(1820,2,2,6,12,‘Rincón
Orozco’),(1821,2,2,6,13,‘San
Joaquín’),(1822,2,2,6,14,‘Zamora.’),(1823,2,2,7,1,‘Progreso’),(1824,2,2,7,2,‘Bajo
Ramírez’),(1825,2,2,7,3,‘Fernández’),(1826,2,2,7,4,‘Guaria’),(1827,2,2,7,5,‘Varela.’),(1828,2,2,8,1,‘Los
Jardines’),(1829,2,2,8,2,‘Ranchera.’),(1830,2,2,8,3,‘Ángeles
Norte’),(1831,2,2,8,4,‘Bajo Córdoba’),(1832,2,2,8,5,‘Bajo
Rodríguez’),(1833,2,2,8,6,‘Balsa’),(1834,2,2,8,7,‘Cataratas’),(1835,2,2,8,8,‘Colonia
Palmareña’),(1836,2,2,8,9,‘Coopezamora’),(1837,2,2,8,10,‘Criques’),(1838,2,2,8,11,‘Juntas
(parte)’),(1839,2,2,8,12,‘Kooper’),(1840,2,2,8,13,‘Lagos’),(1841,2,2,8,14,‘Rocas’),(1842,2,2,8,15,‘San
Jorge’),(1843,2,2,8,16,‘Silencio’),(1844,2,2,8,17,‘Valle
Azul’),(1845,2,2,8,18,‘Zuñiga.’),(1846,2,2,9,1,‘Badilla
Alpizar’),(1847,2,2,9,2,‘Sabana (parte)’),(1848,2,2,9,3,‘Tremedal
(parte).’),(1849,2,2,9,4,‘Bajo Matamoros
(parte)’),(1850,2,2,9,5,‘Catarata’),(1851,2,2,9,6,‘San
Pedro’),(1852,2,2,9,7,‘Valverde.’),(1853,2,2,10,1,‘Alto
Villegas’),(1854,2,2,10,2,‘Bajo Tejares’),(1855,2,2,10,3,‘Dulce
Nombre’),(1856,2,2,10,4,‘Sifón.’),(1857,2,2,11,1,‘Cañuela’),(1858,2,2,11,2,‘Concepción
Arriba’),(1859,2,2,11,3,‘Chaparral’),(1860,2,2,11,4,‘Pérez.’),(1861,2,2,12,1,‘Bajo
Castillo’),(1862,2,2,12,2,‘Bajos’),(1863,2,2,12,3,‘Barranquilla’),(1864,2,2,12,4,‘Carrera
Buena’),(1865,2,2,12,5,‘Jabonal’),(1866,2,2,12,6,‘Jabonalito’),(1867,2,2,12,7,‘Rincón
Chaves’),(1868,2,2,12,8,‘Victoria’),(1869,2,2,12,9,‘Zapotal
(parte).’),(1870,2,2,13,1,‘Abanico’),(1871,2,2,13,2,‘Altura’),(1872,2,2,13,3,‘Bosque’),(1873,2,2,13,4,‘Burrito’),(1874,2,2,13,5,‘Cairo’),(1875,2,2,13,6,‘Castillo
(parte)’),(1876,2,2,13,7,‘Castillo Nuevo’),(1877,2,2,13,8,‘Colonia
Trinidad’),(1878,2,2,13,9,‘Chachagua’),(1879,2,2,13,10,‘La
Cruz’),(1880,2,2,13,11,‘Pocosol’),(1881,2,2,13,12,‘San
Carlos’),(1882,2,2,13,13,‘San Rafael’),(1883,2,2,13,14,‘Sector
Ángeles.’),(1884,2,3,1,1,‘Carmona’),(1885,2,3,1,2,‘Chavarría’),(1886,2,3,1,3,‘Colón’),(1887,2,3,1,4,‘Jiménez’),(1888,2,3,1,5,‘Pinos’),(1889,2,3,1,6,‘Rincón
de Arias’),(1890,2,3,1,7,‘San Antonio’),(1891,2,3,1,8,‘San
Vicente.’),(1892,2,3,1,9,‘Celina.’),(1893,2,3,2,1,‘Barrio (Grecia):
Primavera.’),(1894,2,3,2,2,‘Alfaro’),(1895,2,3,2,3,‘Bajo
Achiote’),(1896,2,3,2,4,‘Camejo’),(1897,2,3,2,5,‘Coopevictoria’),(1898,2,3,2,6,‘Corinto’),(1899,2,3,2,7,‘Higuerones’),(1900,2,3,2,8,‘Mesón’),(1901,2,3,2,9,‘Mojón’),(1902,2,3,2,10,‘Quizarrazal.’),(1903,2,3,3,1,‘Arena’),(1904,2,3,3,2,‘Cedro’),(1905,2,3,3,3,‘Delicias
(parte)’),(1906,2,3,3,4,‘Guayabal
(parte)’),(1907,2,3,3,5,‘Loma’),(1908,2,3,3,6,‘Rodríguez’),(1909,2,3,3,7,‘Santa
Gertrudis Norte’),(1910,2,3,3,8,‘Santa Gertrudis
Sur.’),(1911,2,3,4,1,‘Agualote’),(1912,2,3,4,2,‘Bajo
Sapera’),(1913,2,3,4,3,‘Casillas’),(1914,2,3,4,4,‘Latino’),(1915,2,3,4,5,‘San
Miguel
Arriba.’),(1916,2,3,4,6,‘Cabuyal’),(1917,2,3,4,7,‘Carbonal’),(1918,2,3,4,8,‘Coyotera’),(1919,2,3,4,9,‘San
Miguel.’),(1920,2,3,5,1,‘Pinto.’),(1921,2,3,5,2,‘Bodegas’),(1922,2,3,5,3,‘Cataluña’),(1923,2,3,5,4,‘Cerdas’),(1924,2,3,5,5,‘Delicias
(parte)’),(1925,2,3,5,6,‘Flores’),(1926,2,3,5,7,‘Guayabal
(parte)’),(1927,2,3,5,8,‘Pilas’),(1928,2,3,5,9,‘Planta’),(1929,2,3,5,10,‘Porvenir’),(1930,2,3,5,11,‘Yoses.’),(1931,2,3,6,1,‘Ángeles
Norte’),(1932,2,3,6,2,‘Bolaños’),(1933,2,3,6,3,‘Bosque
Alegre’),(1934,2,3,6,4,‘Caño
Negro’),(1935,2,3,6,5,‘Carmen’),(1936,2,3,6,6,‘Carrizal’),(1937,2,3,6,7,‘Colonia
del
Toro’),(1938,2,3,6,8,‘Crucero’),(1939,2,3,6,9,‘Flor’),(1940,2,3,6,10,‘Hule’),(1941,2,3,6,11,‘La
Trinidad’),(1942,2,3,6,12,‘Laguna’),(1943,2,3,6,13,‘Lagos’),(1944,2,3,6,14,‘Merced’),(1945,2,3,6,15,‘Montelirio’),(1946,2,3,6,16,‘Naranjales’),(1947,2,3,6,17,‘Palmar’),(1948,2,3,6,18,‘Palmera’),(1949,2,3,6,19,‘Pata
de
Gallo’),(1950,2,3,6,20,‘Peoresnada’),(1951,2,3,6,21,‘Pinar’),(1952,2,3,6,22,‘Pueblo
Nuevo’),(1953,2,3,6,23,‘San Fernando’),(1954,2,3,6,24,‘San Gerardo
(parte)’),(1955,2,3,6,25,‘San Jorge’),(1956,2,3,6,26,‘San
José’),(1957,2,3,6,27,‘San Rafael’),(1958,2,3,6,28,‘San
Vicente’),(1959,2,3,6,29,‘Santa Isabel’),(1960,2,3,6,30,‘Santa
Rita’),(1961,2,3,6,31,‘Tabla.’),(1962,2,3,7,1,‘(Grecia):
Poró’),(1963,2,3,7,2,‘Sevilla.’),(1964,2,3,7,3,‘Altos de
Peralta’),(1965,2,3,7,4,‘Argentina’),(1966,2,3,7,5,‘Bajo
Cedros’),(1967,2,3,7,6,‘Lomas’),(1968,2,3,7,7,‘Montezuma’),(1969,2,3,7,8,‘Puerto
Escondido’),(1970,2,3,7,9,‘Raiceros’),(1971,2,3,7,10,‘Rincón de
Salas’),(1972,2,3,7,11,‘Rosales.’),(1973,2,3,8,1,‘Cajón’),(1974,2,3,8,2,‘Cocobolo’),(1975,2,3,8,3,‘Murillo’),(1976,2,3,8,4,‘San
Juan’),(1977,2,3,8,5,‘San
Luis’),(1978,2,3,8,6,‘Virgencita.’),(1979,2,4,1,1,‘Agua
Agria’),(1980,2,4,1,2,‘Calera’),(1981,2,4,1,3,‘Cenízaro’),(1982,2,4,1,4,‘Centeno’),(1983,2,4,1,5,‘Desamparados’),(1984,2,4,1,6,‘Dulce
Nombre’),(1985,2,4,1,7,‘Higuito’),(1986,2,4,1,8,‘Izarco’),(1987,2,4,1,9,‘Maderal’),(1988,2,4,1,10,‘Ramadas’),(1989,2,4,1,11,‘San
Juan de Dios.’),(1990,2,4,2,1,‘Cuesta
Colorada’),(1991,2,4,2,2,‘Libertad’),(1992,2,4,2,3,‘Quebrada
Honda’),(1993,2,4,2,4,‘Limón’),(1994,2,4,2,5,‘Patio de Agua
Norte’),(1995,2,4,2,6,‘Sacra Familia’),(1996,2,4,2,7,‘San Juan
Uno’),(1997,2,4,2,8,‘Zapote.’),(1998,2,4,3,1,‘Garabito’),(1999,2,4,3,2,‘Quebrada
Grande
(parte)’),(2000,2,4,3,3,‘Quinta.’),(2001,2,4,4,1,‘Altamira’),(2002,2,4,4,2,‘Oricuajo’),(2003,2,4,4,3,‘Poza
Redonda’),(2004,2,4,4,4,‘Quebrada Grande
(parte).’),(2005,2,5,1,1,‘Ángeles’),(2006,2,5,1,2,‘Atenea’),(2007,2,5,1,3,‘Boquerón’),(2008,2,5,1,4,‘Escorpio’),(2009,2,5,1,5,‘Güizaro’),(2010,2,5,1,6,‘Oásis’),(2011,2,5,1,7,‘Olivo.’),(2012,2,5,1,8,‘Matías.’),(2013,2,5,2,1,‘Guacalillo’),(2014,2,5,2,2,‘Sabanalarga’),(2015,2,5,2,3,‘San
Vicente.’),(2016,2,5,2,4,‘Barroeta’),(2017,2,5,2,5,‘Boca del
Monte’),(2018,2,5,2,6,‘Cuajiniquil’),(2019,2,5,2,7,‘Estanquillo’),(2020,2,5,2,8,‘Pato
de Agua
(parte).’),(2021,2,5,3,1,‘Cajón.’),(2022,2,5,3,2,‘Callao’),(2023,2,5,3,3,‘Plancillo’),(2024,2,5,3,4,‘Plazoleta.’),(2025,2,5,4,1,‘Alto
Naranjo’),(2026,2,5,4,2,‘Bajo
Cacao’),(2027,2,5,4,3,‘Morazán’),(2028,2,5,4,4,‘Pato de Agua
(parte)’),(2029,2,5,4,5,‘Pavas’),(2030,2,5,4,6,‘Rincón.’),(2031,2,5,5,1,‘Río
Grande’),(2032,2,5,5,2,‘Balsa’),(2033,2,5,5,3,‘Calle
Garita’),(2034,2,5,5,4,‘Coyoles’),(2035,2,5,5,5,‘Pan de
Azúcar’),(2036,2,5,5,6,‘Tornos.’),(2037,2,5,6,1,‘Alto
Cima’),(2038,2,5,6,2,‘Alto
López’),(2039,2,5,6,3,‘Legua’),(2040,2,5,6,4,‘San José
Norte’),(2041,2,5,6,5,‘Torunes’),(2042,2,5,6,6,‘Vainilla.’),(2043,2,5,7,1,‘Rincón
Rodríguez.’),(2044,2,5,8,1,‘Cerrillo’),(2045,2,5,8,2,‘Guácimos’),(2046,2,5,8,3,‘Kilómetro
51’),(2047,2,5,8,4,‘Lapas’),(2048,2,5,8,5,‘Mangos’),(2049,2,5,8,6,‘Quebradas’),(2050,2,5,8,7,‘Vuelta
Herrera.’),(2051,2,6,1,1,‘Bajo
Pilas’),(2052,2,6,1,2,‘Belén’),(2053,2,6,1,3,‘Caña
Dura’),(2054,2,6,1,4,‘Gradas’),(2055,2,6,1,5,‘María
Auxiliadora’),(2056,2,6,1,6,‘Muro’),(2057,2,6,1,7,‘Pueblo
Nuevo’),(2058,2,6,1,8,‘Sacramento’),(2059,2,6,1,9,‘San Lucas
(Carmen)’),(2060,2,6,1,10,‘San
Rafael.’),(2061,2,6,1,11,‘Candelaria’),(2062,2,6,1,12,‘Cantarrana
(parte)’),(2063,2,6,1,13,‘Cinco Esquinas
(parte)’),(2064,2,6,1,14,‘Ciprés’),(2065,2,6,1,15,‘Común’),(2066,2,6,1,16,‘Dulce
Nombre.’),(2067,2,6,2,1,‘Planes.’),(2068,2,6,2,2,‘Bajo
Seevers’),(2069,2,6,2,3,‘Bajos’),(2070,2,6,2,4,‘Palmas’),(2071,2,6,2,5,‘Quesera’),(2072,2,6,2,6,‘San
Francisco’),(2073,2,6,2,7,‘San Miguel
Oeste.’),(2074,2,6,3,1,‘Barranca’),(2075,2,6,3,2,‘Cañuela
Arriba’),(2076,2,6,3,3,‘Cuesta
Venada’),(2077,2,6,3,4,‘Desamparados’),(2078,2,6,3,5,‘Isla’),(2079,2,6,3,6,‘San
Antonio de Barranca’),(2080,2,6,3,7,‘San Pedro’),(2081,2,6,3,8,‘Solís
(parte)’),(2082,2,6,3,9,‘Vuelta San Gerardo.’),(2083,2,6,4,1,‘Bajo
Arrieta’),(2084,2,6,4,2,‘Bajo Valverde’),(2085,2,6,4,3,‘Bajo
Zúñiga’),(2086,2,6,4,4,‘Cruce’),(2087,2,6,4,5,‘Isla
Cocora’),(2088,2,6,4,6,‘Lourdes’),(2089,2,6,4,7,‘Llano
Bonito’),(2090,2,6,4,8,‘Llano Bonito
Sur’),(2091,2,6,4,9,‘Palmita’),(2092,2,6,4,10,‘Quebrada
Honda’),(2093,2,6,4,11,‘Rincón’),(2094,2,6,4,12,‘Solís
(parte)’),(2095,2,6,4,13,‘Zapote.’),(2096,2,6,5,1,‘Puebla’),(2097,2,6,5,2,‘Robles’),(2098,2,6,5,3,‘Tacacal.’),(2099,2,6,6,1,‘Bajo
Murciélago’),(2100,2,6,6,2,‘Cueva’),(2101,2,6,6,3,‘Guarumal’),(2102,2,6,6,4,‘Rincón
Elizondo’),(2103,2,6,6,5,‘San
Antonio’),(2104,2,6,6,6,‘Yoses.’),(2105,2,6,7,1,‘Pérez’),(2106,2,6,7,2,‘Hornos’),(2107,2,6,7,3,‘Llano’),(2108,2,6,7,4,‘Santa
Margarita’),(2109,2,6,7,5,‘Vistas del Valle.’),(2110,2,6,8,1,‘Alto
Murillo’),(2111,2,6,8,2,‘Alto Palmas’),(2112,2,6,8,3,‘Cantarrana
(parte)’),(2113,2,6,8,4,‘Cinco Esquinas
(parte)’),(2114,2,6,8,5,‘Concepción’),(2115,2,6,8,6,‘Roquillo’),(2116,2,6,8,7,‘San
Roque.’),(2117,2,7,1,1,‘Santa Fe’),(2118,2,7,1,2,‘San
Vicente.’),(2119,2,7,2,1,‘Cocaleca
(parte)’),(2120,2,7,2,2,‘Quebrada’),(2121,2,7,2,3,‘Rincón
(Quebrada)’),(2122,2,7,2,4,‘Rincón de
Zaragoza’),(2123,2,7,2,5,‘Vargas’),(2124,2,7,2,6,‘Vásquez.’),(2125,2,7,3,1,‘Bajo
Cabra’),(2126,2,7,3,2,‘Barreal’),(2127,2,7,3,3,‘Tres
Marías’),(2128,2,7,3,4,‘Valle’),(2129,2,7,3,5,‘Victoria.’),(2130,2,7,3,6,‘Calle
Ramírez.’),(2131,2,7,4,1,‘Pinos (parte).’),(2132,2,7,5,1,‘Pinos
(parte).’),(2133,2,7,6,1,‘Calle Roble’),(2134,2,7,6,2,‘Cocaleca
(parte)’),(2135,2,7,6,3,‘Peraza’),(2136,2,7,6,4,‘Rincón de
Salas.’),(2137,2,7,7,1,‘Amistad.’),(2138,2,8,1,1,‘Santa Cecilia (Bajo
Piedras)’),(2139,2,8,1,2,‘Rastro.’),(2140,2,8,1,3,‘Cerro’),(2141,2,8,1,4,‘Chilamate’),(2142,2,8,1,5,‘Hilda’),(2143,2,8,1,6,‘Sitio
(parte)’),(2144,2,8,1,7,‘Zamora.’),(2145,2,8,2,1,‘Altura
(parte)’),(2146,2,8,2,2,‘Corazón de
Jesús’),(2147,2,8,2,3,‘Guapinol’),(2148,2,8,2,4,‘Mastate’),(2149,2,8,2,5,‘San
Juan Norte’),(2150,2,8,2,6,‘Tablones (parte).’),(2151,2,8,3,1,‘Bajo
Zamora’),(2152,2,8,3,2,‘Churuca’),(2153,2,8,3,3,‘Guatuza’),(2154,2,8,3,4,‘Monte’),(2155,2,8,3,5,‘Potrero
Chiquito’),(2156,2,8,3,6,‘Santa Rosa’),(2157,2,8,3,7,‘Sitio
(parte)’),(2158,2,8,3,8,‘Solís’),(2159,2,8,3,9,‘Tablones
(parte)’),(2160,2,8,3,10,‘Volcán.’),(2161,2,8,4,1,‘Bajo
Barahona’),(2162,2,8,4,2,‘Cuatro Esquinas’),(2163,2,8,4,3,‘Santísima
Trinidad.’),(2164,2,8,4,4,‘Carrillos
Alto’),(2165,2,8,4,5,‘Senda’),(2166,2,8,4,6,‘Sonora.’),(2167,2,8,5,1,‘Altura
(parte)’),(2168,2,8,5,2,‘Bajos del
Tigre.’),(2169,2,9,1,1,‘Aguacate’),(2170,2,9,1,2,‘Arboleda’),(2171,2,9,1,3,‘Carmen’),(2172,2,9,1,4,‘Cortezal’),(2173,2,9,1,5,‘Cuatro
Esquinas
Norte’),(2174,2,9,1,6,‘Jesús’),(2175,2,9,1,7,‘Kilómetro’),(2176,2,9,1,8,‘López’),(2177,2,9,1,9,‘Miraflores’),(2178,2,9,1,10,‘Rastro
Viejo’),(2179,2,9,1,11,‘San Rafael’),(2180,2,9,1,12,‘San
Vicente’),(2181,2,9,1,13,‘Tres Marías’),(2182,2,9,1,14,‘Villa los
Reyes.’),(2183,2,9,1,15,‘Alto
Vindas’),(2184,2,9,1,16,‘Esperanza’),(2185,2,9,1,17,‘Tigre.’),(2186,2,9,2,1,‘Cuatro
Esquinas Este’),(2187,2,9,2,2,‘Piedra
Azul.’),(2188,2,9,2,3,‘Guayabal.’),(2189,2,9,3,1,‘Marichal’),(2190,2,9,3,2,‘Concepción’),(2191,2,9,3,3,‘Dantas.’),(2192,2,9,4,1,‘Corazón
de María.’),(2193,2,9,4,2,‘Bajos del
Coyote’),(2194,2,9,4,3,‘Cebadilla’),(2195,2,9,4,4,‘Guápiles’),(2196,2,9,4,5,‘Limonal’),(2197,2,9,4,6,‘Mangos’),(2198,2,9,4,7,‘Mollejones’),(2199,2,9,4,8,‘Piedras
de Fuego’),(2200,2,9,4,9,‘Pozón’),(2201,2,9,4,10,‘San
Jerónimo’),(2202,2,9,4,11,‘Santa Rita.’),(2203,2,9,5,1,‘Angostura
Matamoros’),(2204,2,9,5,2,‘Cascajal’),(2205,2,9,5,3,‘Cuesta
Pitahaya’),(2206,2,9,5,4,‘Guácimo’),(2207,2,9,5,5,‘Hidalgo’),(2208,2,9,5,6,‘Kilómetro
81’),(2209,2,9,5,7,‘Machuca’),(2210,2,9,5,8,‘Matamoros’),(2211,2,9,5,9,‘Túnel’),(2212,2,9,5,10,‘Uvita’),(2213,2,9,5,11,‘Zapote.’),(2214,2,10,1,1,‘Alto
Cruz’),(2215,2,10,1,2,‘Ana
Mercedes’),(2216,2,10,1,3,‘Ángeles’),(2217,2,10,1,4,‘Arco
Iris’),(2218,2,10,1,5,‘Bajo Los Arce’),(2219,2,10,1,6,‘Bajo
Lourdes’),(2220,2,10,1,7,‘Baltazar
Quesada’),(2221,2,10,1,8,‘Bellavista’),(2222,2,10,1,9,‘Carmen’),(2223,2,10,1,10,‘Casilda
Matamoros’),(2224,2,10,1,11,‘Cementerio’),(2225,2,10,1,12,‘Coocique’),(2226,2,10,1,13,‘Colina
1’),(2227,2,10,1,14,‘Colina 2’),(2228,2,10,1,15,‘Corazón de
Jesús’),(2229,2,10,1,16,‘Corobicí’),(2230,2,10,1,17,‘Don
Victorino’),(2231,2,10,1,18,‘El
Campo’),(2232,2,10,1,19,‘Gamonales’),(2233,2,10,1,20,‘Guadalupe’),(2234,2,10,1,21,‘La
Cruz’),(2235,2,10,1,22,‘La Leila’),(2236,2,10,1,23,‘La
Margarita’),(2237,2,10,1,24,‘La Roca’),(2238,2,10,1,25,‘La
Torre’),(2239,2,10,1,26,‘Los Abuelos’),(2240,2,10,1,27,‘Lomas del
Norte’),(2241,2,10,1,28,‘Lutz’),(2242,2,10,1,29,‘Meco’),(2243,2,10,1,30,‘Mercedes’),(2244,2,10,1,31,‘Peje’),(2245,2,10,1,32,‘San
Antonio’),(2246,2,10,1,33,‘San Gerardo’),(2247,2,10,1,34,‘San
Martín’),(2248,2,10,1,35,‘San Pablo’),(2249,2,10,1,36,‘San
Roque’),(2250,2,10,1,37,‘Santa Fe’),(2251,2,10,1,38,‘Selva
Verde’),(2252,2,10,1,39,‘Unión’),(2253,2,10,1,40,‘Villarreal.’),(2254,2,10,1,41,‘Abundancia’),(2255,2,10,1,42,‘Brumas’),(2256,2,10,1,43,‘Calle
Guerrero’),(2257,2,10,1,44,‘San Ramón
(Cariblanca)’),(2258,2,10,1,45,‘Cedral Norte’),(2259,2,10,1,46,‘Cedral
Sur’),(2260,2,10,1,47,‘Colón’),(2261,2,10,1,48,‘Dulce
Nombre’),(2262,2,10,1,49,‘Leones’),(2263,2,10,1,50,‘Lindavista’),(2264,2,10,1,51,‘Manzanos’),(2265,2,10,1,52,‘Montañitas’),(2266,2,10,1,53,‘Palmas’),(2267,2,10,1,54,‘Porvenir’),(2268,2,10,1,55,‘San
Juan (Quebrada Palo)’),(2269,2,10,1,56,‘Ronrón
Abajo’),(2270,2,10,1,57,‘Ronrón Arriba’),(2271,2,10,1,58,‘San
Isidro’),(2272,2,10,1,59,‘San José de la Montaña’),(2273,2,10,1,60,‘San
Luis’),(2274,2,10,1,61,‘San Rafael’),(2275,2,10,1,62,‘San
Vicente’),(2276,2,10,1,63,‘Sucre’),(2277,2,10,1,64,‘Tesalia.’),(2278,2,10,2,1,‘Alto
Gloria’),(2279,2,10,2,2,‘Aquilea (San
Francisco)’),(2280,2,10,2,3,‘Bonanza’),(2281,2,10,2,4,‘Caimitos’),(2282,2,10,2,5,‘Chaparral’),(2283,2,10,2,6,‘Cuestillas’),(2284,2,10,2,7,‘Jabillos
(parte)’),(2285,2,10,2,8,‘Molino’),(2286,2,10,2,9,‘Muelle de San
Carlos’),(2287,2,10,2,10,‘Pejeviejo’),(2288,2,10,2,11,‘Pénjamo’),(2289,2,10,2,12,‘Platanar’),(2290,2,10,2,13,‘Puente
Casa’),(2291,2,10,2,14,‘Puerto Escondido’),(2292,2,10,2,15,‘Quebrada
Azul’),(2293,2,10,2,16,‘San Juan’),(2294,2,10,2,17,‘San
Luis’),(2295,2,10,2,18,‘San Rafael’),(2296,2,10,2,19,‘Santa
Clara’),(2297,2,10,2,20,‘Santa María’),(2298,2,10,2,21,‘Santa
Rita’),(2299,2,10,2,22,‘Sapera’),(2300,2,10,2,23,‘Ulima’),(2301,2,10,2,24,‘Vega’),(2302,2,10,2,25,‘Vieja.’),(2303,2,10,3,1,‘Culebra’),(2304,2,10,3,2,‘Quina
(parte)’),(2305,2,10,3,3,‘San Antonio’),(2306,2,10,3,4,‘San
Bosco.’),(2307,2,10,4,1,‘Latino’),(2308,2,10,4,2,‘Manantial’),(2309,2,10,4,3,‘Montecristo’),(2310,2,10,4,4,‘Nazareth’),(2311,2,10,4,5,‘San
Bosco’),(2312,2,10,4,6,‘San Gerardo Viento
Fresco’),(2313,2,10,4,7,‘Vistas de la
Llanura.’),(2314,2,10,4,8,‘Altamira’),(2315,2,10,4,9,‘Alto Jiménez
(Montecristo)’),(2316,2,10,4,10,‘Bijagual’),(2317,2,10,4,11,‘Boca los
Chiles’),(2318,2,10,4,12,‘Cantarrana (Santa
Fe)’),(2319,2,10,4,13,‘Coopesanjuan’),(2320,2,10,4,14,‘Caño
Negro’),(2321,2,10,4,15,‘Cerrito’),(2322,2,10,4,16,‘Cerro
Cortés’),(2323,2,10,4,17,‘Concepción’),(2324,2,10,4,18,‘Chiles’),(2325,2,10,4,19,‘Danta’),(2326,2,10,4,20,‘Delicias’),(2327,2,10,4,21,‘Faroles’),(2328,2,10,4,22,‘Gloria’),(2329,2,10,4,23,‘Guabo’),(2330,2,10,4,24,‘Llanos
de Altamirita’),(2331,2,10,4,25,‘Pitalito Norte
(Esquipulas)’),(2332,2,10,4,26,‘Pitalito’),(2333,2,10,4,27,‘San
José’),(2334,2,10,4,28,‘Valle
Hermoso’),(2335,2,10,4,29,‘Vasconia’),(2336,2,10,4,30,‘Vuelta de
Kooper.’),(2337,2,10,5,1,‘Carmen’),(2338,2,10,5,2,‘Corazón de
Jesús’),(2339,2,10,5,3,‘El
Ceibo’),(2340,2,10,5,4,‘Jardín’),(2341,2,10,5,5,‘La
Gloria.’),(2342,2,10,5,6,‘Alpes’),(2343,2,10,5,7,‘Brisas’),(2344,2,10,5,8,‘Buenos
Aires’),(2345,2,10,5,9,‘Guayabo’),(2346,2,10,5,10,‘Latas’),(2347,2,10,5,11,‘Marsella’),(2348,2,10,5,12,‘Mesén’),(2349,2,10,5,13,‘Nazareth’),(2350,2,10,5,14,‘Negritos’),(2351,2,10,5,15,‘Paraíso’),(2352,2,10,5,16,‘Pueblo
Viejo’),(2353,2,10,5,17,‘San Cayetano’),(2354,2,10,5,18,‘San
Martín’),(2355,2,10,5,19,‘Unión.’),(2356,2,10,6,1,‘Bosque’),(2357,2,10,6,2,‘Comarca’),(2358,2,10,6,3,‘San
Cristóbal.’),(2359,2,10,6,4,‘Ángeles’),(2360,2,10,6,5,‘Boca
Sahíno’),(2361,2,10,6,6,‘Boca Tapada’),(2362,2,10,6,7,‘Boca Tres
Amigos’),(2363,2,10,6,8,‘Cabra’),(2364,2,10,6,9,‘Canacas’),(2365,2,10,6,10,‘Caño
Chu’),(2366,2,10,6,11,‘Cerro Blanco (San
Marcos)’),(2367,2,10,6,12,‘Cuatro
Esquinas’),(2368,2,10,6,13,‘Chaparrón’),(2369,2,10,6,14,‘Chirivico
(Coopeisabel)’),(2370,2,10,6,15,‘Encanto’),(2371,2,10,6,16,‘Fama
(Carmen)’),(2372,2,10,6,17,‘Flor’),(2373,2,10,6,18,‘I
Griega’),(2374,2,10,6,19,‘Josefina’),(2375,2,10,6,20,‘Legua’),(2376,2,10,6,21,‘Ojoche’),(2377,2,10,6,22,‘Ojochito’),(2378,2,10,6,23,‘Palmar’),(2379,2,10,6,24,‘Pegón’),(2380,2,10,6,25,‘Piedra
Alegre’),(2381,2,10,6,26,‘Puerto Escondido’),(2382,2,10,6,27,‘Quebrada
Grande’),(2383,2,10,6,28,‘Sahíno’),(2384,2,10,6,29,‘San
Luis’),(2385,2,10,6,30,‘Santa
Elena’),(2386,2,10,6,31,‘Tigre’),(2387,2,10,6,32,‘Trinchera’),(2388,2,10,6,33,‘Vegas’),(2389,2,10,6,34,‘Veracrúz’),(2390,2,10,6,35,‘Vuelta
Bolsón (parte)’),(2391,2,10,6,36,‘Vuelta
Tablón’),(2392,2,10,6,37,‘Yucatán.’),(2393,2,10,7,1,‘Agua
Azul’),(2394,2,10,7,2,‘Alamo’),(2395,2,10,7,3,‘Ángeles’),(2396,2,10,7,4,‘Burío’),(2397,2,10,7,5,‘Castillo
(parte)’),(2398,2,10,7,6,‘Guaria’),(2399,2,10,7,7,‘El Campo
(Guayabal)’),(2400,2,10,7,8,‘Jilguero’),(2401,2,10,7,9,‘Llano
Verde’),(2402,2,10,7,10,‘Orquídeas’),(2403,2,10,7,11,‘Palma’),(2404,2,10,7,12,‘Perla’),(2405,2,10,7,13,‘San
Isidro’),(2406,2,10,7,14,‘San Jorge’),(2407,2,10,7,15,‘Santa
Eduviges’),(2408,2,10,7,16,‘Sonafluca’),(2409,2,10,7,17,‘Tanque’),(2410,2,10,7,18,‘Tres
Esquinas’),(2411,2,10,7,19,‘Zeta
Trece.’),(2412,2,10,8,1,‘Ángeles’),(2413,2,10,8,2,‘Palmas.’),(2414,2,10,8,3,‘Cerritos’),(2415,2,10,8,4,‘Esperanza’),(2416,2,10,8,5,‘Futuro’),(2417,2,10,8,6,‘Jabillos
(parte)’),(2418,2,10,8,7,‘Lucha’),(2419,2,10,8,8,‘San
Gerardo’),(2420,2,10,8,9,‘San Isidro’),(2421,2,10,8,10,‘San
José’),(2422,2,10,8,11,‘San Miguel’),(2423,2,10,8,12,‘San
Pedro’),(2424,2,10,8,13,‘San
Rafael.’),(2425,2,10,9,1,‘Altura’),(2426,2,10,9,2,‘Calle
Damas’),(2427,2,10,9,3,‘Corea
(Concepción)’),(2428,2,10,9,4,‘Corte’),(2429,2,10,9,5,‘Loma’),(2430,2,10,9,6,‘Lourdes’),(2431,2,10,9,7,‘Marina’),(2432,2,10,9,8,‘Marinita’),(2433,2,10,9,9,‘San
Rafael’),(2434,2,10,9,10,‘San Rafael Sur’),(2435,2,10,9,11,‘Santa
Rosa’),(2436,2,10,9,12,‘Surtubal’),(2437,2,10,9,13,‘Unión’),(2438,2,10,9,14,‘Vacablanca
(San Francisco)’),(2439,2,10,9,15,‘Villa
María.’),(2440,2,10,10,1,‘Altamirita’),(2441,2,10,10,2,‘Alto Blanca
Lucía’),(2442,2,10,10,3,‘Burío’),(2443,2,10,10,4,‘Cacao’),(2444,2,10,10,5,‘Delicias’),(2445,2,10,10,6,‘Esperanza’),(2446,2,10,10,7,‘Jicarito’),(2447,2,10,10,8,‘Lindavista’),(2448,2,10,10,9,‘Poma
(parte)’),(2449,2,10,10,10,‘Puerto Seco’),(2450,2,10,10,11,‘San
Isidro’),(2451,2,10,10,12,‘Santa Eulalia
(parte)’),(2452,2,10,10,13,‘Santa
Lucía’),(2453,2,10,10,14,‘Tigra.’),(2454,2,10,11,1,‘Almendros’),(2455,2,10,11,2,‘Bellavista’),(2456,2,10,11,3,‘Betania’),(2457,2,10,11,4,‘Boca
de San Carlos’),(2458,2,10,11,5,‘Boca Providencia
(parte)’),(2459,2,10,11,6,‘Cabeceras de Aguas
Zarquitas’),(2460,2,10,11,7,‘Carmen’),(2461,2,10,11,8,‘Cascada’),(2462,2,10,11,9,‘Castelmare’),(2463,2,10,11,10,‘Cocobolo’),(2464,2,10,11,11,‘Coopevega’),(2465,2,10,11,12,‘Corazón
de
Jesús’),(2466,2,10,11,13,‘Crucitas’),(2467,2,10,11,14,‘Chamorrito’),(2468,2,10,11,15,‘Chamorro’),(2469,2,10,11,16,‘Chorreras’),(2470,2,10,11,17,‘Hebrón’),(2471,2,10,11,18,‘Isla
del Cura’),(2472,2,10,11,19,‘Isla Pobres’),(2473,2,10,11,20,‘Isla
Sábalo’),(2474,2,10,11,21,‘Jardín’),(2475,2,10,11,22,‘Kopper’),(2476,2,10,11,23,‘La
Cajeta’),(2477,2,10,11,24,‘Laurel
Galán’),(2478,2,10,11,25,‘Limoncito’),(2479,2,10,11,26,‘Moravia’),(2480,2,10,11,27,‘Patastillo’),(2481,2,10,11,28,‘Pueblo
Nuevo’),(2482,2,10,11,29,‘Recreo’),(2483,2,10,11,30,‘Río
Tico’),(2484,2,10,11,31,‘Roble’),(2485,2,10,11,32,‘San
Antonio’),(2486,2,10,11,33,‘San Fernando’),(2487,2,10,11,34,‘San
Francisco’),(2488,2,10,11,35,‘San Joaquín’),(2489,2,10,11,36,‘San
Jorge’),(2490,2,10,11,37,‘San José’),(2491,2,10,11,38,‘San
Marcos’),(2492,2,10,11,39,‘Santa Rita’),(2493,2,10,11,40,‘Santa
Teresa’),(2494,2,10,11,41,‘San Vito’),(2495,2,10,11,42,‘Tabla Grande
(San Pedro)’),(2496,2,10,11,43,‘Terrón Colorado
(parte)’),(2497,2,10,11,44,‘Ventanas’),(2498,2,10,11,45,‘Vuelta Bolsón
(parte)’),(2499,2,10,11,46,‘Vuelta
Millonarios’),(2500,2,10,11,47,‘Vuelta
Ruedas.’),(2501,2,10,12,1,‘Pericos.’),(2502,2,10,12,2,‘Bajillo’),(2503,2,10,12,3,‘Caño
Ciego’),(2504,2,10,12,4,‘Cedros’),(2505,2,10,12,5,‘Chambacú’),(2506,2,10,12,6,‘Delicias’),(2507,2,10,12,7,‘La
Unión’),(2508,2,10,12,8,‘Maquencal’),(2509,2,10,12,9,‘Mirador’),(2510,2,10,12,10,‘Montelimar’),(2511,2,10,12,11,‘Monterrey’),(2512,2,10,12,12,‘Orquídea’),(2513,2,10,12,13,‘Pataste
Arriba’),(2514,2,10,12,14,‘Sabalito’),(2515,2,10,12,15,‘San
Andrés’),(2516,2,10,12,16,‘San Antonio’),(2517,2,10,12,17,‘San
Cristóbal’),(2518,2,10,12,18,‘San Miguel’),(2519,2,10,12,19,‘Santa
Eulalia (parte)’),(2520,2,10,12,20,‘Santa
Marta.’),(2521,2,10,13,1,‘Fátima’),(2522,2,10,13,2,‘Parajeles’),(2523,2,10,13,3,‘Tres
Perlas’),(2524,2,10,13,4,‘Valle
Hermoso.’),(2525,2,10,13,5,‘Acapulco’),(2526,2,10,13,6,‘Aldea’),(2527,2,10,13,7,‘Ángeles’),(2528,2,10,13,8,‘Azucena’),(2529,2,10,13,9,‘Banderas’),(2530,2,10,13,10,‘Boca
Providencia
(parte)’),(2531,2,10,13,11,‘Brisas’),(2532,2,10,13,12,‘Buenavista’),(2533,2,10,13,13,‘Buenos
Aires’),(2534,2,10,13,14,‘Carrizal’),(2535,2,10,13,15,‘Ceiba’),(2536,2,10,13,16,‘Conchito’),(2537,2,10,13,17,‘Concho’),(2538,2,10,13,18,‘Cuatro
Esquinas’),(2539,2,10,13,19,‘Esterito’),(2540,2,10,13,20,‘Estero’),(2541,2,10,13,21,‘Estrella’),(2542,2,10,13,22,‘Guaria’),(2543,2,10,13,23,‘Jazmín’),(2544,2,10,13,24,‘Jocote’),(2545,2,10,13,25,‘Juanilama’),(2546,2,10,13,26,‘Luisa’),(2547,2,10,13,27,‘Llano
Verde’),(2548,2,10,13,28,‘Morazán’),(2549,2,10,13,29,‘Nieves’),(2550,2,10,13,30,‘Paraíso’),(2551,2,10,13,31,‘Paso
Real’),(2552,2,10,13,32,‘Plomo’),(2553,2,10,13,33,‘Pocosol’),(2554,2,10,13,34,‘Providencia
(San Luis)’),(2555,2,10,13,35,‘Pueblo Nuevo’),(2556,2,10,13,36,‘Pueblo
Santo’),(2557,2,10,13,37,‘Rancho
Quemado’),(2558,2,10,13,38,‘Rubí’),(2559,2,10,13,39,‘San
Alejo’),(2560,2,10,13,40,‘San Bosco’),(2561,2,10,13,41,‘San
Cristobal’),(2562,2,10,13,42,‘San Diego’),(2563,2,10,13,43,‘San
Gerardo’),(2564,2,10,13,44,‘San Isidro’),(2565,2,10,13,45,‘San
Martín’),(2566,2,10,13,46,‘San Rafael’),(2567,2,10,13,47,‘Santa
Cecilia’),(2568,2,10,13,48,‘Santa Esperanza’),(2569,2,10,13,49,‘Santa
María’),(2570,2,10,13,50,‘Terrón Colorado
(parte)’),(2571,2,10,13,51,‘Tiricias’),(2572,2,10,13,52,‘Tres y
Tres.’),(2573,2,11,1,1,‘Cantarranas’),(2574,2,11,1,2,‘Santa
Teresita.’),(2575,2,11,2,1,‘Peña.’),(2576,2,11,4,1,‘Anateri’),(2577,2,11,4,2,‘Bellavista’),(2578,2,11,4,3,‘Morelos.’),(2579,2,11,5,1,‘Picada.’),(2580,2,11,6,1,‘Quina
(parte)’),(2581,2,11,6,2,‘San Juan de Lajas’),(2582,2,11,6,3,‘Santa
Elena.’),(2583,2,11,7,1,‘Ángeles’),(2584,2,11,7,2,‘Brisa’),(2585,2,11,7,3,‘Legua.’),(2586,2,12,1,1,‘Ángeles’),(2587,2,12,1,2,‘Bajo
Raimundo’),(2588,2,12,1,3,‘Canto’),(2589,2,12,1,4,‘Eva’),(2590,2,12,1,5,‘Luisa’),(2591,2,12,1,6,‘San
Rafael (Rincón
Colorado)’),(2592,2,12,1,7,‘Sahinal.’),(2593,2,12,2,1,‘Alto
Castro’),(2594,2,12,2,0,‘Bajo
Trapiche’),(2595,2,12,2,2,‘Coopeoctava’),(2596,2,12,2,0,‘Ranera.’),(2597,2,12,2,3,‘Ratoncillal’),(2598,2,12,2,4,‘Rincón
de Alpízar’),(2599,2,12,2,5,‘Rincón de Ulate’),(2600,2,12,2,6,‘San
Miguel.’),(2601,2,12,3,1,‘Alto
Palomo.’),(2602,2,12,4,1,‘Castro’),(2603,2,12,4,2,‘Concha’),(2604,2,12,4,3,‘Pueblo
Seco’),(2605,2,12,4,4,‘Talolinga’),(2606,2,12,4,5,‘Trojas.’),(2607,2,12,5,1,‘Bambú’),(2608,2,12,5,2,‘Sabanilla.’),(2609,2,13,1,1,‘Don
Chu’),(2610,2,13,1,2,‘La Unión’),(2611,2,13,1,3,‘Las
Palmas’),(2612,2,13,1,4,‘Venecia.’),(2613,2,13,1,5,‘Ángeles
(parte)’),(2614,2,13,1,6,‘Carmen’),(2615,2,13,1,7,‘Colonia
Puntarenas’),(2616,2,13,1,8,‘Corteza’),(2617,2,13,1,9,‘Fósforo’),(2618,2,13,1,10,‘Jazmines’),(2619,2,13,1,11,‘Llano
Azul’),(2620,2,13,1,12,‘Maravilla’),(2621,2,13,1,13,‘Miravalles’),(2622,2,13,1,14,‘Moreno
Cañas’),(2623,2,13,1,15,‘Recreo’),(2624,2,13,1,16,‘San
Fernando’),(2625,2,13,1,17,‘San Luis’),(2626,2,13,1,18,‘San
Martín’),(2627,2,13,1,19,‘Santa Cecilia’),(2628,2,13,1,20,‘Santa
Rosa’),(2629,2,13,1,21,‘Verbena
(parte).’),(2630,2,13,2,1,‘Ceiba’),(2631,2,13,2,2,‘Golfo’),(2632,2,13,2,3,‘Porras.’),(2633,2,13,2,4,‘Aguas
Claras’),(2634,2,13,2,5,‘Buenos Aires’),(2635,2,13,2,6,‘Colonia
Blanca’),(2636,2,13,2,7,‘Colonia Libertad’),(2637,2,13,2,8,‘Cuatro
Bocas’),(2638,2,13,2,9,‘Chepa
(Ángeles)’),(2639,2,13,2,10,‘Guayabal’),(2640,2,13,2,11,‘Guinea’),(2641,2,13,2,12,‘La
Gloria’),(2642,2,13,2,13,‘Porvenir’),(2643,2,13,2,14,‘Río
Negro’),(2644,2,13,2,15,‘Tigra’),(2645,2,13,2,16,‘Torre’),(2646,2,13,2,17,‘Vuelta
San Pedro.’),(2647,2,13,3,1,‘Camelias’),(2648,2,13,3,2,‘Líbano
(Finanzas)’),(2649,2,13,3,3,‘Nazareno.’),(2650,2,13,3,4,‘Ángeles
(parte)’),(2651,2,13,3,5,‘Betania’),(2652,2,13,3,6,‘Caño
Blanco’),(2653,2,13,3,7,‘Cartagos Norte’),(2654,2,13,3,8,‘Cartagos
Sur’),(2655,2,13,3,9,‘Copey (Santa
Lucía)’),(2656,2,13,3,10,‘Delirio’),(2657,2,13,3,11,‘Fátima’),(2658,2,13,3,12,‘Jesús
María’),(2659,2,13,3,13,‘Linda
Vista’),(2660,2,13,3,14,‘Mango’),(2661,2,13,3,15,‘Papagayo’),(2662,2,13,3,16,‘Pinol’),(2663,2,13,3,17,‘Pizotillo’),(2664,2,13,3,18,‘Popoyuapa’),(2665,2,13,3,19,‘Progreso’),(2666,2,13,3,20,‘Pueblo
Nuevo’),(2667,2,13,3,21,‘San Bosco’),(2668,2,13,3,22,‘San
Pedro’),(2669,2,13,3,23,‘San Ramón’),(2670,2,13,3,24,‘Santa Clara Norte
(parte)’),(2671,2,13,3,25,‘Unión’),(2672,2,13,3,26,‘Valle’),(2673,2,13,3,27,‘Valle
Bonito’),(2674,2,13,3,28,‘Victoria
(parte)’),(2675,2,13,3,29,‘Villahermosa’),(2676,2,13,3,30,‘Villanueva.’),(2677,2,13,4,1,‘Altamira’),(2678,2,13,4,2,‘Carlos
Vargas.’),(2679,2,13,4,3,‘Ángeles’),(2680,2,13,4,4,‘Achiote’),(2681,2,13,4,5,‘Cuesta
Pichardo’),(2682,2,13,4,6,‘Chorros’),(2683,2,13,4,7,‘Florecitas’),(2684,2,13,4,8,‘Flores’),(2685,2,13,4,9,‘Higuerón’),(2686,2,13,4,10,‘Jardín’),(2687,2,13,4,11,‘Macho’),(2688,2,13,4,12,‘Pata
de Gallo (San Cristobal) (parte)’),(2689,2,13,4,13,‘Pueblo
Nuevo’),(2690,2,13,4,14,‘Reserva’),(2691,2,13,4,15,‘San
Miguel’),(2692,2,13,4,16,‘Santo
Domingo’),(2693,2,13,4,17,‘Zapote.’),(2694,2,13,5,1,‘Camelias’),(2695,2,13,5,2,‘La
Cruz’),(2696,2,13,5,3,‘México’),(2697,2,13,5,4,‘Mocorón’),(2698,2,13,5,5,‘Pavas’),(2699,2,13,5,6,‘Perla’),(2700,2,13,5,7,‘Quebradón’),(2701,2,13,5,8,‘Santa
Clara Norte (parte)’),(2702,2,13,5,9,‘Santa Clara
Sur’),(2703,2,13,5,10,‘Trapiche’),(2704,2,13,5,11,‘Victoria
(parte).’),(2705,2,13,6,1,‘Birmania’),(2706,2,13,6,2,‘Brasilia’),(2707,2,13,6,3,‘Colonia’),(2708,2,13,6,4,‘Gavilán’),(2709,2,13,6,5,‘Jabalina’),(2710,2,13,6,6,‘Progreso’),(2711,2,13,6,7,‘San
Luis.’),(2712,2,13,7,1,‘Cabanga’),(2713,2,13,7,2,‘Campo
Verde’),(2714,2,13,7,3,‘Carmen’),(2715,2,13,7,4,‘Cinco
Esquinas’),(2716,2,13,7,5,‘Chimurria
Abajo’),(2717,2,13,7,6,‘Flores’),(2718,2,13,7,7,‘Jobo’),(2719,2,13,7,8,‘Montecristo’),(2720,2,13,7,9,‘Nazareth’),(2721,2,13,7,10,‘Quebrada
Grande’),(2722,2,13,7,11,‘San Gabriel’),(2723,2,13,7,12,‘San
Jorge’),(2724,2,13,7,13,‘Socorro’),(2725,2,13,7,14,‘Virgen’),(2726,2,13,7,15,‘Yolillal
(San Antonio).’),(2727,2,13,8,1,‘Armenias’),(2728,2,13,8,2,‘Las
Brisas’),(2729,2,13,8,3,‘Buenavista’),(2730,2,13,8,4,‘Cuatro
Cruces’),(2731,2,13,8,5,‘Guacalito’),(2732,2,13,8,6,‘Milpas’),(2733,2,13,8,7,‘Miramar’),(2734,2,13,8,8,‘Pata
de Gallo (San Cristóbal)
(parte)’),(2735,2,13,8,9,‘Rosario’),(2736,2,13,8,10,‘Verbena
(parte).’),(2737,2,14,1,1,‘Loma’),(2738,2,14,1,2,‘Portón.’),(2739,2,14,1,3,‘Arco
Iris’),(2740,2,14,1,4,‘Berlín’),(2741,2,14,1,5,‘Brisas’),(2742,2,14,1,6,‘Buenos
Aires’),(2743,2,14,1,7,‘Cabro’),(2744,2,14,1,8,‘Cachito’),(2745,2,14,1,9,‘Caña
Castilla’),(2746,2,14,1,10,‘Combate’),(2747,2,14,1,11,‘Coquital’),(2748,2,14,1,12,‘Cristo
Rey’),(2749,2,14,1,13,‘Cuacas’),(2750,2,14,1,14,‘Cuatro
Esquinas’),(2751,2,14,1,15,‘Delicias’),(2752,2,14,1,16,‘El
Cruce’),(2753,2,14,1,17,‘Escaleras’),(2754,2,14,1,18,‘Esperanza’),(2755,2,14,1,19,‘Estrella’),(2756,2,14,1,20,‘Hernández’),(2757,2,14,1,21,‘Isla
Chica’),(2758,2,14,1,22,‘Las
Nubes’),(2759,2,14,1,23,‘Maramba’),(2760,2,14,1,24,‘Masaya’),(2761,2,14,1,25,‘Medio
Queso’),(2762,2,14,1,26,‘Parque’),(2763,2,14,1,27,‘Playuelitas’),(2764,2,14,1,28,‘Primavera’),(2765,2,14,1,29,‘Pueblo
Nuevo’),(2766,2,14,1,30,‘Punta
Cortés’),(2767,2,14,1,31,‘Rampla’),(2768,2,14,1,32,‘Refugio’),(2769,2,14,1,33,‘Roble’),(2770,2,14,1,34,‘San
Alejandro’),(2771,2,14,1,35,‘San Gerardo’),(2772,2,14,1,36,‘San
Jerónimo’),(2773,2,14,1,37,‘San Pablo’),(2774,2,14,1,38,‘Santa
Elena’),(2775,2,14,1,39,‘Santa Fe’),(2776,2,14,1,40,‘Santa
Rita’),(2777,2,14,1,41,‘Santa
Rosa’),(2778,2,14,1,42,‘Solanos’),(2779,2,14,1,43,‘Trocha’),(2780,2,14,1,44,‘Virgen.’),(2781,2,14,2,1,‘Aguas
Negras’),(2782,2,14,2,2,‘Islas Cubas’),(2783,2,14,2,3,‘Nueva
Esperanza’),(2784,2,14,2,4,‘Pilones’),(2785,2,14,2,5,‘Playuelas’),(2786,2,14,2,6,‘Porvenir’),(2787,2,14,2,7,‘San
Antonio’),(2788,2,14,2,8,‘San
Emilio’),(2789,2,14,2,9,‘Veracruz.’),(2790,2,14,3,1,‘Alto los
Reyes’),(2791,2,14,3,2,‘Caño
Ciego’),(2792,2,14,3,3,‘Cóbano’),(2793,2,14,3,4,‘Corozo’),(2794,2,14,3,5,‘Corrales’),(2795,2,14,3,6,‘Coyol’),(2796,2,14,3,7,‘Dos
Aguas’),(2797,2,14,3,8,‘Gallito’),(2798,2,14,3,9,‘Gallo Pinto
(parte)’),(2799,2,14,3,10,‘Montealegre’),(2800,2,14,3,11,‘Nueva
Lucha’),(2801,2,14,3,12,‘Pavón’),(2802,2,14,3,13,‘Quebrada
Grande’),(2803,2,14,3,14,‘Sabogal’),(2804,2,14,3,15,‘San
Antonio’),(2805,2,14,3,16,‘San Francisco’),(2806,2,14,3,17,‘San
Isidro’),(2807,2,14,3,18,‘San José del Amparo’),(2808,2,14,3,19,‘San
Macario’),(2809,2,14,3,20,‘Santa
Cecilia’),(2810,2,14,3,21,‘Trinidad’),(2811,2,14,3,22,‘Vasconia.’),(2812,2,14,4,1,‘Botijo’),(2813,2,14,4,2,‘Chimurria’),(2814,2,14,4,3,‘Colonia
París’),(2815,2,14,4,4,‘Coquitales’),(2816,2,14,4,5,‘Gallo Pinto
(Parte)’),(2817,2,14,4,6,‘Lirios’),(2818,2,14,4,7,‘Lucha’),(2819,2,14,4,8,‘Montealegre
(Parte)’),(2820,2,14,4,9,‘Pueblo Nuevo’),(2821,2,14,4,10,‘San
Humberto’),(2822,2,14,4,11,‘San Isidro’),(2823,2,14,4,12,‘San
Jorge’),(2824,2,14,4,13,‘San
Rafael’),(2825,2,14,4,14,‘Terranova’),(2826,2,14,4,15,‘Tigra’),(2827,2,14,4,16,‘Zamba.’),(2828,2,15,2,1,‘Cabaña
(parte)’),(2829,2,15,2,2,‘Costa Ana’),(2830,2,15,2,3,‘El
Cruce’),(2831,2,15,2,4,‘Guayabito’),(2832,2,15,2,5,‘Mónico’),(2833,2,15,2,6,‘Samén
Abajo’),(2834,2,15,2,7,‘San
José’),(2835,2,15,2,8,‘Thiales’),(2836,2,15,2,9,‘Valle del
Río.’),(2837,2,15,3,1,‘Altagracia’),(2838,2,15,3,2,‘Alto
Sahíno’),(2839,2,15,3,3,‘Bajo
Cartagos’),(2840,2,15,3,4,‘Pato’),(2841,2,15,3,5,‘Pejibaye’),(2842,2,15,3,6,‘Pimiento’),(2843,2,15,3,7,‘Quebradón.’),(2844,2,15,4,1,‘Cabaña
(parte)’),(2845,2,15,4,2,‘Colonia Naranjeña’),(2846,2,15,4,3,‘El
Valle’),(2847,2,15,4,4,‘Florida’),(2848,2,15,4,5,‘Las
Letras’),(2849,2,15,4,6,‘La Paz’),(2850,2,15,4,7,‘La
Unión’),(2851,2,15,4,8,‘Llano Bonito 1’),(2852,2,15,4,9,‘Llano Bonito
2’),(2853,2,15,4,10,‘Palmera’),(2854,2,15,4,11,‘Río
Celeste’),(2855,2,15,4,12,‘Tujankir 1’),(2856,2,15,4,13,‘Tujankir
2.’),(2857,3,1,1,1,‘Ángeles’),(2858,3,1,1,2,‘Asís’),(2859,3,1,1,3,‘Brisas’),(2860,3,1,1,4,‘Calvario’),(2861,3,1,1,5,‘Cerrillos’),(2862,3,1,1,6,‘Corazón
de Jesús’),(2863,3,1,1,7,‘Cortinas’),(2864,3,1,1,8,‘Cruz de Caravaca
(parte)’),(2865,3,1,1,9,‘Estadio’),(2866,3,1,1,10,‘Galera’),(2867,3,1,1,11,‘Hospital
(parte)’),(2868,3,1,1,12,‘Istarú’),(2869,3,1,1,13,‘Jesús Jiménez
(parte)’),(2870,3,1,1,14,‘Matamoros’),(2871,3,1,1,15,‘Montelimar’),(2872,3,1,1,16,‘Puebla’),(2873,3,1,1,17,‘Soledad’),(2874,3,1,1,18,‘Telles.’),(2875,3,1,2,1,‘Cinco
Esquinas’),(2876,3,1,2,2,‘Fátima’),(2877,3,1,2,3,‘Hospital
(parte)’),(2878,3,1,2,4,‘Jesús Jiménez
(parte)’),(2879,3,1,2,5,‘Laborio’),(2880,3,1,2,6,‘Molino’),(2881,3,1,2,7,‘Murillo’),(2882,3,1,2,8,‘Palmas’),(2883,3,1,2,9,‘San
Cayetano.’),(2884,3,1,3,1,‘Alpes’),(2885,3,1,3,2,‘Asilo’),(2886,3,1,3,3,‘Cruz
de Caravaca
(parte)’),(2887,3,1,3,4,‘Diques’),(2888,3,1,3,5,‘Fontana’),(2889,3,1,3,6,‘Jora’),(2890,3,1,3,7,‘López’),(2891,3,1,3,8,‘San
Blas’),(2892,3,1,3,9,‘Santa Eduvigis’),(2893,3,1,3,10,‘Santa
Fe’),(2894,3,1,3,11,‘Solano’),(2895,3,1,3,12,‘Turbina.’),(2896,3,1,4,1,‘Alto
de
Ochomogo’),(2897,3,1,4,2,‘Angelina’),(2898,3,1,4,0,‘Caracol’),(2899,3,1,4,3,‘Banderilla’),(2900,3,1,4,0,‘Cooperrosales’),(2901,3,1,4,4,‘Cruz’),(2902,3,1,4,0,‘Kerkua’),(2903,3,1,4,5,‘Espinal’),(2904,3,1,4,0,‘Molina’),(2905,3,1,4,6,‘Johnson’),(2906,3,1,4,0,‘Poroses.’),(2907,3,1,4,7,‘Lima’),(2908,3,1,4,8,‘Loyola’),(2909,3,1,4,9,‘Nazareth’),(2910,3,1,4,10,‘Ochomogo’),(2911,3,1,4,11,‘Orontes’),(2912,3,1,4,12,‘Pedregal’),(2913,3,1,4,13,‘Quircot’),(2914,3,1,4,14,‘Ronda’),(2915,3,1,4,15,‘Rosas’),(2916,3,1,4,16,‘San
Nicolás’),(2917,3,1,4,17,‘Violín.’),(2918,3,1,5,1,‘Cocorí’),(2919,3,1,5,2,‘Coronado’),(2920,3,1,5,3,‘Guayabal
(parte)’),(2921,3,1,5,4,‘Hervidero’),(2922,3,1,5,5,‘López’),(2923,3,1,5,6,‘Lourdes’),(2924,3,1,5,7,‘Padua’),(2925,3,1,5,8,‘Pitahaya.’),(2926,3,1,5,9,‘Barro
Morado’),(2927,3,1,5,10,‘Cenicero’),(2928,3,1,5,11,‘Muñeco’),(2929,3,1,5,12,‘Navarrito.’),(2930,3,1,6,1,‘Américas’),(2931,3,1,6,2,‘Higuerón’),(2932,3,1,6,3,‘Joya’),(2933,3,1,6,4,‘Marías’),(2934,3,1,6,5,‘Las
Palmas.’),(2935,3,1,7,1,‘Alumbre’),(2936,3,1,7,2,‘Bajo
Amador’),(2937,3,1,7,3,‘Calle
Valverdes’),(2938,3,1,7,4,‘Guaria’),(2939,3,1,7,5,‘Hortensia’),(2940,3,1,7,6,‘Loma
Larga’),(2941,3,1,7,7,‘Llano
Ángeles’),(2942,3,1,7,8,‘Palangana’),(2943,3,1,7,9,‘Rincón de
Abarca’),(2944,3,1,7,10,‘Río
Conejo’),(2945,3,1,7,11,‘Salitrillo’),(2946,3,1,7,12,‘San
Antonio’),(2947,3,1,7,13,‘San Joaquín’),(2948,3,1,7,14,‘San Juan
Norte’),(2949,3,1,7,15,‘San Juan Sur’),(2950,3,1,7,16,‘Santa Elena
(parte)’),(2951,3,1,7,17,‘Santa Elena Arriba.’),(2952,3,1,8,1,‘Cuesta de
Piedra’),(2953,3,1,8,2,‘Misión Norte’),(2954,3,1,8,3,‘Misión
Sur’),(2955,3,1,8,4,‘Ortiga’),(2956,3,1,8,5,‘Rodeo’),(2957,3,1,8,6,‘Sabanilla’),(2958,3,1,8,7,‘Sabanillas’),(2959,3,1,8,8,‘Santísima
Trinidad.’),(2960,3,1,9,1,‘Caballo Blanco (parte)’),(2961,3,1,9,2,‘San
José.’),(2962,3,1,9,3,‘Cóncavas’),(2963,3,1,9,4,‘Navarro’),(2964,3,1,9,5,‘Perlas’),(2965,3,1,9,6,‘Río
Claro.’),(2966,3,1,10,1,‘Azahar.’),(2967,3,1,10,2,‘Avance’),(2968,3,1,10,3,‘Barquero’),(2969,3,1,10,4,‘Cañada’),(2970,3,1,10,5,‘Laguna’),(2971,3,1,10,6,‘Pénjamo’),(2972,3,1,10,7,‘Rodeo.’),(2973,3,1,11,1,‘Alto
Quebradilla’),(2974,3,1,11,2,‘Azahar’),(2975,3,1,11,3,‘Bermejo’),(2976,3,1,11,4,‘Cañada’),(2977,3,1,11,5,‘Copalchí’),(2978,3,1,11,6,‘Coris’),(2979,3,1,11,7,‘Garita
(parte)’),(2980,3,1,11,8,‘Rueda’),(2981,3,1,11,9,‘Valle
Verde.’),(2982,3,2,1,1,‘Barro Hondo’),(2983,3,2,1,2,‘Cruz
Roja’),(2984,3,2,1,3,‘Cucaracho’),(2985,3,2,1,4,‘Chiverre’),(2986,3,2,1,5,‘Estación’),(2987,3,2,1,6,‘Joya’),(2988,3,2,1,7,‘Pandora’),(2989,3,2,1,8,‘Piedra
Grande’),(2990,3,2,1,9,‘Solares’),(2991,3,2,1,10,‘Soledad’),(2992,3,2,1,11,‘Veintiocho
de Diciembre.’),(2993,3,2,1,12,‘Alto
Birrisito’),(2994,3,2,1,13,‘Birrisito’),(2995,3,2,1,14,‘Chiral’),(2996,3,2,1,15,‘Luisiana’),(2997,3,2,1,16,‘Sanchirí’),(2998,3,2,1,17,‘Ujarrás’),(2999,3,2,1,18,‘Villa
Isabel’),(3000,3,2,1,19,‘Rincón.’),(3001,3,2,2,1,‘Acevedo’),(3002,3,2,2,2,‘Ajenjal’),(3003,3,2,2,3,‘Arrabará’),(3004,3,2,2,4,‘Birrís
(este)’),(3005,3,2,2,5,‘Cúscares’),(3006,3,2,2,6,‘Flor’),(3007,3,2,2,7,‘Lapuente’),(3008,3,2,2,8,‘Mesas’),(3009,3,2,2,9,‘Mesitas’),(3010,3,2,2,10,‘Nueva
Ujarrás’),(3011,3,2,2,11,‘Pedregal’),(3012,3,2,2,12,‘Piedra
Azul’),(3013,3,2,2,13,‘Puente Fajardo’),(3014,3,2,2,14,‘Río
Regado’),(3015,3,2,2,15,‘Talolinga’),(3016,3,2,2,16,‘Yas.’),(3017,3,2,3,1,‘Alegría’),(3018,3,2,3,2,‘Alto
Araya’),(3019,3,2,3,3,‘Calle
Jucó’),(3020,3,2,3,4,‘Hotel’),(3021,3,2,3,5,‘Nubes’),(3022,3,2,3,6,‘Palomas’),(3023,3,2,3,7,‘Palomo’),(3024,3,2,3,8,‘Patillos’),(3025,3,2,3,9,‘Puente
Negro’),(3026,3,2,3,10,‘Purisil’),(3027,3,2,3,11,‘Queverí’),(3028,3,2,3,12,‘Río
Macho’),(3029,3,2,3,13,‘San
Rafael’),(3030,3,2,3,14,‘Sitio’),(3031,3,2,3,15,‘Tapantí’),(3032,3,2,3,16,‘Troya’),(3033,3,2,3,17,‘Villa
Mills.’),(3034,3,2,4,1,‘Peñas Blancas’),(3035,3,2,4,2,‘Pueblo
Nuevo.’),(3036,3,2,4,3,‘Bajos de Dorotea’),(3037,3,2,4,4,‘Bajos de
Urasca’),(3038,3,2,4,5,‘Guábata’),(3039,3,2,4,6,‘Guatusito’),(3040,3,2,4,7,‘Hamaca
(parte)’),(3041,3,2,4,8,‘Joyas’),(3042,3,2,4,9,‘Loaiza’),(3043,3,2,4,10,‘San
Jerónimo’),(3044,3,2,4,11,‘Urasca’),(3045,3,2,4,12,‘Volio.’),(3046,3,2,5,1,‘Ayala’),(3047,3,2,5,2,‘Páez
(parte)’),(3048,3,2,5,3,‘Salvador.’),(3049,3,3,1,1,‘Antigua’),(3050,3,3,1,2,‘Villas.’),(3051,3,3,2,1,‘Eulalia’),(3052,3,3,2,2,‘Florencio
del
Castillo’),(3053,3,3,2,3,‘Jirales’),(3054,3,3,2,4,‘Mariana’),(3055,3,3,2,5,‘Tacora.’),(3056,3,3,2,6,‘Rincón
Mesén (parte)’),(3057,3,3,2,7,‘Santiago del
Monte.’),(3058,3,3,3,1,‘Araucarias (parte)’),(3059,3,3,3,2,‘Danza del
Sol’),(3060,3,3,3,3,‘Herrán’),(3061,3,3,3,4,‘Loma
Verde’),(3062,3,3,3,5,‘Unión’),(3063,3,3,3,6,‘Villas de
Ayarco.’),(3064,3,3,4,1,‘Carpintera’),(3065,3,3,4,2,‘San
Miguel’),(3066,3,3,4,3,‘San
Vicente’),(3067,3,3,4,4,‘Pilarica.’),(3068,3,3,4,5,‘Quebrada del
Fierro’),(3069,3,3,4,6,‘Fierro’),(3070,3,3,4,7,‘Yerbabuena.’),(3071,3,3,5,1,‘Cima’),(3072,3,3,5,2,‘Cuadrante’),(3073,3,3,5,3,‘Lirios’),(3074,3,3,5,4,‘Llanos
de Concepción’),(3075,3,3,5,5,‘Naranjal
(parte)’),(3076,3,3,5,6,‘Poró’),(3077,3,3,5,7,‘Salitrillo’),(3078,3,3,5,8,‘San
Francisco’),(3079,3,3,5,9,‘San Josecito (parte).’),(3080,3,3,6,1,‘Alto
del
Carmen’),(3081,3,3,6,2,‘Tirrá.’),(3082,3,3,7,1,‘Bellomonte’),(3083,3,3,7,2,‘Cerrillo’),(3084,3,3,7,3,‘Cumbres’),(3085,3,3,7,4,‘Holandés’),(3086,3,3,7,5,‘Mansiones
(parte)’),(3087,3,3,7,6,‘Montaña Rusa (parte)’),(3088,3,3,7,7,‘Naranjal
(parte)’),(3089,3,3,7,8,‘San Josecito
(parte).’),(3090,3,3,8,1,‘Lindavista (Loma
Gobierno).’),(3091,3,3,8,2,‘Quebradas’),(3092,3,3,8,3,‘Rincón Mesén
(parte).’),(3093,3,4,1,1,‘Alpes’),(3094,3,4,1,2,‘Buenos
Aires’),(3095,3,4,1,3,‘Maravilla’),(3096,3,4,1,4,‘Naranjito’),(3097,3,4,1,5,‘Naranjo’),(3098,3,4,1,6,‘San
Martín.’),(3099,3,4,1,7,‘Durán’),(3100,3,4,1,8,‘Gloria’),(3101,3,4,1,9,‘Quebrada
Honda’),(3102,3,4,1,10,‘Santa Elena’),(3103,3,4,1,11,‘Santa
Marta’),(3104,3,4,1,12,‘Victoria (Alto Victoria).’),(3105,3,4,2,1,‘Alto
Campos’),(3106,3,4,2,2,‘Bajo
Congo’),(3107,3,4,2,3,‘Congo’),(3108,3,4,2,4,‘Duan’),(3109,3,4,2,5,‘Esperanza’),(3110,3,4,2,6,‘Hamaca
(parte)’),(3111,3,4,2,7,‘Sabanilla’),(3112,3,4,2,8,‘San Antonio del
Monte’),(3113,3,4,2,9,‘Volconda’),(3114,3,4,2,10,‘Vueltas.’),(3115,3,4,3,1,‘Alto
Humo’),(3116,3,4,3,2,‘Cacao’),(3117,3,4,3,3,‘Cantarrana’),(3118,3,4,3,4,‘Casa
de
Teja’),(3119,3,4,3,5,‘Ceiba’),(3120,3,4,3,6,‘Chucuyo’),(3121,3,4,3,7,‘Esperanza’),(3122,3,4,3,8,‘Gato’),(3123,3,4,3,9,‘Humo’),(3124,3,4,3,10,‘Joyas’),(3125,3,4,3,11,‘Juray’),(3126,3,4,3,12,‘Omega’),(3127,3,4,3,13,‘Oriente’),(3128,3,4,3,14,‘San
Gerardo’),(3129,3,4,3,15,‘Selva’),(3130,3,4,3,16,‘Taus’),(3131,3,4,3,17,‘Tausito’),(3132,3,4,3,18,‘Yolanda’),(3133,3,4,3,19,‘Zapote.’),(3134,3,5,1,1,‘Américas’),(3135,3,5,1,2,‘Ángeles’),(3136,3,5,1,3,‘Cabiria’),(3137,3,5,1,4,‘Campabadal’),(3138,3,5,1,5,‘Castro
Salazar’),(3139,3,5,1,6,‘Cementerio’),(3140,3,5,1,7,‘Clorito
Picado’),(3141,3,5,1,8,‘Dominica’),(3142,3,5,1,9,‘El
Silencio’),(3143,3,5,1,10,‘Guaria’),(3144,3,5,1,11,‘Haciendita’),(3145,3,5,1,12,‘Margot’),(3146,3,5,1,13,‘Nochebuena’),(3147,3,5,1,14,‘Numa’),(3148,3,5,1,15,‘Pastor’),(3149,3,5,1,16,‘Poró’),(3150,3,5,1,17,‘Pueblo
Nuevo’),(3151,3,5,1,18,‘Repasto’),(3152,3,5,1,19,‘San
Cayetano’),(3153,3,5,1,20,‘San
Rafael’),(3154,3,5,1,21,‘Sictaya.’),(3155,3,5,1,22,‘Bajo
Barrientos’),(3156,3,5,1,23,‘Cañaveral’),(3157,3,5,1,24,‘Colorado’),(3158,3,5,1,25,‘Chiz’),(3159,3,5,1,26,‘Esmeralda’),(3160,3,5,1,27,‘Florencia’),(3161,3,5,1,28,‘Murcia’),(3162,3,5,1,29,‘Pavas’),(3163,3,5,1,30,‘Recreo’),(3164,3,5,1,31,‘Roncha’),(3165,3,5,1,32,‘San
Juan Norte’),(3166,3,5,1,33,‘San Juan Sur.’),(3167,3,5,2,1,‘Leona
(parte).’),(3168,3,5,2,2,‘Abelardo Rojas’),(3169,3,5,2,3,‘Alto
Alemania’),(3170,3,5,2,4,‘Atirro’),(3171,3,5,2,5,‘Balalaica’),(3172,3,5,2,6,‘Buenos
Aires’),(3173,3,5,2,7,‘Canadá’),(3174,3,5,2,8,‘Carmen’),(3175,3,5,2,9,‘Cruzada’),(3176,3,5,2,10,‘Danta’),(3177,3,5,2,11,‘Gaviotas’),(3178,3,5,2,12,‘Guadalupe’),(3179,3,5,2,13,‘Máquina
Vieja’),(3180,3,5,2,14,‘Margarita’),(3181,3,5,2,15,‘Mollejones’),(3182,3,5,2,16,‘Pacayitas’),(3183,3,5,2,17,‘Pacuare’),(3184,3,5,2,18,‘Piedra
Grande’),(3185,3,5,2,19,‘Porvenir de la
Esperanza’),(3186,3,5,2,20,‘Puente Alto’),(3187,3,5,2,21,‘San
Vicente’),(3188,3,5,2,22,‘Selva
(parte)’),(3189,3,5,2,23,‘Silencio’),(3190,3,5,2,24,‘Sonia.’),(3191,3,5,3,1,‘El
Seis.’),(3192,3,5,4,1,‘Bajos de Bonilla’),(3193,3,5,4,2,‘Bolsón
(parte)’),(3194,3,5,4,3,‘Bonilla’),(3195,3,5,4,4,‘Buenos
Aires’),(3196,3,5,4,5,‘Calle Vargas’),(3197,3,5,4,6,‘Carmen
(parte)’),(3198,3,5,4,7,‘Esperanza’),(3199,3,5,4,8,‘Guayabo
Arriba’),(3200,3,5,4,9,‘La Central’),(3201,3,5,4,10,‘La
Fuente’),(3202,3,5,4,11,‘Pastora’),(3203,3,5,4,12,‘Picada’),(3204,3,5,4,13,‘Raicero’),(3205,3,5,4,14,‘Reunión’),(3206,3,5,4,15,‘San
Antonio’),(3207,3,5,4,16,‘San Diego’),(3208,3,5,4,17,‘Torito
(parte).’),(3209,3,5,5,1,‘Cooperativa.’),(3210,3,5,5,2,‘Bonilla
Arriba’),(3211,3,5,5,3,‘Buenavista’),(3212,3,5,5,4,‘Cas’),(3213,3,5,5,5,‘Cimarrones’),(3214,3,5,5,6,‘Colonia
Guayabo’),(3215,3,5,5,7,‘Colonia San
Ramón’),(3216,3,5,5,8,‘Corralón’),(3217,3,5,5,9,‘Dulce
Nombre’),(3218,3,5,5,10,‘El
Dos’),(3219,3,5,5,11,‘Fuente’),(3220,3,5,5,12,‘Guayabo
(parte)’),(3221,3,5,5,13,‘Líbano’),(3222,3,5,5,14,‘Nueva
Flor’),(3223,3,5,5,15,‘Palomo’),(3224,3,5,5,16,‘Pradera’),(3225,3,5,5,17,‘Sandoval’),(3226,3,5,5,18,‘Santa
Tecla’),(3227,3,5,5,19,‘Sauce’),(3228,3,5,5,20,‘Torito
(parte)’),(3229,3,5,5,21,‘Torito
(sur).’),(3230,3,5,6,1,‘Angostura’),(3231,3,5,6,2,‘Bóveda’),(3232,3,5,6,3,‘Buenavista’),(3233,3,5,6,4,‘Chitaría’),(3234,3,5,6,5,‘Eslabón’),(3235,3,5,6,6,‘Isla
Bonita (parte)’),(3236,3,5,6,7,‘Jabillos’),(3237,3,5,6,8,‘San
Rafael’),(3238,3,5,6,9,‘Sitio
Mata’),(3239,3,5,6,10,‘Yama.’),(3240,3,5,7,1,‘Leona
(parte).’),(3241,3,5,7,2,‘Alto
Surtubal’),(3242,3,5,7,3,‘Ángeles’),(3243,3,5,7,4,‘Bajo Pacuare
(norte)’),(3244,3,5,7,5,‘Cabeza de Buey’),(3245,3,5,7,6,‘Cien
Manzanas’),(3246,3,5,7,7,‘Colonia San Lucas’),(3247,3,5,7,8,‘Colonia
Silencio’),(3248,3,5,7,9,‘Colonias’),(3249,3,5,7,10,‘Mata de
Guineo’),(3250,3,5,7,11,‘Nubes’),(3251,3,5,7,12,‘Paulina’),(3252,3,5,7,13,‘Sacro’),(3253,3,5,7,14,‘San
Bosco’),(3254,3,5,7,15,‘Selva (parte).’),(3255,3,5,8,1,‘Bajo Pacuare
(sur)’),(3256,3,5,8,2,‘Dos Amigos’),(3257,3,5,8,3,‘Dulce
Nombre’),(3258,3,5,8,4,‘Guineal’),(3259,3,5,8,5,‘Jicotea’),(3260,3,5,8,6,‘Mina’),(3261,3,5,8,7,‘Morado’),(3262,3,5,8,8,‘Quebradas’),(3263,3,5,8,9,‘San
Martín’),(3264,3,5,8,10,‘San
Rafael’),(3265,3,5,8,11,‘Tacotal.’),(3266,3,5,9,1,‘Aquiares’),(3267,3,5,9,2,‘Bolsón
(parte)’),(3268,3,5,9,3,‘Carmen (parte)’),(3269,3,5,9,4,‘Río
Claro’),(3270,3,5,9,5,‘San Rafael’),(3271,3,5,9,6,‘Verbena
Norte’),(3272,3,5,9,7,‘Verbena Sur.’),(3273,3,5,10,1,‘Alto
June’),(3274,3,5,10,2,‘Corozal’),(3275,3,5,10,3,‘Flor’),(3276,3,5,10,4,‘Guanacasteca’),(3277,3,5,10,5,‘Isla
Bonita(parte)’),(3278,3,5,10,6,‘Pilón de Azúcar’),(3279,3,5,10,7,‘San
Pablo’),(3280,3,5,10,8,‘Sol.’),(3281,3,5,11,1,‘Azul’),(3282,3,5,11,2,‘Ánimas’),(3283,3,5,11,3,‘Alto
de Varas (Alto Varal)’),(3284,3,5,11,4,‘Guayabo
(parte)’),(3285,3,5,11,5,‘Jesús María’),(3286,3,5,11,6,‘La
Isabel’),(3287,3,5,11,7,‘San
Martín.’),(3288,3,5,12,1,‘Carolina’),(3289,3,5,12,2,‘Chirripó Abajo
(parte)’),(3290,3,5,12,3,‘Chirripó
Arriba’),(3291,3,5,12,4,‘Damaris’),(3292,3,5,12,5,’‘),(3293,3,5,12,6,’Fortuna’),(3294,3,5,12,7,‘Jekui’),(3295,3,5,12,8,‘Moravia’),(3296,3,5,12,9,‘Namaldí’),(3297,3,5,12,10,‘Pacuare
arriba’),(3298,3,5,12,11,‘Paso Marcos’),(3299,3,5,12,12,‘Tsipiri
(Platanillo)’),(3300,3,5,12,13,‘Playa
Hermosa’),(3301,3,5,12,14,‘Porvenir’),(3302,3,5,12,15,‘Raíz de
Hule’),(3303,3,5,12,16,‘Río
Blanco’),(3304,3,5,12,17,‘Santubal’),(3305,3,5,12,18,‘Surí’),(3306,3,5,12,19,‘Vereh’),(3307,3,5,12,20,‘Quetzal.’),(3308,3,6,1,1,‘Lourdes’),(3309,3,6,1,2,‘Patalillo.’),(3310,3,6,1,3,‘Buenavista’),(3311,3,6,1,4,‘Buenos
Aires’),(3312,3,6,1,5,‘Charcalillos’),(3313,3,6,1,6,‘Encierrillo’),(3314,3,6,1,7,‘Los
Pinos (Coliblanco)’),(3315,3,6,1,8,‘Llano
Grande’),(3316,3,6,1,9,‘Pascón’),(3317,3,6,1,10,‘Pastora’),(3318,3,6,1,11,‘Plantón’),(3319,3,6,1,12,‘San
Martín (Irazú Sur)’),(3320,3,6,1,13,‘San Rafael de
Irazú.’),(3321,3,6,2,1,‘Bajo Malanga’),(3322,3,6,2,2,‘Aguas
(parte)’),(3323,3,6,2,3,‘Bajo Solano’),(3324,3,6,2,4,‘Ciudad del
Cielo’),(3325,3,6,2,5,‘Descanso’),(3326,3,6,2,6,‘El
Alto’),(3327,3,6,2,7,‘Mata de
Guineo’),(3328,3,6,2,8,‘Monticel.’),(3329,3,6,3,1,‘Bajo
Abarca’),(3330,3,6,3,2,‘Lourdes
(Callejón)’),(3331,3,6,3,3,‘Coliblanco’),(3332,3,6,3,4,‘Santa
Teresa.’),(3333,3,7,1,1,‘Alto Cerrillos (Corazón de
Jesús)’),(3334,3,7,1,2,‘Artavia’),(3335,3,7,1,3,‘Barrial’),(3336,3,7,1,4,‘Bosque’),(3337,3,7,1,5,‘Breñas’),(3338,3,7,1,6,‘Caballo
Blanco
(parte)’),(3339,3,7,1,7,‘Chircagre’),(3340,3,7,1,8,‘Flores’),(3341,3,7,1,9,‘Gamboa’),(3342,3,7,1,10,‘José
Jesús Méndez’),(3343,3,7,1,11,‘Juan Pablo II’),(3344,3,7,1,12,‘Sagrada
Familia’),(3345,3,7,1,13,‘Monseñor Sanabria.’),(3346,3,7,1,14,‘Cuesta
Chinchilla’),(3347,3,7,1,15,‘Llano.’),(3348,3,7,2,1,‘Mata de
Mora’),(3349,3,7,2,2,‘Páez (parte)’),(3350,3,7,2,3,‘Paso
Ancho’),(3351,3,7,2,4,‘San
Cayetano.’),(3352,3,7,3,1,‘Maya’),(3353,3,7,3,2,‘Cruce’),(3354,3,7,3,3,‘Pisco’),(3355,3,7,3,4,‘Sanabria’),(3356,3,7,3,5,‘San
Juan de Chicuá.’),(3357,3,7,4,1,‘Aguas
(parte)’),(3358,3,7,4,2,‘Barrionuevo’),(3359,3,7,4,3,‘Boquerón’),(3360,3,7,4,4,‘Capira’),(3361,3,7,4,5,‘Oratorio.’),(3362,3,7,5,1,‘Cuesta
Quemados’),(3363,3,7,5,2,‘Pasquí’),(3364,3,7,5,3,‘Platanillal’),(3365,3,7,5,4,‘San
Gerardo’),(3366,3,7,5,5,‘San Juan’),(3367,3,7,5,6,‘San
Martín’),(3368,3,7,5,7,‘San
Pablo’),(3369,3,7,5,8,‘Titoral.’),(3370,3,8,1,1,‘Asunción’),(3371,3,8,1,2,‘Barahona’),(3372,3,8,1,3,‘Barrio
Nuevo’),(3373,3,8,1,4,‘Colonia’),(3374,3,8,1,5,‘Chavarría’),(3375,3,8,1,6,‘Sabana’),(3376,3,8,1,7,‘Sabana
Grande’),(3377,3,8,1,8,‘San Rafael’),(3378,3,8,1,9,‘Santa
Gertrudis’),(3379,3,8,1,10,‘Sauces’),(3380,3,8,1,11,‘Silo’),(3381,3,8,1,12,‘Viento
Fresco.’),(3382,3,8,2,1,‘Guatuso’),(3383,3,8,2,2,‘Higuito’),(3384,3,8,2,3,‘Potrerillos.’),(3385,3,8,2,4,‘Altamiradas’),(3386,3,8,2,5,‘Alto
San Francisco’),(3387,3,8,2,6,‘Bajo Gloria’),(3388,3,8,2,7,‘Bajos de
León’),(3389,3,8,2,8,‘Barrancas
(parte)’),(3390,3,8,2,9,‘Cangreja’),(3391,3,8,2,10,‘Cañón
(parte)’),(3392,3,8,2,11,‘Casablanca’),(3393,3,8,2,12,‘Casamata’),(3394,3,8,2,13,‘Cascajal’),(3395,3,8,2,14,‘Conventillo’),(3396,3,8,2,15,‘Cruces’),(3397,3,8,2,16,‘Cruz’),(3398,3,8,2,17,‘Chonta
(parte)’),(3399,3,8,2,18,‘Damita’),(3400,3,8,2,19,‘Dos
Amigos’),(3401,3,8,2,20,‘Empalme
(parte)’),(3402,3,8,2,21,‘Esperanza’),(3403,3,8,2,22,‘Estrella’),(3404,3,8,2,23,‘Guayabal
(parte)’),(3405,3,8,2,24,‘La Luchita’),(3406,3,8,2,25,‘La
Paz’),(3407,3,8,2,26,‘Macho
Gaff’),(3408,3,8,2,27,‘Montserrat’),(3409,3,8,2,28,‘Ojo de Agua
(parte)’),(3410,3,8,2,29,‘Palmital’),(3411,3,8,2,30,‘Palmital
Sur’),(3412,3,8,2,31,‘Palo Verde’),(3413,3,8,2,32,‘Paso Macho
(parte)’),(3414,3,8,2,33,‘Purires (parte)’),(3415,3,8,2,34,‘Salsipuedes
(parte)’),(3416,3,8,2,35,‘San
Cayetano’),(3417,3,8,2,36,‘Surtubal’),(3418,3,8,2,37,‘Tres de
Junio’),(3419,3,8,2,38,‘Vara del
Roble.’),(3420,3,8,3,1,‘Achiotillo’),(3421,3,8,3,2,‘Barrancas
(parte)’),(3422,3,8,3,3,‘Bodocal’),(3423,3,8,3,4,‘Garita
(parte)’),(3424,3,8,3,5,‘Purires
(parte)’),(3425,3,8,3,6,‘Tablón.’),(3426,3,8,4,1,‘Bajo
Zopilote’),(3427,3,8,4,2,‘Caragral’),(3428,3,8,4,3,‘Común.’),(3429,4,1,1,1,‘Ángeles’),(3430,4,1,1,2,‘Carmen’),(3431,4,1,1,3,‘Corazón
de
Jesús’),(3432,4,1,1,4,‘Chino’),(3433,4,1,1,5,‘Estadio’),(3434,4,1,1,6,‘Fátima’),(3435,4,1,1,7,‘Guayabal’),(3436,4,1,1,8,‘La
India’),(3437,4,1,1,9,‘Lourdes’),(3438,4,1,1,10,‘Hospital’),(3439,4,1,1,11,‘María
Auxiliadora
(parte)’),(3440,4,1,1,12,‘Oriente’),(3441,4,1,1,13,‘Pirro’),(3442,4,1,1,14,‘Puebla
(parte)’),(3443,4,1,1,15,‘Rancho Chico’),(3444,4,1,1,16,‘San
Fernando’),(3445,4,1,1,17,‘San
Vicente.’),(3446,4,1,2,1,‘Burío’),(3447,4,1,2,2,‘Carbonal’),(3448,4,1,2,3,‘Cubujuquí’),(3449,4,1,2,4,‘España’),(3450,4,1,2,5,‘Labrador’),(3451,4,1,2,6,‘Mercedes
Sur’),(3452,4,1,2,7,‘Monte Bello’),(3453,4,1,2,8,‘San
Jorge’),(3454,4,1,2,9,‘Santa
Inés.’),(3455,4,1,3,1,‘Aries’),(3456,4,1,3,2,‘Aurora
(parte)’),(3457,4,1,3,3,‘Bernardo
Benavides’),(3458,4,1,3,4,‘Chucos’),(3459,4,1,3,5,‘El Cristo
(parte)’),(3460,4,1,3,6,‘Esmeralda’),(3461,4,1,3,7,‘Esperanza’),(3462,4,1,3,8,‘Granada’),(3463,4,1,3,9,‘Gran
Samaria’),(3464,4,1,3,10,‘Guararí’),(3465,4,1,3,11,‘Lagos’),(3466,4,1,3,12,‘Malinches’),(3467,4,1,3,13,‘Mayorga
(parte)’),(3468,4,1,3,14,‘Nísperos
3’),(3469,4,1,3,15,‘Palma’),(3470,4,1,3,16,‘Trébol’),(3471,4,1,3,17,‘Tropical.’),(3472,4,1,4,1,‘Arcos’),(3473,4,1,4,2,‘Aurora
(parte)’),(3474,4,1,4,3,‘Bajos del Virilla (San
Rafael)’),(3475,4,1,4,4,‘Cariari
(parte)’),(3476,4,1,4,5,‘Carpintera’),(3477,4,1,4,6,‘El Cristo
(parte)’),(3478,4,1,4,7,‘Lagunilla’),(3479,4,1,4,8,‘Linda del
Norte’),(3480,4,1,4,9,‘Mayorga
(parte)’),(3481,4,1,4,10,‘Monterrey’),(3482,4,1,4,11,‘Pitahaya’),(3483,4,1,4,12,‘Pueblo
Nuevo’),(3484,4,1,4,13,‘Valencia (parte)’),(3485,4,1,4,14,‘Vista
Nosara.’),(3486,4,1,5,1,‘Jesús
María’),(3487,4,1,5,2,‘Legua’),(3488,4,1,5,3,‘Legua de
Barva’),(3489,4,1,5,4,‘Montaña Azul’),(3490,4,1,5,5,‘San
Rafael’),(3491,4,1,5,6,‘Virgen del Socorro (parte).’),(3492,4,2,1,1,‘Don
Abraham’),(3493,4,2,1,2,‘San
Bartolomé.’),(3494,4,2,2,1,‘Bosque’),(3495,4,2,2,2,‘Calle
Amada’),(3496,4,2,2,3,‘Calle Los
Naranjos’),(3497,4,2,2,4,‘Espinos’),(3498,4,2,2,5,‘Máquina’),(3499,4,2,2,6,‘Mirador’),(3500,4,2,2,7,‘Morazán’),(3501,4,2,2,8,‘Puente
Salas’),(3502,4,2,2,9,‘Segura’),(3503,4,2,2,10,‘Vista
Llana.’),(3504,4,2,3,1,‘Barrios (Barva):
Cementerio’),(3505,4,2,3,2,‘Ibís.’),(3506,4,2,3,3,‘Buenavista.’),(3507,4,2,4,1,‘Bello
Higuerón’),(3508,4,2,4,2,‘Los
Luises’),(3509,4,2,4,3,‘Plantación’),(3510,4,2,4,4,‘Pórtico.’),(3511,4,2,5,1,‘Doña
Iris’),(3512,4,2,5,2,‘Jardines del Beneficio’),(3513,4,2,5,3,‘Paso Viga
(parte)’),(3514,4,2,5,4,‘Pedregal.’),(3515,4,2,5,5,‘Getsemaní
(parte)’),(3516,4,2,5,6,‘Palmar
(parte).’),(3517,4,2,6,1,‘Gallito’),(3518,4,2,6,2,‘Monte
Alto’),(3519,4,2,6,3,‘Cipresal’),(3520,4,2,6,4,‘Doña
Blanca’),(3521,4,2,6,5,‘Doña
Elena’),(3522,4,2,6,6,‘Higuerón’),(3523,4,2,6,7,‘Huacalillo’),(3524,4,2,6,8,‘El
Collado’),(3525,4,2,6,9,‘Meseta’),(3526,4,2,6,10,‘Paso
Llano’),(3527,4,2,6,11,‘Plan de
Birrí’),(3528,4,2,6,12,‘Porrosatí’),(3529,4,2,6,13,‘Roblealto’),(3530,4,2,6,14,‘Sacramento’),(3531,4,2,6,15,‘San
Martín’),(3532,4,2,6,16,‘San Miguel’),(3533,4,2,6,17,‘Santa
Clara’),(3534,4,2,6,18,‘Zapata.’),(3535,4,3,2,1,‘Barro de
Olla’),(3536,4,3,2,2,‘Calle Don
Pedro’),(3537,4,3,2,3,‘Quintana’),(3538,4,3,2,4,‘Yurusti.’),(3539,4,3,3,1,‘Canoa
(parte)’),(3540,4,3,3,2,‘Castilla’),(3541,4,3,3,3,‘Cuesta
Rojas’),(3542,4,3,3,4,‘Montero’),(3543,4,3,3,5,‘Socorro’),(3544,4,3,3,6,‘Villa
Rossi.’),(3545,4,3,4,1,‘Represa.’),(3546,4,3,5,1,‘Barquero’),(3547,4,3,5,2,‘Higinia’),(3548,4,3,5,3,‘Pacífica.’),(3549,4,3,6,1,‘La
Cooperativa’),(3550,4,3,6,2,‘Pedro León’),(3551,4,3,6,3,‘Primero de
Mayo’),(3552,4,3,6,4,‘Quisqueya’),(3553,4,3,6,5,‘Rinconada’),(3554,4,3,6,6,‘Valencia
(parte).’),(3555,4,3,7,1,‘Calle
Vieja’),(3556,4,3,7,2,‘Lourdes’),(3557,4,3,7,3,‘Quebradas
(parte).’),(3558,4,3,8,1,‘Caballero.’),(3559,4,3,8,2,‘Canoa
(parte).’),(3560,4,4,1,1,‘Trompezón.’),(3561,4,4,2,1,‘Betania’),(3562,4,4,2,2,‘Rosales
(parte).’),(3563,4,4,3,1,‘Cinco Esquinas’),(3564,4,4,3,2,‘Lotes
Moreira’),(3565,4,4,3,3,‘San Juan
Arriba’),(3566,4,4,4,1,‘Altagracia’),(3567,4,4,4,2,‘Birrí’),(3568,4,4,4,3,‘Calle
Quirós (parte)’),(3569,4,4,4,4,‘Catalina’),(3570,4,4,4,5,‘Común
(parte)’),(3571,4,4,4,6,‘Cuesta
Colorada’),(3572,4,4,4,7,‘Guachipelines’),(3573,4,4,4,8,‘Guaracha’),(3574,4,4,4,9,‘Ulises.’),(3575,4,4,5,1,‘Amapola’),(3576,4,4,5,2,‘Cartagos’),(3577,4,4,5,3,‘Chagüite’),(3578,4,4,5,4,‘Giralda’),(3579,4,4,5,5,‘Guararí’),(3580,4,4,5,6,‘Tranquera.’),(3581,4,4,6,1,‘Marías’),(3582,4,4,6,2,‘Purabá’),(3583,4,4,6,3,‘San
Bosco.’),(3584,4,4,6,4,‘Calle Quirós (parte)’),(3585,4,4,6,5,‘Común
(parte)’),(3586,4,4,6,6,‘Lajas.’),(3587,4,5,1,1,‘Amistad’),(3588,4,5,1,2,‘Matasano
(parte)’),(3589,4,5,1,3,‘Paso Viga (parte).’),(3590,4,5,2,1,‘Bajo
Molinos’),(3591,4,5,2,2,‘Joya’),(3592,4,5,2,3,‘Matasano
(parte)’),(3593,4,5,2,4,‘Peralta’),(3594,4,5,2,5,‘Santísima
Trinidad.’),(3595,4,5,3,1,‘Jardines de Roma’),(3596,4,5,3,2,‘Jardines
Universitarios’),(3597,4,5,3,3,‘Suiza.’),(3598,4,5,4,1,‘Paso Viga
(parte)’),(3599,4,5,4,2,‘Saca.’),(3600,4,5,4,3,‘Calle Hernández
(parte)’),(3601,4,5,4,4,‘Castillo’),(3602,4,5,4,5,‘Cerro
Redondo’),(3603,4,5,4,6,‘Getsemaní
(parte)’),(3604,4,5,4,7,‘Joaquina’),(3605,4,5,4,8,‘Lobos’),(3606,4,5,4,9,‘Montecito’),(3607,4,5,4,10,‘Palmar
(parte)’),(3608,4,5,4,11,‘Puente Piedra
(parte)’),(3609,4,5,4,12,‘Quintanar de la
Sierra’),(3610,4,5,4,13,‘Uvita.’),(3611,4,5,5,1,‘Palenque’),(3612,4,5,5,2,‘Anonos’),(3613,4,5,5,3,‘Burial’),(3614,4,5,5,4,‘Calle
Chávez (parte)’),(3615,4,5,5,5,‘Calle Hernández
(parte)’),(3616,4,5,5,6,‘Ciénagas’),(3617,4,5,5,7,‘Charquillo’),(3618,4,5,5,8,‘Mora’),(3619,4,5,5,9,‘Pilas’),(3620,4,5,5,10,‘Puente
Piedra (parte)’),(3621,4,5,5,11,‘Tierra Blanca
(parte)’),(3622,4,5,5,12,‘Turú.’),(3623,4,6,1,1,‘Calle
Cementerio’),(3624,4,6,1,2,‘Colonia
Isidreña’),(3625,4,6,1,3,‘Cooperativa’),(3626,4,6,1,4,‘Cristo
Rey’),(3627,4,6,1,5,‘El
Volador’),(3628,4,6,1,6,‘Villaval.’),(3629,4,6,2,1,‘Santa
Cruz.’),(3630,4,6,2,2,‘El
Arroyo’),(3631,4,6,2,3,‘Huacalillos’),(3632,4,6,2,4,‘Santa Cecilia
(parte)’),(3633,4,6,2,5,‘Santa
Elena’),(3634,4,6,2,6,‘Trapiche’),(3635,4,6,2,7,‘Yerbabuena.’),(3636,4,6,3,1,‘Alhajas’),(3637,4,6,3,2,‘Calle
Caricias’),(3638,4,6,3,3,‘Calle Chávez (parte)’),(3639,4,6,3,4,‘Santa
Cecilia’),(3640,4,6,4,1,‘Aguacate’),(3641,4,6,4,2,‘Astillero’),(3642,4,6,4,3,‘Quebradas
(parte)’),(3643,4,6,4,4,‘Rinconada’),(3644,4,6,4,5,‘Tierra Blanca
(parte)’),(3645,4,6,4,6,‘Vallevistar’),(3646,4,6,4,7,‘Viento
Fresco.’),(3647,4,7,1,1,‘Chompipes
(parte)’),(3648,4,7,1,2,‘Escobal’),(3649,4,7,1,3,‘Labores
(parte)’),(3650,4,7,1,4,‘San
Vicente’),(3651,4,7,1,5,‘Zaiquí.’),(3652,4,7,2,1,‘Fuente’),(3653,4,7,2,2,‘Labores
(parte)’),(3654,4,7,2,3,‘Vista Linda’),(3655,4,7,2,4,‘Cristo Rey
(parte).’),(3656,4,7,2,5,‘Echeverría
(parte).’),(3657,4,7,3,1,‘Arbolito’),(3658,4,7,3,2,‘Bonanza’),(3659,4,7,3,3,‘Bosques
de Doña Rosa’),(3660,4,7,3,4,‘Cariari (parte)’),(3661,4,7,3,5,‘Chompipes
(parte)’),(3662,4,7,3,6,‘Cristo Rey
(parte).’),(3663,4,8,1,1,‘Campanario’),(3664,4,8,1,2,‘Joaquineños’),(3665,4,8,1,3,‘Luisiana’),(3666,4,8,1,4,‘Santa
Marta’),(3667,4,8,1,5,‘Trinidad’),(3668,4,8,1,6,‘Villa
Lico’),(3669,4,8,1,7,‘Villa
María.’),(3670,4,8,2,1,‘Barrantes’),(3671,4,8,2,2,‘Ugalde.’),(3672,4,8,3,1,‘Ángeles’),(3673,4,8,3,2,‘Año
2000’),(3674,4,8,3,3,‘Cristo Rey
(parte)’),(3675,4,8,3,4,‘Geranios’),(3676,4,8,3,5,‘Las
Hadas’),(3677,4,8,3,6,‘Santa Elena’),(3678,4,8,3,7,‘Siglo
Veintiuno.’),(3679,4,8,3,8,‘Echeverría
(parte).’),(3680,4,9,1,1,‘Acapulco’),(3681,4,9,1,2,‘Amada’),(3682,4,9,1,3,‘Asovigui’),(3683,4,9,1,4,‘Colonial’),(3684,4,9,1,5,‘Cruces’),(3685,4,9,1,6,‘Doña
Nina’),(3686,4,9,1,7,‘Irazú’),(3687,4,9,1,8,‘Irma’),(3688,4,9,1,9,‘July’),(3689,4,9,1,10,‘María
Auxiliadora (parte)’),(3690,4,9,1,11,‘Nueva
Jerusalén’),(3691,4,9,1,12,‘Pastoras’),(3692,4,9,1,13,‘Puebla
(parte)’),(3693,4,9,1,14,‘Santa Isabel’),(3694,4,9,1,15,‘Villa
Cortés’),(3695,4,9,1,16,‘Villa Dolores’),(3696,4,9,1,17,‘Villa
Quintana’),(3697,4,9,1,18,‘Uriche’),(3698,4,9,1,19,‘Uruca.’),(3699,4,9,1,20,‘Corobicí’),(3700,4,9,1,21,‘Estrella’),(3701,4,9,1,22,‘Rincón
de Ricardo’),(3702,4,9,1,23,‘Santa
Fe.’),(3703,4,10,1,1,‘Colina’),(3704,4,10,1,2,‘Esperanza’),(3705,4,10,1,3,‘Jardín’),(3706,4,10,1,4,‘Loma
Linda’),(3707,4,10,1,5,‘Progreso.’),(3708,4,10,1,6,‘Achiote’),(3709,4,10,1,7,‘Ahogados’),(3710,4,10,1,8,‘Arbolitos
(parte)’),(3711,4,10,1,9,‘Arrepentidos’),(3712,4,10,1,10,‘Boca
Ceiba’),(3713,4,10,1,11,‘Boca Río
Sucio’),(3714,4,10,1,12,‘Bun’),(3715,4,10,1,13,‘Cabezas’),(3716,4,10,1,14,‘Canfín’),(3717,4,10,1,15,‘Caño
Negro’),(3718,4,10,1,16,‘Cerro Negro (Parte)’),(3719,4,10,1,17,‘Colonia
San José’),(3720,4,10,1,18,‘Coyol’),(3721,4,10,1,19,‘Cristo
Rey’),(3722,4,10,1,20,‘Chilamate’),(3723,4,10,1,21,‘El
Progreso’),(3724,4,10,1,22,‘Esperanza’),(3725,4,10,1,23,‘Estrellales’),(3726,4,10,1,24,‘Guaria’),(3727,4,10,1,25,‘Jardín’),(3728,4,10,1,26,‘Jormo’),(3729,4,10,1,27,‘Las
Marías’),(3730,4,10,1,28,‘Las Orquídeas’),(3731,4,10,1,29,‘Los
Lirios’),(3732,4,10,1,30,‘Malinche’),(3733,4,10,1,31,‘Media
Vuelta’),(3734,4,10,1,32,‘Medias
(parte)’),(3735,4,10,1,33,‘Muelle’),(3736,4,10,1,34,‘Naranjal’),(3737,4,10,1,35,‘Nogal’),(3738,4,10,1,36,‘Pavas’),(3739,4,10,1,37,‘Rojomaca’),(3740,4,10,1,38,‘San
José’),(3741,4,10,1,39,‘San Julián’),(3742,4,10,1,40,‘Tres
Rosales’),(3743,4,10,1,41,‘Vega de Sardinal
(parte)’),(3744,4,10,1,42,‘Zapote.’),(3745,4,10,2,1,‘Ángeles’),(3746,4,10,2,2,‘Arbolitos
(parte)’),(3747,4,10,2,3,‘Bajos de Chilamate’),(3748,4,10,2,4,‘Boca
Sardinal’),(3749,4,10,2,5,‘Bosque’),(3750,4,10,2,6,‘Búfalo’),(3751,4,10,2,7,‘Delicias’),(3752,4,10,2,8,‘El
Uno’),(3753,4,10,2,9,‘Esquipulas’),(3754,4,10,2,10,‘Las
Palmitas’),(3755,4,10,2,11,‘Laquí’),(3756,4,10,2,12,‘Lomas’),(3757,4,10,2,13,‘Llano
Grande’),(3758,4,10,2,14,‘Magsaysay’),(3759,4,10,2,15,‘Masaya’),(3760,4,10,2,16,‘Medias
(parte)’),(3761,4,10,2,17,‘Pangola’),(3762,4,10,2,18,‘Pozo
Azul’),(3763,4,10,2,19,‘Quebrada Grande’),(3764,4,10,2,20,‘Río
Magdaleno’),(3765,4,10,2,21,‘Roble’),(3766,4,10,2,22,‘San Gerardo
(parte)’),(3767,4,10,2,23,‘San Isidro’),(3768,4,10,2,24,‘San José
Sur’),(3769,4,10,2,25,‘San Ramón’),(3770,4,10,2,26,‘La
Delia’),(3771,4,10,2,27,‘Sardinal’),(3772,4,10,2,28,‘Tirimbina’),(3773,4,10,2,29,‘Vega
de Sardinal
(parte)’),(3774,4,10,2,30,‘Venados.’),(3775,4,10,3,1,‘Bambuzal’),(3776,4,10,3,2,‘Cerro
Negro (parte)’),(3777,4,10,3,3,‘Colonia Bambú’),(3778,4,10,3,4,‘Colonia
Colegio’),(3779,4,10,3,5,‘Colonia Esperanza’),(3780,4,10,3,6,‘Colonia
Huetar’),(3781,4,10,3,7,‘Colonia Nazareth’),(3782,4,10,3,8,‘Colonia
Victoria’),(3783,4,10,3,9,‘Colonia
Villalobos’),(3784,4,10,3,10,‘Cubujuquí’),(3785,4,10,3,11,‘Chiripa’),(3786,4,10,3,12,‘Fátima’),(3787,4,10,3,13,‘Flaminia’),(3788,4,10,3,14,‘Finca
Uno’),(3789,4,10,3,15,‘Finca Dos’),(3790,4,10,3,16,‘Finca
Tres’),(3791,4,10,3,17,‘Finca Cinco’),(3792,4,10,3,18,‘Finca
Agua’),(3793,4,10,3,19,‘Finca Zona Siete’),(3794,4,10,3,20,‘Finca Zona
Ocho’),(3795,4,10,3,21,‘Finca Zona Diez’),(3796,4,10,3,22,‘Finca Zona
Once’),(3797,4,10,3,23,‘Isla’),(3798,4,10,3,24,‘Isla
Grande’),(3799,4,10,3,25,‘Isla Israel’),(3800,4,10,3,26,‘La
Conquista’),(3801,4,10,3,27,‘La Chávez’),(3802,4,10,3,28,‘La
Vuelta’),(3803,4,10,3,29,‘Rambla’),(3804,4,10,3,30,‘Pedernales’),(3805,4,10,3,31,‘Platanera’),(3806,4,10,3,32,‘Río
Frío’),(3807,4,10,3,33,‘San Bernardino’),(3808,4,10,3,34,‘San
Luis’),(3809,4,10,3,35,‘Santa Clara’),(3810,4,10,3,36,‘Tapa
Viento’),(3811,4,10,3,37,‘Ticarí’),(3812,4,10,3,38,‘Tigre’),(3813,4,10,3,39,‘Villa
Isabel’),(3814,4,10,3,40,‘Villa Nueva.’),(3815,4,10,4,1,‘Caño San
Luis’),(3816,4,10,4,2,‘Chimurria’),(3817,4,10,4,3,‘Chirriposito’),(3818,4,10,4,4,‘Delta’),(3819,4,10,4,5,‘Gaspar’),(3820,4,10,4,6,‘Lagunilla’),(3821,4,10,4,7,‘La
Lucha’),(3822,4,10,4,8,‘Tigra.’),(3823,4,10,5,1,‘Caño
Tambor’),(3824,4,10,5,2,‘Copalchí’),(3825,4,10,5,3,‘Cureña’),(3826,4,10,5,4,‘Paloseco’),(3827,4,10,5,5,‘Remolinito’),(3828,4,10,5,6,‘Tambor’),(3829,4,10,5,7,‘Tierrabuena’),(3830,4,10,5,8,‘Trinidad’),(3831,4,10,5,9,‘Unión
del Toro’),(3832,4,10,5,10,‘Vuelta Cabo de
Hornos.’),(3833,5,1,1,1,‘Alaska’),(3834,5,1,1,2,‘Ángeles’),(3835,5,1,1,3,‘Buenos
Aires’),(3836,5,1,1,4,‘Capulín’),(3837,5,1,1,5,‘Cerros’),(3838,5,1,1,6,‘Condega’),(3839,5,1,1,7,‘Corazón
de
Jesús’),(3840,5,1,1,8,‘Curime’),(3841,5,1,1,9,‘Choricera’),(3842,5,1,1,10,‘Chorotega’),(3843,5,1,1,11,‘Gallera’),(3844,5,1,1,12,‘Guaria’),(3845,5,1,1,13,‘Jícaro’),(3846,5,1,1,14,‘La
Carreta’),(3847,5,1,1,15,‘Llano La Cruz’),(3848,5,1,1,16,‘Mocho (Santa
Lucía)’),(3849,5,1,1,17,‘Moracia’),(3850,5,1,1,18,‘Nazareth’),(3851,5,1,1,19,‘Pueblo
Nuevo’),(3852,5,1,1,20,‘Sabanero’),(3853,5,1,1,21,‘San
Miguel’),(3854,5,1,1,22,‘San
Roque’),(3855,5,1,1,23,‘Sitio’),(3856,5,1,1,24,‘Veinticinco de
Julio’),(3857,5,1,1,25,‘Victoria’),(3858,5,1,1,26,‘Villanueva.’),(3859,5,1,1,27,‘Arena’),(3860,5,1,1,28,‘Caraña’),(3861,5,1,1,29,‘Isleta
(parte)’),(3862,5,1,1,30,‘Juanilama’),(3863,5,1,1,31,‘Montañita’),(3864,5,1,1,32,‘Paso
Tempisque (parte)’),(3865,5,1,1,33,‘Pelón de la
Bajura’),(3866,5,1,1,34,‘Polvazales’),(3867,5,1,1,35,‘Roble de
Sabana’),(3868,5,1,1,36,‘Rodeito’),(3869,5,1,1,37,‘Salto
(parte)’),(3870,5,1,1,38,‘San Benito’),(3871,5,1,1,39,‘San
Hernán’),(3872,5,1,1,40,‘San Lucas’),(3873,5,1,1,41,‘Santa
Ana’),(3874,5,1,1,42,‘Terreros’),(3875,5,1,1,43,‘Zanjita.’),(3876,5,1,2,1,‘Alcántaro’),(3877,5,1,2,2,‘Buenavista’),(3878,5,1,2,3,‘Guayacán’),(3879,5,1,2,4,‘Pochote.’),(3880,5,1,2,5,‘Brisas’),(3881,5,1,2,6,‘Cedro’),(3882,5,1,2,7,‘Congo’),(3883,5,1,2,8,‘Cueva’),(3884,5,1,2,9,‘Fortuna’),(3885,5,1,2,10,‘Irigaray’),(3886,5,1,2,11,‘Lilas’),(3887,5,1,2,12,‘Pacayales’),(3888,5,1,2,13,‘Panamacito’),(3889,5,1,2,14,‘Pedregal’),(3890,5,1,2,15,‘Pital’),(3891,5,1,2,16,‘Pueblo
Nuevo.’),(3892,5,1,3,1,‘Lourdes’),(3893,5,1,3,2,‘San
Antonio.’),(3894,5,1,3,3,‘Ángeles’),(3895,5,1,3,4,‘Argentina’),(3896,5,1,3,5,‘Buenavista’),(3897,5,1,3,6,‘Consuelo.’),(3898,5,1,4,1,‘Bejuco’),(3899,5,1,4,2,‘Los
Lagos’),(3900,5,1,4,3,‘Nacascolo’),(3901,5,1,4,4,‘Oratorio’),(3902,5,1,4,5,‘Puerto
Culebra’),(3903,5,1,4,6,‘Triunfo.’),(3904,5,1,5,1,‘Gallo’),(3905,5,1,5,2,‘San
Rafael.’),(3906,5,1,5,3,‘Colorado’),(3907,5,1,5,4,‘Curubandé’),(3908,5,1,5,5,‘Porvenir.’),(3909,5,2,1,1,‘Los
Ángeles’),(3910,5,2,1,2,‘Barro
Negro’),(3911,5,2,1,3,‘Cananga’),(3912,5,2,1,4,‘Carmen’),(3913,5,2,1,5,‘Chorotega’),(3914,5,2,1,6,‘Guadalupe’),(3915,5,2,1,7,‘Granja’),(3916,5,2,1,8,‘San
Martín’),(3917,5,2,1,9,‘Santa
Lucía’),(3918,5,2,1,10,‘Virginia.’),(3919,5,2,1,11,‘Cabeceras’),(3920,5,2,1,12,‘Caimital’),(3921,5,2,1,13,‘Carreta’),(3922,5,2,1,14,‘Casitas’),(3923,5,2,1,15,‘Cerro
Verde’),(3924,5,2,1,16,‘Cerro Redondo’),(3925,5,2,1,17,‘Cola de
Gallo’),(3926,5,2,1,18,‘Cuesta’),(3927,5,2,1,19,‘Cuesta Buenos
Aires’),(3928,5,2,1,20,‘Curime’),(3929,5,2,1,21,‘Chivo’),(3930,5,2,1,22,‘Dulce
Nombre’),(3931,5,2,1,23,‘Esperanza
Norte’),(3932,5,2,1,24,‘Estrella’),(3933,5,2,1,25,‘Gamalotal’),(3934,5,2,1,26,‘Garcimuñóz’),(3935,5,2,1,27,‘Guaitil’),(3936,5,2,1,28,‘Guastomatal’),(3937,5,2,1,29,‘Guineas’),(3938,5,2,1,30,‘Hondores’),(3939,5,2,1,31,‘Jobo’),(3940,5,2,1,32,‘Juan
Díaz’),(3941,5,2,1,33,‘Lajas’),(3942,5,2,1,34,‘Loma
Caucela’),(3943,5,2,1,35,‘Miramar
(noroeste)’),(3944,5,2,1,36,‘Nambí’),(3945,5,2,1,37,‘Oriente’),(3946,5,2,1,38,‘Los
Planes’),(3947,5,2,1,39,‘Pedernal’),(3948,5,2,1,40,‘Picudas’),(3949,5,2,1,41,‘Pilahonda’),(3950,5,2,1,42,‘Pilas’),(3951,5,2,1,43,‘Pilas
Blancas’),(3952,5,2,1,44,‘Piragua’),(3953,5,2,1,45,‘Ponedero’),(3954,5,2,1,46,‘Quirimán’),(3955,5,2,1,47,‘Quirimancito’),(3956,5,2,1,48,‘Sabana
Grande’),(3957,5,2,1,49,‘Santa Ana’),(3958,5,2,1,50,‘Sitio
Botija’),(3959,5,2,1,51,‘Tierra Blanca’),(3960,5,2,1,52,‘Tres
Quebradas’),(3961,5,2,1,53,‘Varillas
(Zapotillo)’),(3962,5,2,1,54,‘Virginia’),(3963,5,2,1,55,‘Zompopa.’),(3964,5,2,2,1,‘Acoyapa’),(3965,5,2,2,2,‘Boquete’),(3966,5,2,2,3,‘Camarones’),(3967,5,2,2,4,‘Guastomatal’),(3968,5,2,2,5,‘Iguanita’),(3969,5,2,2,6,‘Lapas’),(3970,5,2,2,7,‘Limonal’),(3971,5,2,2,8,‘Matambuguito’),(3972,5,2,2,9,‘Matina’),(3973,5,2,2,10,‘Mercedes’),(3974,5,2,2,11,‘Monte
Alto’),(3975,5,2,2,12,‘Morote
Norte’),(3976,5,2,2,13,‘Nacaome’),(3977,5,2,2,14,‘Obispo’),(3978,5,2,2,15,‘Pital’),(3979,5,2,2,16,‘Polvazales’),(3980,5,2,2,17,‘Pueblo
Viejo’),(3981,5,2,2,18,‘Puente Guillermina’),(3982,5,2,2,19,‘Puerto
Jesús’),(3983,5,2,2,20,‘Río Vueltas’),(3984,5,2,2,21,‘San
Joaquín’),(3985,5,2,2,22,‘San Juan (parte)’),(3986,5,2,2,23,‘Uvita
(parte)’),(3987,5,2,2,24,‘Vigía’),(3988,5,2,2,25,‘Yerbabuena
(parte)’),(3989,5,2,2,26,‘Zapandí.’),(3990,5,2,3,1,‘Guayabal.’),(3991,5,2,3,2,‘Biscoyol’),(3992,5,2,3,3,‘Bolsa’),(3993,5,2,3,4,‘Boquete’),(3994,5,2,3,5,‘Buenos
Aires’),(3995,5,2,3,6,‘Cañal’),(3996,5,2,3,7,‘Carao’),(3997,5,2,3,8,‘Cerro
Mesas’),(3998,5,2,3,9,‘Conchal’),(3999,5,2,3,10,‘Corral de
Piedra’),(4000,5,2,3,11,‘Corralillo’),(4001,5,2,3,12,‘Coyolar’),(4002,5,2,3,13,‘Cuba’),(4003,5,2,3,14,‘Cuesta
Madroño’),(4004,5,2,3,15,‘Chira’),(4005,5,2,3,16,‘Flor’),(4006,5,2,3,17,‘Florida’),(4007,5,2,3,18,‘Guayabo’),(4008,5,2,3,19,‘Loma
Ayote’),(4009,5,2,3,20,‘Matamba’),(4010,5,2,3,21,‘México’),(4011,5,2,3,22,‘Montañita’),(4012,5,2,3,23,‘Monte
Galán’),(4013,5,2,3,24,‘Moracia’),(4014,5,2,3,25,‘Ojo de
Agua’),(4015,5,2,3,26,‘Palos
Negros’),(4016,5,2,3,27,‘Piave’),(4017,5,2,3,28,‘Piedras
Blancas’),(4018,5,2,3,29,‘Pozas’),(4019,5,2,3,30,‘Pozo de
Agua’),(4020,5,2,3,31,‘Pueblo Nuevo’),(4021,5,2,3,32,‘Puerto
Humo’),(4022,5,2,3,33,‘Rosario’),(4023,5,2,3,34,‘San
Lázaro’),(4024,5,2,3,35,‘San
Vicente’),(4025,5,2,3,36,‘Silencio’),(4026,5,2,3,37,‘Talolinga’),(4027,5,2,3,38,‘Tamarindo’),(4028,5,2,3,39,‘Zapote.’),(4029,5,2,4,1,‘Tortuguero.’),(4030,5,2,4,2,‘Botija’),(4031,5,2,4,3,‘Caballito’),(4032,5,2,4,4,‘Embarcadero’),(4033,5,2,4,5,‘Copal’),(4034,5,2,4,6,‘Loma
Bonita’),(4035,5,2,4,7,‘Millal’),(4036,5,2,4,8,‘Paraíso’),(4037,5,2,4,9,‘Paso
Guabo’),(4038,5,2,4,10,‘Pochote’),(4039,5,2,4,11,‘Puerto
Moreno’),(4040,5,2,4,12,‘Roblar’),(4041,5,2,4,13,‘San Juan
(parte)’),(4042,5,2,4,14,‘Sombrero’),(4043,5,2,4,15,‘Sonzapote’),(4044,5,2,4,16,‘Tres
Esquinas.’),(4045,5,2,5,1,‘Matapalo’),(4046,5,2,5,2,‘Mala
Noche.’),(4047,5,2,5,3,‘Bajo Escondido’),(4048,5,2,5,4,‘Barco
Quebrado’),(4049,5,2,5,5,‘Buenavista’),(4050,5,2,5,6,‘Buenos
Aires’),(4051,5,2,5,7,‘Cambutes’),(4052,5,2,5,8,‘Cangrejal’),(4053,5,2,5,9,‘Cantarrana’),(4054,5,2,5,10,‘Chinampas’),(4055,5,2,5,11,‘Esterones’),(4056,5,2,5,12,‘Galilea’),(4057,5,2,5,13,‘Palmar’),(4058,5,2,5,14,‘Panamá’),(4059,5,2,5,15,‘Pavones’),(4060,5,2,5,16,‘Playa
Buenavista’),(4061,5,2,5,17,‘Primavera’),(4062,5,2,5,18,‘Pueblo
Nuevo’),(4063,5,2,5,19,‘Samaria’),(4064,5,2,5,20,‘San
Fernando’),(4065,5,2,5,21,‘Santo
Domingo’),(4066,5,2,5,22,‘Taranta’),(4067,5,2,5,23,‘Terciopelo’),(4068,5,2,5,24,‘Torito.’),(4069,5,2,6,1,‘Ángeles
de Garza’),(4070,5,2,6,2,‘Bijagua’),(4071,5,2,6,3,‘Cabeceras de
Garza’),(4072,5,2,6,4,‘Coyoles’),(4073,5,2,6,5,‘Cuesta
Winch’),(4074,5,2,6,6,‘Delicias’),(4075,5,2,6,7,‘Esperanza
Sur’),(4076,5,2,6,8,‘Flores’),(4077,5,2,6,9,‘Garza’),(4078,5,2,6,10,‘Guiones’),(4079,5,2,6,11,‘Ligia’),(4080,5,2,6,12,‘Nosara’),(4081,5,2,6,13,‘Playa
Nosara’),(4082,5,2,6,14,‘Playa
Pelada’),(4083,5,2,6,15,‘Portal’),(4084,5,2,6,16,‘Río
Montaña’),(4085,5,2,6,17,‘San Juan’),(4086,5,2,6,18,‘Santa
Marta’),(4087,5,2,6,19,‘Santa
Teresa.’),(4088,5,2,7,1,‘Arcos’),(4089,5,2,7,2,‘Balsal’),(4090,5,2,7,3,‘Caimitalito’),(4091,5,2,7,4,‘Cuajiniquil’),(4092,5,2,7,5,‘Cuesta
Grande’),(4093,5,2,7,6,‘Chumburán’),(4094,5,2,7,7,‘Juntas’),(4095,5,2,7,8,‘Maquenco’),(4096,5,2,7,9,‘Minas’),(4097,5,2,7,10,‘Miramar
Sureste’),(4098,5,2,7,11,‘Naranjal’),(4099,5,2,7,12,‘Naranjalito’),(4100,5,2,7,13,‘Nosarita’),(4101,5,2,7,14,‘Platanillo’),(4102,5,2,7,15,‘Quebrada
Bonita’),(4103,5,2,7,16,‘Santa Elena
(parte)’),(4104,5,2,7,17,‘Zaragoza.’),(4105,5,3,1,1,‘Buenos
Aires’),(4106,5,3,1,2,‘Camarenos’),(4107,5,3,1,3,‘Cátalo
Rojas’),(4108,5,3,1,4,‘Corobicí’),(4109,5,3,1,5,‘Chorotega’),(4110,5,3,1,6,‘Esquipulas’),(4111,5,3,1,7,‘Estocolmo’),(4112,5,3,1,8,‘Flores’),(4113,5,3,1,9,‘Garúa’),(4114,5,3,1,10,‘Guabo’),(4115,5,3,1,11,‘Lajas’),(4116,5,3,1,12,‘Los
Amigos’),(4117,5,3,1,13,‘Malinches’),(4118,5,3,1,14,‘Manchón’),(4119,5,3,1,15,‘Panamá’),(4120,5,3,1,16,‘Pepe
Lujan’),(4121,5,3,1,17,‘Sagamat’),(4122,5,3,1,18,‘San
Martín’),(4123,5,3,1,19,‘Santa
Cecilia’),(4124,5,3,1,20,‘Tenorio’),(4125,5,3,1,21,‘Tulita
Sandino.’),(4126,5,3,1,22,‘Ángeles’),(4127,5,3,1,23,‘Arado’),(4128,5,3,1,24,‘Bernabela’),(4129,5,3,1,25,‘Cacao’),(4130,5,3,1,26,‘Caimito’),(4131,5,3,1,27,‘Congal’),(4132,5,3,1,28,‘Cuatro
Esquinas’),(4133,5,3,1,29,‘Chibola’),(4134,5,3,1,30,‘Chircó’),(4135,5,3,1,31,‘Chumico
(parte)’),(4136,5,3,1,32,‘Guayabal’),(4137,5,3,1,33,‘Hato
Viejo’),(4138,5,3,1,34,‘Lagunilla’),(4139,5,3,1,35,‘Lechuza’),(4140,5,3,1,36,‘Limón’),(4141,5,3,1,37,‘Moya’),(4142,5,3,1,38,‘Puente
Negro’),(4143,5,3,1,39,‘Retallano
(parte)’),(4144,5,3,1,40,‘Rincón’),(4145,5,3,1,41,‘Río Cañas
Viejo’),(4146,5,3,1,42,‘San Juan’),(4147,5,3,1,43,‘San
Pedro’),(4148,5,3,1,44,‘San Pedro Viejo’),(4149,5,3,1,45,‘Vista al
Mar.’),(4150,5,3,2,1,‘Ortega.’),(4151,5,3,2,2,‘Ballena
(parte)’),(4152,5,3,2,3,‘Lagartero’),(4153,5,3,2,4,‘Pochotada.’),(4154,5,3,3,1,‘Jobos.’),(4155,5,3,3,2,‘Aguacate’),(4156,5,3,3,3,‘Avellana’),(4157,5,3,3,4,‘Barrosa’),(4158,5,3,3,5,‘Brisas’),(4159,5,3,3,6,‘Bruno’),(4160,5,3,3,7,‘Cacaovano’),(4161,5,3,3,8,‘Camones’),(4162,5,3,3,9,‘Cañas
Gordas’),(4163,5,3,3,10,‘Ceiba Mocha’),(4164,5,3,3,11,‘Cerro
Brujo’),(4165,5,3,3,12,‘Congo’),(4166,5,3,3,13,‘Delicias’),(4167,5,3,3,14,‘Espavelar’),(4168,5,3,3,15,‘Florida’),(4169,5,3,3,16,‘Gongolona’),(4170,5,3,3,17,‘Guachipelín’),(4171,5,3,3,18,‘Guapote’),(4172,5,3,3,19,‘Hatillo’),(4173,5,3,3,20,‘Isla
Verde’),(4174,5,3,3,21,‘Junquillal’),(4175,5,3,3,22,‘Junta de Río
Verde’),(4176,5,3,3,23,‘Mesas’),(4177,5,3,3,24,‘Montaña’),(4178,5,3,3,25,‘Monteverde’),(4179,5,3,3,26,‘Níspero’),(4180,5,3,3,27,‘Paraíso’),(4181,5,3,3,28,‘Pargos’),(4182,5,3,3,29,‘Paso
Hondo’),(4183,5,3,3,30,‘Pilas’),(4184,5,3,3,31,‘Playa
Lagartillo’),(4185,5,3,3,32,‘Playa
Negra’),(4186,5,3,3,33,‘Pochotes’),(4187,5,3,3,34,‘Ranchos’),(4188,5,3,3,35,‘Retallano
(parte)’),(4189,5,3,3,36,‘Río Seco’),(4190,5,3,3,37,‘Río
Tabaco’),(4191,5,3,3,38,‘San Francisco’),(4192,5,3,3,39,‘San
Jerónimo’),(4193,5,3,3,40,‘Soncoyo’),(4194,5,3,3,41,‘Tieso (San
Rafael)’),(4195,5,3,3,42,‘Trapiche’),(4196,5,3,3,43,‘Venado’),(4197,5,3,3,44,‘Vergel’),(4198,5,3,3,45,‘.’),(4199,5,3,4,1,‘Paraíso.’),(4200,5,3,4,2,‘Bejuco’),(4201,5,3,4,3,‘Chiles’),(4202,5,3,4,4,‘El
Llano’),(4203,5,3,4,5,‘Huacas’),(4204,5,3,4,6,‘Portegolpe’),(4205,5,3,4,7,‘Potrero’),(4206,5,3,4,8,‘Rincón.’),(4207,5,3,5,1,‘Edén’),(4208,5,3,5,2,‘Toyosa.’),(4209,5,3,5,3,‘Cañafístula’),(4210,5,3,5,4,‘Corocitos’),(4211,5,3,5,5,‘Higuerón’),(4212,5,3,5,6,‘Jobo’),(4213,5,3,5,7,‘Lorena’),(4214,5,3,5,8,‘Oratorio’),(4215,5,3,5,9,‘sacatipe’),(4216,5,3,6,1,‘Alemania’),(4217,5,3,6,2,‘Bolillos’),(4218,5,3,6,3,‘Cuajiniquil’),(4219,5,3,6,4,‘Chiquero’),(4220,5,3,6,5,‘Fortuna’),(4221,5,3,6,6,‘Frijolar’),(4222,5,3,6,7,‘Jazminal’),(4223,5,3,6,8,‘Lagarto’),(4224,5,3,6,9,‘Libertad’),(4225,5,3,6,10,‘Limonal’),(4226,5,3,6,11,‘Manzanillo’),(4227,5,3,6,12,‘Marbella’),(4228,5,3,6,13,‘Ostional’),(4229,5,3,6,14,‘Palmares’),(4230,5,3,6,15,‘Piedras
Amarillas’),(4231,5,3,6,16,‘Progreso’),(4232,5,3,6,17,‘Punta
Caliente’),(4233,5,3,6,18,‘Quebrada Seca’),(4234,5,3,6,19,‘Quebrada
Zapote’),(4235,5,3,6,20,‘Rayo’),(4236,5,3,6,21,‘Roble’),(4237,5,3,6,22,‘Rosario’),(4238,5,3,6,23,‘Santa
Cecilia’),(4239,5,3,6,24,‘Santa
Elena’),(4240,5,3,6,25,‘Socorro’),(4241,5,3,6,26,‘Unión’),(4242,5,3,6,27,‘Veracrúz.’),(4243,5,3,7,1,‘Ángeles’),(4244,5,3,7,2,‘Duendes’),(4245,5,3,7,3,‘Lomitas’),(4246,5,3,7,4,‘Oriente.’),(4247,5,3,7,5,‘Calle
Vieja’),(4248,5,3,7,6,‘Coyolar’),(4249,5,3,7,7,‘Chumico
(parte)’),(4250,5,3,7,8,‘Diría’),(4251,5,3,7,9,‘Guaitil’),(4252,5,3,7,10,‘Polvazal’),(4253,5,3,7,11,‘Sequeira’),(4254,5,3,7,12,‘Talolinguita’),(4255,5,3,7,13,‘Trompillal.’),(4256,5,3,8,1,‘Brasilito’),(4257,5,3,8,2,‘Buen
Pastor’),(4258,5,3,8,3,‘Conchal’),(4259,5,3,8,4,‘Flamenco’),(4260,5,3,8,5,‘Garita
Vieja’),(4261,5,3,8,6,‘Jesús
María’),(4262,5,3,8,7,‘Lajas’),(4263,5,3,8,8,‘Lomas’),(4264,5,3,8,9,‘Playa
Cabuya’),(4265,5,3,8,10,‘Playa Grande’),(4266,5,3,8,11,‘Playa
Mina’),(4267,5,3,8,12,‘Playa Real’),(4268,5,3,8,13,‘Puerto
Viejo’),(4269,5,3,8,14,‘Salinas’),(4270,5,3,8,15,‘Salinitas’),(4271,5,3,8,16,‘Tacasolapa’),(4272,5,3,8,17,‘Zapotillal.’),(4273,5,3,9,1,‘Cañafístula’),(4274,5,3,9,2,‘Cebadilla’),(4275,5,3,9,3,‘El
Llano’),(4276,5,3,9,4,‘Garita’),(4277,5,3,9,5,‘Guatemala’),(4278,5,3,9,6,‘Hernández’),(4279,5,3,9,7,‘Icacal’),(4280,5,3,9,8,‘La
Loma’),(4281,5,3,9,9,‘Linderos’),(4282,5,3,9,10,‘Mangos’),(4283,5,3,9,11,‘Palmar’),(4284,5,3,9,12,‘San
Andrés’),(4285,5,3,9,13,‘San José Pinilla’),(4286,5,3,9,14,‘Santa
Rosa’),(4287,5,3,9,15,‘Tamarindo.’),(4288,5,4,1,1,‘Lima’),(4289,5,4,1,2,‘Pedro
Nolasco’),(4290,5,4,1,3,‘Redondel.’),(4291,5,4,1,4,‘Aguacaliente’),(4292,5,4,1,5,‘Arbolito’),(4293,5,4,1,6,‘Bagatzi’),(4294,5,4,1,7,‘Brisas’),(4295,5,4,1,8,‘Bebedero
(parte)’),(4296,5,4,1,9,‘Casavieja
(parte)’),(4297,5,4,1,10,‘Cofradía’),(4298,5,4,1,11,‘Colmenar’),(4299,5,4,1,12,‘Llanos
de
Cortés’),(4300,5,4,1,13,‘Mojica’),(4301,5,4,1,14,‘Montano’),(4302,5,4,1,15,‘Montenegro’),(4303,5,4,1,16,‘Piedras’),(4304,5,4,1,17,‘Pijije’),(4305,5,4,1,18,‘Plazuela’),(4306,5,4,1,19,‘Salitral’),(4307,5,4,1,20,‘Salto
(parte)’),(4308,5,4,1,21,‘Santa
Rosa.’),(4309,5,4,2,1,‘Miravalles.’),(4310,5,4,2,2,‘Casavieja
(parte)’),(4311,5,4,2,3,‘Cuipilapa’),(4312,5,4,2,4,‘Giganta’),(4313,5,4,2,5,‘Hornillas’),(4314,5,4,2,6,‘Macuá’),(4315,5,4,2,7,‘Martillete’),(4316,5,4,2,8,‘Mozotal’),(4317,5,4,2,9,‘Pozo
Azul’),(4318,5,4,2,10,‘Sagrada Familia’),(4319,5,4,2,11,‘San
Bernardo’),(4320,5,4,2,12,‘San Joaquín’),(4321,5,4,2,13,‘Santa
Cecilia’),(4322,5,4,2,14,‘Santa Fe’),(4323,5,4,2,15,‘Santa
Rosa’),(4324,5,4,2,16,‘Unión Ferrer.’),(4325,5,4,3,1,‘Los
Ángeles’),(4326,5,4,3,2,‘Oses.’),(4327,5,4,3,3,‘Barro de
Olla’),(4328,5,4,3,4,‘Horcones’),(4329,5,4,3,5,‘La
Ese’),(4330,5,4,3,6,‘Limonal’),(4331,5,4,3,7,‘Manglar’),(4332,5,4,3,8,‘Mochadero’),(4333,5,4,3,9,‘Pueblo
Nuevo’),(4334,5,4,3,10,‘Rincón de La Cruz’),(4335,5,4,3,11,‘San Isidro
de Limonal’),(4336,5,4,3,12,‘San Jorge’),(4337,5,4,3,13,‘San
Pedro’),(4338,5,4,3,14,‘Torno.’),(4339,5,4,4,1,‘Naranjito.’),(4340,5,4,4,2,‘Río
Chiquito.’),(4341,5,5,1,1,‘Bambú’),(4342,5,5,1,2,‘Cinco
Esquinas’),(4343,5,5,1,3,‘Hollywood’),(4344,5,5,1,4,‘La
Cruz’),(4345,5,5,1,5,‘Santa Lucía’),(4346,5,5,1,6,‘Ballena
(parte)’),(4347,5,5,1,7,‘Corralillo’),(4348,5,5,1,8,‘Guinea’),(4349,5,5,1,9,‘Isleta
(parte)’),(4350,5,5,1,10,‘Jocote’),(4351,5,5,1,11,‘Juanilama’),(4352,5,5,1,12,‘Moralito’),(4353,5,5,1,13,‘Ojoche’),(4354,5,5,1,14,‘San
Francisco.’),(4355,5,5,2,1,‘Coyolera’),(4356,5,5,2,2,‘María
Auxiliadora.’),(4357,5,5,2,3,‘Ángeles’),(4358,5,5,2,4,‘Comunidad’),(4359,5,5,2,5,‘Paso
Tempisque (parte)’),(4360,5,5,2,6,‘San
Rafael.’),(4361,5,5,3,1,‘Carpintera’),(4362,5,5,3,2,‘Colegios’),(4363,5,5,3,3,‘Verdún.’),(4364,5,5,3,4,‘Artola’),(4365,5,5,3,5,‘Cacique’),(4366,5,5,3,6,‘Coco’),(4367,5,5,3,7,‘Chorrera’),(4368,5,5,3,8,‘Guacamaya’),(4369,5,5,3,9,‘Huaquitas’),(4370,5,5,3,10,‘Libertad’),(4371,5,5,3,11,‘Los
Canales’),(4372,5,5,3,12,‘Matapalo’),(4373,5,5,3,13,‘Nancital’),(4374,5,5,3,14,‘Nuevo
Colón’),(4375,5,5,3,15,‘Obandito’),(4376,5,5,3,16,‘Ocotal’),(4377,5,5,3,17,‘Pilas’),(4378,5,5,3,18,‘Playa
Hermosa’),(4379,5,5,3,19,‘Playones’),(4380,5,5,3,20,‘San
Blas’),(4381,5,5,3,21,‘San Martín’),(4382,5,5,3,22,‘Santa
Rita’),(4383,5,5,3,23,‘Segovia’),(4384,5,5,3,24,‘Tabores’),(4385,5,5,3,25,‘Zapotal.’),(4386,5,5,4,1,‘Villita’),(4387,5,5,4,2,‘Alto
San Antonio’),(4388,5,5,4,3,‘Cachimbo’),(4389,5,5,4,4,‘Castilla de
Oro’),(4390,5,5,4,5,‘Coyolito’),(4391,5,5,4,6,‘Gallina’),(4392,5,5,4,7,‘Juanilama’),(4393,5,5,4,8,‘Loma
Bonita’),(4394,5,5,4,9,‘LLano’),(4395,5,5,4,10,‘Ojochal’),(4396,5,5,4,11,‘Palestina’),(4397,5,5,4,12,‘Palmas’),(4398,5,5,4,13,‘Paraíso’),(4399,5,5,4,14,‘Penca’),(4400,5,5,4,15,‘Planes’),(4401,5,5,4,16,‘Poroporo’),(4402,5,5,4,17,‘Río
Cañas Nuevo’),(4403,5,5,4,18,‘Santa Ana’),(4404,5,5,4,19,‘Santo
Domingo.’),(4405,5,6,1,1,‘Albania’),(4406,5,6,1,2,‘Ángeles’),(4407,5,6,1,3,‘Bello
Horizonte’),(4408,5,6,1,4,‘Cantarrana’),(4409,5,6,1,5,‘Castillo’),(4410,5,6,1,6,‘Cueva’),(4411,5,6,1,7,‘Chorotega’),(4412,5,6,1,8,‘Las
Cañas’),(4413,5,6,1,9,‘Malinches’),(4414,5,6,1,10,‘Miravalles’),(4415,5,6,1,11,‘Palmas’),(4416,5,6,1,12,‘San
Cristóbal’),(4417,5,6,1,13,‘San Martín’),(4418,5,6,1,14,‘San
Pedro’),(4419,5,6,1,15,‘Santa Isabel Abajo’),(4420,5,6,1,16,‘Santa
Isabel Arriba’),(4421,5,6,1,17,‘Tenorio’),(4422,5,6,1,18,‘Tres
Marías’),(4423,5,6,1,19,‘Unión.’),(4424,5,6,1,20,‘Cedros’),(4425,5,6,1,21,‘Cepo’),(4426,5,6,1,22,‘Concepción’),(4427,5,6,1,23,‘Corobicí’),(4428,5,6,1,24,‘Correntadas’),(4429,5,6,1,25,‘Cuesta
el Diablo’),(4430,5,6,1,26,‘Cuesta el
Mico’),(4431,5,6,1,27,‘Hotel’),(4432,5,6,1,28,‘Jabilla
Abajo’),(4433,5,6,1,29,‘Jabilla
Arriba’),(4434,5,6,1,30,‘Libertad’),(4435,5,6,1,31,‘Montes de
Oro’),(4436,5,6,1,32,‘Paso
Lajas’),(4437,5,6,1,33,‘Pedregal’),(4438,5,6,1,34,‘Pochota’),(4439,5,6,1,35,‘Pueblo
Nuevo’),(4440,5,6,1,36,‘Sandial (Sandillal)’),(4441,5,6,1,37,‘San Isidro
(parte)’),(4442,5,6,1,38,‘Santa Lucía
(parte)’),(4443,5,6,1,39,‘Vergel.’),(4444,5,6,2,1,‘Aguacaliente’),(4445,5,6,2,2,‘Aguas
Gatas
(parte)’),(4446,5,6,2,3,‘Coyota’),(4447,5,6,2,4,‘Huacal’),(4448,5,6,2,5,‘Las
Flores’),(4449,5,6,2,6,‘Martirio’),(4450,5,6,2,7,‘Panales’),(4451,5,6,2,8,‘Paraíso
(parte)’),(4452,5,6,2,9,‘San Isidro (parte)’),(4453,5,6,2,10,‘Santa
Lucía
(parte)’),(4454,5,6,2,11,‘Tenorio’),(4455,5,6,2,12,‘Vueltas.’),(4456,5,6,3,1,‘El
Coco’),(4457,5,6,3,2,‘El Güis’),(4458,5,6,3,3,‘Eskameca
(parte)’),(4459,5,6,3,4,‘Gotera’),(4460,5,6,3,5,‘Higuerón’),(4461,5,6,3,6,‘Higuerón
Viejo’),(4462,5,6,3,7,‘San
Juan.’),(4463,5,6,4,1,‘Loma.’),(4464,5,6,4,2,‘Coopetaboga’),(4465,5,6,4,3,‘Taboga
(parte).’),(4466,5,6,5,1,‘Brisas’),(4467,5,6,5,2,‘Eskameca
(parte)’),(4468,5,6,5,3,‘Guapinol’),(4469,5,6,5,4,‘Pozas’),(4470,5,6,5,5,‘Puerto
Alegre’),(4471,5,6,5,6,‘Quesera’),(4472,5,6,5,7,‘Santa
Lucía’),(4473,5,6,5,8,‘Taboga
(parte)’),(4474,5,6,5,9,‘Tiquirusas.’),(4475,5,7,1,1,‘Bellavista’),(4476,5,7,1,2,‘Cinco
Esquinas’),(4477,5,7,1,3,‘La Gloria’),(4478,5,7,1,4,‘Paso
Ancho’),(4479,5,7,1,5,‘San Antonio’),(4480,5,7,1,6,‘San
Jorge’),(4481,5,7,1,7,‘San
Pablo.’),(4482,5,7,1,8,‘Blanco’),(4483,5,7,1,9,‘Cecilia’),(4484,5,7,1,10,‘Codornices’),(4485,5,7,1,11,‘Concepción’),(4486,5,7,1,12,‘Coyolito
(parte)’),(4487,5,7,1,13,‘Chiqueros’),(4488,5,7,1,14,‘Desjarretado’),(4489,5,7,1,15,‘Irma’),(4490,5,7,1,16,‘Jarquín
(parte)’),(4491,5,7,1,17,‘Jesús’),(4492,5,7,1,18,‘Lajas’),(4493,5,7,1,19,‘Las
Huacas (Parte)’),(4494,5,7,1,20,‘Limonal’),(4495,5,7,1,21,‘Limonal
Viejo’),(4496,5,7,1,22,‘Matapalo’),(4497,5,7,1,23,‘Naranjos
Agrios’),(4498,5,7,1,24,‘Palma’),(4499,5,7,1,25,‘Peña’),(4500,5,7,1,26,‘Puente
de Tierra’),(4501,5,7,1,27,‘Rancho Alegre
(parte)’),(4502,5,7,1,28,‘Lourdes (Rancho Ania)
(parte)’),(4503,5,7,1,29,‘San Cristóbal’),(4504,5,7,1,30,‘San
Francisco’),(4505,5,7,1,31,‘San Juan Chiquito’),(4506,5,7,1,32,‘Santa
Lucía’),(4507,5,7,1,33,‘Tortugal’),(4508,5,7,1,34,‘Zapote.’),(4509,5,7,2,1,‘Aguas
Claras’),(4510,5,7,2,2,‘Alto Cebadilla’),(4511,5,7,2,3,‘Campos de
Oro’),(4512,5,7,2,4,‘Candelaria’),(4513,5,7,2,5,‘Cañitas’),(4514,5,7,2,6,‘Cruz’),(4515,5,7,2,7,‘Cuesta
Yugo’),(4516,5,7,2,8,‘Dos de
Abangares’),(4517,5,7,2,9,‘Marsellesa’),(4518,5,7,2,10,‘San
Antonio’),(4519,5,7,2,11,‘San
Rafael’),(4520,5,7,2,12,‘Tornos’),(4521,5,7,2,13,‘Tres
Amigos’),(4522,5,7,2,14,‘Turín
(parte).’),(4523,5,7,3,1,‘Arizona’),(4524,5,7,3,2,‘Congo’),(4525,5,7,3,3,‘Nancital’),(4526,5,7,3,4,‘Portones’),(4527,5,7,3,5,‘Pozo
Azul’),(4528,5,7,3,6,‘Rancho Alegre (parte)’),(4529,5,7,3,7,‘Lourdes
(Rancho Ania) (parte)’),(4530,5,7,3,8,‘Tierra
Colorada’),(4531,5,7,3,9,‘Vainilla.’),(4532,5,7,4,1,‘Almendros’),(4533,5,7,4,2,‘Barbudal’),(4534,5,7,4,3,‘Gavilanes’),(4535,5,7,4,4,‘Higuerillas’),(4536,5,7,4,5,‘Las
Huacas (parte)’),(4537,5,7,4,6,‘Monte
Potrero’),(4538,5,7,4,7,‘Quebracho’),(4539,5,7,4,8,‘Peñablanca’),(4540,5,7,4,9,‘San
Buenaventura’),(4541,5,7,4,10,‘San
Joaquín’),(4542,5,7,4,11,‘Solimar’),(4543,5,7,4,12,‘Villafuerte.’),(4544,5,8,1,1,‘Cabra’),(4545,5,8,1,2,‘Carmen’),(4546,5,8,1,3,‘Juan
XXIII’),(4547,5,8,1,4,‘Lomalinda.’),(4548,5,8,1,5,‘Cuatro
Esquinas’),(4549,5,8,1,6,‘Chiripa’),(4550,5,8,1,7,‘Piamonte’),(4551,5,8,1,8,‘Río
Chiquito’),(4552,5,8,1,9,‘San
Luis’),(4553,5,8,1,10,‘Tejona’),(4554,5,8,1,11,‘Tres
Esquinas.’),(4555,5,8,2,1,‘Barrionuevo’),(4556,5,8,2,2,‘Cabeceras de
Cañas’),(4557,5,8,2,3,‘Campos de Oro’),(4558,5,8,2,4,‘Dos de
Tilarán’),(4559,5,8,2,5,‘Esperanza’),(4560,5,8,2,6,‘Florida’),(4561,5,8,2,7,‘Monte
Olivos’),(4562,5,8,2,8,‘Nubes’),(4563,5,8,2,9,‘San
Miguel’),(4564,5,8,2,10,‘Turín
(parte)’),(4565,5,8,2,11,‘Vueltas.’),(4566,5,8,3,1,‘Arenal
Viejo’),(4567,5,8,3,2,‘Colonia Menonita’),(4568,5,8,3,3,‘Río Chiquito
Abajo’),(4569,5,8,3,4,‘Silencio.’),(4570,5,8,4,1,‘Aguilares’),(4571,5,8,4,2,‘Campos
Azules’),(4572,5,8,4,3,‘Montes de Oro (parte)’),(4573,5,8,4,4,‘Naranjos
Agrios’),(4574,5,8,4,5,‘Palma’),(4575,5,8,4,6,‘Quebrada
Azul’),(4576,5,8,4,7,‘Ranchitos’),(4577,5,8,4,8,‘Santa
Rosa.’),(4578,5,8,5,1,‘Alto
Cartago’),(4579,5,8,5,2,‘Maravilla’),(4580,5,8,5,3,‘San
José’),(4581,5,8,5,4,‘Solania.’),(4582,5,8,6,1,‘Aguacate’),(4583,5,8,6,2,‘Aguas
Gatas (parte)’),(4584,5,8,6,3,‘Bajo
Paires’),(4585,5,8,6,4,‘Guadalajara’),(4586,5,8,6,5,‘Montes de Oro
(parte)’),(4587,5,8,6,6,‘Paraíso (parte)’),(4588,5,8,6,7,‘Río
Piedras’),(4589,5,8,6,8,‘Sabalito.’),(4590,5,8,7,1,‘Mata de
Caña’),(4591,5,8,7,2,‘Sangregado’),(4592,5,8,7,3,‘San
Antonio’),(4593,5,8,7,4,‘Unión.’),(4594,5,9,1,1,‘Camas’),(4595,5,9,1,2,‘Limones’),(4596,5,9,1,3,‘Maquenco’),(4597,5,9,1,4,‘San
Rafael’),(4598,5,9,1,5,‘Vista de
Mar.’),(4599,5,9,2,1,‘Angostura’),(4600,5,9,2,2,‘Cacao’),(4601,5,9,2,3,‘Chumico’),(4602,5,9,2,4,‘Guaria’),(4603,5,9,2,5,‘Guastomatal’),(4604,5,9,2,6,‘Morote’),(4605,5,9,2,7,‘Tacanis’),(4606,5,9,2,8,‘Uvita
(parte)’),(4607,5,9,2,9,‘Yerbabuena (parte).’),(4608,5,9,3,1,‘Altos de
Mora’),(4609,5,9,3,2,‘Cabeceras de Río
Ora’),(4610,5,9,3,3,‘Camaronal’),(4611,5,9,3,4,‘Carmen’),(4612,5,9,3,5,‘Cuesta
Bijagua’),(4613,5,9,3,6,‘Leona’),(4614,5,9,3,7,‘Manzanales’),(4615,5,9,3,8,‘Río
Blanco Este’),(4616,5,9,3,9,‘Río de Oro’),(4617,5,9,3,10,‘Río
Ora’),(4618,5,9,3,11,‘San Martín’),(4619,5,9,3,12,‘San
Pedro’),(4620,5,9,3,13,‘Soledad.’),(4621,5,9,4,1,‘Canjel’),(4622,5,9,4,2,‘Canjelito’),(4623,5,9,4,3,‘Corozal
Oeste’),(4624,5,9,4,4,‘Chamarro’),(4625,5,9,4,5,‘Isla
Berrugate’),(4626,5,9,4,6,‘Pavones’),(4627,5,9,4,7,‘Puerto
Thiel’),(4628,5,9,4,8,‘San Pablo
Viejo.’),(4629,5,9,5,1,‘Ángeles’),(4630,5,9,5,2,‘Bellavista’),(4631,5,9,5,3,‘Cabeceras
de Río Bejuco’),(4632,5,9,5,4,‘Chompipe
(parte)’),(4633,5,9,5,5,‘Delicias’),(4634,5,9,5,6,‘Quebrada
Grande’),(4635,5,9,5,7,‘San
Josecito.’),(4636,5,9,6,1,‘Caletas’),(4637,5,9,6,2,‘Candelillo’),(4638,5,9,6,3,‘Corozalito’),(4639,5,9,6,4,‘Chiruta’),(4640,5,9,6,5,‘Chompipe
(parte)’),(4641,5,9,6,6,‘I
Griega’),(4642,5,9,6,7,‘Islita’),(4643,5,9,6,8,‘Jabilla’),(4644,5,9,6,9,‘Jabillos’),(4645,5,9,6,10,‘Maicillal’),(4646,5,9,6,11,‘Maquencal’),(4647,5,9,6,12,‘Milagro’),(4648,5,9,6,13,‘Millal’),(4649,5,9,6,14,‘Mono’),(4650,5,9,6,15,‘Pampas’),(4651,5,9,6,16,‘Paso
Vigas’),(4652,5,9,6,17,‘Pencal’),(4653,5,9,6,18,‘Playa
Coyote’),(4654,5,9,6,19,‘Playa San Miguel’),(4655,5,9,6,20,‘Pueblo
Nuevo’),(4656,5,9,6,21,‘Punta Bejuco’),(4657,5,9,6,22,‘Puerto
Coyote’),(4658,5,9,6,23,‘Quebrada Nando’),(4659,5,9,6,24,‘Quebrada
Seca’),(4660,5,9,6,25,‘Rancho Floriana’),(4661,5,9,6,26,‘San Francisco
de Coyote’),(4662,5,9,6,27,‘San Gabriel’),(4663,5,9,6,28,‘San
Miguel’),(4664,5,9,6,29,‘Triunfo’),(4665,5,9,6,30,‘Zapote.’),(4666,5,10,1,1,‘Corazón
de
Jesús’),(4667,5,10,1,2,‘Fátima’),(4668,5,10,1,3,‘Irving’),(4669,5,10,1,4,‘Orosí’),(4670,5,10,1,5,‘Pinos’),(4671,5,10,1,6,‘Santa
Rosa.’),(4672,5,10,1,7,‘Bellavista’),(4673,5,10,1,8,‘Bello
Horizonte’),(4674,5,10,1,9,‘Brisas’),(4675,5,10,1,10,‘Cacao’),(4676,5,10,1,11,‘Carrizal’),(4677,5,10,1,12,‘Carrizales’),(4678,5,10,1,13,‘Colonia
Bolaños’),(4679,5,10,1,14,‘Copalchí’),(4680,5,10,1,15,‘Infierno’),(4681,5,10,1,16,‘Jobo’),(4682,5,10,1,17,‘Libertad’),(4683,5,10,1,18,‘Monte
Plata’),(4684,5,10,1,19,‘Montes de
Oro’),(4685,5,10,1,20,‘Pampa’),(4686,5,10,1,21,‘Pegón’),(4687,5,10,1,22,‘Peñas
Blancas’),(4688,5,10,1,23,‘Piedra Pómez’),(4689,5,10,1,24,‘Puerto
Soley’),(4690,5,10,1,25,‘Recreo’),(4691,5,10,1,26,‘San
Buenaventura’),(4692,5,10,1,27,‘San Dimas’),(4693,5,10,1,28,‘San
Paco’),(4694,5,10,1,29,‘San Roque’),(4695,5,10,1,30,‘Santa
Rogelia’),(4696,5,10,1,31,‘Santa
Rosa’),(4697,5,10,1,32,‘Soley’),(4698,5,10,1,33,‘Sonzapote’),(4699,5,10,1,34,‘Tempatal’),(4700,5,10,1,35,‘Vueltas.’),(4701,5,10,2,1,‘Ángeles’),(4702,5,10,2,2,‘Corrales
Negros’),(4703,5,10,2,3,‘Pueblo
Nuevo’),(4704,5,10,2,4,’‘),(4705,5,10,2,5,’Argendora’),(4706,5,10,2,6,‘Armenia’),(4707,5,10,2,7,‘Belice’),(4708,5,10,2,8,‘Bellavista’),(4709,5,10,2,9,‘Brisas’),(4710,5,10,2,10,‘Caoba’),(4711,5,10,2,11,‘Esperanza’),(4712,5,10,2,12,‘Flor
del
Norte’),(4713,5,10,2,13,‘Lajosa’),(4714,5,10,2,14,‘Marías’),(4715,5,10,2,15,‘Palmares’),(4716,5,10,2,16,‘San
Antonio’),(4717,5,10,2,17,‘San Cristóbal’),(4718,5,10,2,18,‘San
Rafael’),(4719,5,10,2,19,‘San Vicente’),(4720,5,10,2,20,‘Santa
Elena’),(4721,5,10,2,21,‘Sardina’),(4722,5,10,2,22,‘Virgen.’),(4723,5,10,3,1,‘Paraíso.’),(4724,5,10,3,2,‘Agua
Muerta’),(4725,5,10,3,3,‘Andes’),(4726,5,10,3,4,‘Asilo’),(4727,5,10,3,5,‘Cañita’),(4728,5,10,3,6,‘Carmen’),(4729,5,10,3,7,‘Fortuna’),(4730,5,10,3,8,‘Gloria’),(4731,5,10,3,9,‘Guapinol’),(4732,5,10,3,10,‘Inocentes’),(4733,5,10,3,11,‘Lavaderos’),(4734,5,10,3,12,‘Pochote’),(4735,5,10,3,13,‘San
Antonio’),(4736,5,10,3,14,‘Tapesco.’),(4737,5,10,4,1,‘Cedros’),(4738,5,10,4,2,‘Guaria’),(4739,5,10,4,3,‘Puerto
Castilla’),(4740,5,10,4,4,‘Rabo de Mico
(Aguacaliente).’),(4741,5,11,1,1,‘Ángeles’),(4742,5,11,1,2,‘Arena’),(4743,5,11,1,3,‘Ceiba’),(4744,5,11,1,4,‘Cuesta
Blanca’),(4745,5,11,1,5,‘Libertad’),(4746,5,11,1,6,‘Maravilla’),(4747,5,11,1,7,‘Matambú’),(4748,5,11,1,8,‘Palo
de Jabón’),(4749,5,11,1,9,‘Pilangosta’),(4750,5,11,1,10,‘San Juan
Bosco’),(4751,5,11,1,11,‘San Rafael’),(4752,5,11,1,12,‘Santa Elena
(parte)’),(4753,5,11,1,13,‘Varillal.’),(4754,5,11,2,1,‘Altos del
Socorro’),(4755,5,11,2,2,‘Bajo
Saltos’),(4756,5,11,2,3,‘Cabrera’),(4757,5,11,2,4,‘Cuesta
Roja’),(4758,5,11,2,5,‘Delicias’),(4759,5,11,2,6,‘Guapinol’),(4760,5,11,2,7,‘Loros’),(4761,5,11,2,8,‘Mercedes’),(4762,5,11,2,9,‘Palmares’),(4763,5,11,2,10,‘Río
Zapotal’),(4764,5,11,2,11,‘San
Isidro’),(4765,5,11,2,12,‘Trinidad.’),(4766,5,11,3,1,‘Angostura’),(4767,5,11,3,2,‘Arbolito’),(4768,5,11,3,3,‘Cuesta
Malanoche’),(4769,5,11,3,4,‘Estrada’),(4770,5,11,3,5,‘Jobo’),(4771,5,11,3,6,‘Lajas’),(4772,5,11,3,7,‘Quebrada
Bonita (parte)’),(4773,5,11,3,8,‘San Miguel’),(4774,5,11,3,9,‘Santa
María.’),(4775,5,11,4,1,‘Avellana’),(4776,5,11,4,2,‘Pita
Rayada’),(4777,5,11,4,3,‘Río Blanco Oeste’),(4778,5,11,4,4,‘Tres
Quebradas.’),(4779,6,1,1,1,‘Angostura’),(4780,6,1,1,2,‘Carmen’),(4781,6,1,1,3,‘Cocal’),(4782,6,1,1,4,‘Playitas’),(4783,6,1,1,5,‘Pochote’),(4784,6,1,1,6,‘Pueblo
Nuevo.’),(4785,6,1,1,7,‘Isla Bejuco’),(4786,6,1,1,8,‘Isla
Caballo’),(4787,6,1,1,9,‘Palmar.’),(4788,6,1,2,1,‘Aranjuéz’),(4789,6,1,2,2,‘Brillante
(parte)’),(4790,6,1,2,3,‘Cebadilla’),(4791,6,1,2,4,‘Chapernal’),(4792,6,1,2,5,‘Palermo’),(4793,6,1,2,6,‘Pitahaya
Vieja’),(4794,6,1,2,7,‘Rancho Grande’),(4795,6,1,2,8,‘San Marcos
(parte)’),(4796,6,1,2,9,‘Zapotal.’),(4797,6,1,3,1,‘Alto Pie de
Paloma’),(4798,6,1,3,2,‘Cambalache’),(4799,6,1,3,3,‘Cocoroca’),(4800,6,1,3,4,‘Coyoles
Motos’),(4801,6,1,3,5,‘Don Jaime’),(4802,6,1,3,6,‘Jarquín
(parte)’),(4803,6,1,3,7,‘Judas’),(4804,6,1,3,8,‘Laberinto’),(4805,6,1,3,9,‘Lagarto’),(4806,6,1,3,10,‘Malinche’),(4807,6,1,3,11,‘Morales’),(4808,6,1,3,12,‘Pita’),(4809,6,1,3,13,‘Playa
Coco’),(4810,6,1,3,14,‘Pocitos’),(4811,6,1,3,15,‘Punta
Morales’),(4812,6,1,3,16,‘San Agustín’),(4813,6,1,3,17,‘San
Gerardo’),(4814,6,1,3,18,‘Santa
Juana’),(4815,6,1,3,19,‘Sarmiento’),(4816,6,1,3,20,‘Terrero’),(4817,6,1,3,21,‘Vanegas’),(4818,6,1,3,22,‘Yomalé.’),(4819,6,1,4,1,‘Alto
Fresca’),(4820,6,1,4,2,‘Bajo
Mora’),(4821,6,1,4,3,‘Balsa’),(4822,6,1,4,4,‘Balso’),(4823,6,1,4,5,‘Bijagua’),(4824,6,1,4,6,‘Brisas’),(4825,6,1,4,7,‘Cabo
Blanco’),(4826,6,1,4,8,‘Camaronal’),(4827,6,1,4,9,‘Cantil’),(4828,6,1,4,10,‘Cañablancal’),(4829,6,1,4,11,‘Cerro
Frío’),(4830,6,1,4,12,‘Cerro Indio’),(4831,6,1,4,13,‘Cerro
Pando’),(4832,6,1,4,14,‘Corozal’),(4833,6,1,4,15,‘Coto’),(4834,6,1,4,16,‘Cuajiniquil’),(4835,6,1,4,17,‘Chanchos’),(4836,6,1,4,18,‘Chiqueros’),(4837,6,1,4,19,‘Dominica’),(4838,6,1,4,20,‘El
Mora’),(4839,6,1,4,21,‘Encanto’),(4840,6,1,4,22,‘Fresca’),(4841,6,1,4,23,‘Gloria’),(4842,6,1,4,24,‘Golfo’),(4843,6,1,4,25,‘Guabo’),(4844,6,1,4,26,‘Guadalupe’),(4845,6,1,4,27,‘Ilusión’),(4846,6,1,4,28,‘Isla
Venado’),(4847,6,1,4,29,‘Jicaral’),(4848,6,1,4,30,‘Juan de
León’),(4849,6,1,4,31,‘Milpa’),(4850,6,1,4,32,‘Montaña
Grande’),(4851,6,1,4,33,‘Níspero’),(4852,6,1,4,34,‘Nubes’),(4853,6,1,4,35,‘Once
Estrellas’),(4854,6,1,4,36,‘Piedades’),(4855,6,1,4,37,‘Pilas de
Canjel’),(4856,6,1,4,38,‘Punta de Cera’),(4857,6,1,4,39,‘Río
Seco’),(4858,6,1,4,40,‘Sahíno’),(4859,6,1,4,41,‘San
Blas’),(4860,6,1,4,42,‘San Miguel’),(4861,6,1,4,43,‘San Miguel de Río
Blanco’),(4862,6,1,4,44,‘San Pedro’),(4863,6,1,4,45,‘San
Rafael’),(4864,6,1,4,46,‘San Ramón de Río Blanco’),(4865,6,1,4,47,‘Santa
Rosa’),(4866,6,1,4,48,‘Tigra’),(4867,6,1,4,49,‘Tres
Ríos’),(4868,6,1,4,50,‘Tronconal’),(4869,6,1,4,51,‘Unión’),(4870,6,1,4,52,‘Vainilla.’),(4871,6,1,5,1,‘Angeles’),(4872,6,1,5,2,‘Astro
Blanco’),(4873,6,1,5,3,‘Bajo Negro’),(4874,6,1,5,4,‘Cabeceras de Río
Seco’),(4875,6,1,5,5,‘Campiñas’),(4876,6,1,5,6,‘Cerro
Brujo’),(4877,6,1,5,7,‘Concepción’),(4878,6,1,5,8,‘Curú’),(4879,6,1,5,9,‘Dulce
Nombre’),(4880,6,1,5,10,‘Espaveles’),(4881,6,1,5,11,‘Esperanza’),(4882,6,1,5,12,‘Flor’),(4883,6,1,5,13,‘Gigante’),(4884,6,1,5,14,‘Guaria’),(4885,6,1,5,15,‘Higueronal’),(4886,6,1,5,16,‘Isla
Cedros’),(4887,6,1,5,17,‘Isla Jesucita’),(4888,6,1,5,18,‘Isla
Tortuga’),(4889,6,1,5,19,‘Leona’),(4890,6,1,5,20,‘Mango’),(4891,6,1,5,21,‘Naranjo’),(4892,6,1,5,22,‘Pánica’),(4893,6,1,5,23,‘Paraíso’),(4894,6,1,5,24,‘Playa
Blanca’),(4895,6,1,5,25,‘Playa
Cuchillo’),(4896,6,1,5,26,‘Pochote’),(4897,6,1,5,27,‘Punta del
Río’),(4898,6,1,5,28,‘Quebrada Bonita’),(4899,6,1,5,29,‘Río
Grande’),(4900,6,1,5,30,‘Río Guarial’),(4901,6,1,5,31,‘Río
Seco’),(4902,6,1,5,32,‘Rivas’),(4903,6,1,5,33,‘San
Fernando’),(4904,6,1,5,34,‘San Luis’),(4905,6,1,5,35,‘San
Pedro’),(4906,6,1,5,36,‘San Rafael’),(4907,6,1,5,37,‘San
Vicente’),(4908,6,1,5,38,‘Santa Cecilia’),(4909,6,1,5,39,‘Santa
Lucía’),(4910,6,1,5,40,‘Santa
Rosa’),(4911,6,1,5,41,‘Sonzapote’),(4912,6,1,5,42,‘Tronco
Negro’),(4913,6,1,5,43,‘Valle
Azul’),(4914,6,1,5,44,‘Vueltas.’),(4915,6,1,6,1,‘Abangaritos’),(4916,6,1,6,2,‘Camarita’),(4917,6,1,6,3,‘Costa
de Pájaros’),(4918,6,1,6,4,‘Coyolito (parte)’),(4919,6,1,6,5,‘Cuesta
Portillo.’),(4920,6,1,7,1,‘Alto Méndez’),(4921,6,1,7,2,‘Altos
Fernández’),(4922,6,1,7,3,‘Ángeles’),(4923,6,1,7,4,‘Guaria’),(4924,6,1,7,5,‘Lajón’),(4925,6,1,7,6,‘San
Antonio’),(4926,6,1,7,7,‘Santa
Rosa’),(4927,6,1,7,8,‘Surtubal’),(4928,6,1,7,9,‘Veracruz.’),(4929,6,1,8,1,‘Rioja.’),(4930,6,1,8,2,‘Obregón’),(4931,6,1,8,3,‘San
Joaquín’),(4932,6,1,8,4,‘San Miguel’),(4933,6,1,8,5,‘San
Miguelito’),(4934,6,1,8,6,‘Santa Ana.’),(4935,6,1,9,1,‘Cerro
Plano’),(4936,6,1,9,2,‘Cuesta
Blanca’),(4937,6,1,9,3,‘Lindora’),(4938,6,1,9,4,‘Llanos’),(4939,6,1,9,5,‘Monte
Verde’),(4940,6,1,9,6,‘San
Luis.’),(4941,6,1,11,1,‘Abuela’),(4942,6,1,11,2,‘Arío’),(4943,6,1,11,3,‘Bajos
de Arío’),(4944,6,1,11,4,‘Bajos de Fernández’),(4945,6,1,11,5,‘Bello
Horizonte’),(4946,6,1,11,6,‘Betel’),(4947,6,1,11,7,‘Cabuya’),(4948,6,1,11,8,‘Canaán’),(4949,6,1,11,9,‘Cañada’),(4950,6,1,11,10,‘Caño
Seco Abajo’),(4951,6,1,11,11,‘Caño Seco Arriba’),(4952,6,1,11,12,‘Caño
Seco
Enmedio’),(4953,6,1,11,13,‘Carmen’),(4954,6,1,11,14,‘Cedro’),(4955,6,1,11,15,‘Cerital’),(4956,6,1,11,16,‘Cerro
Buenavista’),(4957,6,1,11,17,‘Cocal’),(4958,6,1,11,18,‘Cocalito’),(4959,6,1,11,19,‘Delicias’),(4960,6,1,11,20,‘Malpaís’),(4961,6,1,11,21,‘Montezuma’),(4962,6,1,11,22,‘Muelle’),(4963,6,1,11,23,‘Pachanga’),(4964,6,1,11,24,‘Pavón’),(4965,6,1,11,25,‘Pénjamo’),(4966,6,1,11,26,‘Piedra
Amarilla’),(4967,6,1,11,27,‘Pita’),(4968,6,1,11,28,‘Río
Enmedio’),(4969,6,1,11,29,‘Río Frío’),(4970,6,1,11,30,‘Río
Negro’),(4971,6,1,11,31,‘San Antonio’),(4972,6,1,11,32,‘San
Isidro’),(4973,6,1,11,33,‘San Jorge’),(4974,6,1,11,34,‘San
Ramón’),(4975,6,1,11,35,‘Santa Clemencia’),(4976,6,1,11,36,‘Santa
Fe’),(4977,6,1,11,37,‘Santa
Teresa’),(4978,6,1,11,38,‘Santiago’),(4979,6,1,11,39,‘Tacotales’),(4980,6,1,11,40,‘Tambor’),(4981,6,1,11,41,‘Villalta.’),(4982,6,1,12,1,‘Camboya’),(4983,6,1,12,2,‘Carrizal’),(4984,6,1,12,3,‘Chacarita’),(4985,6,1,12,4,‘Chacarita
Norte’),(4986,6,1,12,5,‘Fertica’),(4987,6,1,12,6,‘Fray
Casiano’),(4988,6,1,12,7,‘Huerto’),(4989,6,1,12,8,‘Lindavista’),(4990,6,1,12,9,‘Pueblo
Redondo’),(4991,6,1,12,10,‘Reyes’),(4992,6,1,12,11,‘San
Isidro’),(4993,6,1,12,12,‘Santa
Eduvigis’),(4994,6,1,12,13,‘Tanque’),(4995,6,1,12,14,‘Veinte de
Noviembre.’),(4996,6,1,13,1,‘Bocana’),(4997,6,1,13,2,‘Lagartero’),(4998,6,1,13,3,‘Montero’),(4999,6,1,13,4,‘Pilas’),(5000,6,1,13,5,‘Pochote’),(5001,6,1,13,6,‘Puerto
Coloradito’),(5002,6,1,13,7,‘Puerto Mauricio’),(5003,6,1,13,8,‘Puerto
Palito.’),(5004,6,1,14,1,‘Acapulco’),(5005,6,1,14,2,‘Aranjuecito’),(5006,6,1,14,3,‘Chapernalito’),(5007,6,1,14,4,‘Claraboya’),(5008,6,1,14,5,‘Coyolar’),(5009,6,1,14,6,‘Quebrada
Honda’),(5010,6,1,14,7,‘San Marcos (parte).’),(5011,6,1,15,1,‘Boca de
Barranca’),(5012,6,1,15,2,‘Chagüite’),(5013,6,1,15,3,‘El
Roble.’),(5014,6,1,16,1,‘Poblados. Arancibia
Norte’),(5015,6,1,16,2,‘Arancibia
Sur’),(5016,6,1,16,3,‘Lagunas’),(5017,6,1,16,4,‘Ojo de
Agua’),(5018,6,1,16,5,‘Rincón’),(5019,6,1,16,6,‘San Martín
Norte’),(5020,6,1,16,7,‘San Martín Sur.’),(5021,6,2,1,1,‘Marañonal
(parte)’),(5022,6,2,1,2,‘Mojón’),(5023,6,2,1,3,‘Tejar.’),(5024,6,2,1,4,‘Gregg’),(5025,6,2,1,5,‘Humo’),(5026,6,2,1,6,‘Mojoncito’),(5027,6,2,1,7,‘Pan
de
Azúcar.’),(5028,6,2,2,1,‘Jocote’),(5029,6,2,2,2,‘Juanilama’),(5030,6,2,2,3,‘San
Juan Chiquito.’),(5031,6,2,3,1,‘Marañonal
(parte).’),(5032,6,2,3,2,‘Bruselas’),(5033,6,2,3,3,‘Guapinol’),(5034,6,2,3,4,‘Nances’),(5035,6,2,3,5,‘San
Roque.’),(5036,6,2,4,1,‘Alto
Corteza’),(5037,6,2,4,2,‘Barón’),(5038,6,2,4,3,‘Facio’),(5039,6,2,4,4,‘Llanada
del
Cacao’),(5040,6,2,4,5,‘Maratón.’),(5041,6,2,5,1,‘Cerrillos’),(5042,6,2,5,2,‘Mesetas
Abajo’),(5043,6,2,5,3,‘Mesetas Arriba’),(5044,6,2,5,4,‘Peña
Blanca’),(5045,6,2,5,5,‘Pretiles’),(5046,6,2,5,6,‘Quebradas’),(5047,6,2,5,7,‘Sabana
Bonita.’),(5048,6,2,6,1,‘Alto de Las
Mesas’),(5049,6,2,6,2,‘Artieda’),(5050,6,2,6,3,‘Caldera’),(5051,6,2,6,4,‘Cabezas’),(5052,6,2,6,5,‘Cambalache’),(5053,6,2,6,6,‘Cascabel’),(5054,6,2,6,7,‘Corralillo’),(5055,6,2,6,8,‘Cuesta
Jocote’),(5056,6,2,6,9,‘Figueroa’),(5057,6,2,6,10,‘Finca Brazo
Seco’),(5058,6,2,6,11,‘Finca Cortijo’),(5059,6,2,6,12,‘Guardianes de La
Piedra’),(5060,6,2,6,13,‘Hacienda La Moncha’),(5061,6,2,6,14,‘Hacienda
Mata de Guinea’),(5062,6,2,6,15,‘Hacienda Playa
Linda’),(5063,6,2,6,16,‘Hacienda Salinas’),(5064,6,2,6,17,‘Jesús
María’),(5065,6,2,6,18,‘Quebrada
Honda’),(5066,6,2,6,19,‘Salinas’),(5067,6,2,6,20,‘San
Antonio’),(5068,6,2,6,21,‘Silencio’),(5069,6,2,6,22,‘Tivives’),(5070,6,2,6,23,‘Villanueva.’),(5071,6,3,1,1,‘Alto
Buenos Aires’),(5072,6,3,1,2,‘Lomas.’),(5073,6,3,1,3,‘Alto
Alejo’),(5074,6,3,1,4,‘Alto Brisas’),(5075,6,3,1,5,‘Alto
Calderón’),(5076,6,3,1,6,‘Bajo
Brisas’),(5077,6,3,1,7,‘Bolas’),(5078,6,3,1,8,‘Brujo’),(5079,6,3,1,9,‘Cabagra
(parte)’),(5080,6,3,1,10,‘Caracol’),(5081,6,3,1,11,‘Ceibo’),(5082,6,3,1,12,‘Colepato’),(5083,6,3,1,13,‘El
Carmen’),(5084,6,3,1,14,‘Guanacaste’),(5085,6,3,1,15,‘Guadalupe’),(5086,6,3,1,16,‘López’),(5087,6,3,1,17,‘Los
Altos’),(5088,6,3,1,18,‘Llano
Verde’),(5089,6,3,1,19,‘Machomontes’),(5090,6,3,1,20,‘Palmital’),(5091,6,3,1,21,‘Paraíso
(Ánimas)’),(5092,6,3,1,22,‘Paso
Verbá’),(5093,6,3,1,23,‘Piñera’),(5094,6,3,1,24,‘Platanares’),(5095,6,3,1,25,‘Potrero
Cerrado’),(5096,6,3,1,26,‘Puente de Salitre’),(5097,6,3,1,27,‘Río
Azul’),(5098,6,3,1,28,‘Salitre’),(5099,6,3,1,29,‘San
Carlos’),(5100,6,3,1,30,‘San Luis (Florida)’),(5101,6,3,1,31,‘San Miguel
Este’),(5102,6,3,1,32,‘San Miguel Oeste’),(5103,6,3,1,33,‘San
Vicente’),(5104,6,3,1,34,‘Santa Cruz’),(5105,6,3,1,35,‘Santa
Eduvigis’),(5106,6,3,1,36,‘Santa
Marta’),(5107,6,3,1,37,‘Sipar’),(5108,6,3,1,38,‘Ujarrás’),(5109,6,3,1,39,‘Villahermosa’),(5110,6,3,1,40,‘Yheri.’),(5111,6,3,2,1,‘Altamira’),(5112,6,3,2,2,‘Ángel
Arriba’),(5113,6,3,2,3,‘Bajos del Río
Grande’),(5114,6,3,2,4,‘Cacao’),(5115,6,3,2,5,‘Convento’),(5116,6,3,2,6,‘Cordoncillo’),(5117,6,3,2,7,‘Los
Ángeles’),(5118,6,3,2,8,‘Longo
Mai’),(5119,6,3,2,9,‘Peje’),(5120,6,3,2,10,‘Quebradas’),(5121,6,3,2,11,‘Río
Grande’),(5122,6,3,2,12,‘Sabanilla’),(5123,6,3,2,13,‘Sonador’),(5124,6,3,2,14,‘Tarise’),(5125,6,3,2,15,‘Tres
Ríos’),(5126,6,3,2,16,‘Ultrapez.’),(5127,6,3,3,1,‘Alto La
Cruz.’),(5128,6,3,3,2,‘Alto
Tigre’),(5129,6,3,3,3,‘Ángeles’),(5130,6,3,3,4,‘Boca
Limón’),(5131,6,3,3,5,‘Brazos de Oro’),(5132,6,3,3,6,‘Cabagra
(parte)’),(5133,6,3,3,7,‘Campo
Alegre’),(5134,6,3,3,8,‘Capri’),(5135,6,3,3,9,‘Caracol’),(5136,6,3,3,10,‘Caracucho’),(5137,6,3,3,11,‘Clavera’),(5138,6,3,3,12,‘Colegallo’),(5139,6,3,3,13,‘Copal’),(5140,6,3,3,14,‘Coto
Brus (parte)’),(5141,6,3,3,15,‘Cuesta
Marañones’),(5142,6,3,3,16,‘Delicias’),(5143,6,3,3,17,‘Garrote’),(5144,6,3,3,18,‘Guácimo’),(5145,6,3,3,19,‘Guaria’),(5146,6,3,3,20,‘Helechales’),(5147,6,3,3,21,‘Jabillo’),(5148,6,3,3,22,‘Jorón’),(5149,6,3,3,23,‘Juntas’),(5150,6,3,3,24,‘Lucha’),(5151,6,3,3,25,‘Maravilla’),(5152,6,3,3,26,‘Mesas’),(5153,6,3,3,27,‘Mirador’),(5154,6,3,3,28,‘Montelimar’),(5155,6,3,3,29,‘Mosca’),(5156,6,3,3,30,‘Palmira’),(5157,6,3,3,31,‘Paso
Real’),(5158,6,3,3,32,‘Peje’),(5159,6,3,3,33,‘Pita’),(5160,6,3,3,34,‘Platanillal’),(5161,6,3,3,35,‘Quijada’),(5162,6,3,3,36,‘Río
Coto’),(5163,6,3,3,37,‘San Antonio’),(5164,6,3,3,38,‘San
Carlos’),(5165,6,3,3,39,‘San Rafael de Cabagra’),(5166,6,3,3,40,‘Santa
Cecilia’),(5167,6,3,3,41,‘Singri’),(5168,6,3,3,42,‘Tablas’),(5169,6,3,3,43,‘Tamarindo’),(5170,6,3,3,44,‘Térraba’),(5171,6,3,3,45,‘Tres
Colinas’),(5172,6,3,3,46,‘Tierras
Negras’),(5173,6,3,3,47,‘Volcancito’),(5174,6,3,3,48,‘Vueltas.’),(5175,6,3,4,1,‘Alto
del
Mojón’),(5176,6,3,4,2,‘Bellavista’),(5177,6,3,4,3,‘Cajón’),(5178,6,3,4,4,‘Curré’),(5179,6,3,4,5,‘Chamba’),(5180,6,3,4,6,‘Changuenita’),(5181,6,3,4,7,‘Doboncragua’),(5182,6,3,4,8,‘Iguana’),(5183,6,3,4,9,‘Kuibín’),(5184,6,3,4,10,‘Lagarto’),(5185,6,3,4,11,‘Mano
de Tigre’),(5186,6,3,4,12,‘Miravalles’),(5187,6,3,4,13,‘Ojo de Agua
(parte)’),(5188,6,3,4,14,‘Presa’),(5189,6,3,4,15,‘Puerto
Nuevo’),(5190,6,3,4,16,‘Sabanas (Barranco) (parte)’),(5191,6,3,4,17,‘San
Joaquín’),(5192,6,3,4,18,‘Santa
Cruz’),(5193,6,3,4,19,‘Tigre’),(5194,6,3,4,20,‘Tres
Ríos.’),(5195,6,3,5,1,‘Alto Pilas’),(5196,6,3,5,2,‘Bajo
Pilas’),(5197,6,3,5,3,‘Bijagual’),(5198,6,3,5,4,‘Ceibón’),(5199,6,3,5,5,‘Concepción
(La
Danta)’),(5200,6,3,5,6,‘Dibujada’),(5201,6,3,5,7,‘Fortuna’),(5202,6,3,5,8,‘La
Gloria (Los Mangos)’),(5203,6,3,5,9,‘Laguna’),(5204,6,3,5,10,‘Ojo de
Agua’),(5205,6,3,5,11,‘Paso La Tinta’),(5206,6,3,5,12,‘Pueblo
Nuevo’),(5207,6,3,5,13,‘Sabanas (Barranco
parte)’),(5208,6,3,5,14,‘Silencio’),(5209,6,3,5,15,‘Tumbas.’),(5210,6,3,6,1,‘Aguas
Frescas’),(5211,6,3,6,2,‘Alto
Esmeralda’),(5212,6,3,6,3,‘Ángeles’),(5213,6,3,6,4,‘Bajo
Dioses’),(5214,6,3,6,5,‘Bajo
Maíz’),(5215,6,3,6,6,‘Bolsa’),(5216,6,3,6,7,‘Cedral
(Boquete)’),(5217,6,3,6,8,‘Escuadra’),(5218,6,3,6,9,‘Filadelfia
(Aguabuena)’),(5219,6,3,6,10,‘Guagaral’),(5220,6,3,6,11,‘Jabillo’),(5221,6,3,6,12,‘Jalisco’),(5222,6,3,6,13,‘Laguna’),(5223,6,3,6,14,‘Lajas’),(5224,6,3,6,15,‘Maíz
de
Boruca’),(5225,6,3,6,16,‘Mallal’),(5226,6,3,6,17,‘Nubes’),(5227,6,3,6,18,‘Ojo
de Agua (parte)’),(5228,6,3,6,19,‘San
Luis’),(5229,6,3,6,20,‘Virgen.’),(5230,6,3,7,1,‘Alto
Cacao’),(5231,6,3,7,2,‘Bajo
Mamey’),(5232,6,3,7,3,‘Bonga’),(5233,6,3,7,4,‘Cacique’),(5234,6,3,7,5,‘Cantú’),(5235,6,3,7,6,‘Cruces’),(5236,6,3,7,7,‘Limón’),(5237,6,3,7,8,‘Paraíso’),(5238,6,3,7,9,‘Pataste’),(5239,6,3,7,10,‘Pilón’),(5240,6,3,7,11,‘Quebrada
Bonita’),(5241,6,3,7,12,‘San Luis’),(5242,6,3,7,13,‘Santa
Lucía’),(5243,6,3,7,14,‘Santa María’),(5244,6,3,7,15,‘Tres
Ríos’),(5245,6,3,7,16,‘Vegas de Chánguena’),(5246,6,3,7,17,‘Vuelta
Campana’),(5247,6,3,7,18,‘Zapotal.’),(5248,6,3,8,1,‘Almácigo’),(5249,6,3,8,2,‘Altamira’),(5250,6,3,8,3,‘Alto
Sábalo’),(5251,6,3,8,4,‘Bajo Sábalo’),(5252,6,3,8,5,‘Bajos de
Coto’),(5253,6,3,8,6,‘Biolley’),(5254,6,3,8,7,‘Carmen’),(5255,6,3,8,8,‘Hamacas’),(5256,6,3,8,9,‘Guayacán’),(5257,6,3,8,10,‘Manzano’),(5258,6,3,8,11,‘Naranjos’),(5259,6,3,8,12,‘Puna.’),(5260,6,3,9,1,‘Achiote’),(5261,6,3,9,2,‘Alto
Achiote’),(5262,6,3,9,3,‘Cañas’),(5263,6,3,9,4,‘Guadalajara’),(5264,6,3,9,5,‘Llano
Bonito’),(5265,6,3,9,6,‘Oasis’),(5266,6,3,9,7,‘San
Rafael’),(5267,6,3,9,8,‘Santa Cecilia’),(5268,6,3,9,9,‘Santa
María’),(5269,6,3,9,10,‘Santa
Rosa’),(5270,6,3,9,11,‘Socorro.’),(5271,6,4,1,1,‘Alto
Pavones’),(5272,6,4,1,2,‘Bajo
Zamora’),(5273,6,4,1,3,‘Barbudal’),(5274,6,4,1,4,‘Bellavista’),(5275,6,4,1,5,‘Brillante
(parte)’),(5276,6,4,1,6,‘Cabuyal’),(5277,6,4,1,7,‘Delicias’),(5278,6,4,1,8,‘Fraijanes’),(5279,6,4,1,9,‘Lagunilla’),(5280,6,4,1,10,‘Río
Seco’),(5281,6,4,1,11,‘Tajo
Alto’),(5282,6,4,1,12,‘Trinidad’),(5283,6,4,1,13,‘Zagala
Vieja’),(5284,6,4,1,14,‘Zamora’),(5285,6,4,1,15,‘Zapotal
(parte).’),(5286,6,4,2,1,‘Bajo Caliente
(parte)’),(5287,6,4,2,2,‘Cedral’),(5288,6,4,2,3,‘Laguna’),(5289,6,4,2,4,‘Micas’),(5290,6,4,2,5,‘Palmital’),(5291,6,4,2,6,‘San
Buenaventura’),(5292,6,4,2,7,‘Velásquez’),(5293,6,4,2,8,‘Ventanas’),(5294,6,4,2,9,‘Zagala
Nueva.’),(5295,6,4,3,1,‘Aguabuena’),(5296,6,4,3,2,‘Ciruelas’),(5297,6,4,3,3,‘Cuatro
Cruces’),(5298,6,4,3,4,‘Isla’),(5299,6,4,3,5,‘Santa
Rosa’),(5300,6,4,3,6,‘Tiocinto.’),(5301,6,5,1,1,‘Canadá’),(5302,6,5,1,2,‘Cementerio’),(5303,6,5,1,3,‘Cinco
Esquinas’),(5304,6,5,1,4,‘Precario’),(5305,6,5,1,5,‘Pueblo
Nuevo’),(5306,6,5,1,6,‘Renacimiento’),(5307,6,5,1,7,‘Yuca.’),(5308,6,5,1,8,‘Balsar’),(5309,6,5,1,9,‘Bocabrava’),(5310,6,5,1,10,‘Bocachica’),(5311,6,5,1,11,‘Cerrón’),(5312,6,5,1,12,‘Coronado’),(5313,6,5,1,13,‘Chontales’),(5314,6,5,1,14,‘Delicias’),(5315,6,5,1,15,‘Embarcadero’),(5316,6,5,1,16,‘Fuente’),(5317,6,5,1,17,‘Isla
Sorpresa’),(5318,6,5,1,18,‘Lindavista’),(5319,6,5,1,19,‘Lourdes’),(5320,6,5,1,20,‘Ojochal’),(5321,6,5,1,21,‘Ojo
de
Agua’),(5322,6,5,1,22,‘Parcelas’),(5323,6,5,1,23,‘Pozo’),(5324,6,5,1,24,‘Punta
Mala’),(5325,6,5,1,25,‘Punta Mala Arriba’),(5326,6,5,1,26,‘San
Buenaventura’),(5327,6,5,1,27,‘San Juan’),(5328,6,5,1,28,‘San
Marcos’),(5329,6,5,1,29,‘Tagual’),(5330,6,5,1,30,‘Tortuga
Abajo’),(5331,6,5,1,31,‘Tres Ríos’),(5332,6,5,1,32,‘Vista de
Térraba.’),(5333,6,5,2,1,‘Betania’),(5334,6,5,2,2,‘Once de
Abril’),(5335,6,5,2,3,‘Palmar
Sur.’),(5336,6,5,2,4,‘Alemania’),(5337,6,5,2,5,‘Alto
Ángeles’),(5338,6,5,2,6,‘Alto Encanto’),(5339,6,5,2,7,‘Alto
Montura’),(5340,6,5,2,8,‘Bellavista’),(5341,6,5,2,9,‘Calavera’),(5342,6,5,2,10,‘Cansot’),(5343,6,5,2,11,‘Cañablancal
(Este)’),(5344,6,5,2,12,‘Cañablancal (Oeste) Coobó
(Progreso)’),(5345,6,5,2,13,‘Coquito’),(5346,6,5,2,14,‘Gorrión’),(5347,6,5,2,15,‘Jalaca
(parte)’),(5348,6,5,2,16,‘Olla
Cero’),(5349,6,5,2,17,‘Palma’),(5350,6,5,2,18,‘Paraíso’),(5351,6,5,2,19,‘Primero
de Marzo’),(5352,6,5,2,20,‘Puerta del Sol’),(5353,6,5,2,21,‘San
Cristóbal’),(5354,6,5,2,22,‘San Francisco (Tinoco)’),(5355,6,5,2,23,‘San
Gabriel’),(5356,6,5,2,24,‘San Isidro’),(5357,6,5,2,25,‘San
Rafael’),(5358,6,5,2,26,‘Santa
Elena’),(5359,6,5,2,27,‘Silencio’),(5360,6,5,2,28,‘Trocha’),(5361,6,5,2,29,‘Vergel’),(5362,6,5,2,30,‘Victoria’),(5363,6,5,2,31,‘Zapote.’),(5364,6,5,3,1,‘Ajuntaderas’),(5365,6,5,3,2,‘Alto
Los Mogos’),(5366,6,5,3,3,‘Alto San Juan’),(5367,6,5,3,4,‘Bahía
Chal’),(5368,6,5,3,5,‘Bajos
Matías’),(5369,6,5,3,6,‘Barco’),(5370,6,5,3,7,‘Bejuco’),(5371,6,5,3,8,‘Boca
Chocuaco’),(5372,6,5,3,9,‘Gallega’),(5373,6,5,3,10,‘Camíbar’),(5374,6,5,3,11,‘Campo
de
Aguabuena’),(5375,6,5,3,12,‘Cantarrana’),(5376,6,5,3,13,‘Charcos’),(5377,6,5,3,14,‘Chocuaco’),(5378,6,5,3,15,‘Garrobo’),(5379,6,5,3,16,‘Guabos’),(5380,6,5,3,17,‘Isidora’),(5381,6,5,3,18,‘Islotes’),(5382,6,5,3,19,‘Jalaca
(parte)’),(5383,6,5,3,20,‘Julia’),(5384,6,5,3,21,‘Miramar’),(5385,6,5,3,22,‘Mogos’),(5386,6,5,3,23,‘Monterrey’),(5387,6,5,3,24,‘Playa
Palma’),(5388,6,5,3,25,‘Playitas’),(5389,6,5,3,26,‘Potrero’),(5390,6,5,3,27,‘Puerto
Escondido’),(5391,6,5,3,28,‘Rincón’),(5392,6,5,3,29,‘Sábalo’),(5393,6,5,3,30,‘San
Gerardo’),(5394,6,5,3,31,‘San
Juan’),(5395,6,5,3,32,‘Taboga’),(5396,6,5,3,33,‘Taboguita’),(5397,6,5,3,34,‘Tigre’),(5398,6,5,3,35,‘Varillal.’),(5399,6,5,4,1,‘Bahía’),(5400,6,5,4,2,‘Ballena’),(5401,6,5,4,3,‘Brisas’),(5402,6,5,4,4,‘Cambutal’),(5403,6,5,4,5,‘Dominical’),(5404,6,5,4,6,‘Dominicalito’),(5405,6,5,4,7,‘Escaleras’),(5406,6,5,4,8,‘Piedra
Achiote’),(5407,6,5,4,9,‘Piñuela’),(5408,6,5,4,10,‘Playa
Hermosa’),(5409,6,5,4,11,‘Poza Azul’),(5410,6,5,4,12,‘Puerto
Nuevo’),(5411,6,5,4,13,‘Quebrada Grande’),(5412,6,5,4,14,‘Rocas
Amancio’),(5413,6,5,4,15,‘San Josecito’),(5414,6,5,4,16,‘San
Martín’),(5415,6,5,4,17,‘Tortuga
Arriba’),(5416,6,5,4,18,‘Ventanas.’),(5417,6,5,5,1,‘Ángeles’),(5418,6,5,5,2,‘Bellavista’),(5419,6,5,5,3,‘Calera’),(5420,6,5,5,4,‘Cerro
Oscuro’),(5421,6,5,5,5,‘Chacarita’),(5422,6,5,5,6,‘Fila’),(5423,6,5,5,7,‘Finca
Alajuela’),(5424,6,5,5,8,‘Finca Guanacaste’),(5425,6,5,5,9,‘Finca
Puntarenas’),(5426,6,5,5,10,‘Florida’),(5427,6,5,5,11,‘Guaria’),(5428,6,5,5,12,‘Kilómetro
40’),(5429,6,5,5,13,‘Navidad’),(5430,6,5,5,14,‘Nubes’),(5431,6,5,5,15,‘Porvenir’),(5432,6,5,5,16,‘Rincón
Caliente’),(5433,6,5,5,17,‘Salamá’),(5434,6,5,5,18,‘San
Martín’),(5435,6,5,5,19,‘Santa Cecilia’),(5436,6,5,5,20,‘Santa
Rosa’),(5437,6,5,5,21,‘Sinaí’),(5438,6,5,5,22,‘Venecia’),(5439,6,5,5,23,‘Villa
Bonita’),(5440,6,5,5,24,‘Villa
Colón.’),(5441,6,5,6,1,‘Ángeles’),(5442,6,5,6,2,‘Banegas’),(5443,6,5,6,3,‘Boca
Ganado’),(5444,6,5,6,4,‘Campanario’),(5445,6,5,6,5,‘Caletas’),(5446,6,5,6,6,‘Guerra’),(5447,6,5,6,7,‘Planes’),(5448,6,5,6,8,‘Progreso’),(5449,6,5,6,9,‘Quebrada
Ganado’),(5450,6,5,6,10,‘Rancho
Quemado’),(5451,6,5,6,11,‘Riyito’),(5452,6,5,6,12,‘San Josecito
(Rincón)’),(5453,6,5,6,13,‘San Pedrillo.’),(5454,6,6,1,1,‘Boca
Vieja’),(5455,6,6,1,2,‘Cocal’),(5456,6,6,1,3,‘Colinas del
Este’),(5457,6,6,1,4,‘Inmaculada’),(5458,6,6,1,5,‘Junta
Naranjo’),(5459,6,6,1,6,‘La Zona’),(5460,6,6,1,7,‘Rancho
Grande.’),(5461,6,6,1,8,‘Anita’),(5462,6,6,1,9,‘Bartolo’),(5463,6,6,1,10,‘Boca
Naranjo’),(5464,6,6,1,11,‘Cañas’),(5465,6,6,1,12,‘Cañitas’),(5466,6,6,1,13,‘Cerritos’),(5467,6,6,1,14,‘Cerros’),(5468,6,6,1,15,‘Damas’),(5469,6,6,1,16,‘Delicias’),(5470,6,6,1,17,‘Espadilla’),(5471,6,6,1,18,‘Estero
Damas’),(5472,6,6,1,19,‘Estero
Garita’),(5473,6,6,1,20,‘Gallega’),(5474,6,6,1,21,‘Llamarón’),(5475,6,6,1,22,‘Llorona’),(5476,6,6,1,23,‘Managua’),(5477,6,6,1,24,‘Manuel
Antonio’),(5478,6,6,1,25,‘Marítima’),(5479,6,6,1,26,‘Mona’),(5480,6,6,1,27,‘Papaturro’),(5481,6,6,1,28,‘Paquita’),(5482,6,6,1,29,‘Pastora’),(5483,6,6,1,30,‘Quebrada
Azul’),(5484,6,6,1,31,‘Rey’),(5485,6,6,1,32,‘Ríos’),(5486,6,6,1,33,‘Roncador’),(5487,6,6,1,34,‘San
Rafael.’),(5488,6,6,2,1,‘Dos
Bocas’),(5489,6,6,2,2,‘Guabas’),(5490,6,6,2,3,‘Guápil’),(5491,6,6,2,4,‘Hatillo
Nuevo’),(5492,6,6,2,5,‘Hatillo
Viejo’),(5493,6,6,2,6,‘Laguna’),(5494,6,6,2,7,‘Nubes’),(5495,6,6,2,8,‘Palma
Quemada’),(5496,6,6,2,9,‘Pasito’),(5497,6,6,2,10,‘Paso’),(5498,6,6,2,11,‘Paso
Guanacaste’),(5499,6,6,2,12,‘Platanillo’),(5500,6,6,2,13,‘Playa
Matapalo’),(5501,6,6,2,14,‘Portalón’),(5502,6,6,2,15,‘Punto de
Mira’),(5503,6,6,2,16,‘Salitral’),(5504,6,6,2,17,‘Salsipuedes’),(5505,6,6,2,18,‘San
Andrés’),(5506,6,6,2,19,‘Santo
Domingo’),(5507,6,6,2,20,‘Silencio’),(5508,6,6,2,21,‘Tierras
Morenas’),(5509,6,6,2,22,‘Tres Piedras
(parte).’),(5510,6,6,3,1,‘Bijagual’),(5511,6,6,3,2,‘Buenos
Aires’),(5512,6,6,3,3,‘Capital’),(5513,6,6,3,4,‘Concepción’),(5514,6,6,3,5,‘Cotos’),(5515,6,6,3,6,‘Londres’),(5516,6,6,3,7,‘Negro’),(5517,6,6,3,8,‘Pascua’),(5518,6,6,3,9,‘Paso
Indios’),(5519,6,6,3,10,‘Paso
Real’),(5520,6,6,3,11,‘Sábalo’),(5521,6,6,3,12,‘Santa
Juana’),(5522,6,6,3,13,‘Tocorí’),(5523,6,6,3,14,‘Villanueva.’),(5524,6,7,1,1,‘Alamedas’),(5525,6,7,1,2,‘Bellavista’),(5526,6,7,1,3,‘Bolsa’),(5527,6,7,1,4,‘Disco’),(5528,6,7,1,5,‘Kilómetro
1’),(5529,6,7,1,6,‘Kilómetro 2’),(5530,6,7,1,7,‘Kilómetro
3’),(5531,6,7,1,8,‘Laguna’),(5532,6,7,1,9,‘Llano
Bonito’),(5533,6,7,1,10,‘Minerva’),(5534,6,7,1,11,‘Naranjal’),(5535,6,7,1,12,‘Oasis
de Esperanza’),(5536,6,7,1,13,‘Parroquial’),(5537,6,7,1,14,‘Pueblo
Civil’),(5538,6,7,1,15,‘Rotonda’),(5539,6,7,1,16,‘San
Andrés’),(5540,6,7,1,17,‘San Martín’),(5541,6,7,1,18,‘Zona
Gris.’),(5542,6,7,1,19,‘Aguada’),(5543,6,7,1,20,‘Ánimas’),(5544,6,7,1,21,‘Atrocho’),(5545,6,7,1,22,‘Bajo
Chontales’),(5546,6,7,1,23,‘Bajo de Coto’),(5547,6,7,1,24,‘Bajo
Grapa’),(5548,6,7,1,25,‘Bajo Mansito’),(5549,6,7,1,26,‘Bajo
Sucio’),(5550,6,7,1,27,‘Bajos de Cañablanca’),(5551,6,7,1,28,‘Cuarenta y
Cinco’),(5552,6,7,1,29,‘Dos Ríos’),(5553,6,7,1,30,‘Esperanza de
Coto’),(5554,6,7,1,31,‘Gallardo’),(5555,6,7,1,32,‘Huacas’),(5556,6,7,1,33,‘Jorge
Brenes Durán’),(5557,6,7,1,34,‘Kilómetro 5’),(5558,6,7,1,35,‘Kilómetro
7’),(5559,6,7,1,36,‘Kilómetro 9’),(5560,6,7,1,37,‘Kilómetro
16’),(5561,6,7,1,38,‘Kilómetro 20’),(5562,6,7,1,39,‘Kilómetro
24’),(5563,6,7,1,40,‘Manuel Tucker
Martínez’),(5564,6,7,1,41,‘Mona’),(5565,6,7,1,42,‘Nazaret’),(5566,6,7,1,43,‘Paso
Higuerón’),(5567,6,7,1,44,‘Playa Cacao’),(5568,6,7,1,45,‘Puerto
Escondido’),(5569,6,7,1,46,‘Puntarenitas’),(5570,6,7,1,47,‘Purruja’),(5571,6,7,1,48,‘Rancho
Relámpago’),(5572,6,7,1,49,‘Riyito’),(5573,6,7,1,50,‘Saladero’),(5574,6,7,1,51,‘Saladerito’),(5575,6,7,1,52,‘San
Francisco’),(5576,6,7,1,53,‘San
Josecito’),(5577,6,7,1,54,‘Torres’),(5578,6,7,1,55,‘Trenzas’),(5579,6,7,1,56,‘Unión
de Coto’),(5580,6,7,1,57,‘Ureña’),(5581,6,7,1,58,‘Valle
Bonito’),(5582,6,7,1,59,‘Viquilla Dos.’),(5583,6,7,2,1,‘Pueblo
Nuevo.’),(5584,6,7,2,2,‘Aguabuena’),(5585,6,7,2,3,‘Agujas’),(5586,6,7,2,4,‘Miramar
(Altos
Corozal)’),(5587,6,7,2,5,‘Amapola’),(5588,6,7,2,6,‘Balsa’),(5589,6,7,2,7,‘Bambú’),(5590,6,7,2,8,‘Barrigones’),(5591,6,7,2,9,‘Barrio
Bonito’),(5592,6,7,2,10,‘Boca
Gallardo’),(5593,6,7,2,11,‘Cañaza’),(5594,6,7,2,12,‘Carate’),(5595,6,7,2,13,‘Carbonera’),(5596,6,7,2,14,‘Cerro
de Oro’),(5597,6,7,2,15,‘Dos
Brazos’),(5598,6,7,2,16,‘Guadalupe’),(5599,6,7,2,17,‘Independencia’),(5600,6,7,2,18,‘Lajitas’),(5601,6,7,2,19,‘Ñeque’),(5602,6,7,2,20,‘Palma’),(5603,6,7,2,21,‘Paloseco’),(5604,6,7,2,22,‘Playa
Blanca’),(5605,6,7,2,23,‘Playa Tigre’),(5606,6,7,2,24,‘Puerto
Escondido’),(5607,6,7,2,25,‘Quebrada Latarde’),(5608,6,7,2,26,‘Río
Nuevo’),(5609,6,7,2,27,‘Río Oro’),(5610,6,7,2,28,‘Río Piro
(Coyunda)’),(5611,6,7,2,29,‘Sándalo’),(5612,6,7,2,30,‘San
Miguel’),(5613,6,7,2,31,‘Sombrero’),(5614,6,7,2,32,‘Terrones’),(5615,6,7,2,33,‘Tigre.’),(5616,6,7,3,1,‘Santiago.’),(5617,6,7,3,2,‘Bajo
Bonita’),(5618,6,7,3,3,‘Bajo
Cedros’),(5619,6,7,3,4,‘Buenavista’),(5620,6,7,3,5,‘Cerro
Café’),(5621,6,7,3,6,‘Chiqueros’),(5622,6,7,3,7,‘Delicias’),(5623,6,7,3,8,‘El
Alto’),(5624,6,7,3,9,‘Esperanza’),(5625,6,7,3,10,‘Gamba’),(5626,6,7,3,11,‘Kilómetro
29’),(5627,6,7,3,12,‘Kilómetro 33’),(5628,6,7,3,13,‘La
Julieta’),(5629,6,7,3,14,‘Santiago de Caracol’),(5630,6,7,3,15,‘Tigre
(Caracol Norte)’),(5631,6,7,3,16,‘Valle Cedros’),(5632,6,7,3,17,‘Vegas
de Río Claro’),(5633,6,7,3,18,‘Villa Briceño’),(5634,6,7,3,19,‘Viquilla
Uno.’),(5635,6,7,4,1,‘Altos de
Conte’),(5636,6,7,4,2,‘Banco’),(5637,6,7,4,3,‘Burica’),(5638,6,7,4,4,‘Clarita’),(5639,6,7,4,5,‘Cocal
Amarillo’),(5640,6,7,4,6,‘Cuervito’),(5641,6,7,4,7,‘Escuadra’),(5642,6,7,4,8,‘Esperanza
de Sábalos’),(5643,6,7,4,9,‘Estero
Colorado’),(5644,6,7,4,10,‘Estrella’),(5645,6,7,4,11,‘Flor de
Coto’),(5646,6,7,4,12,‘Fortuna de
Coto’),(5647,6,7,4,13,‘Guaymí’),(5648,6,7,4,14,‘Higo’),(5649,6,7,4,15,‘Jardín’),(5650,6,7,4,16,‘La
Virgen’),(5651,6,7,4,17,‘Lindamar’),(5652,6,7,4,18,‘Manzanillo’),(5653,6,7,4,19,‘Pavones’),(5654,6,7,4,20,‘Peñas’),(5655,6,7,4,21,‘Peñita’),(5656,6,7,4,22,‘Puerto
Pilón’),(5657,6,7,4,23,‘Puesto La Playa’),(5658,6,7,4,24,‘Punta
Banco’),(5659,6,7,4,25,‘Quebrada
Honda’),(5660,6,7,4,26,‘Riviera’),(5661,6,7,4,27,‘Sábalos’),(5662,6,7,4,28,‘Tigrito’),(5663,6,7,4,29,‘Unión
del
Sur’),(5664,6,7,4,30,‘Vanegas’),(5665,6,7,4,31,‘Yerba’),(5666,6,7,4,32,‘Zancudo.’),(5667,6,8,1,1,‘Canadá’),(5668,6,8,1,2,‘María
Auxiliadora’),(5669,6,8,1,3,‘Tres Ríos.’),(5670,6,8,1,4,‘Aguas
Claras’),(5671,6,8,1,5,‘Bajo Reyes’),(5672,6,8,1,6,‘Bajo
Venado’),(5673,6,8,1,7,‘Barrantes’),(5674,6,8,1,8,‘Ceibo’),(5675,6,8,1,9,‘Cruces’),(5676,6,8,1,10,‘Cuenca
de Oro’),(5677,6,8,1,11,‘Danto’),(5678,6,8,1,12,‘Fila
Guinea’),(5679,6,8,1,13,‘Isla’),(5680,6,8,1,14,‘Lindavista’),(5681,6,8,1,15,‘Lourdes’),(5682,6,8,1,16,‘Maravilla’),(5683,6,8,1,17,‘Piedra
Pintada’),(5684,6,8,1,18,‘San Joaquín’),(5685,6,8,1,19,‘Santa
Clara’),(5686,6,8,1,20,‘Torre
Alta.’),(5687,6,8,2,1,‘Ángeles’),(5688,6,8,2,2,‘Brasilia’),(5689,6,8,2,3,‘Casablanca’),(5690,6,8,2,4,‘Chanchera’),(5691,6,8,2,5,‘El
Gallo’),(5692,6,8,2,6,‘Juntas’),(5693,6,8,2,7,‘La
Esmeralda’),(5694,6,8,2,8,‘Lucha’),(5695,6,8,2,9,‘Mellizas’),(5696,6,8,2,10,‘Miraflores’),(5697,6,8,2,11,‘Piedra
de Candela’),(5698,6,8,2,12,‘Plantel’),(5699,6,8,2,13,‘Porto
Llano’),(5700,6,8,2,14,‘Primavera’),(5701,6,8,2,15,‘Progreso’),(5702,6,8,2,16,‘Providencia’),(5703,6,8,2,17,‘Pueblo
Nuevo’),(5704,6,8,2,18,‘Río Negro’),(5705,6,8,2,19,‘Río
Sereno’),(5706,6,8,2,20,‘San Antonio’),(5707,6,8,2,21,‘San
Bosco’),(5708,6,8,2,22,‘San Francisco’),(5709,6,8,2,23,‘San
Luis’),(5710,6,8,2,24,‘San Marcos’),(5711,6,8,2,25,‘San
Miguel’),(5712,6,8,2,26,‘San Rafael’),(5713,6,8,2,27,‘San
Ramón’),(5714,6,8,2,28,‘Santa Rosa’),(5715,6,8,2,29,‘Santa
Teresa’),(5716,6,8,2,30,‘Tigra’),(5717,6,8,2,31,‘Trinidad’),(5718,6,8,2,32,‘Unión’),(5719,6,8,2,33,‘Valle
Hermoso.’),(5720,6,8,3,1,‘Bello Oriente’),(5721,6,8,3,2,‘Campo
Tres’),(5722,6,8,3,3,‘Cañas
Gordas’),(5723,6,8,3,4,‘Concepción’),(5724,6,8,3,5,‘Copabuena’),(5725,6,8,3,6,‘Copal’),(5726,6,8,3,7,‘Fila
Zapote’),(5727,6,8,3,8,‘Metaponto’),(5728,6,8,3,9,‘Pilares’),(5729,6,8,3,10,‘Pueblo
Nuevo’),(5730,6,8,3,11,‘Quebrada Bonita’),(5731,6,8,3,12,‘Río
Salto’),(5732,6,8,3,13,‘San Francisco’),(5733,6,8,3,14,‘San
Gabriel’),(5734,6,8,3,15,‘San Pedro’),(5735,6,8,3,16,‘Santa
Cecilia’),(5736,6,8,3,17,‘Santa Marta’),(5737,6,8,3,18,‘Santo
Domingo’),(5738,6,8,3,19,‘Valle
Azul.’),(5739,6,8,4,1,‘Aguacate’),(5740,6,8,4,2,‘Alto
Limoncito’),(5741,6,8,4,3,‘Ángeles’),(5742,6,8,4,4,‘Bonanza’),(5743,6,8,4,5,‘Brusmalis’),(5744,6,8,4,6,‘Chiva’),(5745,6,8,4,7,‘Desamparados’),(5746,6,8,4,8,‘Esperanza’),(5747,6,8,4,9,‘Fila’),(5748,6,8,4,10,‘Manchuria’),(5749,6,8,4,11,‘Sabanilla’),(5750,6,8,4,12,‘San
Gerardo’),(5751,6,8,4,13,‘San Juan’),(5752,6,8,4,14,‘San
Miguel’),(5753,6,8,4,15,‘San Rafael’),(5754,6,8,4,16,‘Santa
Marta’),(5755,6,8,4,17,‘Santa
Rita’),(5756,6,8,4,18,‘Unión’),(5757,6,8,4,19,‘Valle’),(5758,6,8,4,20,‘Villapalacios’),(5759,6,8,4,21,‘Zumbona.’),(5760,6,8,5,1,‘Aguacaliente’),(5761,6,8,5,2,‘Camaquiri’),(5762,6,8,5,3,‘Cocorí’),(5763,6,8,5,4,‘Coto
Brus (parte)’),(5764,6,8,5,5,‘Fila Méndez’),(5765,6,8,5,6,‘Fila
Naranjo’),(5766,6,8,5,7,‘Fila
Tigre’),(5767,6,8,5,8,‘Marías’),(5768,6,8,5,9,‘Monterrey’),(5769,6,8,5,10,‘Palmira’),(5770,6,8,5,11,‘Santa
Fe.’),(5771,6,8,6,1,‘Alpha’),(5772,6,8,6,2,‘Alturas de
Cotón’),(5773,6,8,6,3,‘Brisas’),(5774,6,8,6,4,‘Fila
Pinar’),(5775,6,8,6,5,‘Fila San Rafael’),(5776,6,8,6,6,‘Flor del
Roble’),(5777,6,8,6,7,‘Guinea Arriba’),(5778,6,8,6,8,‘La
Administración’),(5779,6,8,6,9,‘Libertad’),(5780,6,8,6,10,‘Poma’),(5781,6,8,6,11,‘Río
Marzo’),(5782,6,8,6,12,‘Roble’),(5783,6,8,6,13,‘Roble
Arriba’),(5784,6,8,6,14,‘Siete
Colinas.’),(5785,6,9,1,1,‘Julieta’),(5786,6,9,1,2,‘Pueblo
Nuevo.’),(5787,6,9,1,3,‘Alto
Camacho’),(5788,6,9,1,4,‘Ángeles’),(5789,6,9,1,5,‘Bajos
Jicote’),(5790,6,9,1,6,‘Bambú’),(5791,6,9,1,7,‘Bandera’),(5792,6,9,1,8,‘Barbudal’),(5793,6,9,1,9,‘Bejuco’),(5794,6,9,1,10,‘Boca
del Parrita’),(5795,6,9,1,11,‘Carmen
(parte)’),(5796,6,9,1,12,‘Chires’),(5797,6,9,1,13,‘Chires
Arriba’),(5798,6,9,1,14,‘Chirraca Abajo’),(5799,6,9,1,15,‘Chirraca
Arriba’),(5800,6,9,1,16,‘Esterillos Centro’),(5801,6,9,1,17,‘Esterillos
Este’),(5802,6,9,1,18,‘Esterillos Oeste’),(5803,6,9,1,19,‘Fila
Surubres’),(5804,6,9,1,20,‘Guapinol’),(5805,6,9,1,21,‘Higuito’),(5806,6,9,1,22,‘I
Griega’),(5807,6,9,1,23,‘Isla Damas’),(5808,6,9,1,24,‘Isla Palo
Seco’),(5809,6,9,1,25,‘Jicote’),(5810,6,9,1,26,‘Loma’),(5811,6,9,1,27,‘Mesas’),(5812,6,9,1,28,‘Palmas’),(5813,6,9,1,29,‘Palo
Seco’),(5814,6,9,1,30,‘Pirrís’),(5815,6,9,1,31,‘Playa
Palma’),(5816,6,9,1,32,‘Playón’),(5817,6,9,1,33,‘Playón
Sur’),(5818,6,9,1,34,‘Pirrís (Las
Parcelas)’),(5819,6,9,1,35,‘Pocares’),(5820,6,9,1,36,‘Pocarito’),(5821,6,9,1,37,‘Porvenir’),(5822,6,9,1,38,‘Punta
Judas’),(5823,6,9,1,39,‘Rincón Morales’),(5824,6,9,1,40,‘Río Negro
(parte)’),(5825,6,9,1,41,‘Río Seco’),(5826,6,9,1,42,‘San
Antonio’),(5827,6,9,1,43,‘San Bosco’),(5828,6,9,1,44,‘San
Gerardo’),(5829,6,9,1,45,‘San Isidro’),(5830,6,9,1,46,‘San
Juan’),(5831,6,9,1,47,‘San Julián’),(5832,6,9,1,48,‘San Rafael
Norte’),(5833,6,9,1,49,‘Sardinal’),(5834,6,9,1,50,‘Sardinal
Sur’),(5835,6,9,1,51,‘Surubres’),(5836,6,9,1,52,‘Teca’),(5837,6,9,1,53,‘Tigre’),(5838,6,9,1,54,‘Turbio’),(5839,6,9,1,55,‘Valle
Vasconia’),(5840,6,9,1,56,‘Vueltas.’),(5841,6,10,1,1,‘Bosque’),(5842,6,10,1,2,‘Caño
Seco’),(5843,6,10,1,3,‘Capri’),(5844,6,10,1,4,‘Carmen’),(5845,6,10,1,5,‘Corredor’),(5846,6,10,1,6,‘Progreso’),(5847,6,10,1,7,‘San
Juan’),(5848,6,10,1,8,‘Valle del
Sur.’),(5849,6,10,1,9,‘Abrojo’),(5850,6,10,1,10,‘Aguilares’),(5851,6,10,1,11,‘Alto
Limoncito’),(5852,6,10,1,12,‘Bajo
Indios’),(5853,6,10,1,13,‘Betel’),(5854,6,10,1,14,‘Cacoragua’),(5855,6,10,1,15,‘Campiña’),(5856,6,10,1,16,‘Campo
Dos’),(5857,6,10,1,17,‘Campo Dos y
Medio’),(5858,6,10,1,18,‘Cañada’),(5859,6,10,1,19,‘Caracol
Sur’),(5860,6,10,1,20,‘Castaños’),(5861,6,10,1,21,‘Coloradito’),(5862,6,10,1,22,‘Concordia’),(5863,6,10,1,23,‘Coto
42’),(5864,6,10,1,24,‘Coto 44’),(5865,6,10,1,25,‘Coto
45’),(5866,6,10,1,26,‘Coto 47’),(5867,6,10,1,27,‘Coto
49’),(5868,6,10,1,28,‘Coto 50-51’),(5869,6,10,1,29,‘Coto
52-53’),(5870,6,10,1,30,‘Cuesta Fila de Cal’),(5871,6,10,1,31,‘Estrella
del
Sur’),(5872,6,10,1,32,‘Florida’),(5873,6,10,1,33,‘Fortuna’),(5874,6,10,1,34,‘Kilómetro
10’),(5875,6,10,1,35,‘Miramar’),(5876,6,10,1,36,‘Montezuma’),(5877,6,10,1,37,‘Nubes’),(5878,6,10,1,38,‘Pangas’),(5879,6,10,1,39,‘Planes’),(5880,6,10,1,40,‘Pueblo
Nuevo’),(5881,6,10,1,41,‘Río Bonito’),(5882,6,10,1,42,‘Río Nuevo
(Norte)’),(5883,6,10,1,43,‘Río Nuevo (Sur)’),(5884,6,10,1,44,‘San
Antonio Abajo’),(5885,6,10,1,45,‘San Francisco’),(5886,6,10,1,46,‘San
Josecito’),(5887,6,10,1,47,‘San Rafael’),(5888,6,10,1,48,‘Santa
Cecilia’),(5889,6,10,1,49,‘Santa Marta (parte)’),(5890,6,10,1,50,‘Santa
Rita’),(5891,6,10,1,51,‘Tropezón’),(5892,6,10,1,52,‘Unión’),(5893,6,10,1,53,‘Vegas
de Abrojo’),(5894,6,10,1,54,‘Villa Roma.’),(5895,6,10,2,1,‘Canoas Abajo
(parte)’),(5896,6,10,2,2,‘Control’),(5897,6,10,2,3,‘Cuervito’),(5898,6,10,2,4,‘Chorro.’),(5899,6,10,3,1,‘Lotes
(San Jorge).’),(5900,6,10,3,2,‘Altos del Brujo’),(5901,6,10,3,3,‘Bajo
Brujo’),(5902,6,10,3,4,‘Bajo’),(5903,6,10,3,5,‘Barrionuevo’),(5904,6,10,3,6,‘Canoas
Abajo (parte)’),(5905,6,10,3,7,‘Canoas
Arriba’),(5906,6,10,3,8,‘Cañaza’),(5907,6,10,3,9,‘Cerro
Brujo’),(5908,6,10,3,10,‘Colorado’),(5909,6,10,3,11,‘Chiva’),(5910,6,10,3,12,‘Darizara’),(5911,6,10,3,13,‘Gloria’),(5912,6,10,3,14,‘Guay’),(5913,6,10,3,15,‘Guayabal’),(5914,6,10,3,16,‘Mariposa’),(5915,6,10,3,17,‘Níspero’),(5916,6,10,3,18,‘Palma’),(5917,6,10,3,19,‘Paso
Canoas’),(5918,6,10,3,20,‘San Antonio’),(5919,6,10,3,21,‘San
Isidro’),(5920,6,10,3,22,‘San Martín’),(5921,6,10,3,23,‘San
Miguel’),(5922,6,10,3,24,‘Santa Marta
(parte)’),(5923,6,10,3,25,‘Veguitas de
Colorado’),(5924,6,10,3,26,‘Veracruz’),(5925,6,10,3,27,‘Villas de
Darizara.’),(5926,6,10,4,1,‘Alto
Vaquita’),(5927,6,10,4,2,‘Bambito’),(5928,6,10,4,3,‘Bella
Luz’),(5929,6,10,4,4,‘Bijagual’),(5930,6,10,4,5,‘Caimito’),(5931,6,10,4,6,‘Cangrejo
Verde’),(5932,6,10,4,7,‘Caracol de la
Vaca’),(5933,6,10,4,8,‘Cariari’),(5934,6,10,4,9,‘Caucho’),(5935,6,10,4,10,‘Cenizo’),(5936,6,10,4,11,‘Colonia
Libertad’),(5937,6,10,4,12,‘Coyoche’),(5938,6,10,4,13,‘Jobo
Civil’),(5939,6,10,4,14,‘Kilómetro 22’),(5940,6,10,4,15,‘Kilómetro
25’),(5941,6,10,4,16,‘Kilómetro 27’),(5942,6,10,4,17,‘Kilómetro
29’),(5943,6,10,4,18,‘Mango’),(5944,6,10,4,19,‘Pueblo de
Dios’),(5945,6,10,4,20,‘Puerto González Víquez’),(5946,6,10,4,21,‘Río
Incendio’),(5947,6,10,4,22,‘Roble’),(5948,6,10,4,23,‘San
Juan’),(5949,6,10,4,24,‘Santa
Lucía’),(5950,6,10,4,25,‘Tamarindo’),(5951,6,10,4,26,‘Vaca (Santa
Rosa)’),(5952,6,10,4,27,‘Vereh’),(5953,6,10,4,28,‘Zaragoza.’),(5954,6,11,1,1,‘Agujitas’),(5955,6,11,1,2,‘Buenos
Aires’),(5956,6,11,1,3,‘Cañablancal’),(5957,6,11,1,4,‘Cerro
Fresco’),(5958,6,11,1,5,‘Herradura’),(5959,6,11,1,6,‘Mona’),(5960,6,11,1,7,‘Playa
Hermosa’),(5961,6,11,1,8,‘Playa
Herradura’),(5962,6,11,1,9,‘Pochotal’),(5963,6,11,1,10,‘Puerto
Escondido’),(5964,6,11,1,11,‘Quebrada Amarilla’),(5965,6,11,1,12,‘San
Antonio’),(5966,6,11,1,13,‘Turrubaritos’),(5967,6,11,1,14,‘Tusubres.’),(5968,6,11,2,1,‘Agujas’),(5969,6,11,2,2,‘Bajamar’),(5970,6,11,2,3,‘Bellavista’),(5971,6,11,2,4,‘Caletas’),(5972,6,11,2,5,‘Camaronal’),(5973,6,11,2,6,‘Camaronal
Arriba’),(5974,6,11,2,7,‘Capulín’),(5975,6,11,2,8,‘Carrizal de
Bajamar’),(5976,6,11,2,9,‘Guacalillo’),(5977,6,11,2,10,‘Mantas’),(5978,6,11,2,11,‘Nambí’),(5979,6,11,2,12,‘Peñón
de
Tivives’),(5980,6,11,2,13,‘Pigres’),(5981,6,11,2,14,‘Pita’),(5982,6,11,2,15,‘Playa
Azul’),(5983,6,11,2,16,‘Pógeres’),(5984,6,11,2,17,‘Puerto
Peje’),(5985,6,11,2,18,‘Punta
Leona’),(5986,6,11,2,19,‘Tárcoles’),(5987,6,11,2,20,‘Tarcolitos.’),(5988,7,1,1,1,‘Bellavista’),(5989,7,1,1,2,‘Bohío’),(5990,7,1,1,3,‘Bosque’),(5991,7,1,1,4,‘Buenos
Aires’),(5992,7,1,1,5,‘Cangrejos’),(5993,7,1,1,6,‘Cariari’),(5994,7,1,1,7,‘Cerro
Mocho’),(5995,7,1,1,8,‘Cielo
Amarillo’),(5996,7,1,1,9,‘Cieneguita’),(5997,7,1,1,10,‘Colina’),(5998,7,1,1,11,‘Corales’),(5999,7,1,1,12,‘Cruce’),(6000,7,1,1,13,‘Fortín’),(6001,7,1,1,14,‘Garrón’),(6002,7,1,1,15,‘Hospital’),(6003,7,1,1,16,‘Jamaica
Town’),(6004,7,1,1,17,‘Japdeva’),(6005,7,1,1,18,‘Laureles’),(6006,7,1,1,19,‘Limoncito’),(6007,7,1,1,20,‘Lirios’),(6008,7,1,1,21,‘Moín’),(6009,7,1,1,22,‘Piuta’),(6010,7,1,1,23,‘Portete’),(6011,7,1,1,24,‘Pueblo
Nuevo’),(6012,7,1,1,25,‘San Juan’),(6013,7,1,1,26,‘Santa
Eduvigis’),(6014,7,1,1,27,‘Trinidad’),(6015,7,1,1,28,‘Veracruz.’),(6016,7,1,1,29,‘Buenos
Aires’),(6017,7,1,1,30,‘Cocal’),(6018,7,1,1,31,‘Dos
Bocas’),(6019,7,1,1,32,‘Empalme Moín’),(6020,7,1,1,33,‘Milla
Nueve’),(6021,7,1,1,34,‘Santa Rosa’),(6022,7,1,1,35,‘Valle La
Aurora’),(6023,7,1,1,36,‘Villas del Mar Uno’),(6024,7,1,1,37,‘Villas del
Mar Dos’),(6025,7,1,1,38,‘Villa
Hermosa.’),(6026,7,1,2,1,‘Colonia’),(6027,7,1,2,2,‘Finca
Ocho’),(6028,7,1,2,3,‘Guaria’),(6029,7,1,2,4,‘Loras’),(6030,7,1,2,5,‘Pandora
Oeste’),(6031,7,1,2,6,‘Río
Ley.’),(6032,7,1,2,7,‘Alsacia’),(6033,7,1,2,8,‘Armenia’),(6034,7,1,2,9,‘Atalanta’),(6035,7,1,2,10,‘Bananito
Sur’),(6036,7,1,2,11,‘Boca Cuen’),(6037,7,1,2,12,‘Boca Río
Estrella’),(6038,7,1,2,13,‘Bocuare’),(6039,7,1,2,14,‘Bonifacio’),(6040,7,1,2,15,‘Brisas’),(6041,7,1,2,16,‘Buenavista’),(6042,7,1,2,17,‘Burrico’),(6043,7,1,2,18,‘Calveri’),(6044,7,1,2,19,‘Caño
Negro’),(6045,7,1,2,20,‘Cartagena’),(6046,7,1,2,21,‘Casa
Amarilla’),(6047,7,1,2,22,‘Cerere’),(6048,7,1,2,23,‘Concepción’),(6049,7,1,2,24,‘Cuen’),(6050,7,1,2,25,‘Chirripó
Abajo (parte)’),(6051,7,1,2,26,‘Durfuy (San
Miguel)’),(6052,7,1,2,27,‘Duruy’),(6053,7,1,2,28,‘Fortuna’),(6054,7,1,2,29,‘Gavilán’),(6055,7,1,2,30,‘Hueco’),(6056,7,1,2,31,‘I
Griega’),(6057,7,1,2,32,‘Jabuy’),(6058,7,1,2,33,‘Llano
Grande’),(6059,7,1,2,34,‘Manú’),(6060,7,1,2,35,‘Miramar’),(6061,7,1,2,36,‘Moi
(San
Vicente)’),(6062,7,1,2,37,‘Nanabre’),(6063,7,1,2,38,‘Nubes’),(6064,7,1,2,39,‘Penshurt’),(6065,7,1,2,40,‘Pléyades’),(6066,7,1,2,41,‘Porvenir’),(6067,7,1,2,42,‘Progreso’),(6068,7,1,2,43,‘Río
Seco’),(6069,7,1,2,44,‘San Andrés’),(6070,7,1,2,45,‘San
Carlos’),(6071,7,1,2,46,‘San Clemente’),(6072,7,1,2,47,‘San
Rafael’),(6073,7,1,2,48,‘Suruy’),(6074,7,1,2,49,‘Talía’),(6075,7,1,2,50,‘Tobruk’),(6076,7,1,2,51,‘Tuba
Creek (parte)’),(6077,7,1,2,52,‘Valle de las
Rosas’),(6078,7,1,2,53,‘Vegas de
Cerere’),(6079,7,1,2,54,‘Vesta.’),(6080,7,1,2,55,‘Brisas’),(6081,7,1,2,56,‘Brisas
de Veragua’),(6082,7,1,2,57,‘Búfalo’),(6083,7,1,2,58,‘Limón
2000’),(6084,7,1,2,59,‘Loma
Linda’),(6085,7,1,2,60,‘México’),(6086,7,1,2,61,‘Milla
9’),(6087,7,1,2,62,‘Miravalles’),(6088,7,1,2,63,‘Río
Blanco’),(6089,7,1,2,64,‘Río Cedro’),(6090,7,1,2,65,‘Río
Madre’),(6091,7,1,2,66,‘Río Quito’),(6092,7,1,2,67,‘Río
Victoria’),(6093,7,1,2,68,‘Sandoval’),(6094,7,1,2,69,‘Santa
Rita’),(6095,7,1,2,70,‘Victoria.’),(6096,7,1,4,1,‘Aguas
Zarcas’),(6097,7,1,4,2,‘Asunción’),(6098,7,1,4,3,‘Bananito
Norte’),(6099,7,1,4,4,‘Bearesem’),(6100,7,1,4,5,‘Beverley’),(6101,7,1,4,6,‘Calle
Tranvía’),(6102,7,1,4,7,‘Castillo
Nuevo’),(6103,7,1,4,8,‘Dondonia’),(6104,7,1,4,9,‘Filadelfia
Norte’),(6105,7,1,4,10,‘Filadelfia
Sur’),(6106,7,1,4,11,‘Kent’),(6107,7,1,4,12,‘María
Luisa’),(6108,7,1,4,13,‘Mountain
Cow’),(6109,7,1,4,14,‘Paraíso’),(6110,7,1,4,15,‘Polonia’),(6111,7,1,4,16,‘Quitaría’),(6112,7,1,4,17,‘Río
Banano’),(6113,7,1,4,18,‘San
Cecilio’),(6114,7,1,4,19,‘Tigre’),(6115,7,1,4,20,‘Trébol’),(6116,7,1,4,21,‘Westfalia.’),(6117,7,2,1,1,‘Ángeles’),(6118,7,2,1,2,‘Calle
Vargas’),(6119,7,2,1,3,‘Cacique’),(6120,7,2,1,4,‘Cecilia’),(6121,7,2,1,5,‘Coopevigua’),(6122,7,2,1,6,‘Diamantes’),(6123,7,2,1,7,‘Emilia’),(6124,7,2,1,8,‘Floresta’),(6125,7,2,1,9,‘Garabito’),(6126,7,2,1,10,‘Jesús’),(6127,7,2,1,11,‘Palma
Dorada’),(6128,7,2,1,12,‘Palmera’),(6129,7,2,1,13,‘San
Miguel’),(6130,7,2,1,14,‘Sauces’),(6131,7,2,1,15,‘Toro
Amarillo.’),(6132,7,2,1,16,‘Blanco’),(6133,7,2,1,17,‘Calle
Ángeles’),(6134,7,2,1,18,‘Calle
Gobierno’),(6135,7,2,1,19,‘Corinto’),(6136,7,2,1,20,‘Flores’),(6137,7,2,1,21,‘La
Guaria’),(6138,7,2,1,22,‘Marina’),(6139,7,2,1,23,‘Rancho
Redondo.’),(6140,7,2,2,1,‘Granja’),(6141,7,2,2,2,‘Molino’),(6142,7,2,2,3,‘Numancia’),(6143,7,2,2,4,‘Santa
Clara.’),(6144,7,2,2,5,‘Anita Grande’),(6145,7,2,2,6,‘Calle
Diez’),(6146,7,2,2,7,‘Calle Emilia’),(6147,7,2,2,8,‘Calle
Seis’),(6148,7,2,2,9,‘Calle Uno’),(6149,7,2,2,10,‘Condado del
Río’),(6150,7,2,2,11,‘Floritas’),(6151,7,2,2,12,‘Parasal’),(6152,7,2,2,13,‘San
Luis’),(6153,7,2,2,14,‘San Martín’),(6154,7,2,2,15,‘San
Valentín’),(6155,7,2,2,16,‘Suerre.’),(6156,7,2,3,1,‘Cruce de
Jordán’),(6157,7,2,3,2,‘Peligro’),(6158,7,2,3,3,‘Pueblo
Nuevo.’),(6159,7,2,3,4,‘Balastre’),(6160,7,2,3,5,‘Cantagallo’),(6161,7,2,3,6,‘Cartagena’),(6162,7,2,3,7,‘Cayuga’),(6163,7,2,3,8,‘Cocorí’),(6164,7,2,3,9,‘Chirvalo’),(6165,7,2,3,10,‘Encina’),(6166,7,2,3,11,‘Gallopinto’),(6167,7,2,3,12,‘Hamburgo’),(6168,7,2,3,13,‘I
Griega’),(6169,7,2,3,14,‘Indio’),(6170,7,2,3,15,‘Jardín’),(6171,7,2,3,16,‘Mercedes’),(6172,7,2,3,17,‘Palmitas’),(6173,7,2,3,18,‘Porvenir’),(6174,7,2,3,19,‘Primavera’),(6175,7,2,3,20,‘Rótulo’),(6176,7,2,3,21,‘San
Carlos’),(6177,7,2,3,22,‘San Cristóbal’),(6178,7,2,3,23,‘San
Gerardo’),(6179,7,2,3,24,‘San Pedro’),(6180,7,2,3,25,‘Santa
Elena’),(6181,7,2,3,26,‘Santa
Rosa’),(6182,7,2,3,27,‘Sirena’),(6183,7,2,3,28,‘Suárez’),(6184,7,2,3,29,‘Suerte’),(6185,7,2,3,30,‘Tarire’),(6186,7,2,3,31,‘Teresa’),(6187,7,2,3,32,‘Ticabán’),(6188,7,2,3,33,‘Triángulo’),(6189,7,2,3,34,‘Victoria.’),(6190,7,2,4,1,‘La
Cruz’),(6191,7,2,4,2,‘Lesville’),(6192,7,2,4,3,‘Punta de
Riel.’),(6193,7,2,4,4,‘Aguas
Frías’),(6194,7,2,4,5,‘Anabán’),(6195,7,2,4,6,‘Boca Guápiles
(parte)’),(6196,7,2,4,7,‘Castañal’),(6197,7,2,4,8,‘Cruce’),(6198,7,2,4,9,‘Curia’),(6199,7,2,4,10,‘Curva’),(6200,7,2,4,11,‘Curva
del
Humo’),(6201,7,2,4,12,‘Esperanza’),(6202,7,2,4,13,‘Fortuna’),(6203,7,2,4,14,‘Humo’),(6204,7,2,4,15,‘La
Lidia’),(6205,7,2,4,16,‘Lomas
Azules’),(6206,7,2,4,17,‘Londres’),(6207,7,2,4,18,‘Llano
Bonito’),(6208,7,2,4,19,‘Maravilla’),(6209,7,2,4,20,‘Mata de
Limón’),(6210,7,2,4,21,‘Millón’),(6211,7,2,4,22,‘Milloncito’),(6212,7,2,4,23,‘Oeste’),(6213,7,2,4,24,‘Prado
(parte)’),(6214,7,2,4,25,‘Roxana Tres’),(6215,7,2,4,26,‘San
Francisco’),(6216,7,2,4,27,‘San Jorge’),(6217,7,2,4,28,‘Vegas de
Tortuguero.’),(6218,7,2,5,1,‘Astúa-Pirie’),(6219,7,2,5,2,‘Campo de
Aterrizaje (Pueblo
Triste)’),(6220,7,2,5,3,‘Formosa’),(6221,7,2,5,4,‘Palermo.’),(6222,7,2,5,5,‘Ángeles’),(6223,7,2,5,6,‘Banamola’),(6224,7,2,5,7,‘Boca
Guápiles (parte)’),(6225,7,2,5,8,‘Campo Cuatro’),(6226,7,2,5,9,‘Campo
Dos’),(6227,7,2,5,10,‘Campo Tres’),(6228,7,2,5,11,‘Campo Tres
Este’),(6229,7,2,5,12,‘Campo Tres Oeste’),(6230,7,2,5,13,‘Esperanza
(Cantarrana)’),(6231,7,2,5,14,‘Caño
Chiquero’),(6232,7,2,5,15,‘Carolina’),(6233,7,2,5,16,‘Ceibo’),(6234,7,2,5,17,‘Coopecariari’),(6235,7,2,5,18,‘Cuatro
Esquinas’),(6236,7,2,5,19,‘Encanto’),(6237,7,2,5,20,‘Frutera’),(6238,7,2,5,21,‘Gaviotas’),(6239,7,2,5,22,‘Hojancha’),(6240,7,2,5,23,‘Maná’),(6241,7,2,5,24,‘Monterrey’),(6242,7,2,5,25,‘Nazaret’),(6243,7,2,5,26,‘Progreso’),(6244,7,2,5,27,‘Pueblo
Nuevo’),(6245,7,2,5,28,‘Sagrada Familia’),(6246,7,2,5,29,‘San
Miguel’),(6247,7,2,5,30,‘Semillero’),(6248,7,2,5,31,‘Vega de Río
Palacios’),(6249,7,2,5,32,‘Zacatales.’),(6250,7,2,6,1,‘Barra del
Colorado
Este.’),(6251,7,2,6,2,‘Aragón’),(6252,7,2,6,3,‘Buenavista’),(6253,7,2,6,4,‘Malanga’),(6254,7,2,6,5,‘Puerto
Lindo’),(6255,7,2,6,6,‘San
Gerardo’),(6256,7,2,6,7,‘Tortuguero’),(6257,7,2,6,8,‘Verdades.’),(6258,7,2,7,1,‘Santa
Elena.’),(6259,7,2,7,2,‘Brisas del Toro
Amarillo’),(6260,7,2,7,3,‘Cascadas’),(6261,7,2,7,4,‘La
Victoria’),(6262,7,2,7,5,‘Losilla’),(6263,7,2,7,6,‘San
Bosco’),(6264,7,2,7,7,‘Prado
(parte).’),(6265,7,3,1,1,‘Betania’),(6266,7,3,1,2,‘Brooklin’),(6267,7,3,1,3,‘Indiana
Uno’),(6268,7,3,1,4,‘INVU’),(6269,7,3,1,5,‘María
Auxiliadora’),(6270,7,3,1,6,‘Palmiras’),(6271,7,3,1,7,‘Quebrador’),(6272,7,3,1,8,‘San
Rafael’),(6273,7,3,1,9,‘San
Martín’),(6274,7,3,1,10,‘Triunfo.’),(6275,7,3,1,11,‘Amelia’),(6276,7,3,1,12,‘Amistad’),(6277,7,3,1,13,‘Ángeles’),(6278,7,3,1,14,‘Bajo
Tigre’),(6279,7,3,1,15,‘Barnstorf’),(6280,7,3,1,16,‘Boca
Pacuare’),(6281,7,3,1,17,‘Boca
Parismina’),(6282,7,3,1,18,‘Calvario’),(6283,7,3,1,19,‘Calle
Tajo’),(6284,7,3,1,20,‘Canadá’),(6285,7,3,1,21,‘Caño
Blanco’),(6286,7,3,1,22,‘Carmen’),(6287,7,3,1,23,‘Celina’),(6288,7,3,1,24,‘Ciudadela
Flores’),(6289,7,3,1,25,‘Cocal’),(6290,7,3,1,26,‘Coco’),(6291,7,3,1,27,‘Dos
Bocas (Suerre)’),(6292,7,3,1,28,‘Encanto
(norte)’),(6293,7,3,1,29,‘Encanto
(sur)’),(6294,7,3,1,30,‘Ganga’),(6295,7,3,1,31,‘Guayacán’),(6296,7,3,1,32,‘Imperio’),(6297,7,3,1,33,‘Indiana
Dos’),(6298,7,3,1,34,‘Indiana
Tres’),(6299,7,3,1,35,‘Islona’),(6300,7,3,1,36,‘Lindavista’),(6301,7,3,1,37,‘Livingston’),(6302,7,3,1,38,‘Lucha’),(6303,7,3,1,39,‘Maryland’),(6304,7,3,1,40,‘Milla
52’),(6305,7,3,1,41,‘Moravia’),(6306,7,3,1,42,‘Morazán’),(6307,7,3,1,43,‘Nueva
Esperanza’),(6308,7,3,1,44,‘Nueva Virginia’),(6309,7,3,1,45,‘Pueblo
Civil’),(6310,7,3,1,46,‘San Alberto Nuevo’),(6311,7,3,1,47,‘San Alberto
Viejo’),(6312,7,3,1,48,‘San Alejo’),(6313,7,3,1,49,‘San
Joaquín’),(6314,7,3,1,50,‘Santo Domingo’),(6315,7,3,1,51,‘Vegas de
Imperio.’),(6316,7,3,2,1,‘Alto Mirador’),(6317,7,3,2,2,‘Altos de
Pacuarito’),(6318,7,3,2,3,‘Buenos
Aires’),(6319,7,3,2,4,‘Cimarrones’),(6320,7,3,2,5,‘Culpeper’),(6321,7,3,2,6,‘Cultivez’),(6322,7,3,2,7,‘Freehold’),(6323,7,3,2,8,‘Freeman
(San Rafael)’),(6324,7,3,2,9,‘Galicia’),(6325,7,3,2,10,‘Isla
Nueva’),(6326,7,3,2,11,‘Leona’),(6327,7,3,2,12,‘Madre de
Dios’),(6328,7,3,2,13,‘Manila’),(6329,7,3,2,14,‘Monteverde’),(6330,7,3,2,15,‘Pacuare’),(6331,7,3,2,16,‘Perla’),(6332,7,3,2,17,‘Perlita’),(6333,7,3,2,18,‘Río
Hondo’),(6334,7,3,2,19,‘San Luis’),(6335,7,3,2,20,‘San
Carlos’),(6336,7,3,2,21,‘San Isidro’),(6337,7,3,2,22,‘San
Pablo’),(6338,7,3,2,23,‘Santa Rosa’),(6339,7,3,2,24,‘Ten
Switch’),(6340,7,3,2,25,‘Trinidad’),(6341,7,3,2,26,‘Unión
Campesina’),(6342,7,3,2,27,‘Waldeck.’),(6343,7,3,3,1,‘El
Alto.’),(6344,7,3,3,2,‘Alto Gracias a Dios’),(6345,7,3,3,3,‘Alto
Laurelar’),(6346,7,3,3,4,‘Altos de Pascua’),(6347,7,3,3,5,‘Bonilla
Abajo’),(6348,7,3,3,6,‘Casorla’),(6349,7,3,3,7,‘Chonta’),(6350,7,3,3,8,‘Destierro’),(6351,7,3,3,9,‘Fourth
Cliff’),(6352,7,3,3,10,‘Huecos’),(6353,7,3,3,11,‘Lomas’),(6354,7,3,3,12,‘Llano’),(6355,7,3,3,13,‘Pascua’),(6356,7,3,3,14,‘Roca’),(6357,7,3,3,15,‘Rubí’),(6358,7,3,3,16,‘San
Antonio’),(6359,7,3,3,17,‘Tunel
Camp.’),(6360,7,3,4,1,‘América’),(6361,7,3,4,2,‘Babilonia.’),(6362,7,3,4,3,‘Cacao’),(6363,7,3,4,4,‘Colombiana’),(6364,7,3,4,5,‘Herediana’),(6365,7,3,4,6,‘Milano’),(6366,7,3,4,7,‘Trinidad’),(6367,7,3,4,8,‘Williamsburg.’),(6368,7,3,5,1,‘Francia’),(6369,7,3,5,2,‘Ana’),(6370,7,3,5,3,‘Bellavista’),(6371,7,3,5,4,‘Boca
Río
Jiménez’),(6372,7,3,5,5,‘Catalinas’),(6373,7,3,5,6,‘Castilla’),(6374,7,3,5,7,‘Cocal’),(6375,7,3,5,8,‘Golden
Grove’),(6376,7,3,5,9,‘Josefina’),(6377,7,3,5,10,‘Junta’),(6378,7,3,5,11,‘Laureles’),(6379,7,3,5,12,‘Luisiana’),(6380,7,3,5,13,‘Milla
3’),(6381,7,3,5,14,‘Milla 4’),(6382,7,3,5,15,‘Milla
5’),(6383,7,3,5,16,‘Milla
6’),(6384,7,3,5,17,‘Ontario’),(6385,7,3,5,18,‘Peje’),(6386,7,3,5,19,‘Seis
Amigos’),(6387,7,3,5,20,‘Silencio.’),(6388,7,3,6,1,‘Alto
Herediana’),(6389,7,3,6,2,‘Cruce’),(6390,7,3,6,3,‘Portón
Iberia’),(6391,7,3,6,4,‘Río
Peje’),(6392,7,3,6,5,‘Vueltas.’),(6393,7,4,1,1,‘Fields’),(6394,7,4,1,2,‘Sand
Box.’),(6395,7,4,1,3,‘Altamira’),(6396,7,4,1,4,‘Akberie (Piedra
Grande)’),(6397,7,4,1,5,‘Bambú’),(6398,7,4,1,6,‘Chase’),(6399,7,4,1,7,‘Cuabre’),(6400,7,4,1,8,‘Gavilán
Canta’),(6401,7,4,1,9,‘Mleyuk 1’),(6402,7,4,1,10,‘Mleyuk
2’),(6403,7,4,1,11,‘Monte
Sión’),(6404,7,4,1,12,‘Olivia’),(6405,7,4,1,13,‘Hu-Berie (Rancho
Grande)’),(6406,7,4,1,14,‘Shiroles’),(6407,7,4,1,15,‘Sibujú’),(6408,7,4,1,16,‘Suretka’),(6409,7,4,1,17,‘Uatsi.’),(6410,7,4,2,1,‘Ania’),(6411,7,4,2,2,‘Boca
Sixaola’),(6412,7,4,2,3,‘Catarina’),(6413,7,4,2,4,‘Celia’),(6414,7,4,2,5,‘Daytonia’),(6415,7,4,2,6,‘Gandoca’),(6416,7,4,2,7,‘Margarita’),(6417,7,4,2,8,‘Mata
de Limón’),(6418,7,4,2,9,‘Noventa y
Seis’),(6419,7,4,2,10,‘Palma’),(6420,7,4,2,11,‘Paraíso’),(6421,7,4,2,12,‘Parque’),(6422,7,4,2,13,‘San
Miguel’),(6423,7,4,2,14,‘San Miguelito’),(6424,7,4,2,15,‘San
Rafael’),(6425,7,4,2,16,‘Virginia’),(6426,7,4,2,17,‘Zavala.’),(6427,7,4,3,1,‘Buenavista
(Katuir)’),(6428,7,4,3,2,‘Bordón’),(6429,7,4,3,3,‘Carbón’),(6430,7,4,3,4,‘Carbón
1’),(6431,7,4,3,5,‘Carbón
2’),(6432,7,4,3,6,‘Catarata’),(6433,7,4,3,7,‘Cocles’),(6434,7,4,3,8,‘Comadre’),(6435,7,4,3,9,‘Dindirí’),(6436,7,4,3,10,‘Gibraltar’),(6437,7,4,3,11,‘Hone
Creek’),(6438,7,4,3,12,‘Hotel
Creek’),(6439,7,4,3,13,‘Kekoldi’),(6440,7,4,3,14,‘Limonal’),(6441,7,4,3,15,‘Manzanillo’),(6442,7,4,3,16,‘Mile
Creek’),(6443,7,4,3,17,‘Patiño’),(6444,7,4,3,18,‘Playa
Chiquita’),(6445,7,4,3,19,‘Puerto Viejo’),(6446,7,4,3,20,‘Punta
Caliente’),(6447,7,4,3,21,‘Punta Cocles’),(6448,7,4,3,22,‘Punta
Mona’),(6449,7,4,3,23,‘Punta Uva’),(6450,7,4,3,24,‘Tuba Creek
(parte).’),(6451,7,4,4,1,‘Alto Cuen (Kjacka Bata)’),(6452,7,4,4,2,‘Alto
Lari (Duriñak)’),(6453,7,4,4,3,‘Alto
Urén’),(6454,7,4,4,4,‘Arenal’),(6455,7,4,4,5,‘Bajo
Blei’),(6456,7,4,4,6,‘Bajo Cuen’),(6457,7,4,4,7,‘Boca
Urén’),(6458,7,4,4,8,‘Bris’),(6459,7,4,4,9,‘Cachabli’),(6460,7,4,4,10,‘Coroma’),(6461,7,4,4,11,‘Croriña’),(6462,7,4,4,12,‘China
Kichá’),(6463,7,4,4,13,‘Dururpe’),(6464,7,4,4,14,‘Guachalaba’),(6465,7,4,4,15,‘Katsi’),(6466,7,4,4,16,‘Kichuguecha’),(6467,7,4,4,17,‘Kivut’),(6468,7,4,4,18,‘Mojoncito’),(6469,7,4,4,19,‘Namuwakir’),(6470,7,4,4,20,‘Orochico’),(6471,7,4,4,21,‘Ourut’),(6472,7,4,4,22,‘Purisquí’),(6473,7,4,4,23,‘Purita’),(6474,7,4,4,24,‘Rangalle’),(6475,7,4,4,25,‘San
José
Cabecar’),(6476,7,4,4,26,‘Sepeque’),(6477,7,4,4,27,‘Shewab’),(6478,7,4,4,28,‘Sipurio’),(6479,7,4,4,29,‘Soky’),(6480,7,4,4,30,‘Sorókicha’),(6481,7,4,4,31,‘Sukut’),(6482,7,4,4,32,‘Surayo’),(6483,7,4,4,33,‘Suiri’),(6484,7,4,4,34,‘Telire’),(6485,7,4,4,35,‘Turubokicha’),(6486,7,4,4,36,‘Urén.’),(6487,7,5,1,1,‘Goli’),(6488,7,5,1,2,‘Luisa
Oeste’),(6489,7,5,1,3,‘Milla
23.’),(6490,7,5,1,4,‘Baltimore’),(6491,7,5,1,5,‘Barra de Matina
Norte’),(6492,7,5,1,6,‘Bristol’),(6493,7,5,1,7,‘Colonia
Puriscaleña’),(6494,7,5,1,8,‘Corina’),(6495,7,5,1,9,‘Chirripó’),(6496,7,5,1,10,‘Chumico’),(6497,7,5,1,11,‘Esperanza’),(6498,7,5,1,12,‘Helvetia’),(6499,7,5,1,13,‘Hilda’),(6500,7,5,1,14,‘Línea
B’),(6501,7,5,1,15,‘Milla
4’),(6502,7,5,1,16,‘Palmeras’),(6503,7,5,1,17,‘Pozo
Azul’),(6504,7,5,1,18,‘Punta de Lanza’),(6505,7,5,1,19,‘San
Miguel’),(6506,7,5,1,20,‘Victoria’),(6507,7,5,1,21,‘Xirinachs.’),(6508,7,5,2,1,‘Almendros’),(6509,7,5,2,2,‘Margarita’),(6510,7,5,2,3,‘Milla
24’),(6511,7,5,2,4,‘Milla
25’),(6512,7,5,2,5,‘Parcelas’),(6513,7,5,2,6,‘Ramal
Siete.’),(6514,7,5,2,7,‘Barbilla’),(6515,7,5,2,8,‘Berta’),(6516,7,5,2,9,‘Damasco’),(6517,7,5,2,10,‘Davao’),(6518,7,5,2,11,‘Dos
Ramas’),(6519,7,5,2,12,‘Espavel’),(6520,7,5,2,13,‘Goshen’),(6521,7,5,2,14,‘Leyte’),(6522,7,5,2,15,‘Lola’),(6523,7,5,2,16,‘Luzón’),(6524,7,5,2,17,‘Milla
27’),(6525,7,5,2,18,‘Milla
28’),(6526,7,5,2,19,‘Oracabesa’),(6527,7,5,2,20,‘Sahara’),(6528,7,5,2,21,‘Santa
Marta’),(6529,7,5,2,22,‘Titán’),(6530,7,5,2,23,‘Vegas.’),(6531,7,5,3,1,‘San
José.’),(6532,7,5,3,2,‘Bananita’),(6533,7,5,3,3,‘Barra de Matina
Sur’),(6534,7,5,3,4,‘Boca del Pantano’),(6535,7,5,3,5,‘Boca Río
Matina’),(6536,7,5,3,6,‘Boston’),(6537,7,5,3,7,‘Brisas’),(6538,7,5,3,8,‘California’),(6539,7,5,3,9,‘Indio’),(6540,7,5,3,10,‘Larga
Distancia’),(6541,7,5,3,11,‘Lomas del Toro’),(6542,7,5,3,12,‘Luisa
Este’),(6543,7,5,3,13,‘Maravilla’),(6544,7,5,3,14,‘Milla
14’),(6545,7,5,3,15,‘Nueva
York’),(6546,7,5,3,16,‘Palacios’),(6547,7,5,3,17,‘Palestina’),(6548,7,5,3,18,‘Peje’),(6549,7,5,3,19,‘Punta
de Riel’),(6550,7,5,3,20,‘Río Cuba’),(6551,7,5,3,21,‘Río
Peje’),(6552,7,5,3,22,‘Saborío’),(6553,7,5,3,23,‘San
Edmundo’),(6554,7,5,3,24,‘Santa
María’),(6555,7,5,3,25,‘Sterling’),(6556,7,5,3,26,‘Strafford’),(6557,7,5,3,27,‘Toro’),(6558,7,5,3,28,‘Trinidad’),(6559,7,5,3,29,‘Venecia’),(6560,7,5,3,30,‘Zent.’),(6561,7,6,1,1,‘Africa’),(6562,7,6,1,2,‘Cantarrana’),(6563,7,6,1,3,‘Estación
Rudín’),(6564,7,6,1,4,‘Guayacán.’),(6565,7,6,1,5,‘Aguacate’),(6566,7,6,1,6,‘Angelina’),(6567,7,6,1,7,‘Bosque’),(6568,7,6,1,8,‘Cabaña’),(6569,7,6,1,9,‘Edén’),(6570,7,6,1,10,‘El
Tres’),(6571,7,6,1,11,‘Fox
Hall’),(6572,7,6,1,12,‘Guaira’),(6573,7,6,1,13,‘Hogar’),(6574,7,6,1,14,‘Parismina’),(6575,7,6,1,15,‘San
Luis’),(6576,7,6,1,16,‘Selva.’),(6577,7,6,2,1,‘Bremen’),(6578,7,6,2,2,‘Argentina’),(6579,7,6,2,3,‘Confianza’),(6580,7,6,2,4,‘Iroquois.’),(6581,7,6,3,1,‘Pocora
Sur.’),(6582,7,6,3,2,‘Ojo de
Agua.’),(6583,7,6,4,1,‘Ángeles’),(6584,7,6,4,2,‘Bocas del Río
Silencio’),(6585,7,6,4,3,‘Camarón’),(6586,7,6,4,4,‘Cartagena’),(6587,7,6,4,5,‘Dulce
Nombre’),(6588,7,6,4,6,‘Escocia’),(6589,7,6,4,7,‘Irlanda’),(6590,7,6,4,8,‘Jardín’),(6591,7,6,4,9,‘Ligia’),(6592,7,6,4,10,‘Lucha’),(6593,7,6,4,11,‘Santa
María’),(6594,7,6,4,12,‘Santa
Rosa’),(6595,7,6,4,13,‘Socorro.’),(6596,7,6,5,1,‘Aguas
Gatas’),(6597,7,6,5,2,‘Carambola’),(6598,7,6,5,3,‘Castaño’),(6599,7,6,5,4,‘Esperanza’),(6600,7,6,5,5,‘Fruta
de Pan’),(6601,7,6,5,6,‘Limbo’),(6602,7,6,5,7,‘San
Cristóbal’),(6603,7,6,5,8,‘Zancudo.’);



  ------------------------------------
  – Table structure for table canton
  ------------------------------------

DROP TABLE IF EXISTS canton;

CREATE TABLE canton ( id int NOT NULL, codigoProvincia int NOT NULL,
codigo int NOT NULL, canton varchar(45) DEFAULT NULL, PRIMARY KEY (id) )
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  ---------------------------------
  – Dumping data for table canton
  ---------------------------------

INSERT INTO canton VALUES (1,1,1,‘San
José’),(2,1,2,‘Escazú’),(3,1,3,‘Desamparados’),(4,1,4,‘Puriscal’),(5,1,5,‘Tarrazú’),(6,1,6,‘Aserrí’),(7,1,7,‘Mora’),(8,1,8,‘Goicoechea’),(9,1,9,‘Santa
Ana’),(10,1,10,‘Alajuelita’),(11,1,11,‘Vásquez de
Coronado’),(12,1,12,‘Acosta’),(13,1,13,‘Tibás’),(14,1,14,‘Moravia’),(15,1,15,‘Montes
de
Oca’),(16,1,16,‘Turrubares’),(17,1,17,‘Dota’),(18,1,18,‘Curridabat’),(19,1,19,‘Pérez
Zeledón’),(20,1,20,‘León Cortéz
Castro’),(21,2,1,‘Alajuela’),(22,2,2,‘San
Ramón’),(23,2,3,‘Grecia’),(24,2,4,‘San
Mateo’),(25,2,5,‘Atenas’),(26,2,6,‘Naranjo’),(27,2,7,‘Palmares’),(28,2,8,‘Poás’),(29,2,9,‘Orotina’),(30,2,10,‘San
Carlos’),(31,2,11,‘Zarcero’),(32,2,12,‘Valverde
Vega’),(33,2,13,‘Upala’),(34,2,14,‘Los
Chiles’),(35,2,15,‘Guatuso’),(36,3,1,‘Cartago’),(37,3,2,‘Paraíso’),(38,3,3,‘La
Unión’),(39,3,4,‘Jiménez’),(40,3,5,‘Turrialba’),(41,3,6,‘Alvarado’),(42,3,7,‘Oreamuno’),(43,3,8,‘El
Guarco’),(44,4,1,‘Heredia’),(45,4,2,‘Barva’),(46,4,3,‘Santo
Domingo’),(47,4,4,‘Santa Bárbara’),(48,4,5,‘San Rafaél’),(49,4,6,‘San
Isidro’),(50,4,7,‘Belén’),(51,4,8,‘Flores’),(52,4,9,‘San
Pablo’),(53,4,10,‘Sarapiquí’),(54,5,1,‘Liberia’),(55,5,2,‘Nicoya’),(56,5,3,‘Santa
Cruz’),(57,5,4,‘Bagaces’),(58,5,5,‘Carrillo’),(59,5,6,‘Cañas’),(60,5,7,‘Abangáres’),(61,5,8,‘Tilarán’),(62,5,9,‘Nandayure’),(63,5,10,‘La
Cruz’),(64,5,11,‘Hojancha’),(65,6,1,‘Puntarenas’),(66,6,2,‘Esparza’),(67,6,3,‘Buenos
Aires’),(68,6,4,‘Montes de
Oro’),(69,6,5,‘Osa’),(70,6,6,‘Aguirre’),(71,6,7,‘Golfito’),(72,6,8,‘Coto
Brus’),(73,6,9,‘Parrita’),(74,6,10,‘Corredores’),(75,6,11,‘Garabito’),(76,7,1,‘Limón’),(77,7,2,‘Pococí’),(78,7,3,‘Siquirres’),(79,7,4,‘Talamanca’),(80,7,5,‘Matina’),(81,7,6,‘Guácimo’);

UN

  ----------------------------------
  – Table structure for table cart
  ----------------------------------

DROP TABLE IF EXISTS cart;

CREATE TABLE cart ( ID int NOT NULL AUTO_INCREMENT, OrderID int DEFAULT
‘0’, ItemID int DEFAULT ‘0’, StatusID tinyint DEFAULT ‘1’, PRIMARY KEY
(ID) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


  -----------------------------------------------
  – Table structure for table categoriaservicio
  -----------------------------------------------

DROP TABLE IF EXISTS categoriaservicio;

CREATE TABLE categoriaservicio ( ID int NOT NULL AUTO_INCREMENT,
Descripcion varchar(245) DEFAULT NULL, Estado tinyint DEFAULT ‘1’,
PRIMARY KEY (ID) ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  --------------------------------------------
  – Dumping data for table categoriaservicio
  --------------------------------------------

INSERT INTO categoriaservicio VALUES (1,‘Lavacar’,1);


  -----------------------------------------------
  – Table structure for table categoriavehiculo
  -----------------------------------------------

DROP TABLE IF EXISTS categoriavehiculo;

CREATE TABLE categoriavehiculo ( ID int unsigned NOT NULL
AUTO_INCREMENT, TipoVehiculo varchar(45) DEFAULT NULL, Imagen
varchar(80) NOT NULL, Estado tinyint unsigned NOT NULL DEFAULT ‘1’,
OrdenClasificacion tinyint unsigned DEFAULT ‘0’, PRIMARY KEY (ID) )
ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

  --------------------------------------------
  – Dumping data for table categoriavehiculo
  --------------------------------------------

INSERT INTO categoriavehiculo VALUES (1,‘Sedan’,’‘,1,0),(2,’SUV
compacto’,‘fas fa-car-side’,1,2),(3,‘Pick Up’,‘fas
fa-truck-monster’,1,4),(7,‘SUV Grande’,‘fas
fa-car-side’,1,3),(8,‘MiniBus’,‘fas
fa-shuttle-van’,1,5),(9,‘Buses’,’‘,1,6),(10,’Moto’,’’,1,7);

UN

  --------------------------------------
  – Table structure for table clientes
  --------------------------------------

DROP TABLE IF EXISTS clientes;

CREATE TABLE clientes ( ID int NOT NULL AUTO_INCREMENT, NombreCompleto
varchar(45) NOT NULL, Cedula varchar(45) DEFAULT NULL, Empresa
varchar(45) DEFAULT NULL, Correo varchar(80) NOT NULL, Telefono
varchar(45) NOT NULL, Direccion varchar(45) DEFAULT NULL, Distrito
varchar(45) DEFAULT NULL, Canton varchar(45) DEFAULT NULL, Provincia
varchar(45) DEFAULT NULL, Pais varchar(45) DEFAULT ‘CR’, IVA int DEFAULT
‘13’, fecha_registro datetime DEFAULT CURRENT_TIMESTAMP, active tinyint
DEFAULT ‘1’, FechaRegistro datetime DEFAULT NULL, PRIMARY KEY (ID),
UNIQUE KEY Cedula_UNIQUE (Cedula) ) ENGINE=InnoDB AUTO_INCREMENT=5
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



  --------------------------------------
  – Table structure for table distrito
  --------------------------------------

DROP TABLE IF EXISTS distrito;

CREATE TABLE distrito ( id int NOT NULL, codigoProvincia int DEFAULT
NULL, codigoCanton int DEFAULT NULL, codigo int DEFAULT NULL, distrito
varchar(70) DEFAULT NULL, PRIMARY KEY (id) ) ENGINE=InnoDB DEFAULT
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -----------------------------------
  – Dumping data for table distrito
  -----------------------------------

INSERT INTO distrito VALUES
(1,1,1,1,‘CARMEN’),(2,1,1,2,‘MERCED’),(3,1,1,3,‘HOSPITAL’),(4,1,1,4,‘CATEDRAL’),(5,1,1,5,‘ZAPOTE’),(6,1,1,6,‘SAN
FRANCISCO DE DOS RÍOS’),(7,1,1,7,‘URUCA’),(8,1,1,8,‘MATA
REDONDA’),(9,1,1,9,‘PAVAS’),(10,1,1,10,‘HATILLO’),(11,1,1,11,‘SAN
SEBASTIÁN’),(12,1,2,1,‘ESCAZÚ’),(13,1,2,2,‘SAN ANTONIO’),(14,1,2,3,‘SAN
RAFAEL’),(15,1,3,1,‘DESAMPARADOS’),(16,1,3,2,‘SAN
MIGUEL’),(17,1,3,3,‘SAN JUAN DE DIOS’),(18,1,3,4,‘SAN RAFAEL
ARRIBA’),(19,1,3,5,‘SAN
ANTONIO’),(20,1,3,6,‘FRAILES’),(21,1,3,7,‘PATARRÁ’),(22,1,3,8,‘SAN
CRISTÓBAL’),(23,1,3,9,‘ROSARIO’),(24,1,3,10,‘DAMAS’),(25,1,3,11,‘SAN
RAFAEL ABAJO’),(26,1,3,12,‘GRAVILIAS’),(27,1,3,13,‘LOS
GUIDO’),(28,1,4,1,‘SANTIAGO’),(29,1,4,2,‘MERCEDES
SUR’),(30,1,4,3,‘BARBACOAS’),(31,1,4,4,‘GRIFO ALTO’),(32,1,4,5,‘SAN
RAFAEL’),(33,1,4,6,‘CANDELARITA’),(34,1,4,7,‘DESAMPARADITOS’),(35,1,4,8,‘SAN
ANTONIO’),(36,1,4,9,‘CHIRES’),(37,1,5,1,‘SAN MARCOS’),(38,1,5,2,‘SAN
LORENZO’),(39,1,5,3,‘SAN
CARLOS’),(40,1,6,1,‘ASERRI’),(41,1,6,2,‘TARBACA’),(42,1,6,3,‘VUELTA DE
JORCO’),(43,1,6,4,‘SAN
GABRIEL’),(44,1,6,5,‘LEGUA’),(45,1,6,6,‘MONTERREY’),(46,1,6,7,‘SALITRILLOS’),(47,1,7,1,‘COLÓN’),(48,1,7,2,‘GUAYABO’),(49,1,7,3,‘TABARCIA’),(50,1,7,4,‘PIEDRAS
NEGRAS’),(51,1,7,5,‘PICAGRES’),(52,1,7,6,‘JARIS’),(53,1,7,7,‘QUITIRRISI’),(54,1,8,1,‘GUADALUPE’),(55,1,8,2,‘SAN
FRANCISCO’),(56,1,8,3,‘CALLE BLANCOS’),(57,1,8,4,‘MATA DE
PLÁTANO’),(58,1,8,5,‘IPÍS’),(59,1,8,6,‘RANCHO
REDONDO’),(60,1,8,7,‘PURRAL’),(61,1,9,1,‘SANTA
ANA’),(62,1,9,2,‘SALITRAL’),(63,1,9,3,‘POZOS’),(64,1,9,4,‘URUCA’),(65,1,9,5,‘PIEDADES’),(66,1,9,6,‘BRASIL’),(67,1,10,1,‘ALAJUELITA’),(68,1,10,2,‘SAN
JOSECITO’),(69,1,10,3,‘SAN
ANTONIO’),(70,1,10,4,‘CONCEPCIÓN’),(71,1,10,5,‘SAN
FELIPE’),(72,1,11,1,‘SAN ISIDRO’),(73,1,11,2,‘SAN
RAFAEL’),(74,1,11,3,‘DULCE NOMBRE DE
JESÚS’),(75,1,11,4,‘PATALILLO’),(76,1,11,5,‘CASCAJAL’),(77,1,12,1,‘SAN
IGNACIO’),(78,1,12,2,‘GUAITIL
Villa’),(79,1,12,3,‘PALMICHAL’),(80,1,12,4,‘CANGREJAL’),(81,1,12,5,‘SABANILLAS’),(82,1,13,1,‘SAN
JUAN’),(83,1,13,2,‘CINCO ESQUINAS’),(84,1,13,3,‘ANSELMO
LLORENTE’),(85,1,13,4,‘LEON XIII’),(86,1,13,5,‘COLIMA’),(87,1,14,1,‘SAN
VICENTE’),(88,1,14,2,‘SAN JERÓNIMO’),(89,1,14,3,‘LA
TRINIDAD’),(90,1,15,1,‘SAN
PEDRO’),(91,1,15,2,‘SABANILLA’),(92,1,15,3,‘MERCEDES’),(93,1,15,4,‘SAN
RAFAEL’),(94,1,16,1,‘SAN PABLO’),(95,1,16,2,‘SAN PEDRO’),(96,1,16,3,‘SAN
JUAN DE MATA’),(97,1,16,4,‘SAN
LUIS’),(98,1,16,5,‘CARARA’),(99,1,17,1,‘SANTA
MARÍA’),(100,1,17,2,‘JARDÍN’),(101,1,17,3,‘COPEY’),(102,1,18,1,‘CURRIDABAT’),(103,1,18,2,‘GRANADILLA’),(104,1,18,3,‘SÁNCHEZ’),(105,1,18,4,‘TIRRASES’),(106,1,19,1,‘SAN
ISIDRO DE EL GENERAL’),(107,1,19,2,‘EL GENERAL’),(108,1,19,3,‘DANIEL
FLORES’),(109,1,19,4,‘RIVAS’),(110,1,19,5,‘SAN
PEDRO’),(111,1,19,6,‘PLATANARES’),(112,1,19,7,‘PEJIBAYE’),(113,1,19,8,‘CAJÓN’),(114,1,19,9,‘BARÚ’),(115,1,19,10,‘RÍO
NUEVO’),(116,1,19,11,‘PÁRAMO’),(117,1,20,1,‘SAN PABLO’),(118,1,20,2,‘SAN
ANDRÉS’),(119,1,20,3,‘LLANO BONITO’),(120,1,20,4,‘SAN
ISIDRO’),(121,1,20,5,‘SANTA CRUZ’),(122,1,20,6,‘SAN
ANTONIO’),(123,2,21,1,‘ALAJUELA’),(124,2,21,2,‘SAN
JOSÉ’),(125,2,21,3,‘CARRIZAL’),(126,2,21,4,‘SAN
ANTONIO’),(127,2,21,5,‘GUÁCIMA’),(128,2,21,6,‘SAN
ISIDRO’),(129,2,21,7,‘SABANILLA’),(130,2,21,8,‘SAN
RAFAEL’),(131,2,21,9,‘RÍO
SEGUNDO’),(132,2,21,10,‘DESAMPARADOS’),(133,2,21,11,‘TURRÚCARES’),(134,2,21,12,‘TAMBOR’),(135,2,21,13,‘GARITA’),(136,2,21,14,‘SARAPIQUÍ’),(137,2,22,1,‘SAN
RAMÓN’),(138,2,22,2,‘SANTIAGO’),(139,2,22,3,‘SAN
JUAN’),(140,2,22,4,‘PIEDADES NORTE’),(141,2,22,5,‘PIEDADES
SUR’),(142,2,22,6,‘SAN RAFAEL’),(143,2,22,7,‘SAN
ISIDRO’),(144,2,22,8,‘ÁNGELES’),(145,2,22,9,‘ALFARO’),(146,2,22,10,‘VOLIO’),(147,2,22,11,‘CONCEPCIÓN’),(148,2,22,12,‘ZAPOTAL’),(149,2,22,13,‘PEÑAS
BLANCAS’),(150,2,23,1,‘GRECIA’),(151,2,23,2,‘SAN
ISIDRO’),(152,2,23,3,‘SAN JOSÉ’),(153,2,23,4,‘SAN
ROQUE’),(154,2,23,5,‘TACARES’),(155,2,23,6,‘RÍO
CUARTO’),(156,2,23,7,‘PUENTE DE
PIEDRA’),(157,2,23,8,‘BOLÍVAR’),(158,2,24,1,‘SAN
MATEO’),(159,2,24,2,‘DESMONTE’),(160,2,24,3,‘JESÚS
MARÍA’),(161,2,24,4,‘LABRADOR’),(162,2,25,1,‘ATENAS’),(163,2,25,2,‘JESÚS’),(164,2,25,3,‘MERCEDES’),(165,2,25,4,‘SAN
ISIDRO’),(166,2,25,5,‘CONCEPCIÓN’),(167,2,25,6,‘SAN
JOSE’),(168,2,25,7,‘SANTA
EULALIA’),(169,2,25,8,‘ESCOBAL’),(170,2,26,1,‘NARANJO’),(171,2,26,2,‘SAN
MIGUEL’),(172,2,26,3,‘SAN JOSÉ’),(173,2,26,4,‘CIRRÍ
SUR’),(174,2,26,5,‘SAN JERÓNIMO’),(175,2,26,6,‘SAN
JUAN’),(176,2,26,7,‘EL
ROSARIO’),(177,2,26,8,‘PALMITOS’),(178,2,27,1,‘PALMARES’),(179,2,27,2,‘ZARAGOZA’),(180,2,27,3,‘BUENOS
AIRES’),(181,2,27,4,‘SANTIAGO’),(182,2,27,5,‘CANDELARIA’),(183,2,27,6,‘ESQUÍPULAS’),(184,2,27,7,‘LA
GRANJA’),(185,2,28,1,‘SAN PEDRO’),(186,2,28,2,‘SAN
JUAN’),(187,2,28,3,‘SAN
RAFAEL’),(188,2,28,4,‘CARRILLOS’),(189,2,28,5,‘SABANA
REDONDA’),(190,2,29,1,‘OROTINA’),(191,2,29,2,‘EL
MASTATE’),(192,2,29,3,‘HACIENDA
VIEJA’),(193,2,29,4,‘COYOLAR’),(194,2,29,5,‘LA
CEIBA’),(195,2,30,1,‘QUESADA’),(196,2,30,2,‘FLORENCIA’),(197,2,30,3,‘BUENAVISTA’),(198,2,30,4,‘AGUAS
ZARCAS’),(199,2,30,5,‘VENECIA’),(200,2,30,6,‘PITAL’),(201,2,30,7,‘LA
FORTUNA’),(202,2,30,8,‘LA TIGRA’),(203,2,30,9,‘LA
PALMERA’),(204,2,30,10,‘VENADO’),(205,2,30,11,‘CUTRIS’),(206,2,30,12,‘MONTERREY’),(207,2,30,13,‘POCOSOL’),(208,2,31,1,‘ZARCERO’),(209,2,31,2,‘LAGUNA’),(210,2,31,4,‘GUADALUPE’),(211,2,31,5,‘PALMIRA’),(212,2,31,6,‘ZAPOTE’),(213,2,31,7,‘BRISAS’),(214,2,32,1,‘SARCHÍ
NORTE’),(215,2,32,2,‘SARCHÍ SUR’),(216,2,32,3,‘TORO
AMARILLO’),(217,2,32,4,‘SAN
PEDRO’),(218,2,32,5,‘RODRÍGUEZ’),(219,2,33,1,‘UPALA’),(220,2,33,2,‘AGUAS
CLARAS’),(221,2,33,3,‘SAN JOSÉ o
PIZOTE’),(222,2,33,4,‘BIJAGUA’),(223,2,33,5,‘DELICIAS’),(224,2,33,6,‘DOS
RÍOS’),(225,2,33,7,‘YOLILLAL’),(226,2,33,8,‘CANALETE’),(227,2,34,1,‘LOS
CHILES’),(228,2,34,2,‘CAÑO NEGRO’),(229,2,34,3,‘EL
AMPARO’),(230,2,34,4,‘SAN
JORGE’),(231,2,35,2,‘BUENAVISTA’),(232,2,35,3,‘COTE’),(233,2,35,4,‘KATIRA’),(234,3,36,1,‘ORIENTAL’),(235,3,36,2,‘OCCIDENTAL’),(236,3,36,3,‘CARMEN’),(237,3,36,4,‘SAN
NICOLÁS’),(238,3,36,5,‘AGUACALIENTE o SAN
FRANCISCO’),(239,3,36,6,‘GUADALUPE o
ARENILLA’),(240,3,36,7,‘CORRALILLO’),(241,3,36,8,‘TIERRA
BLANCA’),(242,3,36,9,‘DULCE NOMBRE’),(243,3,36,10,‘LLANO
GRANDE’),(244,3,36,11,‘QUEBRADILLA’),(245,3,37,1,‘PARAÍSO’),(246,3,37,2,‘SANTIAGO’),(247,3,37,3,‘OROSI’),(248,3,37,4,‘CACHÍ’),(249,3,37,5,‘LLANOS
DE SANTA LUCÍA’),(250,3,38,1,‘TRES RÍOS’),(251,3,38,2,‘SAN
DIEGO’),(252,3,38,3,‘SAN JUAN’),(253,3,38,4,‘SAN
RAFAEL’),(254,3,38,5,‘CONCEPCIÓN’),(255,3,38,6,‘DULCE
NOMBRE’),(256,3,38,7,‘SAN RAMÓN’),(257,3,38,8,‘RÍO
AZUL’),(258,3,39,1,‘JUAN
VIÑAS’),(259,3,39,2,‘TUCURRIQUE’),(260,3,39,3,‘PEJIBAYE’),(261,3,40,1,‘TURRIALBA’),(262,3,40,2,‘LA
SUIZA’),(263,3,40,3,‘PERALTA’),(264,3,40,4,‘SANTA
CRUZ’),(265,3,40,5,‘SANTA
TERESITA’),(266,3,40,6,‘PAVONES’),(267,3,40,7,‘TUIS’),(268,3,40,8,‘TAYUTIC’),(269,3,40,9,‘SANTA
ROSA’),(270,3,40,10,‘TRES EQUIS’),(271,3,40,11,‘LA
ISABEL’),(272,3,40,12,‘CHIRRIPÓ’),(273,3,41,1,‘PACAYAS’),(274,3,41,2,‘CERVANTES’),(275,3,41,3,‘CAPELLADES’),(276,3,42,1,‘SAN
RAFAEL’),(277,3,42,2,‘COT’),(278,3,42,3,‘POTRERO
CERRADO’),(279,3,42,4,‘CIPRESES’),(280,3,42,5,‘SANTA
ROSA’),(281,3,43,1,‘EL TEJAR’),(282,3,43,2,‘SAN
ISIDRO’),(283,3,43,3,‘TOBOSI’),(284,3,43,4,‘PATIO DE
AGUA’),(285,4,44,1,‘HEREDIA’),(286,4,44,2,‘MERCEDES’),(287,4,44,3,‘SAN
FRANCISCO’),(288,4,44,4,‘ULLOA’),(289,4,44,5,‘VARABLANCA’),(290,4,45,1,‘BARVA’),(291,4,45,2,‘SAN
PEDRO’),(292,4,45,3,‘SAN PABLO’),(293,4,45,4,‘SAN
ROQUE’),(294,4,45,5,‘SANTA LUCÍA’),(295,4,45,6,‘SAN JOSÉ DE LA
MONTAÑA’),(296,4,46,2,‘SAN VICENTE’),(297,4,46,3,‘SAN
MIGUEL’),(298,4,46,4,‘PARACITO’),(299,4,46,5,‘SANTO
TOMÁS’),(300,4,46,6,‘SANTA
ROSA’),(301,4,46,7,‘TURES’),(302,4,46,8,‘PARÁ’),(303,4,47,1,‘SANTA
BÁRBARA’),(304,4,47,2,‘SAN PEDRO’),(305,4,47,3,‘SAN
JUAN’),(306,4,47,4,‘JESÚS’),(307,4,47,5,‘SANTO
DOMINGO’),(308,4,47,6,‘PURABÁ’),(309,4,48,1,‘SAN
RAFAEL’),(310,4,48,2,‘SAN
JOSECITO’),(311,4,48,3,‘SANTIAGO’),(312,4,48,4,‘ÁNGELES’),(313,4,48,5,‘CONCEPCIÓN’),(314,4,49,1,‘SAN
ISIDRO’),(315,4,49,2,‘SAN
JOSÉ’),(316,4,49,3,‘CONCEPCIÓN’),(317,4,49,4,‘SAN
FRANCISCO’),(318,4,50,1,‘SAN ANTONIO’),(319,4,50,2,‘LA
RIBERA’),(320,4,50,3,‘LA ASUNCIÓN’),(321,4,51,1,‘SAN
JOAQUÍN’),(322,4,51,2,‘BARRANTES’),(323,4,51,3,‘LLORENTE’),(324,4,52,1,‘SAN
PABLO’),(325,4,53,1,‘PUERTO VIEJO’),(326,4,53,2,‘LA
VIRGEN’),(327,4,53,3,‘LAS HORQUETAS’),(328,4,53,4,‘LLANURAS DEL
GASPAR’),(329,4,53,5,‘CUREÑA’),(330,5,54,1,‘LIBERIA’),(331,5,54,2,‘CAÑAS
DULCES’),(332,5,54,3,‘MAYORGA’),(333,5,54,4,‘NACASCOLO’),(334,5,54,5,‘CURUBANDÉ’),(335,5,55,1,‘NICOYA’),(336,5,55,2,‘MANSIÓN’),(337,5,55,3,‘SAN
ANTONIO’),(338,5,55,4,‘QUEBRADA
HONDA’),(339,5,55,5,‘SÁMARA’),(340,5,55,6,‘NOSARA’),(341,5,55,7,‘BELÉN
DE NOSARITA’),(342,5,56,1,‘SANTA
CRUZ’),(343,5,56,2,‘BOLSÓN’),(344,5,56,3,‘VEINTISIETE DE
ABRIL’),(345,5,56,4,‘TEMPATE’),(346,5,56,5,‘CARTAGENA’),(347,5,56,6,‘CUAJINIQUIL’),(348,5,56,7,‘DIRIÁ’),(349,5,56,8,‘CABO
VELAS’),(350,5,56,9,‘TAMARINDO’),(351,5,57,1,‘BAGACES’),(352,5,57,2,‘LA
FORTUNA’),(353,5,57,3,‘MOGOTE’),(354,5,57,4,‘RÍO
NARANJO’),(355,5,58,1,‘FILADELFIA’),(356,5,58,2,‘PALMIRA’),(357,5,58,3,‘SARDINAL’),(358,5,58,4,‘BELÉN’),(359,5,59,1,‘CAÑAS’),(360,5,59,2,‘PALMIRA’),(361,5,59,3,‘SAN
MIGUEL’),(362,5,59,4,‘BEBEDERO’),(363,5,59,5,‘POROZAL’),(364,5,60,1,‘LAS
JUNTAS’),(365,5,60,2,‘SIERRA’),(366,5,60,3,‘SAN
JUAN’),(367,5,60,4,‘COLORADO’),(368,5,61,1,‘TILARÁN’),(369,5,61,2,‘QUEBRADA
GRANDE’),(370,5,61,3,‘TRONADORA’),(371,5,61,4,‘SANTA
ROSA’),(372,5,61,5,‘LÍBANO’),(373,5,61,6,‘TIERRAS
MORENAS’),(374,5,61,7,‘ARENAL’),(375,5,62,1,‘CARMONA’),(376,5,62,2,‘SANTA
RITA’),(377,5,62,3,‘ZAPOTAL’),(378,5,62,4,‘SAN
PABLO’),(379,5,62,5,‘PORVENIR’),(380,5,62,6,‘BEJUCO’),(381,5,63,1,‘LA
CRUZ’),(382,5,63,2,‘SANTA CECILIA’),(383,5,63,3,‘LA
GARITA’),(384,5,63,4,‘SANTA
ELENA’),(385,5,64,1,‘HOJANCHA’),(386,5,64,2,‘MONTE
ROMO’),(387,5,64,3,‘PUERTO
CARRILLO’),(388,5,64,4,‘HUACAS’),(389,6,65,1,‘PUNTARENAS’),(390,6,65,2,‘PITAHAYA’),(391,6,65,3,‘CHOMES’),(392,6,65,4,‘LEPANTO’),(393,6,65,5,‘PAQUERA’),(394,6,65,6,‘MANZANILLO’),(395,6,65,7,‘GUACIMAL’),(396,6,65,8,‘BARRANCA’),(397,6,65,9,‘MONTE
VERDE’),(398,6,65,11,‘CÓBANO’),(399,6,65,12,‘CHACARITA’),(400,6,65,13,‘CHIRA’),(401,6,65,14,‘ACAPULCO’),(402,6,65,15,‘EL
ROBLE’),(403,6,65,16,‘ARANCIBIA’),(404,6,66,1,‘ESPÍRITU
SANTO’),(405,6,66,2,‘SAN JUAN
GRANDE’),(406,6,66,3,‘MACACONA’),(407,6,66,4,‘SAN
RAFAEL’),(408,6,66,5,‘SAN
JERÓNIMO’),(409,6,66,6,‘CALDERA’),(410,6,67,1,‘BUENOS
AIRES’),(411,6,67,2,‘VOLCÁN’),(412,6,67,3,‘POTRERO
GRANDE’),(413,6,67,4,‘BORUCA’),(414,6,67,5,‘PILAS’),(415,6,67,6,‘COLINAS’),(416,6,67,7,‘CHÁNGUENA’),(417,6,67,8,‘BIOLLEY’),(418,6,67,9,‘BRUNKA’),(419,6,68,1,‘MIRAMAR’),(420,6,68,2,‘LA
UNIÓN’),(421,6,68,3,‘SAN ISIDRO’),(422,6,69,1,‘PUERTO
CORTÉS’),(423,6,69,2,‘PALMAR’),(424,6,69,3,‘SIERPE’),(425,6,69,4,‘BAHÍA
BALLENA’),(426,6,69,5,‘PIEDRAS BLANCAS’),(427,6,69,6,‘BAHÍA
DRAKE’),(428,6,70,1,‘QUEPOS’),(429,6,70,2,‘SAVEGRE’),(430,6,70,3,‘NARANJITO’),(431,6,71,1,‘GOLFITO’),(432,6,71,2,‘PUERTO
JIMÉNEZ’),(433,6,71,3,‘GUAYCARÁ’),(434,6,71,4,‘PAVÓN’),(435,6,72,1,‘SAN
VITO’),(436,6,72,2,‘SABALITO’),(437,6,72,3,‘AGUABUENA’),(438,6,72,4,‘LIMONCITO’),(439,6,72,5,‘PITTIER’),(440,6,72,6,‘GUTIERREZ
BRAUN’),(441,6,73,1,‘PARRITA’),(442,6,74,1,‘CORREDOR’),(443,6,74,2,‘LA
CUESTA’),(444,6,74,3,‘CANOAS’),(445,6,74,4,‘LAUREL’),(446,6,75,1,‘JACÓ’),(447,6,75,2,‘TÁRCOLES’),(448,7,76,1,‘LIMÓN’),(449,7,76,2,‘VALLE
LA
ESTRELLA’),(450,7,76,4,‘MATAMA’),(451,7,77,1,‘GUÁPILES’),(452,7,77,2,‘JIMÉNEZ’),(453,7,77,3,‘RITA’),(454,7,77,4,‘ROXANA’),(455,7,77,5,‘CARIARI’),(456,7,77,6,‘COLORADO’),(457,7,77,7,‘LA
COLONIA’),(458,7,78,1,‘SIQUIRRES’),(459,7,78,2,‘PACUARITO’),(460,7,78,3,‘FLORIDA’),(461,7,78,4,‘GERMANIA’),(462,7,78,5,‘EL
CAIRO’),(463,7,78,6,‘ALEGRÍA’),(464,7,79,1,‘BRATSI’),(465,7,79,2,‘SIXAOLA’),(466,7,79,3,‘CAHUITA’),(467,7,79,4,‘TELIRE’),(468,7,80,1,‘MATINA’),(469,7,80,2,‘BATÁN’),(470,7,80,3,‘CARRANDI’),(471,7,81,1,‘GUÁCIMO’),(472,7,81,2,‘MERCEDES’),(473,7,81,3,‘POCORA’),(474,7,81,4,‘RÍO
JIMÉNEZ’),(475,7,81,5,‘DUACARÍ’);


  -------------------------------------
  – Table structure for table estados
  -------------------------------------

DROP TABLE IF EXISTS estados;

CREATE TABLE estados ( ID int NOT NULL AUTO_INCREMENT, Descripcion
varchar(45) DEFAULT NULL, PRIMARY KEY (ID) ) ENGINE=InnoDB
AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  ----------------------------------
  – Dumping data for table estados
  ----------------------------------

INSERT INTO estados VALUES (1,‘No Emitida Aun’),(2,‘Nuevo
Ingreso’),(3,‘En Proceso’),(4,‘Finalizado’),(5,‘Pagado - En espera de
entrega’),(6,‘Entregado al
cliente’),(7,‘Cancelado’),(8,‘draft’),(9,‘created’),(10,‘in_progress’),(11,‘completed’),(12,‘cancelled’);


  ---------------------------------------------
  – Table structure for table orden_historial
  ---------------------------------------------

DROP TABLE IF EXISTS orden_historial;

CREATE TABLE orden_historial ( ID int NOT NULL AUTO_INCREMENT, OrdenID
int NOT NULL, Accion varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
Detalle text COLLATE utf8mb4_unicode_ci, UsuarioID int DEFAULT NULL,
Fecha timestamp NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (ID), KEY
idx_historial_orden (OrdenID), KEY idx_historial_usuario (UsuarioID) )
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



  ---------------------------------------------
  – Table structure for table orden_servicios
  ---------------------------------------------

DROP TABLE IF EXISTS orden_servicios;

CREATE TABLE orden_servicios ( ID int NOT NULL AUTO_INCREMENT, OrdenID
int NOT NULL, ServicioID int NOT NULL, Descripcion varchar(100) COLLATE
utf8mb4_unicode_ci DEFAULT NULL, Precio decimal(10,2) NOT NULL, Cantidad
int DEFAULT ‘1’, Subtotal decimal(10,2) NOT NULL, PRIMARY KEY (ID), KEY
OrdenID (OrdenID) ) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



  -------------------------------------
  – Table structure for table ordenes
  -------------------------------------

DROP TABLE IF EXISTS ordenes;

CREATE TABLE ordenes ( ID int unsigned NOT NULL AUTO_INCREMENT,
ClienteID int DEFAULT NULL, Monto decimal(10,2) DEFAULT ‘0.00’,
Descuento decimal(10,2) DEFAULT ‘0.00’, Impuesto decimal(10,2) DEFAULT
‘0.13’, Estado tinyint DEFAULT ‘1’, FechaIngreso timestamp NULL DEFAULT
CURRENT_TIMESTAMP, FechaProceso timestamp NULL DEFAULT NULL,
FechaTerminado timestamp NULL DEFAULT NULL, FechaCierre timestamp NULL
DEFAULT NULL, FacturaNo varchar(45) DEFAULT NULL, Categoria int DEFAULT
‘0’, TipoServicio int DEFAULT ‘1’, VehiculoID int DEFAULT ‘0’,
ServiciosJSON json DEFAULT NULL, UpdatedAt timestamp NULL DEFAULT
CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP, Observaciones text,
PRIMARY KEY (ID), KEY main
(ID,ClienteID,Estado,Categoria,VehiculoID,TipoServicio) ) ENGINE=InnoDB
AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

 
  --------------------------------------
  – Table structure for table paquetes
  --------------------------------------

DROP TABLE IF EXISTS paquetes;

CREATE TABLE paquetes ( ID int unsigned NOT NULL AUTO_INCREMENT, ItemsID
tinyint DEFAULT NULL, Descripcion varchar(45) NOT NULL, PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -----------------------------------
  – Dumping data for table paquetes
  -----------------------------------


  -------------------------------------
  – Table structure for table precios
  -------------------------------------

DROP TABLE IF EXISTS precios;

CREATE TABLE precios ( ID int unsigned NOT NULL AUTO_INCREMENT,
TipoCategoriaID int unsigned NOT NULL, ServicioID int unsigned NOT NULL,
Descripcion text, Precio double NOT NULL DEFAULT ‘0’, Descuento
decimal(10,0) NOT NULL DEFAULT ‘0’, Impuesto decimal(10,0) NOT NULL
DEFAULT ‘13’, PackageID tinyint NOT NULL DEFAULT ‘0’, DateTime timestamp
NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (ID), UNIQUE KEY
uk_servicio_categoria (ServicioID,TipoCategoriaID), KEY idx_servicio
(ServicioID), KEY idx_categoria (TipoCategoriaID) ) ENGINE=InnoDB
AUTO_INCREMENT=233 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  ----------------------------------
  – Dumping data for table precios
  ----------------------------------


  ---------------------------------------
  – Table structure for table provincia
  ---------------------------------------

DROP TABLE IF EXISTS provincia;

CREATE TABLE provincia ( id int NOT NULL, codigo int DEFAULT NULL,
provincia varchar(45) DEFAULT NULL, PRIMARY KEY (id) ) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  ------------------------------------
  – Dumping data for table provincia
  ------------------------------------

INSERT INTO provincia VALUES (1,1,‘San
José’),(2,2,‘Alajuela’),(3,3,‘Cartago’),(4,4,‘Heredia’),(5,5,‘Guanacaste’),(6,6,‘Puntarenas’),(7,7,‘Limón’);



  -----------------------------------
  – Table structure for table roles
  -----------------------------------

DROP TABLE IF EXISTS roles;

CREATE TABLE roles ( ID int NOT NULL AUTO_INCREMENT, Descripcion
varchar(90) DEFAULT ‘0’, Reportes tinyint DEFAULT ‘0’, Usuarios tinyint
DEFAULT ‘0’, Clientes tinyint DEFAULT ‘0’, Ordenes tinyint DEFAULT ‘0’,
Actividad tinyint DEFAULT ‘0’, Financiero tinyint DEFAULT ‘0’, PRIMARY
KEY (ID) ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

  --------------------------------
  – Dumping data for table roles
  --------------------------------

INSERT INTO roles VALUES
(1,‘Administrador’,1,1,1,1,1,1),(2,‘Asistente’,1,0,1,1,1,0),(3,‘Coodinador’,0,0,0,1,1,0);


  ---------------------------------------
  – Table structure for table servicios
  ---------------------------------------

DROP TABLE IF EXISTS servicios;

CREATE TABLE servicios ( ID int unsigned NOT NULL AUTO_INCREMENT,
Descripcion varchar(45) NOT NULL, CategoriaServicioID tinyint DEFAULT
‘1’, PRIMARY KEY (ID) ) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  ------------------------------------
  – Dumping data for table servicios
  ------------------------------------

INSERT INTO servicios VALUES (11,‘Lavado Exteriores’,1),(12,‘Limpieza
Interior’,1),(13,‘Lavado Chasis’,1);


  --------------------------------------
  – Table structure for table usuarios
  --------------------------------------

DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios ( id bigint unsigned NOT NULL AUTO_INCREMENT, name
varchar(255) CHARACTER



  ---------------------------------------
  – Table structure for table vehiculos
  ---------------------------------------

DROP TABLE IF EXISTS vehiculos;

CREATE TABLE vehiculos ( ID int NOT NULL AUTO_INCREMENT, ClienteID int
NOT NULL, Marca varchar(20) NOT NULL, Modelo varchar(25) NOT NULL, Year
varchar(4) NOT NULL, Placa varchar(7) NOT NULL, CategoriaVehiculo
tinyint DEFAULT ‘0’, FechaRegistro datetime DEFAULT CURRENT_TIMESTAMP,
active tinyint DEFAULT ‘1’, Color varchar(20) DEFAULT NULL,
Observaciones varchar(100) DEFAULT NULL, PRIMARY KEY (ID), UNIQUE KEY
Placa (Placa), KEY ClienteID (ClienteID) ) ENGINE=InnoDB
AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


