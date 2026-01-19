<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  
  <!-- Primary Meta Tags -->
  <title>Error del Sistema - FROSH LavaCar App</title>
  <meta name="description" content="Ha ocurrido un error en el sistema. Nuestro equipo técnico ha sido notificado y está trabajando para solucionarlo." />
  <meta name="robots" content="noindex, nofollow" />
  
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
      --warning-color: #ffc107;
      --danger-color: #dc3545;
    }
    
    body {
      font-family: 'Inter', sans-serif;
      line-height: 1.6;
      color: #333;
      background: linear-gradient(135deg, #fff5f5 0%, #ffe6e6 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }
    
    .navbar {
      background: var(--dark-color) !important;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .navbar-brand {
      font-weight: 700;
      font-size: 1.5rem;
      color: white !important;
    }
    
    .nav-link {
      font-weight: 500;
      transition: color 0.3s ease;
      color: rgba(255,255,255,0.8) !important;
    }
    
    .nav-link:hover {
      color: var(--primary-color) !important;
    }
    
    .error-container {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px 0;
    }
    
    .error-content {
      text-align: center;
      max-width: 700px;
      padding: 50px 40px;
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(220, 53, 69, 0.1);
      border: 1px solid #f8d7da;
      position: relative;
      overflow: hidden;
      margin: 0 auto;
    }
    
    .error-content::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--danger-color), #ff6b6b);
    }
    
    .error-icon {
      font-size: 7rem;
      color: var(--danger-color);
      margin-bottom: 30px;
      display: block;
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% {
        transform: scale(1);
        opacity: 1;
      }
      50% {
        transform: scale(1.05);
        opacity: 0.8;
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }
    
    .error-title {
      font-size: 3.5rem;
      font-weight: 800;
      color: var(--danger-color);
      margin-bottom: 20px;
      line-height: 1.2;
    }
    
    .error-subtitle {
      font-size: 1.8rem;
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 25px;
    }
    
    .error-description {
      font-size: 1.1rem;
      color: #6c757d;
      margin-bottom: 30px;
      line-height: 1.7;
    }
    
    .error-details {
      background: #f8f9fa;
      border-left: 4px solid var(--danger-color);
      padding: 20px;
      margin: 30px 0;
      border-radius: 0 8px 8px 0;
      text-align: left;
    }
    
    .error-details h6 {
      color: var(--danger-color);
      font-weight: 600;
      margin-bottom: 15px;
    }
    
    .error-details ul {
      margin: 0;
      padding-left: 20px;
    }
    
    .error-details li {
      margin-bottom: 8px;
      color: #495057;
    }
    
    .action-buttons {
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
      margin-top: 40px;
    }
    
    @media (min-width: 576px) {
      .action-buttons {
        flex-direction: row;
        justify-content: center;
      }
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      padding: 14px 35px;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(13, 110, 253, 0.3);
    }
    
    .btn-outline-danger {
      border: 2px solid var(--danger-color);
      color: var(--danger-color);
      padding: 14px 35px;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: transparent;
    }
    
    .btn-outline-danger:hover {
      background: var(--danger-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(220, 53, 69, 0.3);
    }
    
    .btn-outline-secondary {
      border: 2px solid #6c757d;
      color: #6c757d;
      padding: 12px 30px;
      font-weight: 600;
      border-radius: 50px;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: transparent;
    }
    
    .btn-outline-secondary:hover {
      background: #6c757d;
      color: white;
      transform: translateY(-2px);
    }
    
    .support-section {
      margin-top: 50px;
      padding-top: 30px;
      border-top: 1px solid #eaeaea;
    }
    
    .support-section h5 {
      color: var(--dark-color);
      font-weight: 600;
      margin-bottom: 20px;
    }
    
    .support-item {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 15px 20px;
      margin: 10px;
      border-radius: 12px;
      transition: all 0.3s ease;
      text-decoration: none;
      color: #6c757d;
      background: #f8f9fa;
      border: 1px solid #e9ecef;
    }
    
    .support-item:hover {
      background: var(--success-color);
      color: white;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(25, 135, 84, 0.3);
    }
    
    .support-item i {
      margin-right: 12px;
      font-size: 1.2rem;
    }
    
    .status-indicator {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      background: #fff3cd;
      border: 1px solid #ffeaa7;
      border-radius: 20px;
      color: #856404;
      font-size: 0.9rem;
      font-weight: 500;
      margin-bottom: 20px;
    }
    
    .status-indicator.investigating {
      background: #d1ecf1;
      border-color: #bee5eb;
      color: #0c5460;
    }
    
    .status-indicator.resolved {
      background: #d4edda;
      border-color: #c3e6cb;
      color: #155724;
    }
    
    footer {
      background-color: var(--dark-color);
      color: #adb5bd;
      padding: 40px 0;
      margin-top: auto;
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
    
    /* Mobile optimizations */
    @media (max-width: 768px) {
      .error-title {
        font-size: 2.5rem;
      }
      
      .error-subtitle {
        font-size: 1.4rem;
      }
      
      .error-description {
        font-size: 1rem;
      }
      
      .error-icon {
        font-size: 5rem;
      }
      
      .error-content {
        margin: 20px;
        padding: 30px 25px;
      }
      
      .support-item {
        margin: 5px 0;
      }
    }
    
    /* Animation for page load */
    .error-content {
      animation: slideInDown 0.8s ease-out;
    }
    
    @keyframes slideInDown {
      from {
        opacity: 0;
        transform: translateY(-50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Error code display */
    .error-code {
      position: absolute;
      top: 15px;
      right: 20px;
      background: var(--danger-color);
      color: white;
      padding: 5px 12px;
      border-radius: 15px;
      font-size: 0.8rem;
      font-weight: 600;
    }
  </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php">
      <i class="fas fa-car me-2"></i>FROSH LavaCar App
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-light ms-lg-3 px-3" href="login.php">Iniciar Sesión</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-primary ms-2 px-3" href="register.php">Registrarse</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- ERROR CONTENT -->
<div class="error-container">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-11 col-xl-9">
        <div class="error-content">
          <div class="error-code">ERROR <?php echo $_GET['code'] ?? '500'; ?></div>
          
          <i class="fas fa-exclamation-triangle error-icon"></i>
          
          <div class="status-indicator investigating">
            <i class="fas fa-search"></i>
            Nuestro equipo está investigando
          </div>
          
          <h1 class="error-title">¡Oops!</h1>
          <h2 class="error-subtitle">Algo salió mal</h2>
          
          <p class="error-description">
            Ha ocurrido un error inesperado en el sistema. Nuestro equipo técnico ha sido notificado automáticamente 
            y está trabajando para resolver el problema lo antes posible.
          </p>
          
          <div class="error-details">
            <h6><i class="fas fa-info-circle me-2"></i>¿Qué puedes hacer mientras tanto?</h6>
            <ul>
              <li>Intenta recargar la página en unos minutos</li>
              <li>Verifica tu conexión a internet</li>
              <li>Regresa a la página principal y navega desde allí</li>
              <li>Si el problema persiste, contacta a nuestro soporte técnico</li>
            </ul>
          </div>
          
          <div class="action-buttons">
            <a href="javascript:location.reload()" class="btn btn-primary">
              <i class="fas fa-redo"></i>
              Intentar de Nuevo
            </a>
            <a href="index.php" class="btn btn-outline-danger">
              <i class="fas fa-home"></i>
              Ir al Inicio
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-secondary">
              <i class="fas fa-arrow-left"></i>
              Volver Atrás
            </a>
          </div>
          
          <div class="support-section">
            <h5>¿Necesitas ayuda inmediata?</h5>
            <div class="row">
              <div class="col-md-4">
                <a href="https://wa.me/50663957241" class="support-item" target="_blank">
                  <i class="fab fa-whatsapp"></i>
                  WhatsApp Soporte
                </a>
              </div>
              <div class="col-md-4">
                <a href="mailto:froshsystems@gmail.com" class="support-item">
                  <i class="fas fa-envelope"></i>
                  Email Técnico
                </a>
              </div>
              <div class="col-md-4">
                <a href="index.php" class="support-item">
                  <i class="fas fa-home"></i>
                  Página Principal
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- FOOTER -->
<footer class="py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mb-4">
        <h4 class="fw-bold text-white mb-4">
          <i class="fas fa-car me-2"></i>FROSH LavaCar App
        </h4>
        <p>Solución de gestión especializada para Centros de Lavado de autos. Optimizamos tu operación y mejoramos la experiencia de tus clientes.</p>
      </div>
      
      <div class="col-lg-4 mb-4">
        <h5 class="text-white fw-bold mb-4">Enlaces rápidos</h5>
        <ul class="list-unstyled">
          <li class="mb-2"><a href="index.php#problema" class="text-decoration-none text-light">Problemas que resolvemos</a></li>
          <li class="mb-2"><a href="index.php#funciona" class="text-decoration-none text-light">Cómo funciona</a></li>
          <li class="mb-2"><a href="index.php#beneficios" class="text-decoration-none text-light">Beneficios</a></li>
          <li class="mb-2"><a href="register.php" class="text-decoration-none text-light">Crear cuenta</a></li>
        </ul>
      </div>
      
      <div class="col-lg-4 mb-4">
        <h5 class="text-white fw-bold mb-4">Contacto</h5>
        <ul class="list-unstyled">
          <li class="mb-3">
            <a href="https://wa.me/50663957241" class="text-white text-decoration-none" aria-label="WhatsApp" target="_blank">
              <i class="fab fa-whatsapp me-2"></i>WhatsApp
            </a>
          </li>
          <li class="mb-3">
            <i class="fas fa-envelope me-2"></i>
            froshsystems@gmail.com
          </li>
          <li>
            <i class="fas fa-map-marker-alt me-2"></i>
            <span class="text-light">Alajuela, Costa Rica</span>
          </li>
        </ul>
      </div>
    </div>
    
    <hr class="my-4 border-secondary">
    
    <div class="text-center">
      <p class="mb-2">© <?php echo date('Y');?> <strong>Frosh Systems</strong> - Sistema de Gestión para Centros de Lavado de Autos</p>
      <small class="text-muted">Optimiza tu operación, mejora la satisfacción de tus clientes y haz crecer tu negocio</small>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get error details from URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const errorCode = urlParams.get('code') || '500';
    const errorMessage = urlParams.get('message');
    const errorType = urlParams.get('type');
    
    // Update error code display
    const errorCodeElement = document.querySelector('.error-code');
    if (errorCodeElement) {
      errorCodeElement.textContent = `ERROR ${errorCode}`;
    }
    
    // Update error message if provided
    if (errorMessage) {
      const descriptionElement = document.querySelector('.error-description');
      if (descriptionElement) {
        descriptionElement.innerHTML = `
          <strong>Detalles del error:</strong><br>
          ${decodeURIComponent(errorMessage)}
          <br><br>
          Nuestro equipo técnico ha sido notificado automáticamente y está trabajando para resolver el problema.
        `;
      }
    }
    
    // Update status indicator based on error type
    const statusIndicator = document.querySelector('.status-indicator');
    if (errorType === 'maintenance') {
      statusIndicator.className = 'status-indicator';
      statusIndicator.innerHTML = '<i class="fas fa-tools"></i> Mantenimiento programado';
    } else if (errorType === 'database') {
      statusIndicator.className = 'status-indicator investigating';
      statusIndicator.innerHTML = '<i class="fas fa-database"></i> Problema de base de datos detectado';
    }
    
    // Auto-retry functionality (optional)
    let retryCount = 0;
    const maxRetries = 3;
    
    function autoRetry() {
      if (retryCount < maxRetries && errorCode === '500') {
        retryCount++;
        setTimeout(() => {
          console.log(`Auto-retry attempt ${retryCount}/${maxRetries}`);
          // You could implement actual retry logic here
        }, 30000 * retryCount); // Exponential backoff
      }
    }
    
    // Start auto-retry for server errors
    if (errorCode === '500') {
      autoRetry();
    }
    
    // Add click tracking for support links
    document.querySelectorAll('.support-item').forEach(link => {
      link.addEventListener('click', function() {
        console.log('Support link clicked:', this.href);
        // You could send this to analytics
      });
    });
    
    // Animate error icon on hover
    const errorIcon = document.querySelector('.error-icon');
    if (errorIcon) {
      errorIcon.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.1) rotate(-5deg)';
      });
      
      errorIcon.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1) rotate(0deg)';
      });
    }
    
    // Show different messages based on time of day
    const hour = new Date().getHours();
    const timeBasedMessage = document.createElement('small');
    timeBasedMessage.className = 'text-muted d-block mt-3';
    
    if (hour >= 0 && hour < 6) {
      timeBasedMessage.textContent = 'Nuestro equipo de soporte estará disponible en unas horas.';
    } else if (hour >= 6 && hour < 18) {
      timeBasedMessage.textContent = 'Nuestro equipo de soporte está disponible ahora.';
    } else {
      timeBasedMessage.textContent = 'Responderemos tu consulta a primera hora del día siguiente.';
    }
    
    const supportSection = document.querySelector('.support-section h5');
    if (supportSection) {
      supportSection.appendChild(timeBasedMessage);
    }
  });
</script>

</body>
</html>