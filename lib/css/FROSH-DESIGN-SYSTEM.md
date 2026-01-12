# 🎨 FROSH Design System

## Identidad Visual
- **Logo**: Cuadro negro con letras blancas "FROSH"
- **Colores principales**: Negro y escala de grises
- **Fondos de contenido**: Blancos para máxima legibilidad

## 📍 Ubicación de Estilos
Los estilos FROSH están centralizados en:
- **Global**: `lavacar/partials/header.php` (Botones y badges principales)
- **Componentes**: `lib/css/frosh-components.css` (Cards, layouts, utilidades)

## 🎯 Clases de Botones FROSH

### **Acciones Principales**
```html
<!-- Para acciones más importantes -->
<button class="btn btn-frosh-primary">Crear Orden</button>
<button class="btn btn-outline-frosh-primary">Ver Detalles</button>
```

### **Acciones Secundarias**
```html
<!-- Para acciones importantes pero no críticas -->
<button class="btn btn-frosh-dark">Editar</button>
<button class="btn btn-outline-frosh-dark">Modificar</button>
```

### **Acciones Terciarias**
```html
<!-- Para acciones de soporte -->
<button class="btn btn-frosh-gray">Ver Más</button>
<button class="btn btn-outline-frosh-gray">Cambiar Estado</button>
```

### **Acciones Ligeras**
```html
<!-- Para acciones sutiles -->
<button class="btn btn-frosh-light">Cancelar</button>
```

## 🏷️ Badges FROSH

```html
<span class="badge badge-frosh-primary">Crítico</span>
<span class="badge badge-frosh-dark">Activo</span>
<span class="badge badge-frosh-gray">Pendiente</span>
<span class="badge badge-frosh-light">Inactivo</span>
```

## 🃏 Componentes FROSH

### **Cards**
```html
<!-- Card con header oscuro -->
<div class="card card-frosh-light">
    <div class="card-header">Título</div>
    <div class="card-body">Contenido</div>
</div>

<!-- Card completamente oscura -->
<div class="card card-frosh-dark">
    <div class="card-header">Título</div>
    <div class="card-body">Contenido</div>
</div>
```

### **Badges**
```html
<span class="badge badge-frosh-dark">Activo</span>
<span class="badge badge-frosh-gray">Pendiente</span>
<span class="badge badge-frosh-light">Inactivo</span>
```

### **Alerts**
```html
<div class="alert alert-frosh-dark">Mensaje importante</div>
<div class="alert alert-frosh-light">Información general</div>
```

## 📋 Guía de Uso por Módulo

### **Administración**
- Botones principales: `btn-frosh-primary`
- Botones de edición: `btn-frosh-dark`
- Botones de estado: `btn-outline-frosh-gray`

### **Órdenes**
- Crear orden: `btn-frosh-primary`
- Editar orden: `btn-frosh-dark`
- Acciones secundarias: `btn-frosh-gray`

### **Reportes**
- Generar reporte: `btn-frosh-primary`
- Filtros: `btn-frosh-dark`
- Exportar: `btn-outline-frosh-dark`

## 🎨 Variables CSS Disponibles

```css
/* Colores FROSH */
--frosh-black: #000000
--frosh-dark: #1a1a1a
--frosh-gray-900: #111827
--frosh-gray-800: #1f2937
--frosh-gray-700: #374151
--frosh-gray-600: #4b5563
--frosh-gray-500: #6b7280
--frosh-gray-400: #9ca3af
--frosh-gray-300: #d1d5db
--frosh-gray-200: #e5e7eb
--frosh-gray-100: #f3f4f6
--frosh-gray-50: #f9fafb

/* Contenido */
--content-bg: #ffffff
--content-bg-alt: #fafbfc
--content-border: #e5e7eb
--content-text: #111827
--content-text-muted: #6b7280
```

## ✅ Mejores Prácticas

1. **Consistencia**: Usa siempre las clases FROSH en lugar de Bootstrap estándar
2. **Jerarquía**: `primary` > `dark` > `gray` > `light`
3. **Accesibilidad**: Todas las clases incluyen estados focus y disabled
4. **Performance**: Las clases están optimizadas y se cargan una sola vez
5. **Mantenimiento**: Cambios globales se hacen en `frosh-components.css`

## 🚀 Implementación Rápida

Para convertir botones existentes:
```html
<!-- Antes -->
<button class="btn btn-primary">Acción</button>

<!-- Después -->
<button class="btn btn-frosh-primary">Acción</button>
```

## 📱 Responsive

Todas las clases FROSH son completamente responsive y funcionan en:
- Desktop
- Tablet  
- Mobile
- Touch devices