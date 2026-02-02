# FROSH Order Editing Features Documentation

## Overview
This document describes the new order editing and management features implemented in the FROSH multi-tenant platform.

## Features Implemented

### 1. Custom Services in Order Wizard ✅

**Location:** `lavacar/ordenes/steps/paso_servicios.php`

**Description:** Allows users to add custom services with custom pricing during order creation.

**Features:**
- Add custom service name and price
- Visual distinction (green background, star icon)
- Integrated with order totals calculation
- Validation for required fields
- Remove custom services functionality

**Usage:**
1. Go to Order Wizard → Step 2 (Services)
2. Click "Agregar Servicio" button
3. Enter service name and price
4. Click checkmark to add
5. Custom services appear in the main services table with green styling

### 2. Order Editing Functionality ✅

**Location:** `lavacar/reportes/ordenes-activas.php`

**Description:** Allows editing of existing orders that are not closed (Estado < 4).

**Features:**
- Edit service names and prices
- Add new services to existing orders
- Remove services from orders
- Update order observations
- Real-time total calculation
- Prevents editing of closed orders

**Backend Files:**
- `lavacar/ajax/obtener-orden-editar.php` - Loads order data for editing
- `lavacar/ajax/actualizar-orden.php` - Saves order changes

**Usage:**
1. Go to Active Orders (`lavacar/reportes/ordenes-activas.php`)
2. Click the "Edit" button (yellow pencil icon) on any non-closed order
3. Modify services, prices, or observations
4. Click "Guardar Cambios" to save

### 3. Calculator Interface for Order Closure ✅

**Location:** `lavacar/reportes/ordenes-activas.php`

**Description:** Advanced calculator interface for finalizing orders before closure.

**Features:**
- Edit final prices before closing
- Add additional items/charges
- Apply discounts
- Add closure notes
- Real-time total calculation with IVA
- Only available for "Terminado" orders

**Backend Files:**
- `lavacar/ajax/cerrar-orden-final.php` - Processes final order closure

**Usage:**
1. Go to Active Orders
2. For orders in "Terminado" state, click the "Calculator" button
3. Adjust prices, add items, apply discounts
4. Add closure notes if needed
5. Click "Cerrar Orden" to finalize

## Database Changes

### New Columns Added:
1. **ordenes table:**
   - `Descuento` DECIMAL(10,2) DEFAULT 0.00 - For storing discount amounts
   - `FechaCierre` DATETIME NULL - For storing closure timestamp

2. **orden_servicios table:**
   - `ServicioPersonalizado` VARCHAR(255) NULL - For storing custom service names

### Migration Script:
Run `sql-add-descuento-column.sql` on each tenant database to add required columns.

## User Interface Enhancements

### Active Orders Page:
- **Edit Button:** Yellow pencil icon for orders with Estado < 4
- **Calculator Button:** Orange calculator icon for orders with Estado = 3
- **Mobile Responsive:** All buttons work on mobile cards
- **Toast Notifications:** Modern toast-style alerts instead of browser alerts

### Order Detail Modal:
- **Edit Order Button:** Available for non-closed orders
- **Calculator Button:** Available for terminated orders
- **Enhanced Information Display:** Shows all order details, services, and totals

## Technical Implementation

### JavaScript Functions:
- `editarOrden(ordenId)` - Opens order editor modal
- `mostrarCalculadoraCierre(ordenId)` - Opens calculator modal
- `renderizarEditorOrden(orden)` - Renders order editing interface
- `renderizarCalculadoraCierre(orden)` - Renders calculator interface
- `recalcularTotalesEditor()` - Recalculates totals in editor
- `recalcularTotalesCalculadora()` - Recalculates totals in calculator

### Security Features:
- Session validation on all AJAX endpoints
- Order state validation (prevents editing closed orders)
- SQL injection protection with prepared statements
- Transaction rollback on errors

## Testing

### Test File: `test-order-editing.php`
Comprehensive testing interface that checks:
1. Database schema completeness
2. AJAX file existence
3. Available orders for testing
4. Feature availability by order state

### Manual Testing Steps:
1. **Custom Services:**
   - Create new order
   - Add custom services in step 2
   - Verify they appear with green styling
   - Complete order creation

2. **Order Editing:**
   - Find non-closed order in active orders
   - Click edit button
   - Modify services and prices
   - Save changes and verify updates

3. **Calculator Closure:**
   - Find order in "Terminado" state
   - Click calculator button
   - Adjust prices and add discount
   - Close order and verify final state

## Error Handling

### Common Issues:
1. **Missing Database Columns:** Run migration script
2. **Permission Errors:** Check user authentication
3. **Order State Conflicts:** Verify order is in correct state for operation

### Logging:
- All errors logged to PHP error log
- AJAX responses include detailed error messages
- Transaction rollbacks prevent data corruption

## Future Enhancements

### Potential Improvements:
1. **Audit Trail:** Track all order modifications
2. **Email Notifications:** Send updates when orders are modified
3. **Bulk Operations:** Edit multiple orders simultaneously
4. **Advanced Discounts:** Percentage-based discounts
5. **Service Templates:** Save custom services as templates

## Support

### Files Modified:
- `lavacar/reportes/ordenes-activas.php` - Main interface
- `lavacar/ordenes/guardar_orden.php` - Custom services support
- `lavacar/ordenes/steps/paso_servicios.php` - Custom services UI
- `lavacar/ordenes/wizard.js` - Custom services JavaScript

### Files Created:
- `lavacar/ajax/obtener-orden-editar.php`
- `lavacar/ajax/actualizar-orden.php`
- `lavacar/ajax/cerrar-orden-final.php`
- `sql-add-descuento-column.sql`
- `test-order-editing.php`

### Dependencies:
- Bootstrap 5 (for modals and styling)
- Font Awesome 6 (for icons)
- jQuery/Vanilla JS (for AJAX calls)
- PHP MySQLi (for database operations)

---

**Implementation Status:** ✅ Complete
**Testing Status:** ✅ Ready for testing
**Documentation Status:** ✅ Complete