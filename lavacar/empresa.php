<?php
require_once '../lib/config.php';
require_once 'lib/Auth.php';
require_once '../backend/CompanyRegistry.php';

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

if(!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

$companyRegistry = new CompanyRegistry($db);

// Get registry data
$companyInfo = $companyRegistry->getCompanyInfo();
$departments = $companyRegistry->getDepartments();
$systems = $companyRegistry->getSystems();
$dataClassifications = $companyRegistry->getDataClassifications();
$frameworks = $companyRegistry->getComplianceFrameworks();
$registryStats = $companyRegistry->getRegistryStats();

// Handle form submissions
if($_POST) {
    if(isset($_POST['update_company_info'])) {
        $companyData = [
            'id' => $companyInfo['id'],
            'company_name' => $_POST['company_name'],
            'legal_name' => $_POST['legal_name'],
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'country' => $_POST['country'],
            'postal_code' => $_POST['postal_code'],
            'phone' => $_POST['phone'],
            'email' => $_POST['email'],
            'website' => $_POST['website'],
            'tax_id' => $_POST['tax_id'],
            'fiscal_year_start' => $_POST['fiscal_year_start'],
            'fiscal_year_end' => $_POST['fiscal_year_end'],
            'timezone' => $_POST['timezone'],
            'currency' => $_POST['currency']
        ];
        
        if($companyRegistry->updateCompanyInfo($companyData)) {
            header("Location: company-registry.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Registry - SOC2 Compliance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <?php include 'sidebar.php'; ?>
    
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Company Registry</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateCompanyModal">
                    <i class="fas fa-edit me-1"></i>Edit Company Info
                </button>
            </div>
        </div>

        <!-- Registry Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h5>Departments</h5>
                        <h2><?php echo $registryStats['department_count']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5>Active Systems</h5>
                        <h2>
                            <?php 
                            $active_systems = array_filter($registryStats['system_counts'], function($item) {
                                return $item['status'] == 'active';
                            });
                            echo count($active_systems) > 0 ? current($active_systems)['count'] : 0;
                            ?>
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h5>Data Classifications</h5>
                        <h2><?php echo count($dataClassifications); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h5>Frameworks</h5>
                        <h2><?php echo $registryStats['framework_count']; ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Company Information -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-building me-2"></i>Company Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td><strong>Company Name:</strong></td>
                                <td><?php echo htmlspecialchars($companyInfo['company_name']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Legal Name:</strong></td>
                                <td><?php echo htmlspecialchars($companyInfo['legal_name']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td><?php echo nl2br(htmlspecialchars($companyInfo['address'])); ?></td>
                            </tr>
                            <tr>
                                <td><strong>City/State:</strong></td>
                                <td><?php echo htmlspecialchars($companyInfo['city'] . ', ' . $companyInfo['state']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Country:</strong></td>
                                <td><?php echo htmlspecialchars($companyInfo['country']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Phone:</strong></td>
                                <td><?php echo htmlspecialchars($companyInfo['phone']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?php echo htmlspecialchars($companyInfo['email']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Website:</strong></td>
                                <td><?php echo htmlspecialchars($companyInfo['website']); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Fiscal Year:</strong></td>
                                <td><?php echo date('M j, Y', strtotime($companyInfo['fiscal_year_start'])) . ' - ' . date('M j, Y', strtotime($companyInfo['fiscal_year_end'])); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Data Classifications -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-database me-2"></i>Data Classifications
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach($dataClassifications as $classification): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($classification['classification_level']); ?></h6>
                                    <small>Retention: <?php echo $classification['retention_period_years']; ?> years</small>
                                </div>
                                <p class="mb-1 small"><?php echo htmlspecialchars($classification['description']); ?></p>
                                <small class="text-muted">
                                    <?php echo $classification['encryption_required'] ? 'Encryption Required' : 'No Encryption'; ?>
                                </small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Departments & Systems -->
            <div class="col-md-6">
                <!-- Departments -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-sitemap me-2"></i>Departments
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Code</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($departments as $dept): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($dept['department_name']); ?></td>
                                        <td><code><?php echo htmlspecialchars($dept['department_code']); ?></code></td>
                                        <td>
                                            <span class="badge <?php echo $dept['is_active'] ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo $dept['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Compliance Frameworks -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shield-alt me-2"></i>Compliance Frameworks
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach($frameworks as $framework): ?>
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($framework['framework_name']); ?> v<?php echo htmlspecialchars($framework['framework_version']); ?></h6>
                                    <span class="badge 
                                        <?php echo $framework['status'] == 'certified' ? 'bg-success' : 
                                               ($framework['status'] == 'in-progress' ? 'bg-warning' : 'bg-secondary'); ?>">
                                        <?php echo ucfirst($framework['status']); ?>
                                    </span>
                                </div>
                                <p class="mb-1 small"><?php echo htmlspecialchars($framework['description']); ?></p>
                                <small class="text-muted">
                                    Audit: <?php echo ucfirst($framework['audit_frequency']); ?>
                                    <?php if($framework['next_audit_date']): ?>
                                     | Next: <?php echo date('M j, Y', strtotime($framework['next_audit_date'])); ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Systems Table -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-server me-2"></i>Registered Systems
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>System Name</th>
                                <th>Type</th>
                                <th>Department</th>
                                <th>Data Classification</th>
                                <th>Status</th>
                                <th>Go-Live Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($systems as $system): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($system['system_name']); ?></td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo ucfirst(str_replace('-', ' ', $system['system_type'])); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($system['department_name']); ?></td>
                                <td>
                                    <span class="badge 
                                        <?php echo $system['data_classification'] == 'restricted' ? 'bg-danger' : 
                                               ($system['data_classification'] == 'confidential' ? 'bg-warning' : 
                                               ($system['data_classification'] == 'internal' ? 'bg-info' : 'bg-success')); ?>">
                                        <?php echo ucfirst($system['data_classification']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge 
                                        <?php echo $system['status'] == 'active' ? 'bg-success' : 
                                               ($system['status'] == 'development' ? 'bg-warning' : 'bg-secondary'); ?>">
                                        <?php echo ucfirst($system['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo $system['go_live_date'] ? date('M j, Y', strtotime($system['go_live_date'])) : 'N/A'; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
  </div>
</div>
    <!-- Update Company Info Modal -->
    <div class="modal fade" id="updateCompanyModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Company Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" name="company_name" 
                                       value="<?php echo htmlspecialchars($companyInfo['company_name']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Legal Name</label>
                                <input type="text" class="form-control" name="legal_name" 
                                       value="<?php echo htmlspecialchars($companyInfo['legal_name']); ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"><?php echo htmlspecialchars($companyInfo['address']); ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" 
                                       value="<?php echo htmlspecialchars($companyInfo['city']); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">State</label>
                                <input type="text" class="form-control" name="state" 
                                       value="<?php echo htmlspecialchars($companyInfo['state']); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Postal Code</label>
                                <input type="text" class="form-control" name="postal_code" 
                                       value="<?php echo htmlspecialchars($companyInfo['postal_code']); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" name="country" 
                                       value="<?php echo htmlspecialchars($companyInfo['country']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" 
                                       value="<?php echo htmlspecialchars($companyInfo['phone']); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" 
                                       value="<?php echo htmlspecialchars($companyInfo['email']); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Website</label>
                                <input type="url" class="form-control" name="website" 
                                       value="<?php echo htmlspecialchars($companyInfo['website']); ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tax ID</label>
                                <input type="text" class="form-control" name="tax_id" 
                                       value="<?php echo htmlspecialchars($companyInfo['tax_id']); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Fiscal Year Start</label>
                                <input type="date" class="form-control" name="fiscal_year_start" 
                                       value="<?php echo $companyInfo['fiscal_year_start']; ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Fiscal Year End</label>
                                <input type="date" class="form-control" name="fiscal_year_end" 
                                       value="<?php echo $companyInfo['fiscal_year_end']; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Timezone</label>
                                <select class="form-select" name="timezone">
                                    <option value="UTC" <?php echo $companyInfo['timezone'] == 'UTC' ? 'selected' : ''; ?>>UTC</option>
                                    <option value="America/New_York" <?php echo $companyInfo['timezone'] == 'America/New_York' ? 'selected' : ''; ?>>Eastern Time</option>
                                    <option value="America/Chicago" <?php echo $companyInfo['timezone'] == 'America/Chicago' ? 'selected' : ''; ?>>Central Time</option>
                                    <option value="America/Denver" <?php echo $companyInfo['timezone'] == 'America/Denver' ? 'selected' : ''; ?>>Mountain Time</option>
                                    <option value="America/Los_Angeles" <?php echo $companyInfo['timezone'] == 'America/Los_Angeles' ? 'selected' : ''; ?>>Pacific Time</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Currency</label>
                                <select class="form-select" name="currency">
                                    <option value="USD" <?php echo $companyInfo['currency'] == 'USD' ? 'selected' : ''; ?>>USD</option>
                                    <option value="EUR" <?php echo $companyInfo['currency'] == 'EUR' ? 'selected' : ''; ?>>EUR</option>
                                    <option value="GBP" <?php echo $companyInfo['currency'] == 'GBP' ? 'selected' : ''; ?>>GBP</option>
                                    <option value="CAD" <?php echo $companyInfo['currency'] == 'CAD' ? 'selected' : ''; ?>>CAD</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_company_info" class="btn btn-primary">Update Information</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>