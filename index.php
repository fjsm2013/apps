<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  
  <!-- Primary Meta Tags -->
  <title>FROSH LavaCar App | Sistema de Gestión para Autolavados</title>
  <meta name="description" content="Sistema de gestión especializado para autolavados. Organiza turnos, controla estados de lavado y envía notificaciones automáticas a clientes. Mejora la eficiencia operativa y la satisfacción del cliente." />
  <meta name="keywords" content="app para lavacar, sistema gestión autolavados, software lavado de autos, turnos lavacar, notificaciones automáticas lavado, gestión de clientes autolavados, app lavado vehículos" />
  <meta name="robots" content="index, follow" />
  <meta name="author" content="FROSH LavaCar App" />
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:title" content="FROSH LavaCar App | Sistema de Gestión para Autolavados" />
  <meta property="og:description" content="Solución completa para gestionar tu autolavado eficientemente" />
  <meta property="og:image" content="https://ejemplo.com/og-image.jpg" />
  <meta property="og:url" content="https://frosh-lavacar.com" />
  <meta property="og:site_name" content="FROSH LavaCar App" />
  <meta property="og:locale" content="es_ES" />
  
  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="FROSH LavaCar App | Sistema de Gestión para Autolavados" />
  <meta name="twitter:description" content="Gestión inteligente para tu negocio de lavado de autos" />
  <meta name="twitter:image" content="https://ejemplo.com/twitter-image.jpg" />
  
  <!-- Schema.org markup -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "SoftwareApplication",
    "name": "FROSH LavaCar App",
    "applicationCategory": "BusinessApplication",
    "operatingSystem": "Web, iOS, Android",
    "description": "Sistema de gestión especializado para autolavados que optimiza turnos y comunicación con clientes",
    "offers": {
      "@type": "Offer",
      "price": "0",
      "priceCurrency": "USD"
    },
    "aggregateRating": {
      "@type": "AggregateRating",
      "ratingValue": "4.8",
      "reviewCount": "125"
    }
  }
  </script>
  
  <!-- Canonical URL -->
  <link rel="canonical" href="https://frosh-lavacar.com" />
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="/favicon.ico">
  
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    :root {
      --primary-color: #0d6efd;
      --secondary-color: #0dcaf0;
      --dark-color: #212529;
      --light-color: #f8f9fa;
      --success-color: #198754;
    }
    
    body {
      font-family: 'Inter', sans-serif;
      line-height: 1.6;
      color: #333;
    }
    
    .hero {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 150px 0 100px;
      position: relative;
      overflow: hidden;
    }
    
    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="white" opacity="0.05"/></svg>');
      background-size: cover;
    }
    
    .section-padding {
      padding: 100px 0;
    }
    
    @media (max-width: 768px) {
      .section-padding {
        padding: 60px 0;
      }
    }
    
    .icon {
      font-size: 48px;
      color: var(--primary-color);
      margin-bottom: 20px;
      display: inline-block;
    }
    
    .step {
      background: white;
      border-radius: 16px;
      padding: 30px;
      height: 100%;
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border: 1px solid #eaeaea;
    }
    
    .step:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }
    
    .step-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      color: white;
      font-size: 28px;
    }
    
    .cta-section {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .cta-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><circle cx="50" cy="50" r="40" fill="white" opacity="0.05"/></svg>');
      background-size: cover;
    }
    
    .demo-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      border: none;
    }
    
    .required::after {
      content: " *";
      color: #dc3545;
    }
    
    .benefit-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 15px;
    }
    
    .benefit-icon {
      color: var(--success-color);
      margin-right: 10px;
      font-size: 20px;
      flex-shrink: 0;
      margin-top: 3px;
    }
    
    .problem-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 15px;
      padding: 10px 15px;
      border-radius: 8px;
      background: #f8f9fa;
    }
    
    .problem-icon {
      margin-right: 10px;
      font-size: 20px;
      flex-shrink: 0;
      margin-top: 3px;
    }
    
    .nav-link {
      font-weight: 500;
      transition: color 0.3s ease;
    }
    
    .nav-link:hover {
      color: var(--primary-color) !important;
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
    }
    
    h1, h2, h3, h4, h5, h6 {
      font-weight: 700;
      line-height: 1.3;
      margin-bottom: 1rem;
    }
    
    .display-5 {
      font-size: 3.5rem;
      font-weight: 800;
    }
    
    @media (max-width: 768px) {
      .display-5 {
        font-size: 2.5rem;
      }
    }
    
    .lead {
      font-size: 1.25rem;
      font-weight: 400;
    }
    
    footer {
      background-color: var(--dark-color);
      color: #adb5bd;
      padding: 40px 0;
    }
    
    footer h5, footer h6 {
      color: #fff !important;
    }
    
    footer .text-muted {
      color: #adb5bd !important;
    }
    
    footer .text-white {
      color: #fff !important;
    }
    
    footer a.text-muted:hover {
      color: #fff !important;
    }
    
    .back-to-top {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      background: var(--primary-color);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      opacity: 0;
      transition: opacity 0.3s ease;
      z-index: 1000;
    }
    
    .back-to-top.visible {
      opacity: 1;
    }
    
    .highlight {
      background: linear-gradient(120deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 202, 240, 0.1) 100%);
      padding: 3px 8px;
      border-radius: 4px;
    }
    
    /* CTA Flotante para móviles */
    .mobile-cta-float {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 1000;
      display: none;
      width: calc(100% - 40px);
      max-width: 350px;
      background: linear-gradient(135deg, #274AB3, #0dcaf0);
      border-radius: 15px;
      padding: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.3);
      animation: slideUp 0.3s ease-out;
    }
    
    @keyframes slideUp {
      from {
        transform: translateX(-50%) translateY(100px);
        opacity: 0;
      }
      to {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
      }
    }
    
    .mobile-cta-float.visible {
      display: block;
    }
    
    .mobile-cta-float .btn {
      border-radius: 10px;
      font-weight: 600;
      padding: 10px 20px;
    }
    
    .mobile-cta-close {
      position: absolute;
      top: 5px;
      right: 10px;
      background: none;
      border: none;
      color: white;
      font-size: 18px;
      cursor: pointer;
      opacity: 0.7;
    }
    
    .mobile-cta-close:hover {
      opacity: 1;
    }
    
    /* Solo mostrar en móviles */
    @media (min-width: 768px) {
      .mobile-cta-float {
        display: none !important;
      }
    }
  </style>
</head>

<body>

<!-- Back to top button -->
<a href="#" id="backToTop" class="back-to-top">
  <i class="fas fa-chevron-up"></i>
</a>

<!-- CTA Flotante para móviles -->
<div id="mobileCTA" class="mobile-cta-float">
  <button class="mobile-cta-close" onclick="hideMobileCTA()">×</button>
  <div class="text-center text-white">
    <h6 class="fw-bold mb-2">¡Optimiza tu autolavado!</h6>
    <div class="d-flex gap-2">
      <a href="register.php" class="btn btn-light flex-fill">
        <i class="fas fa-rocket me-1"></i>Registrarse
      </a>
      <a href="login.php" class="btn btn-outline-light flex-fill">
        <i class="fas fa-user me-1"></i>Entrar
      </a>
    </div>
  </div>
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">FROSH LavaCar App</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="#problema">Problema</a></li>
        <li class="nav-item"><a class="nav-link" href="#funciona">Cómo funciona</a></li>
        <li class="nav-item"><a class="nav-link" href="#pasos">Estados</a></li>
        <li class="nav-item"><a class="nav-link" href="#beneficios">Beneficios</a></li>
        <li class="nav-item">
          <a class="btn btn-outline-light ms-lg-3 px-3" href="login.php">
            <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
          </a>
        </li>
        <li class="nav-item">
          <a class="btn btn-primary ms-2 px-3" href="register.php">
            <i class="fas fa-user-plus me-1"></i>Registrarse
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- HERO -->
<section class="hero text-center">
  <div class="container position-relative">
    <h1 class="display-5 fw-bold mb-4">
      Software de Gestión para <span class="highlight">Autolavados</span>
    </h1>
    <p class="lead mb-4">
      <strong>FROSH LavaCar App</strong> organiza turnos, controla cada etapa del lavado y envía notificaciones automáticas en tiempo real. 
      <br>Reduce el desorden operativo y aumenta la satisfacción de tus clientes.
    </p>
    <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
      <?php /* Comentado temporalmente - Botón Ver Demo
      <a href="#contacto" class="btn btn-light btn-lg fw-semibold px-5 py-3">
        <i class="fas fa-play-circle me-2"></i>Ver Demo
      </a>
      */ ?>
      <a href="register.php" class="btn btn-light btn-lg fw-semibold px-5 py-3">
        <i class="fas fa-rocket me-2"></i>Prueba Gratuita 7 Días
      </a>
      <a href="#funciona" class="btn btn-outline-light btn-lg fw-semibold px-5 py-3">
        <i class="fas fa-info-circle me-2"></i>Ver Cómo Funciona
      </a>
    </div>
    
    <!-- Trust indicators -->
    <div class="row mt-5 pt-4">
      <div class="col-md-3 col-6 mb-3">
        <div class="text-white">
          <h3 class="fw-bold">100%</h3>
          <p class="mb-0">Enfocado en autolavados</p>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-3">
        <div class="text-white">
          <h3 class="fw-bold">+40%</h3>
          <p class="mb-0">Eficiencia</p>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-3">
        <div class="text-white">
          <h3 class="fw-bold">99%</h3>
          <p class="mb-0">Satisfacción</p>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-3">
        <div class="text-white">
          <h3 class="fw-bold">24/7</h3>
          <p class="mb-0">Plataforma disponible</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PROBLEMA -->

<!-- TABLA COMPARATIVA - Antes vs Con FROSH -->
<section id="problema" class="section-padding bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Antes vs Con FROSH LavaCar App</h2>
      <p class="text-muted lead">La diferencia es evidente: transforma tu operación completamente</p>
    </div>

    <!-- Versión Desktop -->
    <div class="d-none d-md-block">
      <div class="table-responsive">
        <table class="table table-borderless comparison-table">
          <thead>
            <tr>
              <th class="text-center py-4" style="width: 40%;">
                <div class="comparison-header before">
                  <i class="fas fa-times-circle fa-2x mb-2 text-danger"></i>
                  <h3 class="fw-bold">Sin FroshApp</h3>
                  <p class="mb-0 text-muted">Operación tradicional</p>
                </div>
              </th>
              <th style="width: 20%;"></th>
              <th class="text-center py-4" style="width: 40%;">
                <div class="comparison-header after">
                  <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                  <h3 class="fw-bold">Con FroshApp</h3>
                  <p class="mb-0 text-muted">Operación automatizada</p>
                </div>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="comparison-cell before">
                <div class="p-3 bg-white rounded shadow-sm border-start border-danger border-3">
                  <i class="fas fa-phone-slash text-danger me-2"></i>
                  <strong>Clientes preguntando constantemente "¿Ya está mi carro?"</strong><br>
                  <small class="text-muted">Las interrupciones frecuentes afectan el flujo de trabajo y generan estrés en el personal.</small>
                </div>
              </td>
              <td class="text-center align-middle">
                <i class="fas fa-arrow-right text-primary fa-lg"></i>
              </td>
              <td class="comparison-cell after">
                <div class="p-3 bg-white rounded shadow-sm border-start border-success border-3">
                  <i class="fas fa-bell text-success me-2"></i>
                  <strong>Comunicación automatizada con el cliente</strong><br>
                  <small class="text-muted">Los clientes reciben actualizaciones claras y oportunas sobre el estado de su vehículo, reduciendo interrupciones.</small>
                </div>
              </td>
            </tr>
            <tr>
              <td class="comparison-cell before">
                <div class="p-3 bg-white rounded shadow-sm border-start border-danger border-3">
                  <i class="fas fa-question-circle text-danger me-2"></i>
                  <strong>Desorganización en la fila de vehículos</strong><br>
                  <small class="text-muted">Confusión sobre qué auto sigue, prioridades mal definidas y tiempos de espera poco claros.</small>
                </div>
              </td>
              <td class="text-center align-middle">
                <i class="fas fa-arrow-right text-primary fa-lg"></i>
              </td>
              <td class="comparison-cell after">
                <div class="p-3 bg-white rounded shadow-sm border-start border-success border-3">
                  <i class="fas fa-list-ol text-success me-2"></i>
                  <strong>Gestión ordenada de la fila de trabajo</strong><br>
                  <small class="text-muted">El sistema organiza automáticamente los vehículos según prioridad y etapa del servicio.</small>
                </div>
              </td>
            </tr>
            <tr>
              <td class="comparison-cell before">
                <div class="p-3 bg-white rounded shadow-sm border-start border-danger border-3">
                  <i class="fas fa-eye-slash text-danger me-2"></i>
                  <strong>Falta de control del proceso</strong><br>
                  <small class="text-muted">Dificultad para saber en qué etapa se encuentra cada vehículo y cuánto tiempo falta para finalizarlo.</small>
                </div>
              </td>
              <td class="text-center align-middle">
                <i class="fas fa-arrow-right text-primary fa-lg"></i>
              </td>
              <td class="comparison-cell after">
                <div class="p-3 bg-white rounded shadow-sm border-start border-success border-3">
                  <i class="fas fa-tasks text-success me-2"></i>
                  <strong>Control total del proceso</strong><br>
                  <small class="text-muted">Visualización en tiempo real del estado de cada vehículo y de los tiempos estimados.</small>
                </div>
              </td>
            </tr>
            <tr>
              <td class="comparison-cell before">
                <div class="p-3 bg-white rounded shadow-sm border-start border-danger border-3">
                  <i class="fas fa-angry text-danger me-2"></i>
                  <strong>Reclamos por tiempos de espera</strong><br>
                  <small class="text-muted">Clientes insatisfechos debido a la falta de información sobre demoras.</small>
                </div>
              </td>
              <td class="text-center align-middle">
                <i class="fas fa-arrow-right text-primary fa-lg"></i>
              </td>
              <td class="comparison-cell after">
                <div class="p-3 bg-white rounded shadow-sm border-start border-success border-3">
                  <i class="fas fa-smile text-success me-2"></i>
                  <strong>Mayor transparencia y satisfacción del cliente</strong><br>
                  <small class="text-muted">Información clara que reduce reclamos y mejora la experiencia del usuario.</small>
                </div>
              </td>
            </tr>
            <tr>
              <td class="comparison-cell before">
                <div class="p-3 bg-white rounded shadow-sm border-start border-danger border-3">
                  <i class="fas fa-clock text-danger me-2"></i>
                  <strong>Pérdida de eficiencia operativa</strong><br>
                  <small class="text-muted">Tiempo valioso empleado en exceso en coordinar manualmente el flujo de trabajo.</small>
                </div>
              </td>
              <td class="text-center align-middle">
                <i class="fas fa-arrow-right text-primary fa-lg"></i>
              </td>
              <td class="comparison-cell after">
                <div class="p-3 bg-white rounded shadow-sm border-start border-success border-3">
                  <i class="fas fa-rocket text-success me-2"></i>
                  <strong>Optimización de la eficiencia operativa</strong><br>
                  <small class="text-muted">Automatización del flujo de trabajo que permite al equipo enfocarse en el servicio.</small>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Versión Mobile -->
    <div class="d-md-none">
      <div class="comparison-mobile">
        <!-- Item 1 -->
        <div class="comparison-item-single mb-4">
          <div class="card shadow-sm">
            <div class="card-body p-3">
              <!-- Sin FroshApp -->
              <div class="comparison-side mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-start">
                  <i class="fas fa-times-circle text-danger me-2 fa-lg flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <div class="badge bg-danger mb-2">Sin FroshApp</div>
                    <p class="mb-1 fw-bold">Clientes preguntando constantemente "¿Ya está mi carro?"</p>
                    <small class="text-muted">Las interrupciones frecuentes afectan el flujo de trabajo y generan estrés en el personal.</small>
                  </div>
                </div>
              </div>
              <!-- Con FroshApp -->
              <div class="comparison-side">
                <div class="d-flex align-items-start">
                  <i class="fas fa-check-circle text-success me-2 fa-lg flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <div class="badge bg-success mb-2">Con FroshApp</div>
                    <p class="mb-1 fw-bold">Comunicación automatizada con el cliente</p>
                    <small class="text-muted">Los clientes reciben actualizaciones claras y oportunas sobre el estado de su vehículo, reduciendo interrupciones.</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Item 2 -->
        <div class="comparison-item-single mb-4">
          <div class="card shadow-sm">
            <div class="card-body p-3">
              <!-- Sin FroshApp -->
              <div class="comparison-side mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-start">
                  <i class="fas fa-times-circle text-danger me-2 fa-lg flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <div class="badge bg-danger mb-2">Sin FroshApp</div>
                    <p class="mb-1 fw-bold">Desorganización en la fila de vehículos</p>
                    <small class="text-muted">Confusión sobre qué auto sigue, prioridades mal definidas y tiempos de espera poco claros.</small>
                  </div>
                </div>
              </div>
              <!-- Con FroshApp -->
              <div class="comparison-side">
                <div class="d-flex align-items-start">
                  <i class="fas fa-check-circle text-success me-2 fa-lg flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <div class="badge bg-success mb-2">Con FroshApp</div>
                    <p class="mb-1 fw-bold">Gestión ordenada de la fila de trabajo</p>
                    <small class="text-muted">El sistema organiza automáticamente los vehículos según prioridad y etapa del servicio.</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Item 3 -->
        <div class="comparison-item-single mb-4">
          <div class="card shadow-sm">
            <div class="card-body p-3">
              <!-- Sin FroshApp -->
              <div class="comparison-side mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-start">
                  <i class="fas fa-times-circle text-danger me-2 fa-lg flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <div class="badge bg-danger mb-2">Sin FroshApp</div>
                    <p class="mb-1 fw-bold">Falta de control del proceso</p>
                    <small class="text-muted">Dificultad para saber en qué etapa se encuentra cada vehículo y cuánto tiempo falta para finalizarlo.</small>
                  </div>
                </div>
              </div>
              <!-- Con FroshApp -->
              <div class="comparison-side">
                <div class="d-flex align-items-start">
                  <i class="fas fa-check-circle text-success me-2 fa-lg flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <div class="badge bg-success mb-2">Con FroshApp</div>
                    <p class="mb-1 fw-bold">Control total del proceso</p>
                    <small class="text-muted">Visualización en tiempo real del estado de cada vehículo y de los tiempos estimados.</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Item 4 -->
        <div class="comparison-item-single mb-4">
          <div class="card shadow-sm">
            <div class="card-body p-3">
              <!-- Sin FroshApp -->
              <div class="comparison-side mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-start">
                  <i class="fas fa-times-circle text-danger me-2 fa-lg flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <div class="badge bg-danger mb-2">Sin FroshApp</div>
                    <p class="mb-1 fw-bold">Reclamos por tiempos de espera</p>
                    <small class="text-muted">Clientes insatisfechos debido a la falta de información sobre demoras.</small>
                  </div>
                </div>
              </div>
              <!-- Con FroshApp -->
              <div class="comparison-side">
                <div class="d-flex align-items-start">
                  <i class="fas fa-check-circle text-success me-2 fa-lg flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <div class="badge bg-success mb-2">Con FroshApp</div>
                    <p class="mb-1 fw-bold">Mayor transparencia y satisfacción del cliente</p>
                    <small class="text-muted">Información clara que reduce reclamos y mejora la experiencia del usuario.</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Item 5 -->
        <div class="comparison-item-single mb-4">
          <div class="card shadow-sm">
            <div class="card-body p-3">
              <!-- Sin FroshApp -->
              <div class="comparison-side mb-3 pb-3 border-bottom">
                <div class="d-flex align-items-start">
                  <i class="fas fa-times-circle text-danger me-2 fa-lg flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <div class="badge bg-danger mb-2">Sin FroshApp</div>
                    <p class="mb-1 fw-bold">Pérdida de eficiencia operativa</p>
                    <small class="text-muted">Tiempo valioso empleado en exceso en coordinar manualmente el flujo de trabajo.</small>
                  </div>
                </div>
              </div>
              <!-- Con FroshApp -->
              <div class="comparison-side">
                <div class="d-flex align-items-start">
                  <i class="fas fa-check-circle text-success me-2 fa-lg flex-shrink-0"></i>
                  <div class="flex-grow-1">
                    <div class="badge bg-success mb-2">Con FroshApp</div>
                    <p class="mb-1 fw-bold">Optimización de la eficiencia operativa</p>
                    <small class="text-muted">Automatización del flujo de trabajo que permite al equipo enfocarse en el servicio.</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA al final de la comparación -->
    <div class="text-center mt-5">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card border-primary border-2 shadow-sm">
            <div class="card-body p-4">
              <h4 class="fw-bold text-primary mb-3">
                <i class="fas fa-lightbulb me-2"></i>¿Por qué seguir con los problemas del pasado?
              </h4>
              <p class="mb-4">Haz la transición a una operación moderna y eficiente. Tus clientes y tu equipo te lo agradecerán.</p>
              <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="register.php" class="btn btn-primary btn-lg fw-semibold px-4">
                  <i class="fas fa-arrow-right me-2"></i>Hacer el Cambio Ahora
                </a>
                <a href="login.php" class="btn btn-outline-primary btn-lg fw-semibold px-4">
                  <i class="fas fa-user me-2"></i>Ya soy usuario
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>





<!-- CTA ESTRATÉGICO 1 - Después de problemas -->
<section class="py-4 bg-primary">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8 text-center text-md-start">
        <h4 class="text-white fw-bold mb-2">¿Reconoces estos problemas en tu autolavado?</h4>
        <p class="text-white-50 mb-0">FROSH LavaCar App los resuelve todos automáticamente</p>
      </div>
      <div class="col-md-4 text-center mt-3 mt-md-0">
        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center justify-content-md-end">
          <a href="register.php" class="btn btn-light fw-semibold px-4">
            <i class="fas fa-rocket me-2"></i>Registrarse
          </a>
          <a href="login.php" class="btn btn-outline-light fw-semibold px-4">
            <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CÓMO FUNCIONA -->
<section id="funciona" class="section-padding bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">¿Cómo funciona FROSH LavaCar App?</h2>
      <p class="text-muted lead">Una solución simple pero poderosa para optimizar tu operación diaria</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="step text-center h-100">
          <div class="step-icon">
            <i class="fas fa-car"></i>
          </div>
          <h4 class="fw-bold">Registro Rápido</h4>
          <p>
            Registra vehículos en segundos con datos del cliente y tipo de servicio. 
            <strong>Asignación automática</strong> a la fila de lavado con tiempo estimado.
          </p>
          <div class="mt-3">
            <span class="badge bg-primary">1 minuto</span>
            <span class="badge bg-secondary ms-2">Sin papel</span>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="step text-center h-100">
          <div class="step-icon">
            <i class="fas fa-tasks"></i>
          </div>
          <h4 class="fw-bold">Control de Estados</h4>
          <p>
            Actualiza el estado del vehículo <strong>con un solo toque</strong>. Visualiza el progreso en tiempo real y asigna tareas al personal específico.
          </p>
          <div class="mt-3">
            <span class="badge bg-primary">Tiempo real</span>
            <span class="badge bg-secondary ms-2">Seguimiento</span>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="step text-center h-100">
          <div class="step-icon">
            <i class="fas fa-bell"></i>
          </div>
          <h4 class="fw-bold">Notificaciones Automáticas</h4>
          <p>
            El cliente recibe <strong>avisos automáticos</strong> por Correo Electronico(Email) cuando su vehículo avanza o finaliza. Sin intervención manual.
          </p>
          <div class="mt-3">
            <span class="badge bg-primary">Automático</span>
            <span class="badge bg-secondary ms-2">Multiplataforma</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA ESTRATÉGICO 2 - Después de "Cómo funciona" -->
<section class="py-5 bg-light border-top border-bottom">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-md-8 text-center text-md-start">
        <h3 class="fw-bold mb-2">¿Listo para automatizar tu autolavado?</h3>
        <p class="text-muted mb-0">Comienza en menos de 5 minutos. Sin instalaciones complicadas.</p>
      </div>
      <div class="col-md-4 text-center mt-3 mt-md-0">
        <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center justify-content-md-end">
          <a href="register.php" class="btn btn-primary fw-semibold px-4 py-2">
            <i class="fas fa-play-circle me-2"></i>Empezar Ahora
          </a>
          <a href="login.php" class="btn btn-outline-primary fw-semibold px-4 py-2">
            <i class="fas fa-user me-2"></i>Ya tengo cuenta
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ESTADOS -->
<section id="pasos" class="section-padding">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Estados del Lavado en Tiempo Real</h2>
      <p class="text-muted lead">Transparencia total: el cliente siempre sabe en qué etapa está su vehículo</p>
    </div>

    <div class="row g-4 justify-content-center">
      <div class="col-12 col-md-4">
        <div class="step text-center h-100">
          <div class="step-icon bg-warning text-dark">
            <i class="fas fa-clock"></i>
          </div>
          <h4 class="fw-bold">⏳ En Espera</h4>
          <p>El vehículo está en fila esperando su turno. El cliente recibe notificación con tiempo estimado de inicio.</p>
          <div class="mt-3">
            <span class="badge bg-warning text-dark">Notificación enviada</span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="step text-center h-100">
          <div class="step-icon bg-info text-white">
            <i class="fas fa-soap"></i>
          </div>
          <h4 class="fw-bold">🧼 En Proceso</h4>
          <p>El proceso de lavado ha comenzado. El cliente es notificado y puede ver el progreso estimado.</p>
          <div class="mt-3">
            <span class="badge bg-info">En curso</span>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="step text-center h-100">
          <div class="step-icon bg-success text-white">
            <i class="fas fa-check-circle"></i>
          </div>
          <h4 class="fw-bold">✅ Finalizado</h4>
          <p>El vehículo está listo para retirar. Notificación automática con instrucciones de pago y retiro.</p>
          <div class="mt-3">
            <span class="badge bg-success">Listo</span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Additional status -->
    <div class="row mt-4 justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="step text-center">
          <div class="step-icon bg-secondary text-white">
            <i class="fas fa-user-check"></i>
          </div>
          <h4 class="fw-bold">📋 Entregado al Cliente</h4>
          <p>Confirmación de entrega y registro de satisfacción. Datos almacenados para historial y análisis.</p>
          <div class="mt-3">
            <span class="badge bg-secondary">Completado</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- BENEFICIOS -->
<section id="beneficios" class="section-padding bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Beneficios Tangibles para tu Negocio</h2>
      <p class="text-muted lead">Resultados comprobados que impactan directamente en tu rentabilidad</p>
    </div>

    <div class="row g-4">
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body p-4">
            <h4 class="fw-bold text-primary"><i class="fas fa-chart-line me-2"></i> Eficiencia Operativa</h4>
            <div class="benefit-item">
              <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
              <div>
                <h6 class="fw-bold">Organización clara de turnos</h6>
                <p class="mb-0">Reduce tiempos muertos y optimiza el uso de personal y recursos.</p>
              </div>
            </div>
            <div class="benefit-item">
              <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
              <div>
                <h6 class="fw-bold">Menos errores y reclamos</h6>
                <p class="mb-0">Sistema automatizado minimiza errores humanos y malentendidos.</p>
              </div>
            </div>
            <div class="benefit-item">
              <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
              <div>
                <h6 class="fw-bold">Ahorro de tiempo operativo</h6>
                <p class="mb-0">Hasta 40% menos tiempo en coordinación y gestión manual.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-body p-4">
            <h4 class="fw-bold text-primary"><i class="fas fa-users me-2"></i> Experiencia del Cliente</h4>
            <div class="benefit-item">
              <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
              <div>
                <h6 class="fw-bold">Imagen moderna y profesional</h6>
                <p class="mb-0">Tecnología que diferencia tu autolavado de la competencia tradicional.</p>
              </div>
            </div>
            <div class="benefit-item">
              <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
              <div>
                <h6 class="fw-bold">Mejor experiencia del cliente</h6>
                <p class="mb-0">Transparencia y comunicación proactiva aumentan la satisfacción.</p>
              </div>
            </div>
            <div class="benefit-item">
              <div class="benefit-icon"><i class="fas fa-check-circle"></i></div>
              <div>
                <h6 class="fw-bold">Fidelización de clientes</h6>
                <p class="mb-0">Clientes satisfechos regresan y recomiendan tu servicio.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- ROI Section -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="card border-primary border-2">
          <div class="card-body p-4 text-center">
            <h3 class="fw-bold text-primary mb-3"><i class="fas fa-dollar-sign me-2"></i> Retorno de Inversión Garantizado</h3>
            <div class="row">
              <div class="col-md-4 mb-3">
                <h2 class="fw-bold text-success">+25%</h2>
                <p class="mb-0">Capacidad de atención</p>
              </div>
              <div class="col-md-4 mb-3">
                <h2 class="fw-bold text-success">-60%</h2>
                <p class="mb-0">Llamadas de seguimiento</p>
              </div>
              <div class="col-md-4 mb-3">
                <h2 class="fw-bold text-success">+35%</h2>
                <p class="mb-0">Satisfacción cliente</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA ESTRATÉGICO 3 - Después de beneficios -->
<section class="py-5" style="background: linear-gradient(135deg, #274AB3, #0dcaf0);">
  <div class="container">
    <div class="text-center text-white">
      <h2 class="fw-bold mb-3 text-white">¡Transforma tu autolavado hoy mismo!</h2>
      <p class="lead mb-4 text-white">Únete a cientos de autolavados que ya optimizaron su operación</p>
      <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
        <a href="register.php" class="btn btn-light btn-lg fw-semibold px-5 py-3">
          <i class="fas fa-rocket me-2"></i>Crear Cuenta Gratis
        </a>
        <a href="login.php" class="btn btn-outline-light btn-lg fw-semibold px-5 py-3">
          <i class="fas fa-sign-in-alt me-2"></i>Acceder a mi cuenta
        </a>
      </div>
      <p class="mt-3 mb-0 small text-white">
        <i class="fas fa-shield-alt me-1"></i> Configuración en 5 minutos
      </p>
    </div>
  </div>
</section>

<?php /* Comentado temporalmente - Formulario de Demo
<!-- FORM -->
<section id="contacto" class="section-padding cta-section">
  <div class="container position-relative">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="demo-card p-4 p-md-5">
          <div class="card-body">
            <div class="text-center mb-4">
              <h2 class="fw-bold demo-title">Solicita Tu Demo Gratuita</h2>
              <p class="text-muted">
                Descubre cómo FROSH LavaCar App puede transformar la gestión de tu autolavado. 
                <strong>Sin compromiso, sin costo inicial.</strong>
              </p>
            </div>

            <form id="demoForm" method="post">
              <div class="row">
                <!-- Nombre -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Nombre completo</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="nombre" class="form-control" placeholder="Juan Pérez" required aria-label="Nombre completo">
                  </div>
                </div>

                <!-- Empresa -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Nombre del autolavado/empresa</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                    <input type="text" name="empresa" class="form-control" placeholder="Ej: AutoLimpio Express" required aria-label="Nombre de empresa">
                  </div>
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Correo electrónico</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="correo@empresa.com" required aria-label="Correo electrónico">
                  </div>
                </div>

                <!-- Teléfono -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Teléfono</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" name="telefono" class="form-control" placeholder="+506 8888 8888" required aria-label="Teléfono">
                  </div>
                </div>

                <!-- Tipo de negocio -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Tipo de negocio</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                    <select name="tipo_negocio" class="form-select" required aria-label="Tipo de negocio">
                      <option value="">Seleccionar</option>
                      <option>Autolavado tradicional</option>
                      <option>Estación de lavado premium/detallado</option>
                      <option>Concesionario de autos</option>
                      <option>Flota empresarial</option>
                      <option>Centro de servicio múltiple</option>
                      <option>Otro</option>
                    </select>
                  </div>
                </div>

                <!-- Cantidad de vehículos -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Vehículos diarios promedio</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                    <select name="vehiculos_diarios" class="form-select" required aria-label="Vehículos diarios">
                      <option value="">Seleccionar</option>
                      <option>1-10 vehículos</option>
                      <option>11-25 vehículos</option>
                      <option>26-50 vehículos</option>
                      <option>51-100 vehículos</option>
                      <option>Más de 100 vehículos</option>
                    </select>
                  </div>
                </div>

                <!-- Mensaje -->
                <div class="col-12 mb-4">
                  <label class="form-label">¿Qué desafíos específicos enfrentas en tu autolavado?</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-comment-dots"></i></span>
                    <textarea name="mensaje" class="form-control" rows="4" placeholder="Cuéntanos sobre los principales problemas que quieres resolver en tu operación..." aria-label="Mensaje"></textarea>
                  </div>
                </div>

                <!-- Captcha -->
                <div class="col-12 mb-4">
                  <label class="form-label required">Pregunta de seguridad</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                    <input type="text" readonly class="form-control" id="captchaQuestion" value="Cargando..." style="background: #f8f9fa;">
                    <input type="number" name="captcha_answer" class="form-control" placeholder="Tu respuesta" required aria-label="Respuesta captcha" style="max-width: 150px;">
                  </div>
                  <small class="text-muted">Esta pregunta nos ayuda a prevenir spam</small>
                </div>

                <!-- Botón -->
                <div class="col-12">
                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold" id="submitBtn">
                      <i class="fas fa-calendar-check me-2"></i> Solicitar Demo Personalizada
                    </button>
                  </div>
                  <p class="text-center text-muted mt-3 small">
                    <i class="fas fa-shield-alt me-1"></i> Tus datos están seguros. No compartimos información con terceros.
                  </p>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
*/ ?>

<!-- FOOTER -->
<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel">
          <i class="fas fa-check-circle me-2"></i>¡Solicitud Enviada!
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <i class="fas fa-check-circle text-success" style="font-size: 4rem; margin-bottom: 1rem;"></i>
        <h4 class="mb-3">¡Gracias por tu interés!</h4>
        <p class="mb-0" id="successMessage">
          Nos pondremos en contacto contigo en las próximas 24 horas para coordinar tu demo personalizada.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Entendido</button>
      </div>
    </div>
  </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="errorModalLabel">
          <i class="fas fa-exclamation-circle me-2"></i>Error
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem; margin-bottom: 1rem;"></i>
        <p class="mb-0" id="errorMessage">
          Ocurrió un error al procesar tu solicitud.
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<footer class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mb-4">
        <h4 class="fw-bold text-white mb-4">
          <i class="fas fa-car me-2"></i>FROSH LavaCar App
        </h4>
        <p>Solución de gestión especializada para Autolavados. Optimizamos tu operación y mejoramos la experiencia de tus clientes.</p>

      </div>
      
      <div class="col-lg-4 mb-4">
        <h5 class="text-white fw-bold mb-4">Enlaces rápidos</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="#problema" class="text-decoration-none text-light">Problemas que resolvemos</a></li>
          <li class="mb-2"><a href="#funciona" class="text-decoration-none text-light">Cómo funciona</a></li>
          <li class="mb-2"><a href="#beneficios" class="text-decoration-none text-light">Beneficios</a></li>
          <li class="mb-2"><a href="register.php" class="text-decoration-none text-light">Registrarse</a></li>
        </ul>
      </div>
      
      <div class="col-lg-4 mb-4">
        <h5 class="text-white fw-bold mb-4">Contacto</h5>

               <!-- <div class="d-flex gap-3 mt-4">
          <a href="#" class="text-white" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-white" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" class="text-white" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
          <a href="https://wa.me/50663957241" class="text-white" aria-label="WhatsApp" target="_blank"><i class="fab fa-whatsapp"></i></a>
        </div>-->
        <ul class="list-unstyled"><li class="mb-3">
            <a href="https://wa.me/50663957241" class="text-white text-decoration-none" aria-label="WhatsApp" target="_blank"><i class="fab fa-whatsapp"></i> Whatsapp</a>

          </li>
          <li class="mb-3">
            <i class="fas fa-envelope me-2"></i>
            froshsystems@gmail.com
          </li>
          <!--<li class="mb-3">
            <i class="fas fa-phone me-2"></i>
            <a href="tel:+50663957241" class="text-decoration-none text-light">+506 63957241</a>
          </li>-->
          <li>
            <i class="fas fa-map-marker-alt me-2"></i>
            <span class="text-light">Alajuela, Costa Rica</span>
          </li>
        </ul>
      </div>
    </div>
    
    <hr class="my-4 border-secondary">
    
    <div class="text-center">
      <p class="mb-2">© <?php echo date('Y');?> <strong>Frosh Systems</strong> - Sistema de Gestión para Autolavados</p>
      <small class="text-muted">Optimiza tu operación, mejora la satisfacción de tus clientes y haz crecer tu negocio</small>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
  // Load captcha on page load
  function loadCaptcha() {
    fetch('generate-captcha.php')
      .then(response => response.json())
      .then(data => {
        document.getElementById('captchaQuestion').value = data.question;
      })
      .catch(error => {
        console.error('Error loading captcha:', error);
        document.getElementById('captchaQuestion').value = 'Error al cargar pregunta';
      });
  }
  
  // Back to top button functionality
  document.addEventListener('DOMContentLoaded', function() {
    <?php /* Comentado temporalmente - Captcha para demo form
    // Load captcha
    loadCaptcha();
    */ ?>
    
    const backToTopButton = document.getElementById('backToTop');
    const mobileCTA = document.getElementById('mobileCTA');
    let ctaShown = false;
    let ctaHidden = false;
    
    window.addEventListener('scroll', function() {
      // Back to top button
      if (window.scrollY > 300) {
        backToTopButton.classList.add('visible');
      } else {
        backToTopButton.classList.remove('visible');
      }
      
      // Mobile CTA - mostrar después de scroll significativo
      if (window.scrollY > 800 && !ctaShown && !ctaHidden && window.innerWidth < 768) {
        mobileCTA.classList.add('visible');
        ctaShown = true;
        
        // Auto-hide después de 10 segundos
        setTimeout(() => {
          if (!ctaHidden) {
            hideMobileCTA();
          }
        }, 10000);
      }
    });
    
    backToTopButton.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
    
    // Función global para ocultar CTA móvil
    window.hideMobileCTA = function() {
      mobileCTA.classList.remove('visible');
      ctaHidden = true;
    };
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        if (targetId === '#') return;
        
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
          e.preventDefault();
          window.scrollTo({
            top: targetElement.offsetTop - 80,
            behavior: 'smooth'
          });
          
          // Update URL without page jump
          history.pushState(null, null, targetId);
        }
      });
    });
    
    <?php /* Comentado temporalmente - Form submission para demo
    // Form submission with AJAX
    const demoForm = document.getElementById('demoForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (demoForm) {
      demoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Enviando...';
        
        // Get form data
        const formData = new FormData(demoForm);
        
        // Send request
        fetch('process-demo-request.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Show success modal
            document.getElementById('successMessage').textContent = data.message;
            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();
            // Reset form
            demoForm.reset();
            // Reload captcha
            loadCaptcha();
          } else {
            // Show error modal
            document.getElementById('errorMessage').textContent = data.message;
            const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
            errorModal.show();
            // Reload captcha on error
            loadCaptcha();
          }
        })
        .catch(error => {
          console.error('Error:', error);
          document.getElementById('errorMessage').textContent = 'Ocurrió un error al enviar el formulario. Por favor, intenta de nuevo.';
          const errorModal = new bootstrap.Modal(document.getElementById('errorModal'));
          errorModal.show();
          loadCaptcha();
        })
        .finally(() => {
          // Re-enable submit button
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="fas fa-calendar-check me-2"></i> Solicitar Demo Personalizada';
        });
      });
    }
    */ ?>
  });
</script>
</body>
</html>