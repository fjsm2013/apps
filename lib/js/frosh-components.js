/**
 * FROSH REUSABLE COMPONENTS JAVASCRIPT
 * Common functions and utilities for the Frosh car wash system
 */

// ===== GLOBAL UTILITIES =====

const FroshUtils = {
    
    /**
     * Show modern alert notification
     */
    showAlert: function(message, type = 'info', duration = 5000) {
        // Remove existing alert
        const existingAlert = document.querySelector('.frosh-alert');
        if (existingAlert) existingAlert.remove();
        
        const alert = document.createElement('div');
        alert.className = `frosh-alert alert-${type}`;
        
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-triangle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        const colors = {
            success: '#10b981',
            error: '#ef4444',
            warning: '#f59e0b',
            info: '#3b82f6'
        };
        
        alert.innerHTML = `
            <i class="fa-solid ${icons[type] || icons.info}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" class="alert-close">
                <i class="fa-solid fa-times"></i>
            </button>
        `;
        
        alert.style.cssText = `
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            background: white; border-radius: 12px; padding: 16px 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            border-left: 4px solid ${colors[type] || colors.info};
            display: flex; align-items: center; gap: 12px; max-width: 400px;
            animation: slideIn 0.3s ease; font-weight: 500; color: #1e293b;
        `;
        
        // Add styles if not already added
        if (!document.getElementById('frosh-alert-styles')) {
            const style = document.createElement('style');
            style.id = 'frosh-alert-styles';
            style.textContent = `
                @keyframes slideIn { 
                    from { transform: translateX(100%); opacity: 0; } 
                    to { transform: translateX(0); opacity: 1; } 
                }
                .alert-close { 
                    background: none; border: none; color: #64748b; cursor: pointer; 
                    padding: 4px; border-radius: 4px; transition: all 0.2s ease; 
                }
                .alert-close:hover { background: #f1f5f9; color: #1e293b; }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(alert);
        
        // Auto remove after duration
        if (duration > 0) {
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    alert.style.animation = 'slideIn 0.3s ease reverse';
                    setTimeout(() => alert.remove(), 300);
                }
            }, duration);
        }
    },

    /**
     * Show confirmation dialog
     */
    confirm: function(message, options = {}) {
        return new Promise((resolve) => {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fa-solid fa-question-circle me-2 text-warning"></i>
                                ${options.title || 'Confirmar acción'}
                            </h5>
                        </div>
                        <div class="modal-body">
                            <p>${message}</p>
                            ${options.subMessage ? `<small class="text-muted">${options.subMessage}</small>` : ''}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                ${options.cancelText || 'Cancelar'}
                            </button>
                            <button type="button" class="btn btn-danger confirm-btn">
                                ${options.confirmText || 'Confirmar'}
                            </button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            
            modal.querySelector('.confirm-btn').addEventListener('click', () => {
                bsModal.hide();
                resolve(true);
            });
            
            modal.addEventListener('hidden.bs.modal', () => {
                modal.remove();
                resolve(false);
            });
            
            bsModal.show();
        });
    },

    /**
     * Format currency
     */
    formatCurrency: function(amount, currency = '₡', decimals = 0) {
        if (amount === null || amount === undefined || amount === '') return 'N/A';
        return currency + ' ' + new Intl.NumberFormat('es-CR').format(Number(amount).toFixed(decimals));
    },

    /**
     * Format date
     */
    formatDate: function(date, format = 'dd/mm/yyyy') {
        if (!date) return 'N/A';
        const d = new Date(date);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        
        switch (format) {
            case 'dd/mm/yyyy':
                return `${day}/${month}/${year}`;
            case 'yyyy-mm-dd':
                return `${year}-${month}-${day}`;
            case 'long':
                return d.toLocaleDateString('es-CR', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
            default:
                return `${day}/${month}/${year}`;
        }
    },

    /**
     * Debounce function
     */
    debounce: function(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Make AJAX request
     */
    ajax: function(url, options = {}) {
        const defaults = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };
        
        const config = { ...defaults, ...options };
        
        if (config.data && config.method !== 'GET') {
            if (config.headers['Content-Type'] === 'application/json') {
                config.body = JSON.stringify(config.data);
            } else {
                const formData = new FormData();
                Object.keys(config.data).forEach(key => {
                    formData.append(key, config.data[key]);
                });
                config.body = formData;
                delete config.headers['Content-Type']; // Let browser set it
            }
        }
        
        return fetch(url, config)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                throw error;
            });
    },

    /**
     * Show loading overlay
     */
    showLoading: function(element, message = 'Cargando...') {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary mb-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">${message}</p>
            </div>
        `;
        
        element.style.position = 'relative';
        element.appendChild(overlay);
        return overlay;
    },

    /**
     * Hide loading overlay
     */
    hideLoading: function(element) {
        const overlay = element.querySelector('.loading-overlay');
        if (overlay) {
            overlay.remove();
        }
    },

    /**
     * Validate form
     */
    validateForm: function(form, rules = {}) {
        const errors = {};
        const formData = new FormData(form);
        
        Object.keys(rules).forEach(fieldName => {
            const value = formData.get(fieldName);
            const rule = rules[fieldName];
            
            if (rule.required && (!value || value.trim() === '')) {
                errors[fieldName] = rule.message || `${fieldName} es requerido`;
            }
            
            if (value && rule.pattern && !rule.pattern.test(value)) {
                errors[fieldName] = rule.message || `${fieldName} tiene formato inválido`;
            }
            
            if (value && rule.minLength && value.length < rule.minLength) {
                errors[fieldName] = rule.message || `${fieldName} debe tener al menos ${rule.minLength} caracteres`;
            }
        });
        
        // Show/hide error messages
        Object.keys(rules).forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            const errorElement = form.querySelector(`.error-${fieldName}`);
            
            if (errors[fieldName]) {
                field?.classList.add('is-invalid');
                if (errorElement) {
                    errorElement.textContent = errors[fieldName];
                    errorElement.style.display = 'block';
                }
            } else {
                field?.classList.remove('is-invalid');
                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            }
        });
        
        return Object.keys(errors).length === 0;
    }
};

// ===== COMPONENT CLASSES =====

/**
 * DataTable component for enhanced tables
 */
class FroshDataTable {
    constructor(element, options = {}) {
        this.element = element;
        this.options = {
            searchable: true,
            sortable: true,
            pagination: true,
            pageSize: 10,
            ...options
        };
        this.data = [];
        this.filteredData = [];
        this.currentPage = 1;
        this.init();
    }
    
    init() {
        if (this.options.searchable) {
            this.addSearchBox();
        }
        this.bindEvents();
    }
    
    addSearchBox() {
        const searchBox = document.createElement('div');
        searchBox.className = 'mb-3';
        searchBox.innerHTML = `
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fa-solid fa-search"></i>
                </span>
                <input type="text" class="form-control" placeholder="Buscar...">
            </div>
        `;
        
        this.element.parentNode.insertBefore(searchBox, this.element);
        
        const searchInput = searchBox.querySelector('input');
        searchInput.addEventListener('input', FroshUtils.debounce((e) => {
            this.search(e.target.value);
        }, 300));
    }
    
    search(query) {
        // Implementation for search functionality
        console.log('Searching for:', query);
    }
    
    bindEvents() {
        // Bind sorting events
        if (this.options.sortable) {
            this.element.querySelectorAll('th[data-sortable]').forEach(th => {
                th.style.cursor = 'pointer';
                th.addEventListener('click', () => {
                    this.sort(th.dataset.sortable);
                });
            });
        }
    }
    
    sort(column) {
        console.log('Sorting by:', column);
    }
}

/**
 * Modal manager for dynamic modals
 */
class FroshModal {
    constructor(options = {}) {
        this.options = {
            size: 'modal-lg',
            backdrop: true,
            keyboard: true,
            ...options
        };
        this.modal = null;
        this.bsModal = null;
    }
    
    show(title, content, footer = '') {
        this.create(title, content, footer);
        this.bsModal.show();
    }
    
    create(title, content, footer) {
        if (this.modal) {
            this.modal.remove();
        }
        
        this.modal = document.createElement('div');
        this.modal.className = 'modal fade';
        this.modal.innerHTML = `
            <div class="modal-dialog ${this.options.size}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">${content}</div>
                    ${footer ? `<div class="modal-footer">${footer}</div>` : ''}
                </div>
            </div>
        `;
        
        document.body.appendChild(this.modal);
        this.bsModal = new bootstrap.Modal(this.modal, this.options);
        
        this.modal.addEventListener('hidden.bs.modal', () => {
            this.modal.remove();
        });
    }
    
    hide() {
        if (this.bsModal) {
            this.bsModal.hide();
        }
    }
}

// ===== INITIALIZATION =====

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize Floating Action Button
    const fabMain = document.getElementById('fabMain');
    const fabMenu = document.getElementById('fabMenu');
    
    if (fabMain && fabMenu) {
        fabMain.addEventListener('click', function() {
            fabMain.classList.toggle('active');
            fabMenu.classList.toggle('show');
        });

        // Close FAB menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.fab-container')) {
                fabMain.classList.remove('active');
                fabMenu.classList.remove('show');
            }
        });
    }
    
    // Mark active navigation items
    const currentPath = window.location.pathname;
    const navButtons = document.querySelectorAll('.nav-btn, .dropdown-item');
    
    navButtons.forEach(btn => {
        const href = btn.getAttribute('href');
        if (href && currentPath.includes(href.split('/').pop())) {
            btn.classList.add('active');
        }
    });
    
    // Auto-focus search inputs
    const searchInputs = document.querySelectorAll('input[name="search"]');
    searchInputs.forEach(input => {
        if (!input.value) {
            input.focus();
        }
    });
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// ===== GLOBAL FUNCTIONS FOR BACKWARD COMPATIBILITY =====

function showAlert(message, type = 'info', duration = 5000) {
    FroshUtils.showAlert(message, type, duration);
}

function confirmAction(message, options = {}) {
    return FroshUtils.confirm(message, options);
}

function formatCurrency(amount, currency = '₡', decimals = 0) {
    return FroshUtils.formatCurrency(amount, currency, decimals);
}

function formatDate(date, format = 'dd/mm/yyyy') {
    return FroshUtils.formatDate(date, format);
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { FroshUtils, FroshDataTable, FroshModal };
}