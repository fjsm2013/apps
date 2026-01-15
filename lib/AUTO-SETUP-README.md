# FROSH Auto-Setup System

## Descripción

El sistema de auto-setup verifica automáticamente si la base de datos master (`frosh_lavacar`) existe cuando se carga la aplicación por primera vez en un nuevo servidor. Si no existe, la crea automáticamente con todas las tablas necesarias.

## Cómo Funciona

1. **Primera carga**: Cuando se accede a cualquier página de la aplicación por primera vez
2. **Verificación**: El sistema verifica si existe el archivo `.setup_complete` en `lib/`
3. **Creación**: Si no existe, verifica si la base de datos `frosh_lavacar` existe
4. **Setup**: Si la base de datos no existe, la crea con todas las tablas del schema
5. **Marca**: Crea el archivo `.setup_complete` para no repetir el proceso

## Archivos Involucrados

- **lib/check-setup.php**: Verifica si el setup es necesario
- **lib/auto-setup.php**: Clase que maneja la creación de la base de datos
- **lib/config.php**: Incluye check-setup.php al inicio
- **lib/schema/master.sql**: Schema de la base de datos master
- **lib/.setup_complete**: Archivo de marca (se crea automáticamente)

## Configuración

El sistema usa las credenciales de base de datos definidas en `lib/config.php`:



## Tablas Creadas Automáticamente

- empresas
- users
- planes (con 3 planes por defecto: Bronce, Plata, Oro)
- suscripciones
- password_reset_tokens
- roles (con 3 roles por defecto: Administrador, Asistente, Operador)
- Y todas las demás tablas del schema master.sql

## Resetear el Setup

Si necesitas que el sistema vuelva a ejecutar el setup:

1. Elimina el archivo `lib/.setup_complete`
2. (Opcional) Elimina la base de datos `frosh_lavacar`
3. Recarga cualquier página de la aplicación

## Logs

Los errores y mensajes del auto-setup se registran en el error log de PHP.

## Notas Importantes

- El setup solo se ejecuta UNA VEZ por instalación
- Si hay un error, se crea el archivo `lib/.setup_error` con detalles
- El sistema NO sobrescribe bases de datos existentes
- Es seguro ejecutar en producción (no afecta datos existentes)
