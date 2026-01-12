<?php
/**
 * FROSH Multi-Tenant Platform - Landing Page
 * This is the main entry point for the FROSH system
 */

session_start();
require_once 'lib/config.php';
require_once 'lib/Auth.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: lavacar/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>FROSH · Sistema de Gestión Empresarial</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- FROSH Global CSS -->
    <link rel="stylesheet" href="lib/css/frosh-global.css">

    <style>
    body {
        background: linear-gradient(135deg, var(--frosh-gray-50) 0%, var(--frosh-gray-100) 100%);
        min-height: 100vh;
    }

    .hero-section {
        background: linear-gradient(135deg, var(--frosh-black) 0%, var(--frosh-gray-800) 100%);
        color: white;
        padding: 100px 0;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.03), transparent);
        animation: shimmer 4s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
        100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    .hero-content {
        position: relative;
        z-index: 1;
    }

    .hero-title {
        font-size: 4rem;
        font-weight: 800;
        letter-spacing: 3px;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .hero-subtitle {
        font-size: 1.5rem;
        font-weight: 300;
        opacity: 0.9;
        margin-bottom: 2rem;
    }

    .feature-card {
        background: white;
        border-radius: var(--border-radius-lg);
        padding: 2rem;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--content-border);
        transition: all var(--transition-base);
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        color: white;
    }

    .feature-icon.management {
        background: linear-gradient(135deg, var(--report-info), #1e3a8a);
    }

    .feature-icon.reports {
        background: linear-gradient(135deg, var(--report-success), #059669);
    }

    .feature-icon.security {
        background: linear-gradient(135deg, var(--report-warning), #b8941f);
    }

    .feature-icon.support {
        background: linear-gradient(135deg, var(--frosh-gray-700), var(--frosh-black));
    }

    .cta-section {
        background: var(--frosh-gray-50);
        padding: 80px 0;
    }

    .stats-section {
        background: white;
        padding: 60px 0;
        border-top: 1px solid var(--content-border);
        border-bottom: 1px solid var(--content-border);
    }

    .stat-item {
        text-align: center;
        padding: 1rem;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: var(--report-info);
        display: block;
    }

    .stat-label {
        font-size: 1rem;
        color: var(--frosh-gray-600);
        font-weight: 500;
    }

    .navbar-brand {
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 2px;
        color: white !important;
    }

    .navbar {
        background: rgba(0, 0, 0, 0.9) !important;
        backdrop-filter: blur(10px);
    }

    .footer {
        background: var(--frosh-black);
        color: white;
        padding: 40px 0 20px;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
        }
        
        .feature-card {
            margin-bottom: 2rem;
        }
    }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">FROSH</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Planes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light ms-2 px-3" href="login.php">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light ms-2 px-3 text-dark" href="register.php">Registrarse</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="hero-title">FROSH</h1>
                        <p class="hero-subtitle">Sistema de Gestión Empresarial Multi-Tenant</p>
                        <p class="lead mb-4">
                            Gestiona tu negocio de lavado de autos con la plataforma más completa y segura del mercado.
                            Diseñada específicamente para empresas de servicios automotrices.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="register.php" class="btn btn-report-warning btn-lg">
                                <i class="fas fa-rocket me-2"></i>Comenzar Gratis
                            </a>
                            <a href="login.php" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-car-wash" style="font-size: 15rem; opacity: 0.1;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Empresas Activas</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">Vehículos Gestionados</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">99.9%</span>
                        <span class="stat-label">Tiempo de Actividad</span>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Soporte Técnico</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-4 fw-bold mb-3">¿Por qué elegir FROSH?</h2>
                    <p class="lead text-muted">
                        Nuestra plataforma está diseñada específicamente para empresas de lavado de autos,
                        con todas las herramientas que necesitas para hacer crecer tu negocio.
                    </p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon management mx-auto">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Gestión Completa</h4>
                        <p class="text-muted">
                            Administra clientes, vehículos, servicios y órdenes desde una sola plataforma intuitiva.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon reports mx-auto">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Reportes Avanzados</h4>
                        <p class="text-muted">
                            Obtén insights valiosos con reportes detallados de ventas, servicios y rendimiento.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon security mx-auto">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Seguridad Total</h4>
                        <p class="text-muted">
                            Protección de datos de nivel empresarial con encriptación y backups automáticos.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon support mx-auto">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Soporte 24/7</h4>
                        <p class="text-muted">
                            Nuestro equipo de expertos está disponible para ayudarte cuando lo necesites.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="cta-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-4 fw-bold mb-3">Planes que se adaptan a tu negocio</h2>
                    <p class="lead text-muted">
                        Comienza con nuestro plan gratuito de 30 días y escala según crezca tu empresa.
                    </p>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                <?php
                // Get available plans
                $plans = ObtenerRegistros($conn, "SELECT * FROM planes WHERE estado = 'activo' ORDER BY precio_mensual ASC");
                
                if ($plans) {
                    foreach ($plans as $plan) {
                        $isPopular = $plan['nombre'] === 'Plata';
                        echo "
                        <div class='col-lg-4 col-md-6'>
                            <div class='card h-100 " . ($isPopular ? 'border-primary' : '') . "'>
                                " . ($isPopular ? "<div class='badge bg-primary position-absolute top-0 start-50 translate-middle px-3 py-2'>Más Popular</div>" : "") . "
                                <div class='card-body text-center p-4'>
                                    <h3 class='card-title fw-bold'>{$plan['nombre']}</h3>
                                    <div class='display-4 fw-bold text-primary mb-3'>
                                        $" . number_format($plan['precio_mensual'], 0) . "
                                        <small class='fs-6 text-muted'>/mes</small>
                                    </div>
                                    <p class='text-muted mb-4'>{$plan['descripcion']}</p>
                                    <ul class='list-unstyled mb-4'>
                                        <li class='mb-2'><i class='fas fa-check text-success me-2'></i>Hasta {$plan['max_vehiculos']} vehículos</li>
                                        <li class='mb-2'><i class='fas fa-check text-success me-2'></i>{$plan['max_usuarios']} usuarios</li>
                                        <li class='mb-2'><i class='fas fa-check text-success me-2'></i>{$plan['limite_almacenamiento_gb']}GB almacenamiento</li>
                                        <li class='mb-2'><i class='fas fa-check text-success me-2'></i>Soporte por email</li>
                                    </ul>
                                    <a href='register.php' class='btn " . ($isPopular ? 'btn-primary' : 'btn-outline-primary') . " btn-lg w-100'>
                                        Comenzar Ahora
                                    </a>
                                </div>
                            </div>
                        </div>";
                    }
                }
                ?>
            </div>

            <div class="row mt-5">
                <div class="col-lg-8 mx-auto text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-gift me-2"></i>
                        <strong>¡Prueba gratuita de 30 días!</strong> 
                        Todos los planes incluyen acceso completo durante el primer mes sin costo.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">FROSH</h5>
                    <p class="text-muted">
                        La plataforma de gestión empresarial más completa para negocios de lavado de autos.
                    </p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Producto</h6>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-muted text-decoration-none">Características</a></li>
                        <li><a href="#pricing" class="text-muted text-decoration-none">Precios</a></li>
                        <li><a href="register.php" class="text-muted text-decoration-none">Registrarse</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Soporte</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-muted text-decoration-none">Documentación</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Contacto</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">Estado del Sistema</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-bold mb-3">Contacto</h6>
                    <p class="text-muted mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        soporte@frosh.com
                    </p>
                    <p class="text-muted mb-2">
                        <i class="fas fa-phone me-2"></i>
                        +506 2000-0000
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-muted mb-0">
                        © <?= date('Y') ?> FROSH. Todos los derechos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-muted me-3">Términos de Servicio</a>
                    <a href="#" class="text-muted">Política de Privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Navbar background on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.style.background = 'rgba(0, 0, 0, 0.95)';
        } else {
            navbar.style.background = 'rgba(0, 0, 0, 0.9)';
        }
    });

    // Animate stats on scroll
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(stat => {
                    const finalValue = stat.textContent;
                    stat.textContent = '0';
                    
                    const increment = () => {
                        const current = parseInt(stat.textContent);
                        const target = parseInt(finalValue.replace(/[^\d]/g, ''));
                        const step = Math.ceil(target / 50);
                        
                        if (current < target) {
                            stat.textContent = Math.min(current + step, target) + finalValue.replace(/[\d]/g, '');
                            setTimeout(increment, 30);
                        } else {
                            stat.textContent = finalValue;
                        }
                    };
                    
                    setTimeout(increment, 200);
                });
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
    </script>
</body>

</html>