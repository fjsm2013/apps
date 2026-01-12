# 🎨 FROSH Global CSS Integration Guide

## Overview
This guide explains how to implement the FROSH global CSS system across all tenant interfaces while maintaining security, consistency, and the established dark visual identity.

## 📁 File Structure

```
lib/css/
├── frosh-global.css        # Core global styles and components
├── frosh-security.css      # Security-focused styles and indicators
├── frosh-components.css    # Car wash specific components (existing)
├── FROSH-DESIGN-SYSTEM.md # Design system documentation (existing)
└── FROSH-INTEGRATION-GUIDE.md # This file
```

## 🚀 Implementation Steps

### 1. Update Header Files

**For Tenant Applications (`lavacar/partials/header.php`):**
```php
<!-- FROSH Global CSS Framework -->
<link rel="stylesheet" href="<?= LAVACAR_BASE_URL ?>/../lib/css/frosh-global.css">
<link rel="stylesheet" href="<?= LAVACAR_BASE_URL ?>/../lib/css/frosh-security.css">
<link rel="stylesheet" href="<?= LAVACAR_BASE_URL ?>/../lib/css/frosh-components.css">
```

**For Login and Public Pages:**
```html
<!-- FROSH Global CSS Framework -->
<link rel="stylesheet" href="lib/css/frosh-global.css">
<link rel="stylesheet" href="lib/css/frosh-security.css">
```

### 2. Security Indicators

Add security indicators to all authenticated pages:

```html
<!-- Secure connection indicator -->
<div class="secure-connection">
    <span class="security-indicator"></span>
    Conexión Segura
</div>

<!-- Tenant boundary indicator -->
<div class="tenant-boundary" data-tenant="<?= htmlspecialchars($user['company']['name']) ?>">
    <!-- Page content -->
</div>
```

### 3. Form Security Enhancements

**Password Fields:**
```html
<div class="form-group">
    <label class="form-label">Contraseña</label>
    <input type="password" class="form-control" id="password" required>
    <div class="password-strength" id="passwordStrength">
        <div class="password-strength-bar"></div>
    </div>
</div>
```

**Sensitive Data Protection:**
```html
<span class="sensitive-data no-select">
    <?= htmlspecialchars($sensitiveInfo) ?>
</span>
```

### 4. Role-Based Styling

Apply role-based visual indicators:

```html
<div class="card role-<?= strtolower($user['role_name']) ?>">
    <div class="card-header">
        <h5>User Information</h5>
        <span class="security-badge-form secure">Verified</span>
    </div>
    <div class="card-body">
        <!-- Content -->
    </div>
</div>
```

### 5. Activity Monitoring

Add activity indicators for real-time feedback:

```html
<div class="user-avatar activity-indicator">
    <img src="avatar.jpg" alt="User">
    <!-- Activity dot will be added automatically -->
</div>
```

## 🎯 Component Usage

### Buttons (Marketing Colors)

```html
<!-- Primary actions -->
<button class="btn btn-frosh-primary">Crear Orden</button>

<!-- Marketing approved colors -->
<button class="btn btn-report-info">Filtrar</button>
<button class="btn btn-report-warning">Advertencia</button>
<button class="btn btn-report-success">Exportar</button>

<!-- Outline variants -->
<button class="btn btn-outline-report-info">Ver Detalles</button>
```

### Badges

```html
<!-- Status indicators -->
<span class="badge badge-report-success">Activo</span>
<span class="badge badge-report-warning">Pendiente</span>
<span class="badge badge-report-info">En Proceso</span>

<!-- Security badges -->
<span class="badge-frosh-primary">Admin</span>
<span class="security-badge-form secure">Verificado</span>
```

### Cards

```html
<div class="card">
    <div class="card-header">
        <h5>Título</h5>
        <span class="last-activity">Última actividad: hace 5 min</span>
    </div>
    <div class="card-body">
        <p>Contenido de la tarjeta</p>
    </div>
    <div class="card-footer">
        <button class="btn btn-report-info btn-sm">Acción</button>
    </div>
</div>
```

### Alerts

```html
<!-- Security alerts -->
<div class="alert alert-danger">
    <i class="fas fa-exclamation-triangle me-2"></i>
    Mensaje de seguridad importante
</div>

<!-- Success messages -->
<div class="alert alert-success">
    <i class="fas fa-check-circle me-2"></i>
    Operación completada exitosamente
</div>
```

## 🔒 Security Features

### 1. Session Management

```javascript
// Add to your JavaScript
class SecurityManager {
    constructor() {
        this.initSessionWarning();
        this.initWindowBlur();
    }
    
    initSessionWarning() {
        // Show warning 5 minutes before session expires
        setTimeout(() => {
            this.showSessionWarning();
        }, (sessionTimeout - 300) * 1000);
    }
    
    initWindowBlur() {
        window.addEventListener('blur', () => {
            document.body.classList.add('window-blur');
        });
        
        window.addEventListener('focus', () => {
            document.body.classList.remove('window-blur');
        });
    }
    
    showSessionWarning() {
        const warning = document.createElement('div');
        warning.className = 'session-warning';
        warning.innerHTML = `
            <h4>Sesión por Expirar</h4>
            <p>Su sesión expirará en 5 minutos</p>
            <button class="btn btn-report-warning" onclick="extendSession()">
                Extender Sesión
            </button>
        `;
        document.body.appendChild(warning);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    new SecurityManager();
});
```

### 2. Password Strength Validation

```javascript
function validatePasswordStrength(password) {
    const strength = document.getElementById('passwordStrength');
    const strengthBar = strength.querySelector('.password-strength-bar');
    
    let score = 0;
    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;
    
    const levels = ['', 'weak', 'fair', 'good', 'strong'];
    strength.className = `password-strength password-strength-${levels[score]}`;
}
```

### 3. Data Classification

```html
<!-- Classify data sensitivity -->
<div class="data-confidential">
    <h5>Información Confidencial</h5>
    <p class="sensitive-data">Datos sensibles aquí</p>
</div>

<div class="data-internal">
    <h5>Información Interna</h5>
    <p>Datos internos de la empresa</p>
</div>
```

## 📱 Responsive Considerations

The global CSS includes comprehensive responsive design:

- **Mobile-first approach**
- **Touch-friendly interactions**
- **Optimized security indicators for mobile**
- **Accessible navigation patterns**

## ♿ Accessibility Features

### Built-in Accessibility:

- **High contrast mode support**
- **Reduced motion preferences**
- **Keyboard navigation focus indicators**
- **Screen reader compatible security context**

### Implementation:

```html
<!-- Screen reader security context -->
<span class="sr-only-security">
    Área segura - Datos protegidos por encriptación
</span>

<!-- Accessible form labels -->
<label for="username" class="form-label">
    Usuario
    <span class="security-badge-form secure">Verificado</span>
</label>
```

## 🎨 Customization

### Tenant-Specific Branding:

```css
/* Override in tenant-specific CSS */
:root {
    --tenant-primary: #your-brand-color;
    --tenant-secondary: #your-secondary-color;
}

.tenant-custom .btn-frosh-primary {
    background: var(--tenant-primary);
    border-color: var(--tenant-primary);
}
```

### Dark Mode Support:

```css
@media (prefers-color-scheme: dark) {
    :root {
        --content-bg: #1f2937;
        --content-text: #f9fafb;
        --content-border: #374151;
    }
}
```

## 🔧 Performance Optimization

### CSS Loading Strategy:

1. **Critical CSS inline** for above-the-fold content
2. **Preload global CSS** for faster rendering
3. **Lazy load** component-specific styles

```html
<!-- Preload critical styles -->
<link rel="preload" href="lib/css/frosh-global.css" as="style">
<link rel="stylesheet" href="lib/css/frosh-global.css">
```

### Bundle Optimization:

- **Minify CSS** in production
- **Remove unused styles** with PurgeCSS
- **Use CSS custom properties** for dynamic theming

## 📊 Monitoring and Analytics

### CSS Performance Metrics:

- **First Contentful Paint (FCP)**
- **Largest Contentful Paint (LCP)**
- **Cumulative Layout Shift (CLS)**

### Security Metrics:

- **Session timeout compliance**
- **Password strength adoption**
- **Security indicator visibility**

## 🚀 Migration Checklist

- [ ] Update all header files with new CSS imports
- [ ] Add security indicators to authenticated pages
- [ ] Implement password strength validation
- [ ] Add role-based styling to user interfaces
- [ ] Test responsive design on all devices
- [ ] Validate accessibility compliance
- [ ] Performance test with new CSS bundle
- [ ] Update documentation for development team

## 📞 Support

For questions about implementing the FROSH global CSS system:

1. **Check the design system documentation** (`FROSH-DESIGN-SYSTEM.md`)
2. **Review component examples** in existing implementations
3. **Test security features** in development environment
4. **Validate accessibility** with screen readers and keyboard navigation

---

**Remember**: The FROSH global CSS system is designed to maintain security, consistency, and user experience across all tenant interfaces while preserving the established dark visual identity and marketing-approved color scheme.