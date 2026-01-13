<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Frosh | Gestion de Lavacars</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Frosh Components CSS -->
    <link rel="stylesheet" href="<?= LAVACAR_BASE_URL ?>/../lib/css/frosh-components.css">

    <style>
    :root {
        --primary: #3b82f6;
        --success: #2ecc71;
        --warning: #f39c12;
        --danger: #e74c3c;
        --dark: #2c3e50;
        
        /* FROSH Brand Colors */
        --frosh-black: #000000;
        --frosh-dark: #1a1a1a;
        --frosh-gray-900: #111827;
        --frosh-gray-800: #1f2937;
        --frosh-gray-700: #374151;
        --frosh-gray-600: #4b5563;
        --frosh-gray-500: #6b7280;
        --frosh-gray-400: #9ca3af;
        --frosh-gray-300: #d1d5db;
        --frosh-gray-200: #e5e7eb;
        --frosh-gray-100: #f3f4f6;
        --frosh-gray-50: #f9fafb;
        
        /* Colores personalizados para reportes - sugerencia de marketing */
        --report-warning: #D3AF37;   /* Dorado suave */
        --report-info: #274AB3;      /* Azul profundo */
        --report-success: #10b981;   /* Verde success */
        --report-danger: #ef4444;    /* Rojo para alertas */
    }

    /* ===== FROSH BUTTON SYSTEM ===== */
    .btn-frosh-primary {
        background: var(--frosh-black);
        border: 1px solid var(--frosh-black);
        color: white;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-frosh-primary:hover {
        background: var(--frosh-dark);
        border-color: var(--frosh-dark);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-frosh-dark {
        background: var(--frosh-gray-800);
        border: 1px solid var(--frosh-gray-700);
        color: white;
        transition: all 0.2s ease;
    }

    .btn-frosh-dark:hover {
        background: var(--frosh-gray-700);
        border-color: var(--frosh-gray-600);
        color: white;
        transform: translateY(-1px);
    }

    .btn-frosh-gray {
        background: var(--frosh-gray-600);
        border: 1px solid var(--frosh-gray-500);
        color: white;
        transition: all 0.2s ease;
    }

    .btn-frosh-gray:hover {
        background: var(--frosh-gray-500);
        border-color: var(--frosh-gray-400);
        color: white;
        transform: translateY(-1px);
    }

    .btn-frosh-light {
        background: var(--frosh-gray-100);
        border: 1px solid var(--frosh-gray-300);
        color: var(--frosh-gray-800);
        transition: all 0.2s ease;
    }

    .btn-frosh-light:hover {
        background: var(--frosh-gray-200);
        border-color: var(--frosh-gray-400);
        color: var(--frosh-gray-900);
        transform: translateY(-1px);
    }

    /* Outline variants */
    .btn-outline-frosh-primary {
        background: transparent;
        border: 1px solid var(--frosh-black);
        color: var(--frosh-black);
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-outline-frosh-primary:hover {
        background: var(--frosh-black);
        border-color: var(--frosh-black);
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline-frosh-dark {
        background: transparent;
        border: 1px solid var(--frosh-gray-700);
        color: var(--frosh-gray-700);
        transition: all 0.2s ease;
    }

    .btn-outline-frosh-dark:hover {
        background: var(--frosh-gray-800);
        border-color: var(--frosh-gray-800);
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline-frosh-gray {
        background: transparent;
        border: 1px solid var(--frosh-gray-500);
        color: var(--frosh-gray-600);
        transition: all 0.2s ease;
    }

    .btn-outline-frosh-gray:hover {
        background: var(--frosh-gray-600);
        border-color: var(--frosh-gray-600);
        color: white;
        transform: translateY(-1px);
    }

    /* FROSH Badges */
    .badge-frosh-primary {
        background: var(--frosh-black);
        color: white;
    }

    .badge-frosh-dark {
        background: var(--frosh-gray-800);
        color: white;
    }

    .badge-frosh-gray {
        background: var(--frosh-gray-600);
        color: white;
    }

    .badge-frosh-light {
        background: var(--frosh-gray-200);
        color: var(--frosh-gray-800);
    }

    /* Badges para reportes con colores de marketing */
    .badge-report-warning {
        background: var(--report-warning);
        color: white;
        font-weight: 600;
    }

    .badge-report-info {
        background: var(--report-info);
        color: white;
        font-weight: 600;
    }

    .badge-report-success {
        background: var(--report-success);
        color: white;
        font-weight: 600;
    }

    .badge-report-danger {
        background: var(--report-danger);
        color: white;
        font-weight: 600;
    }

    /* Botones para reportes con colores de marketing */
    .btn-report-warning {
        background: var(--report-warning);
        border: 1px solid var(--report-warning);
        color: white;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-report-warning:hover {
        background: #b8941f;
        border-color: #b8941f;
        color: white;
        transform: translateY(-1px);
    }

    .btn-report-info {
        background: var(--report-info);
        border: 1px solid var(--report-info);
        color: white;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-report-info:hover {
        background: #1e3a8a;
        border-color: #1e3a8a;
        color: white;
        transform: translateY(-1px);
    }

    .btn-report-success {
        background: var(--report-success);
        border: 1px solid var(--report-success);
        color: white;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-report-success:hover {
        background: #059669;
        border-color: #059669;
        color: white;
        transform: translateY(-1px);
    }

    /* Outline variants para reportes */
    .btn-outline-report-warning {
        background: transparent;
        border: 1px solid var(--report-warning);
        color: var(--report-warning);
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-outline-report-warning:hover {
        background: var(--report-warning);
        border-color: var(--report-warning);
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline-report-info {
        background: transparent;
        border: 1px solid var(--report-info);
        color: var(--report-info);
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-outline-report-info:hover {
        background: var(--report-info);
        border-color: var(--report-info);
        color: white;
        transform: translateY(-1px);
    }

    .btn-outline-report-success {
        background: transparent;
        border: 1px solid var(--report-success);
        color: var(--report-success);
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .btn-outline-report-success:hover {
        background: var(--report-success);
        border-color: var(--report-success);
        color: white;
        transform: translateY(-1px);
    }

    /* Small button enhancements */
    .btn-sm.btn-frosh-primary,
    .btn-sm.btn-frosh-dark,
    .btn-sm.btn-frosh-gray,
    .btn-sm.btn-frosh-light,
    .btn-sm.btn-outline-frosh-primary,
    .btn-sm.btn-outline-frosh-dark,
    .btn-sm.btn-outline-frosh-gray {
        font-weight: 500;
        border-radius: 6px;
    }

    /* Focus states */
    .btn-frosh-primary:focus,
    .btn-frosh-dark:focus,
    .btn-frosh-gray:focus,
    .btn-frosh-light:focus,
    .btn-outline-frosh-primary:focus,
    .btn-outline-frosh-dark:focus,
    .btn-outline-frosh-gray:focus {
        box-shadow: 0 0 0 3px rgba(31, 41, 55, 0.25);
    }

    /* Disabled states */
    .btn-frosh-primary:disabled,
    .btn-frosh-dark:disabled,
    .btn-frosh-gray:disabled,
    .btn-frosh-light:disabled,
    .btn-outline-frosh-primary:disabled,
    .btn-outline-frosh-dark:disabled,
    .btn-outline-frosh-gray:disabled {
        opacity: 0.5;
        transform: none;
    }

    body {
        font-family: "Segoe UI", system-ui, sans-serif;
        background: var(--frosh-gray-50);
        color: var(--frosh-gray-900);
    }

    /* ---------- HEADER ---------- */
    .app-header {
        background: var(--frosh-black);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 2px solid var(--frosh-gray-800);
    }

    .logo {
        height: 42px;
        transition: transform 0.2s ease;
        filter: brightness(1.1);
    }

    .logo:hover {
        transform: scale(1.02);
        filter: brightness(1.2);
    }

    .logo-link {
        text-decoration: none;
    }

    /* Quick Navigation */
    .quick-nav {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: 16px;
        padding-left: 16px;
        border-left: 1px solid var(--frosh-gray-700);
    }

    .nav-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 14px;
        border-radius: 8px;
        text-decoration: none;
        color: var(--frosh-gray-300);
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .nav-btn:hover {
        color: white;
        background-color: var(--frosh-gray-800);
        text-decoration: none;
        transform: translateY(-1px);
    }

    .nav-btn i {
        font-size: 1rem;
    }

    /* Mobile Navigation Button */
    .mobile-nav-btn {
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 1.1rem;
        background: var(--frosh-gray-800);
        border-color: var(--frosh-gray-600);
        color: var(--frosh-gray-200);
    }

    .mobile-nav-btn:hover {
        background: var(--frosh-gray-700);
        border-color: var(--frosh-gray-500);
        color: white;
    }

    /* User Menu Button */
    .user-menu-btn {
        padding: 10px 14px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
        background: var(--frosh-gray-800);
        border-color: var(--frosh-gray-600);
        color: var(--frosh-gray-200);
    }

    .user-menu-btn:hover {
        background: var(--frosh-gray-700);
        border-color: var(--frosh-gray-500);
        color: white;
    }

    .user-menu-btn i {
        font-size: 1.2rem;
    }

    /* Dropdown Improvements */
    .dropdown-menu {
        border: 1px solid var(--frosh-gray-200);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        border-radius: 12px;
        padding: 8px 0;
        min-width: 220px;
        background: white;
    }

    .dropdown-item {
        padding: 12px 16px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        color: var(--frosh-gray-700);
    }

    .dropdown-item:hover {
        background-color: var(--frosh-gray-50);
        color: var(--frosh-gray-900);
    }

    .dropdown-item i {
        width: 18px;
        text-align: center;
        color: var(--frosh-gray-500);
    }

    .dropdown-header {
        padding: 12px 16px 8px;
        font-size: 0.85rem;
        color: var(--frosh-gray-600);
        font-weight: 600;
        margin-bottom: 0;
        background: var(--frosh-gray-50);
    }

    .dropdown-divider {
        margin: 8px 0;
        border-color: var(--frosh-gray-200);
    }

    /* Active page indicator - Desktop (gris como antes) */
    .nav-btn.active {
        color: white;
        background-color: var(--frosh-gray-700);
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .nav-btn.active:hover {
        background-color: var(--frosh-gray-600);
    }

    /* Active dropdown items - Mobile (azul FROSH) */
    .dropdown-item.active {
        color: white;
        background-color: var(--report-info, #274AB3);
    }

    .dropdown-item.active:hover {
        background-color: #1e3a8a;
        color: white;
    }

    /* ---------- DASHBOARD ---------- */
    .dashboard-title {
        font-weight: 700;
        color: var(--frosh-gray-900);
    }

    .dash-card {
        width: 100%;
        border: none;
        border-radius: 16px;
        padding: 40px 15px;
        background: white;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all .3s ease;
        min-height: 160px;
        position: relative;
        border: 1px solid var(--frosh-gray-200);
    }

    .dash-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border-color: var(--frosh-gray-300);
    }

    .dash-card:active {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .dash-card i {
        font-size: 3.5rem;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    }

    .dash-card:hover i {
        transform: scale(1.15);
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
    }

    /* Icon color hints for better UX */
    .admin i {
        color: var(--report-info, #274AB3);
    }

    .order i {
        color: var(--report-success, #10b981);
    }

    .active i {
        color: var(--report-warning, #D3AF37);
    }

    .reports i {
        color: var(--frosh-gray-700, #374151);
    }

    .dash-card span {
        font-size: 1.1rem;
        letter-spacing: 0.8px;
        text-align: center;
        line-height: 1.3;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--frosh-gray-800);
    }

    /* Badge counter for active orders */
    .badge-counter {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, var(--report-warning, #D3AF37), #b8941f);
        color: white;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(211, 175, 55, 0.4);
        animation: pulse-badge 2s infinite;
        border: 2px solid white;
    }

    @keyframes pulse-badge {
        0% { 
            transform: scale(1); 
            box-shadow: 0 4px 12px rgba(211, 175, 55, 0.4);
        }
        50% { 
            transform: scale(1.1); 
            box-shadow: 0 6px 20px rgba(211, 175, 55, 0.6);
        }
        100% { 
            transform: scale(1); 
            box-shadow: 0 4px 12px rgba(211, 175, 55, 0.4);
        }
    }

    .admin {
        color: var(--frosh-gray-700);
    }

    .admin:hover {
        color: white;
        background: linear-gradient(135deg, rgba(39, 74, 179, 0.8), rgba(30, 58, 138, 0.9));
    }

    .order {
        color: var(--frosh-gray-700);
    }

    .order:hover {
        color: white;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.8), rgba(5, 150, 105, 0.9));
    }

    .active {
        color: var(--frosh-gray-700);
    }

    .active:hover {
        color: white;
        background: linear-gradient(135deg, rgba(211, 175, 55, 0.8), rgba(184, 148, 31, 0.9));
    }

    .reports {
        color: var(--frosh-gray-700);
    }

    .reports:hover {
        color: white;
        background: linear-gradient(135deg, rgba(75, 85, 99, 0.8), rgba(55, 65, 81, 0.9));
    }

    /* ===== DASHBOARD ENHANCEMENTS ===== */
    .company-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .company-info .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
        border-radius: 20px;
        box-shadow: 0 2px 8px rgba(39, 74, 179, 0.2);
    }

    .dashboard-title {
        color: var(--frosh-gray-900);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    /* Enhanced dashboard card shadows with marketing colors */
    .admin:hover {
        box-shadow: 0 25px 50px -12px rgba(39, 74, 179, 0.3);
    }

    .order:hover {
        box-shadow: 0 25px 50px -12px rgba(16, 185, 129, 0.3);
    }

    .active:hover {
        box-shadow: 0 25px 50px -12px rgba(211, 175, 55, 0.3);
    }

    .reports:hover {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3);
    }

    /* Dashboard card entrance animation */
    #dashboardCards .col-6 {
        animation: slideInUp 0.6s ease-out both;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive animation adjustments */
    @media (max-width: 768px) {
        #dashboardCards .col-6 {
            animation-duration: 0.4s;
        }
    }

    /* ---------- FOOTER ---------- */
    footer {
        margin-top: 60px;
        padding: 20px 0;
        text-align: center;
        font-size: .85rem;
        color: #7f8c8d;
    }

    /* ---------- MOBILE ---------- */
    @media (max-width: 576px) {
        .dash-card {
            padding: 35px 12px;
            min-height: 140px;
        }
        
        .dash-card i {
            font-size: 2.8rem;
        }
        
        .dash-card span {
            font-size: 1rem;
            letter-spacing: 0.6px;
        }
        
        .badge-counter {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }

        /* Mobile header adjustments */
        .app-header {
            padding: 8px 0;
        }

        .logo {
            height: 36px;
        }

        .mobile-nav-btn, .user-menu-btn {
            padding: 6px 10px;
            font-size: 1rem;
        }

        .dropdown-menu {
            min-width: 180px;
            font-size: 0.85rem;
        }

        .dropdown-item {
            padding: 8px 12px;
        }
    }

    @media (max-width: 768px) {
        .dash-card {
            margin-bottom: 20px;
        }

        /* Hide quick nav on tablets */
        .quick-nav {
            display: none !important;
        }

        .logo {
            height: 38px;
        }
    }

    @media (min-width: 769px) {
        /* Show desktop navigation */
        .mobile-nav-btn {
            display: none !important;
        }
    }

    /* Mejoras para pantallas táctiles */
    @media (hover: none) and (pointer: coarse) {
        .dash-card {
            padding: 45px 20px;
            min-height: 170px;
        }
        
        .dash-card:hover {
            transform: none;
            background: white;
        }
        
        .dash-card:active {
            transform: scale(0.98);
            transition: transform 0.1s ease;
        }
    }

    a {
        text-decoration: none !important;
    }

    /* Modal z-index fixes for FROSH overlay conflicts */
    .modal {
        z-index: 10050 !important;
    }
    
    .modal-backdrop {
        z-index: 10040 !important;
    }
    
    /* Ensure modal content is clickable and properly centered */
    .modal-content {
        position: relative;
        z-index: 10051;
        pointer-events: auto;
    }
    
    /* Fix modal centering issues */
    .modal-dialog {
        margin: 1.75rem auto !important;
        max-width: none;
        position: relative;
        width: auto;
        pointer-events: none;
    }
    
    .modal-dialog-centered {
        display: flex !important;
        align-items: center !important;
        min-height: calc(100% - 3.5rem) !important;
    }
    
    .modal-lg {
        max-width: 800px !important;
    }
    
    /* Prevent FROSH overlay interference */
    body.modal-open {
        overflow: hidden;
    }
    
    body.modal-open #froshOverLay {
        pointer-events: none;
    }
    
    /* Ensure all interactive elements are clickable */
    button, .btn, a, input, select, textarea {
        pointer-events: auto !important;
        position: relative;
        z-index: 1;
    }
    
    /* Fix table button groups */
    .btn-group {
        position: relative;
        z-index: 2;
    }
    
    /* Ensure modal buttons work */
    .modal .btn {
        pointer-events: auto !important;
        z-index: 10052;
    }
    
    /* Override any conflicting positioning */
    .modal.show .modal-dialog {
        transform: none !important;
        margin-left: auto !important;
        margin-right: auto !important;
    }
    
    /* Ensure proper modal display */
    .modal.show {
        display: block !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    
    /* Fix any flexbox issues */
    .modal-dialog-centered::before {
        display: block;
        height: calc(100vh - 3.5rem);
        height: -webkit-min-content;
        height: -moz-min-content;
        height: min-content;
        content: "";
    }

    /* froshOverLay Styles */
    #froshOverLay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.92);
        display: flex;
        align-items: flex-start;
        /* align at top instead of center */
        justify-content: center;
        z-index: 9999;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.5s ease, visibility 0.5s;
        overflow-y: auto;
        /* enable vertical scroll */
        padding: 10px 5px;
        /* space around content when scrolling */
    }

    #froshOverLay.show {
        opacity: 1;
        visibility: visible;
    }

    .overlay-content {
        width: 90%;
        max-width: 1080px;
        background: linear-gradient(135deg, #fff 0%, #abd0d5 100%);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        position: relative;
        transform: translateY(20px);
        transition: transform 0.5s ease;
        max-height: 90vh;
        /* prevent overflow beyond viewport */
        overflow-y: auto;
        /* scroll inside content if still too tall */
    }

    #froshOverLay.show .overlay-content {
        transform: translateY(0);
    }

    .close-btn {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(3, 1, 9, 0.84);
        color: whitesmoke;
        border: none;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        font-size: 1.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .close-btn:hover {
        background: rgba(54, 34, 34, 0.9);
        transform: rotate(90deg);
    }


    .overlay-body {
        font-size: 1.15rem;
        line-height: 1.7;
        margin-bottom: 30px;
    }

    /* ===== SWITCH (SLIDER CHECKBOX) ===== */
    .switch {
        position: relative;
        display: inline-block;
        width: 46px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background-color: #d1d5db;
        /* gray-300 */
        transition: 0.3s;
        border-radius: 34px;
    }

    .slider::before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        top: 3px;
        background-color: white;
        transition: 0.3s;
        border-radius: 50%;
    }

    /* ON */
    .switch input:checked+.slider {
        background-color: #111827;
        /* dark */
    }

    .switch input:checked+.slider::before {
        transform: translateX(22px);
    }

    /* FOCUS */
    .switch input:focus+.slider {
        box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.25);
    }

    /* DISABLED (optional) */
    .switch input:disabled+.slider {
        opacity: 0.5;
        cursor: not-allowed;
    }
    </style>
</head>

<body>

<!-- Floating Action Button for Mobile Quick Actions -->
<div class="fab-container d-md-none">
    <!--<button class="fab-main" id="fabMain" title="Acciones rápidas">
        <i class="fa-solid fa-plus"></i>
    </button>-->
    <div class="fab-menu" id="fabMenu">
        <a href="<?= LAVACAR_BASE_URL ?>/dashboard.php" class="fab-item" title="Dashboard">
            <i class="fa-solid fa-home"></i>
        </a>
        <a href="<?= LAVACAR_BASE_URL ?>/ordenes/index.php" class="fab-item" title="Nueva Orden">
            <i class="fa-solid fa-plus-circle"></i>
        </a>
        <a href="<?= LAVACAR_BASE_URL ?>/reportes/ordenes-activas.php" class="fab-item" title="Órdenes Activas">
            <i class="fa-solid fa-list-check"></i>
        </a>
        <a href="<?= LAVACAR_BASE_URL ?>/reportes/index.php" class="fab-item" title="Reportes">
            <i class="fa-solid fa-chart-bar"></i>
        </a>
    </div>
</div>

<style>
/* Floating Action Button */
.fab-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.fab-main {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #3b82f6;
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.fab-main:hover {
    background: #2563eb;
    transform: scale(1.05);
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5);
}

.fab-main.active {
    transform: rotate(45deg);
    background: #ef4444;
}

.fab-main.active:hover {
    background: #dc2626;
}

.fab-menu {
    position: absolute;
    bottom: 70px;
    right: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.fab-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.fab-item {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: white;
    color: #374151;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.2s ease;
    font-size: 1.1rem;
}

.fab-item:hover {
    background: #f3f4f6;
    color: #3b82f6;
    transform: scale(1.1);
    text-decoration: none;
}

/* Hide FAB on desktop */
@media (min-width: 768px) {
    .fab-container {
        display: none !important;
    }
}
</style>

<script>
// Mark active navigation item
document.addEventListener('DOMContentLoaded', function() {
    // Esperar un poco para asegurar que el DOM esté completamente cargado
    setTimeout(function() {
        const currentPath = window.location.pathname;
        const navButtons = document.querySelectorAll('.nav-btn, .dropdown-item');
        
        // Debug: mostrar la ruta actual
        console.log('Current path:', currentPath);
        
        // Primero remover TODAS las clases active de todos los elementos de navegación
        navButtons.forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Determinar qué sección está activa basado en la ruta actual
        let targetHref = '';
        
        if (currentPath.includes('/ordenes/')) {
            targetHref = '/ordenes/index.php';
            console.log('Detected: ordenes section');
        } else if (currentPath.includes('/reportes/ordenes-activas.php')) {
            targetHref = '/reportes/ordenes-activas.php';
            console.log('Detected: ordenes-activas section');
        } else if (currentPath.includes('/reportes/')) {
            targetHref = '/reportes/index.php';
            console.log('Detected: reportes section');
        } else if (currentPath.includes('/administracion/')) {
            targetHref = '/administracion/index.php';
            console.log('Detected: administracion section');
        } else if (currentPath.includes('/dashboard.php') || currentPath.endsWith('/lavacar/') || currentPath.endsWith('/lavacar')) {
            targetHref = '/dashboard.php';
            console.log('Detected: dashboard section');
        }
        
        // Solo activar el elemento que coincida exactamente con el targetHref
        if (targetHref) {
            navButtons.forEach(btn => {
                const href = btn.getAttribute('href');
                if (href && href.includes(targetHref)) {
                    btn.classList.add('active');
                    console.log('Activated:', href);
                }
            });
        }
    }, 100); // Esperar 100ms para asegurar que todo esté cargado
    
    // Función adicional para limpiar navegación activa después de Bootstrap
    function cleanActiveNavigation() {
        const currentPath = window.location.pathname;
        const navButtons = document.querySelectorAll('.nav-btn, .dropdown-item');
        
        // Remover todas las clases active
        navButtons.forEach(btn => btn.classList.remove('active'));
        
        // Determinar el href objetivo
        let targetHref = '';
        if (currentPath.includes('/ordenes/')) {
            targetHref = '/ordenes/index.php';
        } else if (currentPath.includes('/reportes/ordenes-activas.php')) {
            targetHref = '/reportes/ordenes-activas.php';
        } else if (currentPath.includes('/reportes/')) {
            targetHref = '/reportes/index.php';
        } else if (currentPath.includes('/administracion/')) {
            targetHref = '/administracion/index.php';
        } else if (currentPath.includes('/dashboard.php') || currentPath.endsWith('/lavacar/') || currentPath.endsWith('/lavacar')) {
            targetHref = '/dashboard.php';
        }
        
        // Activar solo el correcto
        if (targetHref) {
            navButtons.forEach(btn => {
                const href = btn.getAttribute('href');
                if (href && href.includes(targetHref)) {
                    btn.classList.add('active');
                }
            });
        }
    }
    
    // Ejecutar limpieza adicional después de un tiempo más largo
    setTimeout(cleanActiveNavigation, 500);
    
    // También ejecutar cuando se abra el dropdown móvil
    document.addEventListener('click', function(e) {
        if (e.target.closest('.mobile-nav-btn')) {
            setTimeout(cleanActiveNavigation, 50);
        }
    });

    // Floating Action Button functionality
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
});
</script>

    <!-- HEADER -->
    <header class="app-header py-2">
        <div class="container">
            <!-- Top row with logo, navigation and user menu -->
            <div class="d-flex justify-content-between align-items-center">
                <!-- Logo and Quick Navigation -->
                <div class="d-flex align-items-center gap-3">
                    <a href="<?= LAVACAR_BASE_URL ?>/dashboard.php" class="logo-link">
                        <img src="<?= LAVACAR_BASE_URL ?>/lib/images/logo-frosh.png" alt="Frosh" class="logo">
                    </a>
                    
                    <!-- Quick Navigation Menu -->
                    <div class="quick-nav d-flex">
                        <a href="<?= LAVACAR_BASE_URL ?>/dashboard.php" class="nav-btn" title="Dashboard">
                            <i class="fa-solid fa-home"></i>
                            <span>Inicio</span>
                        </a>
                        <a href="<?= LAVACAR_BASE_URL ?>/ordenes/index.php" class="nav-btn" title="Nueva Orden">
                            <i class="fa-solid fa-plus-circle"></i>
                            <span>Nueva Orden</span>
                        </a>
                        <a href="<?= LAVACAR_BASE_URL ?>/reportes/ordenes-activas.php" class="nav-btn" title="Órdenes Activas">
                            <i class="fa-solid fa-list-check"></i>
                            <span>Órdenes</span>
                        </a>
                        <a href="<?= LAVACAR_BASE_URL ?>/reportes/index.php" class="nav-btn" title="Reportes">
                            <i class="fa-solid fa-chart-bar"></i>
                            <span>Reportes</span>
                        </a>
                    </div>
                </div>

                <!-- Mobile Navigation + User Menu -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Mobile Navigation Dropdown -->
                    <div class="dropdown d-md-none">
                        <button class="btn btn-outline-primary btn-sm mobile-nav-btn" data-bs-toggle="dropdown" title="Navegación">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= LAVACAR_BASE_URL ?>/dashboard.php">
                                <i class="fa-solid fa-home me-2"></i>Dashboard
                            </a></li>
                            <li><a class="dropdown-item" href="<?= LAVACAR_BASE_URL ?>/ordenes/index.php">
                                <i class="fa-solid fa-plus-circle me-2"></i>Nueva Orden
                            </a></li>
                            <li><a class="dropdown-item" href="<?= LAVACAR_BASE_URL ?>/reportes/ordenes-activas.php">
                                <i class="fa-solid fa-list-check me-2"></i>Órdenes Activas
                            </a></li>
                            <li><a class="dropdown-item" href="<?= LAVACAR_BASE_URL ?>/reportes/index.php">
                                <i class="fa-solid fa-chart-bar me-2"></i>Reportes
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= LAVACAR_BASE_URL ?>/administracion/index.php">
                                <i class="fa-solid fa-cog me-2"></i>Administración
                            </a></li>
                        </ul>
                    </div>

                    <!-- User Menu -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm user-menu-btn" data-bs-toggle="dropdown" title="Usuario">
                            <i class="fas fa-user-circle"></i>
                            <span class="d-none d-sm-inline ms-1"><?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">
                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($user['name']) ?>
                            </h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= LAVACAR_BASE_URL ?>/administracion/perfil.php">
                                <i class="fas fa-user-edit me-2"></i>Mi Perfil
                            </a></li>
                            <li><a class="dropdown-item" href="<?= LAVACAR_BASE_URL ?>/administracion/usuarios.php">
                                <i class="fas fa-users me-2"></i>Usuarios
                            </a></li>
                            <li><a class="dropdown-item" href="<?= LAVACAR_BASE_URL ?>/administracion/miempresa.php">
                                <i class="fas fa-building me-2"></i>Mi Empresa
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= LAVACAR_BASE_URL ?>/../logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>