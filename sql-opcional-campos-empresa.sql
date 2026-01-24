-- Script para agregar campos faltantes a las tablas de empresa
-- Ejecutar este script en cada base de datos de empresa (tenant)

-- Agregar campo Detalles a la tabla servicios si no existe
ALTER TABLE servicios 
ADD COLUMN IF NOT EXISTS Detalles TEXT NULL 
AFTER Descripcion;

-- Verificar la estructura actualizada
DESCRIBE servicios;