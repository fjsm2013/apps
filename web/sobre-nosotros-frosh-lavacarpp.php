<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!-- Título optimizado para SEO con palabra clave principal -->
    <title>FROSH LavaCar App | Sistema de Gestión para Autolavados - Sobre Nosotros</title>
        <!-- Meta descripción mejorada con palabras clave y llamada a acción -->
    <meta name="description" content="FROSH LavaCar App: La solución digital que organiza autolavados eliminando el caos. Conoce nuestra historia, cómo optimizamos procesos y aumentamos la rentabilidad de negocios de lavado de autos." />
        <!-- Meta keywords para SEO tradicional -->
    <meta name="keywords" content="aplicación autolavados, gestión lavacarros, software lavado de autos, organización autolavado, app para lavacar, control de vehículos, sistema gestión autolavados, digitalización autolavados" />
        <!-- Open Graph para redes sociales -->
    <meta property="og:title" content="FROSH LavaCar App | Sistema de Gestión Inteligente para Autolavados" />
    <meta property="og:description" content="La solución digital que organiza y optimiza la gestión de tu autolavado desde el primer día." />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://www.froshlavacarapp.com/sobre-nosotros" />
    <meta property="og:image" content="https://www.froshlavacarapp.com/images/og-image.jpg" />
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://www.froshlavacarapp.com/sobre-nosotros-frosh-lavacarpp.php" />
    
    <!-- Schema.org structured data for Local Business -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "FROSH LavaCar App",
        "description": "Sistema de gestión digital para autolavados que organiza turnos, controla procesos y mejora la experiencia del cliente",
        "url": "https://www.froshlavacarapp.com",
        "logo": "https://www.froshlavacarapp.com/images/logo.png",
        "founder": {
            "@type": "Person",
            "name": "Javier Saavedra"
        },
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "Costa Rica"
        },
        "sameAs": [
            "https://facebook.com/froshlavacarapp",
            "https://instagram.com/froshlavacarapp",
            "https://linkedin.com/company/froshlavacarapp"
        ]
    }
    </script>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-blue: #0d6efd;
            --secondary-blue: #0dcaf0;
            --accent-green: #198754;
            --dark-bg: #212529;
            --light-bg: #f8f9fa;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        .hero {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: #fff;
            padding: 140px 0 100px;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
        }
        
        .section-padding {
            padding: 100px 0;
        }
        
        @media (max-width: 768px) {
            .section-padding {
                padding: 70px 0;
            }
            .hero {
                padding: 120px 0 80px;
            }
        }
        
        .about-card {
            border: none;
            border-radius: 16px;
            background: #fff;
            height: 100%;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-top: 4px solid var(--primary-blue);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .about-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(13, 110, 253, 0.1);
        }
        
        .problem-card {
            border-top-color: #dc3545;
        }
        
        .solution-card {
            border-top-color: var(--accent-green);
        }
        
        .benefit-card {
            border-top-color: #ffc107;
        }
        
        .icon {
            font-size: 48px;
            height: 80px;
            width: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px;
        }
        
        .problem-icon {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .solution-icon {
            background-color: rgba(25, 135, 84, 0.1);
            color: var(--accent-green);
        }
        
        .benefit-icon {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .highlight-text {
            background: linear-gradient(120deg, rgba(13, 110, 253, 0.1) 0%, rgba(13, 110, 253, 0) 100%);
            border-left: 4px solid var(--primary-blue);
            padding: 25px;
            border-radius: 0 8px 8px 0;
        }
        
        .founder-section {
            background-color: var(--light-bg);
        }
        
        .founder-img {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .metric-box {
            text-align: center;
            padding: 25px 15px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }
        
        .metric-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-blue);
            line-height: 1;
        }
        
        .metric-label {
            font-size: 0.9rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 8px;
        }
        
        .cta-section {
            background: linear-gradient(rgba(13, 110, 253, 0.95), rgba(13, 202, 240, 0.95)), url('https://images.unsplash.com/photo-1565689221354-d87f85d4aee2?q=80&w=2070');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
        }
        
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.2);
        }
        
        footer {
            background-color: var(--dark-bg);
            color: #adb5bd;
        }
        
        .footer-links a {
            color: #adb5bd;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }
        
        .breadcrumb-item.active {
            color: rgba(255,255,255,0.8);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            line-height: 1.2;
        }
        
        h1 {
            font-size: 3rem;
        }
        
        @media (max-width: 768px) {
            h1 {
                font-size: 2.2rem;
            }
            h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
     <?php include_once "menu-frosh-lavacarpp.php" ?>

    <!-- HERO -->
    <section class="hero text-center">
        <div class="container position-relative">
            <nav aria-label="breadcrumb" class="d-flex justify-content-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Inicio</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Sobre Nosotros</li>
                </ol>
            </nav>
            
            <h1 class="display-4 fw-bold mb-4">Transformando la Gestión de Autolavados en Latinoamérica</h1>
            <p class="lead fs-4 mb-4 max-w-800 mx-auto">
                Nacimos para eliminar el caos en los lavacarros y convertir la gestión diaria en un proceso organizado, eficiente y rentable.
            </p>
            <a href="#nuestra-historia" class="btn btn-light btn-lg mt-3">
                Conoce nuestra historia <i class="fas fa-arrow-down ms-2"></i>
            </a>
        </div>
    </section>

    <!-- NUESTRA HISTORIA -->
    <section id="nuestra-historia" class="section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="fw-bold mb-4">Cómo nació FROSH LavaCar App</h2>
                    <p class="mb-4">
                        En 2023, <strong>Javier Saavedra</strong>, observó una problemática recurrente en los autolavados de su ciudad: 
                        caos organizacional, clientes frustrados por la falta de información y dueños perdiendo dinero por ineficiencias en el proceso.
                    </p>
                    <p class="mb-4">
                        Después de entrevistar a varios dueños de autolavados en Costa Rica, identificó que el problema no era la falta de clientes, 
                        sino la <strong>gestión ineficiente</strong> que limitaba su capacidad de crecimiento y afectaba la experiencia del cliente.
                    </p>
                    <div class="highlight-text mb-4">
                        <p class="mb-0 fst-italic">
                            "No se trataba de lavar más autos, sino de organizar mejor el proceso. Un autolavado organizado es un negocio rentable."
                        </p>
                        <p class="mb-0 mt-2 text-end fw-semibold">— Javier Saavedra, Fundador</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-4 p-lg-5 bg-light rounded-3">
                        <h3 class="fw-bold mb-4 text-center">El Problema que Detectamos</h3>
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <div class="icon problem-icon mb-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h6>Clientes Confundidos</h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <div class="icon problem-icon mb-3">
                                        <i class="fas fa-car"></i>
                                    </div>
                                    <h6>Filas Desorganizadas</h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <div class="icon problem-icon mb-3">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h6>Falta de Control</h6>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3">
                                    <div class="icon problem-icon mb-3">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <h6>Errores en la Entrega</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- LA SOLUCIÓN -->
 <section class="section-padding cta-section text-center">
        <div class="container">
            <div class="text-center mb-5">
             <h2 class="fw-bold mb-4 display-5">Nuestra Solución: Tecnología Simple pero Poderosa</h2>
               <p class="lead mb-5 max-w-700 mx-auto">
                    Desarrollamos una aplicación intuitiva diseñada específicamente para las necesidades reales de los autolavados latinoamericanos.
                </p>
            </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-md-4">
                    <div class="card about-card p-4 h-100">
                            <div class="icon benefit-icon mb-3">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Diseñada para Dueños de Negocios</h5>
                        <p class="mb-0">
                            Interfaz intuitiva que cualquier dueño de autolavado puede usar sin necesidad de conocimientos técnicos. 
                            Aprendizaje en minutos.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="card about-card p-4 h-100">
                        <div class="icon benefit-icon mb-3">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Control Total del Proceso</h5>
                        <p class="mb-0">
                            Sistema de turnos numerados, estados de progreso y alertas automáticas. 
                            Sabrás exactamente en qué estado está cada vehículo en tiempo real.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                 <div class="card about-card p-4 h-100">
                        <div class="icon benefit-icon mb-3">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Clientes Satisfechos</h5>
                        <p class="mb-0">
                            Notificaciones automáticas vía email informando cada etapa del proceso. 
                            Clientes tranquilos y confiados en tu servicio.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- RESULTADOS Y MÉTRICAS -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Resultados Tangibles para Tu Autolavado</h2>
                <p class="lead text-muted max-w-700 mx-auto">
                    Los autolavados que implementan FROSH experimentan mejoras medibles desde el primer mes.
                </p>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-md-3 col-sm-6">
                    <div class="metric-box">
                        <div class="metric-value">&#8593;</div>
                        <div class="metric-label">Más eficiencia</div>
                        <p class="small mt-3 mb-0">Reducción en tiempos de espera y optimización del flujo de trabajo</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="metric-box">
                        <div class="metric-value">&#8593;</div>
                        <div class="metric-label">Menos consultas</div>
                        <p class="small mt-3 mb-0">Disminución de preguntas repetitivas de clientes sobre estado de sus autos</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="metric-box">
                        <div class="metric-value">&#8593;</div>
                        <div class="metric-label">Más capacidad</div>
                        <p class="small mt-3 mb-0">Aumento en la cantidad de vehículos que puedes atender por día</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="metric-box">
                        <div class="metric-value">&#8593;</div>
                        <div class="metric-label">Clientes satisfechos</div>
                        <p class="small mt-3 mb-0">Mejora en la experiencia del cliente y fidelización</p>
                    </div>
                </div>
            </div>
            
            <div class="row justify-content-center mt-5">
                <div class="col-lg-10">
                    <div class="bg-primary text-white rounded-3 p-5">
                        <div class="row align-items-center">
                            <div class="col-lg-8">
                                <h3 class="fw-bold mb-3">¿Listo para transformar la gestión de tu autolavado?</h3>
                                <p class="mb-0">Solicita una demostración gratuita y personalizada para tu negocio.</p>
                            </div>
                            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                <a href="index.html#contacto" class="btn btn-light btn-lg">
                                    Solicitar Demo <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FUNDADOR -->
    <section class="section-padding founder-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 text-center mb-5 mb-lg-0">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" 
                         alt="Javier Saavedra - Fundador de FROSH LavaCar App" class="founder-img">
                    <h4 class="mt-4 mb-1">Javier Saavedra</h4>
                    <p class="text-muted">Fundador y CEO</p>
                    <div class="d-flex justify-content-center mt-3">
                        <a href="#" class="text-primary me-3"><i class="fab fa-linkedin fa-lg"></i></a>
                        <a href="#" class="text-primary me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-primary"><i class="fab fa-instagram fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <h2 class="fw-bold mb-4">Palabras del Fundador</h2>
                    <blockquote class="blockquote fs-5 mb-4">
                        <p class="mb-4">
                            "Nuestra misión es simple pero poderosa: <strong>eliminar el caos de los autolavados</strong> 
                            mediante tecnología accesible. Sabemos que detrás de cada autolavado hay un emprendedor 
                            trabajando dario para sacar adelante su negocio y su familia."
                        </p>
                        <p class="mb-4">
                            "Por eso creamos FROSH: para que puedas <strong>enfocarte en lo que realmente importa</strong> 
                            – atender a tus clientes y hacer crecer tu negocio – mientras nosotros nos encargamos de 
                            organizar y optimizar tu operación diaria."
                        </p>
                        <footer class="blockquote-footer mt-4">Javier Saavedra, <cite title="Source Title">Fundador de FROSH LavaCar App</cite></footer>
                    </blockquote>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA FINAL -->
    <section class="section-padding cta-section text-center">
        <div class="container">
            <h2 class="fw-bold mb-4 display-5">Organiza Tu Autolavado Hoy Mismo</h2>
           
            <div class="row g-4 justify-content-center mb-5">
                <div class="col-md-4">
                    <div class="card about-card p-4 h-100">
                        <div class="icon benefit-icon mb-3">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Implementación Rápida</h5>
                        <p class="mb-0">Tu autolavado funcionando con FROSH en menos de 24 horas. </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card about-card p-4 h-100">
                        <div class="icon benefit-icon mb-3">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Soporte Dedicado</h5>
                        <p class="mb-0">Equipo de soporte en español disponible para resolver tus dudas y acompañarte en el proceso.</p>
                    </div>
                </div>
             
            
            <a href="index.php#contacto" class="btn btn-light btn-lg px-5 py-3 fw-bold">
                <i class="fas fa-calendar-check me-2"></i> Agenda Tu Demo Gratuita
            </a>
              </div>
    </section>

<!-- FOOTER -->
<footer class="py-5">
  <?php include_once "footer-frosh-lavacar-app.php" ?>
</footer>


    
 
</body>
</html>