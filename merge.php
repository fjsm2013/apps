<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Primary Meta Tags -->
  <title>FROSH LavaCar App | Sistema de Gestión para Lavaderos de Autos</title>
  <meta name="description" content="Sistema de gestión especializado para lavaderos de autos. Organiza turnos, controla estados de lavado y envía notificaciones automáticas a clientes. Mejora la eficiencia operativa y la satisfacción del cliente." />
  <meta name="keywords" content="app para lavacar, sistema gestión lavaderos, software lavado de autos, turnos lavacar, notificaciones automáticas lavado, gestión de clientes lavaderos, app lavado vehículos" />
  <meta name="robots" content="index, follow" />
  <meta name="author" content="FROSH LavaCar App" />
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website" />
  <meta property="og:title" content="FROSH LavaCar App | Sistema de Gestión para Lavaderos de Autos" />
  <meta property="og:description" content="Solución completa para gestionar tu lavadero de autos eficientemente" />
  <meta property="og:image" content="https://ejemplo.com/og-image.jpg" />
  <meta property="og:url" content="https://frosh-lavacar.com" />
  <meta property="og:site_name" content="FROSH LavaCar App" />
  <meta property="og:locale" content="es_ES" />
  
  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="FROSH LavaCar App | Sistema de Gestión para Lavaderos" />
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
    "description": "Sistema de gestión especializado para lavaderos de autos que optimiza turnos y comunicación con clientes",
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
  </style>
</head>

<body>

<!-- Back to top button -->
<a href="#" id="backToTop" class="back-to-top">
  <i class="fas fa-chevron-up"></i>
</a>

<!-- NAVBAR -->
  <?php include_once "menu-frosh-lavacarpp.php" ?>

<!-- HERO -->
<section class="hero text-center">
  <div class="container position-relative">
    <h1 class="display-5 fw-bold mb-4">
      Software de Gestión para <span class="highlight">Lavaderos de Autos</span>
    </h1>
    <p class="lead mb-4">
      <strong>FROSH LavaCar App</strong> organiza turnos, controla cada etapa del lavado y envía notificaciones automáticas en tiempo real. 
      <br>Reduce el desorden operativo y aumenta la satisfacción de tus clientes.
    </p>
    <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
      <a href="#contacto" class="btn btn-light btn-lg fw-semibold px-5 py-3">
        <i class="fas fa-play-circle me-2"></i>Solicitar demo gratuita
      </a>
      <a href="#funciona" class="btn btn-outline-light btn-lg fw-semibold px-5 py-3">
        <i class="fas fa-info-circle me-2"></i>Ver cómo funciona
      </a>
    </div>
    
    <!-- Trust indicators -->
    <div class="row mt-5 pt-4">
      <div class="col-md-3 col-6 mb-3">
        <div class="text-white">
          <h3 class="fw-bold">+500</h3>
          <p class="mb-0">Lavaderos</p>
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
          <p class="mb-0">Soporte</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- PROBLEMA -->
<section id="problema" class="section-padding">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="fw-bold">¿Estás enfrentando estos problemas en tu lavadero?</h2>
      <p class="text-muted lead">Identificamos los desafíos comunes que afectan la productividad de los lavaderos de autos</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6">
        <div class="problem-item">
          <div class="problem-icon text-danger"><i class="fas fa-times-circle"></i></div>
          <div>
            <h5 class="fw-bold">Clientes preguntando constantemente</h5>
            <p class="mb-0">"¿Ya está mi carro?" interrumpe tu flujo de trabajo y genera estrés en el personal.</p>
          </div>
        </div>
        
        <div class="problem-item">
          <div class="problem-icon text-danger"><i class="fas fa-times-circle"></i></div>
          <div>
            <h5 class="fw-bold">Desorganización en la fila de vehículos</h5>
            <p class="mb-0">Confusión sobre qué auto sigue, prioridades y tiempos de espera no claros.</p>
          </div>
        </div>
        
        <div class="problem-item">
          <div class="problem-icon text-danger"><i class="fas fa-times-circle"></i></div>
          <div>
            <h5 class="fw-bold">Falta de control del proceso</h5>
            <p class="mb-0">No saber exactamente en qué etapa está cada vehículo y cuánto tiempo llevará.</p>
          </div>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="problem-item">
          <div class="problem-icon text-danger"><i class="fas fa-times-circle"></i></div>
          <div>
            <h5 class="fw-bold">Reclamos por tiempos de espera</h5>
            <p class="mb-0">Clientes insatisfechos por falta de información sobre demoras y esperas.</p>
          </div>
        </div>
        
        <div class="problem-item">
          <div class="problem-icon text-danger"><i class="fas fa-times-circle"></i></div>
          <div>
            <h5 class="fw-bold">Pérdida de eficiencia operativa</h5>
            <p class="mb-0">Tiempo valioso desperdiciado en coordinar manualmente el flujo de trabajo.</p>
          </div>
        </div>
        
        <div class="problem-item bg-primary text-white">
          <div class="problem-icon text-white"><i class="fas fa-check-circle"></i></div>
          <div>
            <h5 class="fw-bold">Solución Integral FROSH</h5>
            <p class="mb-0"><strong>FROSH LavaCar App elimina estos problemas</strong> con un sistema automatizado de gestión.</p>
          </div>
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
            El cliente recibe <strong>avisos automáticos</strong> por SMS o WhatsApp cuando su vehículo avanza o finaliza. Sin intervención manual.
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
                <p class="mb-0">Tecnología que diferencia tu lavadero de la competencia tradicional.</p>
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
                Descubre cómo FROSH LavaCar App puede transformar la gestión de tu lavadero. 
                <strong>Sin compromiso, sin costo inicial.</strong>
              </p>
            </div>

            <form action="#" method="post" id="demoForm">
              <div class="row">
                <!-- Nombre -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Nombre completo</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="Juan Pérez" required aria-label="Nombre completo">
                  </div>
                </div>

                <!-- Empresa -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Nombre del lavadero/empresa</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                    <input type="text" class="form-control" placeholder="Ej: AutoLimpio Express" required aria-label="Nombre de empresa">
                  </div>
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Correo electrónico</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" placeholder="correo@empresa.com" required aria-label="Correo electrónico">
                  </div>
                </div>

                <!-- Teléfono -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Teléfono</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="tel" class="form-control" placeholder="+506 8888 8888" required aria-label="Teléfono">
                  </div>
                </div>

                <!-- Tipo de negocio -->
                <div class="col-md-6 mb-3">
                  <label class="form-label required">Tipo de negocio</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                    <select class="form-select" required aria-label="Tipo de negocio">
                      <option value="">Seleccionar</option>
                      <option>Lavacar tradicional</option>
                      <option>Lavadero premium/detallado</option>
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
                    <select class="form-select" required aria-label="Vehículos diarios">
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
                  <label class="form-label">¿Qué desafíos específicos enfrentas en tu lavadero?</label>
                  <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-comment-dots"></i></span>
                    <textarea class="form-control" rows="4" placeholder="Cuéntanos sobre los principales problemas que quieres resolver en tu operación..." aria-label="Mensaje"></textarea>
                  </div>
                </div>

                <!-- Botón -->
                <div class="col-12">
                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
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

<!-- FOOTER -->
<footer class="py-5">
  <?php include_once "footer-frosh-lavacar-app.php" ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
  // Back to top button functionality
  document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
      if (window.scrollY > 300) {
        backToTopButton.classList.add('visible');
      } else {
        backToTopButton.classList.remove('visible');
      }
    });
    
    backToTopButton.addEventListener('click', function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
    
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
    
    // Form submission
    const demoForm = document.getElementById('demoForm');
    if (demoForm) {
      demoForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Simple validation
        let isValid = true;
        const requiredFields = demoForm.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
          if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
          } else {
            field.classList.remove('is-invalid');
          }
        });
        
        if (isValid) {
          // In a real implementation, you would send the form data to a server
          alert('¡Gracias por tu interés! Nos pondremos en contacto contigo en las próximas 24 horas para coordinar tu demo personalizada.');
          demoForm.reset();
        } else {
          alert('Por favor, completa todos los campos requeridos marcados con *.');
        }
      });
    }
    
    // Add Bootstrap validation styling
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
      form.addEventListener('submit', event => {
        if (!form.checkValidity()) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  });
</script>
</body>
</html>