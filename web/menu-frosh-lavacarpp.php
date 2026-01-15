<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#" aria-label="FROSH LavaCar App - Inicio">
      <i class="fas fa-car me-2"></i>FROSH LavaCar App
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="index.php#problema">Soluciona</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#funciona">Cómo funciona</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#pasos">Estados</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php#beneficios">Beneficios</a></li>
        <li class="nav-item"><a class="nav-link" href="sobre-nosotros-frosh-lavacarpp.php">Sobre Nosotros</a></li>
        <li class="nav-item">
          <a class="btn btn-primary ms-lg-3" href="#contacto">Solicitar demo gratuita</a>
        </li>
      </ul>
    </div>
  </div>
</nav>


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