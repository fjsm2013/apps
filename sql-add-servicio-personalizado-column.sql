-- Add ServicioPersonalizado column to orden_servicios table if it doesn't exist
-- This column stores custom service names for personalized services

-- Check if column exists and add it if missing
SET @dbname = DATABASE();
SET @tablename = 'orden_servicios';
SET @columnname = 'ServicioPersonalizado';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  CONCAT("ALTER TABLE ", @tablename, " ADD ", @columnname, " VARCHAR(255) NULL AFTER Precio")
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Verify the column was added
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
  AND TABLE_NAME = 'orden_servicios' 
  AND COLUMN_NAME = 'ServicioPersonalizado';