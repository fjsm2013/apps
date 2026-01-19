<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="favicon.ico">
  
  <!-- Primary Meta Tags -->
  <title>Página No Encontrada - Frosh Systems</title>
  <meta name="description" content="La página que buscas no existe o ha sido movida. Regresa al inicio de FROSH LavaCar App." />
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
      background: linear-gradient(135deg, var(--light-color) 0%, #e9ecef 100%);
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
      max-width: 600px;
      padding: 40px;
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      border: 1px solid #eaeaea;
      margin: 0 auto;
    }
    
    .error-icon {
      font-size: 8rem;
      color: var(--primary-color);
      margin-bottom: 30px;
      display: block;
      animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
      }
      40% {
        transform: translateY(-10px);
      }
      60% {
        transform: translateY(-5px);
      }
    }
    
    .error-title {
      font-size: 4rem;
      font-weight: 800;
      color: var(--primary-color);
      margin-bottom: 20px;
      line-height: 1.2;
    }
    
    .error-subtitle {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--dark-color);
      margin-bottom: 20px;
    }
    
    .error-description {
      font-size: 1.1rem;
      color: #6c757d;
      margin-bottom: 40px;
      line-height: 1.6;
    }
    
    .action-buttons {
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
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
      padding: 12px 30px;
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
    
    .btn-outline-primary {
      border: 2px solid var(--primary-color);
      color: var(--primary-color);
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
    
    .btn-outline-primary:hover {
      background: var(--primary-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(13, 110, 253, 0.3);
    }
    
    .helpful-links {
      margin-top: 50px;
      padding-top: 30px;
      border-top: 1px solid #eaeaea;
    }
    
    .helpful-links h5 {
      color: var(--dark-color);
      font-weight: 600;
      margin-bottom: 20px;
    }
    
    .link-item {
      display: flex;
      align-items: center;
      padding: 10px 15px;
      margin-bottom: 10px;
      border-radius: 10px;
      transition: all 0.3s ease;
      text-decoration: none;
      color: #6c757d;
      background: #f8f9fa;
    }
    
    .link-item:hover {
      background: var(--primary-color);
      color: white;
      transform: translateX(5px);
    }
    
    .link-item i {
      margin-right: 12px;
      width: 20px;
      text-align: center;
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
        font-size: 3rem;
      }
      
      .error-subtitle {
        font-size: 1.3rem;
      }
      
      .error-description {
        font-size: 1rem;
      }
      
      .error-icon {
        font-size: 6rem;
      }
      
      .error-content {
        margin: 20px;
        padding: 30px 20px;
      }
    }
    
    /* Animation for page load */
    .error-content {
      animation: fadeInUp 0.8s ease-out;
    }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
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
      <div class="col-12 col-lg-10 col-xl-8">
        <div class="error-content">
          <i class="fas fa-search error-icon"></i>
          
          <h1 class="error-title">404</h1>
          <h2 class="error-subtitle">Página No Encontrada</h2>
          
          <p class="error-description">
            Lo sentimos, la página que estás buscando no existe o ha sido movida. 
            Puede que hayas escrito mal la dirección o que la página haya sido eliminada.
          </p>
          
          <div class="action-buttons">
            <a href="index.php" class="btn btn-primary">
              <i class="fas fa-home"></i>
              Volver al Inicio
            </a>
            <a href="javascript:history.back()" class="btn btn-outline-primary">
              <i class="fas fa-arrow-left"></i>
              Página Anterior
            </a>
          </div>
          
          <div class="helpful-links">
            <h5>Enlaces Útiles</h5>
            <div class="row">
              <div class="col-md-6">
                <a href="index.php" class="link-item">
                  <i class="fas fa-home"></i>
                  Página Principal
                </a>
                <a href="login.php" class="link-item">
                  <i class="fas fa-sign-in-alt"></i>
                  Iniciar Sesión
                </a>
                <a href="register.php" class="link-item">
                  <i class="fas fa-user-plus"></i>
                  Crear Cuenta
                </a>
              </div>
              <div class="col-md-6">
                <a href="index.php#funciona" class="link-item">
                  <i class="fas fa-info-circle"></i>
                  Cómo Funciona
                </a>
                <a href="index.php#beneficios" class="link-item">
                  <i class="fas fa-star"></i>
                  Beneficios
                </a>
                <a href="https://wa.me/50663957241" class="link-item" target="_blank">
                  <i class="fab fa-whatsapp"></i>
                  Soporte WhatsApp
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
  // Add some interactive effects
  document.addEventListener('DOMContentLoaded', function() {
    // Animate the error icon on hover
    const errorIcon = document.querySelector('.error-icon');
    if (errorIcon) {
      errorIcon.addEventListener('mouseenter', function() {
        this.style.transform = 'scale(1.1) rotate(10deg)';
      });
      
      errorIcon.addEventListener('mouseleave', function() {
        this.style.transform = 'scale(1) rotate(0deg)';
      });
    }
    
    // Add click tracking for analytics (if needed)
    document.querySelectorAll('.link-item, .btn').forEach(link => {
      link.addEventListener('click', function() {
        console.log('404 page link clicked:', this.href || this.textContent);
      });
    });
    
    // Auto-redirect after 30 seconds (optional)
    // setTimeout(() => {
    //   if (confirm('¿Te gustaría regresar a la página principal?')) {
    //     window.location.href = 'index.php';
    //   }
    // }, 30000);
  });
</script>

</body>
</html>