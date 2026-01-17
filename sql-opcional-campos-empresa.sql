-- =====================================================
-- CAMPOS OPCIONALES PARA LA TABLA EMPRESAS
-- =====================================================
-- Estos campos son opcionales y mejorarían la experiencia
-- del setup wizard al pre-llenar más información

-- IMPORTANTE: Ejecuta solo los campos que NO tengas ya en tu tabla
-- Puedes verificar qué campos tienes ejecutando: check-master-empresa-fields.php

USE frosh_lavacar;

-- Agregar campo telefono si no existe
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS telefono VARCHAR(50) NULL 
COMMENT 'Teléfono principal de la empresa';

-- Agregar campo email si no existe  
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS email VARCHAR(100) NULL 
COMMENT 'Email corporativo de la empresa';

-- Agregar campo ciudad si no existe
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS ciudad VARCHAR(100) NULL 
COMMENT 'Ciudad donde se ubica la empresa';

-- Agregar campo pais si no existe
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS pais VARCHAR(100) NULL 
COMMENT 'País donde se ubica la empresa';

-- Agregar campo ruc_identificacion si no existe
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS ruc_identificacion VARCHAR(50) NULL 
COMMENT 'RUC o número de identificación fiscal';

-- Agregar campo fecha_actualizacion si no existe
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS fecha_actualizacion TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
COMMENT 'Fecha de última actualización';

-- =====================================================
-- CAMPOS OPERATIVOS POR DEFECTO (NUEVOS)
-- =====================================================
-- Estos campos establecen valores por defecto para todos los lavaderos de la empresa

-- Horarios por defecto
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS hora_apertura_default TIME NULL DEFAULT '08:00:00'
COMMENT 'Hora de apertura por defecto para lavaderos';

ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS hora_cierre_default TIME NULL DEFAULT '18:00:00'
COMMENT 'Hora de cierre por defecto para lavaderos';

-- Días laborales por defecto
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS dias_laborales_default VARCHAR(200) NULL DEFAULT 'Lunes,Martes,Miércoles,Jueves,Viernes,Sábado'
COMMENT 'Días laborales por defecto (separados por comas)';

-- Configuración operativa por defecto
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS capacidad_maxima_default INT NULL DEFAULT 50
COMMENT 'Capacidad máxima diaria por defecto';

ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS tiempo_promedio_default INT NULL DEFAULT 30
COMMENT 'Tiempo promedio por servicio en minutos (por defecto)';

-- Moneda por defecto
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS moneda_default VARCHAR(10) NULL DEFAULT 'CRC'
COMMENT 'Moneda por defecto (CRC, USD, EUR)';

-- Tipo de negocio para mejores defaults
ALTER TABLE empresas 
ADD COLUMN IF NOT EXISTS tipo_negocio ENUM('lavadero_express', 'lavadero_completo', 'detallado_premium', 'cadena_lavaderos') NULL DEFAULT 'lavadero_completo'
COMMENT 'Tipo de negocio para configuraciones inteligentes';

-- =====================================================
-- VERIFICAR ESTRUCTURA ACTUALIZADA
-- =====================================================
-- Ejecuta esto para ver la estructura final:
-- DESCRIBE empresas;

-- =====================================================
-- EJEMPLO DE ACTUALIZACIÓN DE DATOS CON NUEVOS CAMPOS
-- =====================================================
-- Si quieres actualizar datos de ejemplo para tu empresa:
-- (Reemplaza 1 con el ID real de tu empresa)

/*
UPDATE empresas SET 
    telefono = '+506 8888-8888',
    email = 'contacto@miempresa.com', 
    ciudad = 'San José',
    pais = 'Costa Rica',
    ruc_identificacion = '123456789',
    -- Nuevos campos operativos
    hora_apertura_default = '07:00:00',
    hora_cierre_default = '19:00:00', 
    dias_laborales_default = 'Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo',
    capacidad_maxima_default = 80,
    tiempo_promedio_default = 25,
    moneda_default = 'CRC',
    tipo_negocio = 'lavadero_completo'
WHERE id_empresa = 1;
*/

-- =====================================================
-- CONFIGURACIONES INTELIGENTES POR TIPO DE NEGOCIO
-- =====================================================
-- Ejemplos de configuraciones según el tipo de negocio:

/*
-- Lavadero Express (rápido, alto volumen)
UPDATE empresas SET 
    hora_apertura_default = '06:00:00',
    hora_cierre_default = '20:00:00',
    capacidad_maxima_default = 100,
    tiempo_promedio_default = 15,
    tipo_negocio = 'lavadero_express'
WHERE tipo_negocio = 'lavadero_express';

-- Detallado Premium (servicio especializado)  
UPDATE empresas SET
    hora_apertura_default = '08:00:00',
    hora_cierre_default = '17:00:00',
    capacidad_maxima_default = 20,
    tiempo_promedio_default = 90,
    tipo_negocio = 'detallado_premium'
WHERE tipo_negocio = 'detallado_premium';

-- Cadena de Lavaderos (múltiples ubicaciones)
UPDATE empresas SET
    hora_apertura_default = '07:00:00', 
    hora_cierre_default = '19:00:00',
    capacidad_maxima_default = 60,
    tiempo_promedio_default = 30,
    tipo_negocio = 'cadena_lavaderos'
WHERE tipo_negocio = 'cadena_lavaderos';
*/

-- =====================================================
-- NOTAS IMPORTANTES
-- =====================================================
-- 1. Estos campos son OPCIONALES - el wizard funciona sin ellos
-- 2. Si no tienes estos campos, el wizard usa valores por defecto básicos
-- 3. Los usuarios pueden personalizar cada lavadero individualmente
-- 4. Tener estos campos mejora la experiencia con defaults inteligentes
-- 5. El wizard detecta automáticamente qué campos están disponibles

-- =====================================================
-- BENEFICIOS DE LOS NUEVOS CAMPOS OPERATIVOS
-- =====================================================
-- ✅ Configuración más rápida para nuevos lavaderos
-- ✅ Consistencia entre lavaderos de la misma empresa  
-- ✅ Defaults inteligentes según tipo de negocio
-- ✅ Menos campos que llenar manualmente
-- ✅ Configuración centralizada a nivel empresa
-- ✅ Cada lavadero puede personalizar según necesidades locales

-- =====================================================
-- CAMPOS AGREGADOS Y SU PROPÓSITO
-- =====================================================
-- hora_apertura_default: Hora estándar de apertura (ej: 08:00)
-- hora_cierre_default: Hora estándar de cierre (ej: 18:00)  
-- dias_laborales_default: Días que trabajan normalmente
-- capacidad_maxima_default: Cuántos autos pueden atender por día
-- tiempo_promedio_default: Minutos promedio por servicio básico
-- moneda_default: Moneda que usan (CRC, USD, EUR)
-- tipo_negocio: Tipo de lavadero para configuraciones inteligentes