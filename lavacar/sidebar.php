 <!-- Sidebar -->
 <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
     <div class="position-sticky pt-3">
         <img src='../lib/images/logo.png' class='img-fluid' style='max-width:300px' />
         <p style='padding:4px 12px'>
             <?php
                $userInfo = $auth->getUser();
                //var_dump($userInfo);
                printf("<b>User: </b>%s %s (<b>%s</b>)", $userInfo['first_name'], $userInfo['last_name'], $userInfo['role']);

                ?>
         </p>
         <h5 class="sidebar-heading px-3 mt-1 mb-1 text-muted">
             SOC2 Compliance
         </h5>
         <ul class="nav flex-column">
             <li class="nav-item">
                 <a class="nav-link active" href="dashboard.php">
                     <i class="fas fa-tachometer-alt me-2"></i>
                     Dashboard
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="policies.php">
                     <i class="fas fa-file-signature me-2"></i>
                     Policies
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="controls.php">
                     <i class="fas fa-sliders-h me-2"></i>
                     Controls
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="evidence.php">
                     <i class="fas fa-archive me-2"></i>
                     Evidence
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="changeManagement.php">
                     <i class="fas fa-random me-2"></i>
                     Change Management
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="incidents.php">
                     <i class="fas fa-first-aid me-2"></i>
                     Incidents
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="risks.php">
                     <i class="fas fa-chart-line me-2"></i>
                     Risk Assessment
                 </a>
             </li>

             <?php
                // Add User Management to sidebar for admins
                //if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):
                ?>
             <hr />
             <h5 class="sidebar-heading px-3 mt-4 mb-1 text-muted">
                 Management
             </h5>
             <li class="nav-item">
                 <a class="nav-link" href="user-management.php">
                     <i class="fas fa-user-cog me-2"></i>
                     User Management
                 </a>
             </li>
             <!--<li class="nav-item">
    <a class="nav-link" href="bulk-import.php">
        <i class="fas fa-file-import me-2"></i>
        Bulk Import
    </a>
</li>-->

             <li class="nav-item">
                 <a class="nav-link" href="company-registry.php">
                     <i class="fas fa-building me-2"></i>
                     Company Information
                 </a>
             </li>

             <li class="nav-item">
                 <a class="nav-link" href="system-management.php">
                     <i class="fas fa-server me-2"></i>
                     System Management
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="reports.php">
                     <i class="fas fa-chart-pie me-2"></i>
                     Reports
                 </a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="logout.php">
                     <i class="fas fa-sign-out-alt me-2"></i>
                     Close Session
                 </a>
             </li>
             <?php //endif; 
                ?>
         </ul>
     </div>
 </nav>