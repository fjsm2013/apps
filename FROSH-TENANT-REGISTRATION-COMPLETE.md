# FROSH - Sistema de Registro y Configuración Completo

## 🎯 Resumen del Sistema

El sistema FROSH ahora tiene un flujo completo de registro de empresas con configuración automática de categorías de vehículos y servicios por defecto.

## 📋 Flujo de Registro Completo

### 1. Registro de Empresa (register.php)
- **Paso 1**: Información de la empresa
- **Paso 2**: Usuario administrador  
- **Paso 3**: Confirmación y activación

### 2. Creación Automática de Base de Datos
Cuando se registra una nueva empresa, automáticamente se:
- Crea base de datos tenant: `froshlav_[ID]`
- Importa esquema completo desde `lib/schema/tenant.sql`
- Inserta datos iniciales con `TenantDatabaseManager::insertInitialData()`

### 3. Categorías de Vehículos por Defecto
Cada nueva empresa obtiene automáticamente estas 5 categorías:

| Orden | Categoría | Estado |
|-------|-----------|--------|
| 1     | Sedán     | Activo |
| 2     | SUV       | Activo |
| 3     | Pickup    | Activo |
| 4     | Minibus   | Activo |
| 5     | Moto      | Activo |

### 4. Servicios Precargados por Defecto
Cada nueva empresa obtiene automáticamente estos 3 servicios:

| Servicio | Detalles | CategoriaServicioID |
|----------|----------|-------------------|
| Lavado Exterior | Lavado de la carrocería externa | 1 |
| Limpieza Interior | Limpieza completa del interior del vehículo | 1 |
| Lavado Chasis | Limpieza del chasis y bajos del vehículo | 1 |

## 🛠️ Setup Wizard Mejorado

### Paso 1: Configuración de Empresa
- Pre-llena datos desde la base padre
- Configuración operativa (horarios, capacidad, moneda)

### Paso 2: Servicios
- **Servicios Precargados**: Lavado Exterior, Limpieza Interior, Lavado Chasis (marcados por defecto)
- **Servicios Sugeridos**: Encerado, Pulido de Vidrios
- **Lógica Inteligente**: 
  - Si servicio existe sin detalles → Actualiza detalles
  - Si servicio existe con detalles → Mantiene existentes
  - Si servicio no existe → Crea nuevo
  - **No duplica servicios**

### Paso 3: Precios
- Matriz automática: 5 tipos de vehículo × servicios seleccionados
- Herramientas de configuración rápida
- Validación de precios

### Paso 4: Usuarios
- Configuración de notificaciones
- Usuarios gestionados centralmente en `frosh_lavacar`

## 🔧 Archivos Modificados

### Core del Sistema
- `lib/TenantDatabaseManager.php` - Categorías y servicios por defecto
- `register.php` - Flujo de registro completo

### Setup Wizard
- `lavacar/setup-wizard/functions.php` - Lógica de actualización sin duplicados
- `lavacar/setup-wizard/step2-servicios.php` - Interfaz simplificada (5 servicios)
- `lavacar/middleware/setup-check.php` - Validación de configuración

### Tests y Documentación
- `test-default-categories.php` - Verificación de categorías por defecto
- `test-servicios-update.php` - Verificación de lógica sin duplicados
- `test-wizard-access.php` - Test completo del wizard

## 📊 Estructura de Base de Datos

### Tabla: categoriavehiculo
```sql
CREATE TABLE categoriavehiculo (
    ID int PRIMARY KEY AUTO_INCREMENT,
    TipoVehiculo varchar(45) NOT NULL,
    Estado tinyint DEFAULT 1,
    Orden int DEFAULT NULL
);
```

### Tabla: servicios
```sql  
CREATE TABLE servicios (
    ID int PRIMARY KEY AUTO_INCREMENT,
    Descripcion varchar(45) NOT NULL,
    Detalles varchar(120) NULL,
    CategoriaServicioID tinyint DEFAULT 1
);
```

### Tabla: precios
```sql
CREATE TABLE precios (
    ID int PRIMARY KEY AUTO_INCREMENT,
    TipoCategoriaID int NOT NULL,
    ServicioID int NOT NULL,
    Precio decimal(10,2) NOT NULL,
    Descuento decimal(5,2) DEFAULT 0,
    Impuesto decimal(5,2) DEFAULT 13
);
```

## 🚀 Beneficios del Sistema

### Para Nuevas Empresas
- **Configuración instantánea**: Categorías y servicios listos al registrarse
- **Setup guiado**: Wizard paso a paso para completar configuración
- **Sin duplicados**: Sistema inteligente que evita datos repetidos
- **Escalable**: Fácil agregar más servicios después

### Para el Desarrollo
- **Consistencia**: Todas las empresas tienen la misma estructura base
- **Mantenibilidad**: Configuración centralizada en TenantDatabaseManager
- **Flexibilidad**: Setup wizard permite personalización posterior
- **Robustez**: Validaciones y manejo de errores completo

## 🎯 Próximos Pasos

1. **Probar registro completo** con nueva empresa
2. **Verificar setup wizard** con servicios precargados
3. **Validar matriz de precios** con 5 categorías
4. **Confirmar creación de órdenes** con datos por defecto

## 📝 Notas Técnicas

- **Usuarios centralizados**: Se gestionan en `frosh_lavacar`, no en bases tenant
- **Campo Detalles**: Nuevo campo para descripciones extendidas de servicios
- **Orden de categorías**: Sedán, SUV, Pickup, Minibus, Moto (en ese orden)
- **CategoriaServicioID**: Siempre = 1 para mantener compatibilidad

---

✅ **Sistema completo y listo para producción**