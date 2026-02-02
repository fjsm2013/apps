# Sistema de Solicitud de Demo - FROSH

## Descripción

Sistema completo de formulario de solicitud de demo con captcha anti-bot y notificaciones por email usando templates HTML profesionales.

## Archivos Creados

### 1. **process-demo-request.php**
- Procesa las solicitudes de demo
- Valida captcha y campos requeridos
- Guarda en base de datos
- Envía emails con templates HTML

### 2. **generate-captcha.php**
- Genera preguntas matemáticas simples (ej: "¿Cuánto es 5 + 3?")
- Almacena respuesta en sesión
- Previene spam de bots

### 3. **lib/templates/demo-request.htm**
- Template HTML profesional para emails
- Diseño responsive
- Colores corporativos de FROSH

### 4. **Tabla en Base de Datos**
```sql
CREATE TABLE demo_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    empresa VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefono VARCHAR(50) NOT NULL,
    tipo_negocio VARCHAR(100) NOT NULL,
    vehiculos_diarios VARCHAR(50) NOT NULL,
    mensaje TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Cómo Funciona

### 1. Usuario llena el formulario
- Nombre completo
- Empresa
- Email
- Teléfono
- Tipo de negocio
- Vehículos diarios promedio
- Mensaje (opcional)
- **Captcha matemático** (ej: "¿Cuánto es 7 + 4?")

### 2. Validación
- Verifica campos requeridos
- Valida formato de email
- **Verifica respuesta del captcha**
- Si falla, recarga nuevo captcha

### 3. Guardado
- Crea tabla `demo_requests` si no existe
- Guarda solicitud en base de datos
- Timestamp automático

### 4. Notificaciones por Email

#### Email al Cliente (Confirmación)
- ✅ Confirmación de solicitud recibida
- 📋 Resumen de información enviada
- 📞 Próximos pasos
- 📧 Información de contacto

#### Email al Equipo FROSH (Notificación)
- 🎯 Información completa del prospecto
- 📊 Datos de contacto con links directos
- 📅 Fecha y hora de solicitud
- ✉️ Botón para responder directamente

## Captcha Anti-Bot

### Características
- **Simple pero efectivo**: Preguntas matemáticas básicas
- **No requiere librerías externas**: Implementación nativa PHP
- **User-friendly**: Fácil para humanos, difícil para bots
- **Se recarga automáticamente**: Después de cada intento

### Ejemplo de Preguntas
- ¿Cuánto es 3 + 5?
- ¿Cuánto es 8 + 2?
- ¿Cuánto es 6 + 9?

## Emails Enviados

### 1. Al Cliente
**Asunto:** "Confirmación de Solicitud de Demo - FROSH LavaCar App"
**Para:** Email del cliente
**Contenido:**
- Confirmación de recepción
- Resumen de solicitud
- Próximos pasos
- Información de contacto

### 2. Al Equipo FROSH (Principal)
**Asunto:** "🎯 Nueva Solicitud de Demo - [Nombre Empresa]"
**Para:** froshsystems@gmail.com
**Contenido:**
- Información completa del prospecto
- Links directos (email, teléfono)
- Mensaje del cliente
- Fecha/hora de solicitud
- Próximos pasos sugeridos

### 3. Al Equipo FROSH (Copia)
**Asunto:** "🎯 Nueva Solicitud de Demo - [Nombre Empresa]"
**Para:** myinterpal@gmail.com
**Contenido:** Mismo que email principal

## Integración en index.php

### Formulario HTML
- Campos con atributo `name` para POST
- Campo captcha con pregunta dinámica
- Botón con spinner de carga

### JavaScript
- Carga captcha al iniciar página
- Envío AJAX del formulario
- Manejo de respuestas (éxito/error)
- Recarga automática de captcha
- Feedback visual al usuario

## Configuración

### Emails de Notificación
Editar en `process-demo-request.php`:
```php
// Email principal
['froshsystems@gmail.com', 'Equipo FROSH']

// Email secundario
['myinterpal@gmail.com', 'Administración']
```

### Template de Email
Editar `lib/templates/demo-request.htm` para personalizar:
- Colores corporativos
- Logo
- Información de contacto
- Footer

## Seguridad

✅ **Captcha matemático** previene bots
✅ **Validación server-side** de todos los campos
✅ **Sanitización de datos** antes de guardar
✅ **Prepared statements** previenen SQL injection
✅ **Validación de email** con filter_var
✅ **Session-based captcha** no se puede adivinar
✅ **Auto-creación de tabla** segura

## Testing

### Probar el Formulario
1. Ir a `index.php#contacto`
2. Llenar todos los campos
3. Resolver el captcha
4. Enviar
5. Verificar:
   - Mensaje de éxito
   - Email de confirmación al cliente
   - Email de notificación al equipo

### Probar el Captcha
1. Intentar enviar con respuesta incorrecta
2. Debe mostrar error
3. Captcha se recarga automáticamente
4. Intentar con respuesta correcta
5. Debe procesar correctamente

## Logs

Los errores se registran en el error log de PHP:
```php
error_log('Demo request error: ' . $e->getMessage());
error_log('Error enviando correo de demo: ' . $e->getMessage());
```

## Mantenimiento

### Ver Solicitudes
```sql
SELECT * FROM demo_requests ORDER BY created_at DESC;
```

### Limpiar Solicitudes Antiguas
```sql
DELETE FROM demo_requests WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);
```

### Estadísticas
```sql
SELECT 
    tipo_negocio, 
    COUNT(*) as total 
FROM demo_requests 
GROUP BY tipo_negocio 
ORDER BY total DESC;
```

## Mejoras Futuras

- [ ] Panel de administración para ver solicitudes
- [ ] Integración con CRM
- [ ] Recordatorios automáticos de seguimiento
- [ ] Analytics de conversión
- [ ] A/B testing de formulario
- [ ] Captcha con imágenes (opcional)
- [ ] Integración con WhatsApp
- [ ] SMS de confirmación

## Soporte

Para problemas o preguntas:
- Email: froshsystems@gmail.com
- Teléfono: +506 6395 7241
