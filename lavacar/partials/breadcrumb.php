<?php
// Breadcrumb component - Usage: include 'lavacar/partials/breadcrumb.php';
// Define $breadcrumbs array before including this file

if (!isset($breadcrumbs) || empty($breadcrumbs)) {
    return;
}
?>

<nav aria-label="breadcrumb" class="mb-3">
    <div class="container">
        <ol class="breadcrumb bg-transparent p-0 m-0">
            <li class="breadcrumb-item">
                <a href="<?= LAVACAR_BASE_URL ?>/dashboard.php" class="breadcrumb-link">
                    <i class="fa-solid fa-home me-1"></i>Inicio
                </a>
            </li>
            <?php foreach ($breadcrumbs as $index => $crumb): ?>
                <?php if ($index === count($breadcrumbs) - 1): ?>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= htmlspecialchars($crumb['title']) ?>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item">
                        <a href="<?= htmlspecialchars($crumb['url']) ?>" class="breadcrumb-link">
                            <?= htmlspecialchars($crumb['title']) ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>

<style>
.breadcrumb {
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.breadcrumb-item {
    color: #6b7280;
}

.breadcrumb-item.active {
    color: #374151;
    font-weight: 500;
}

.breadcrumb-link {
    color: #3b82f6;
    text-decoration: none;
    transition: color 0.2s ease;
}

.breadcrumb-link:hover {
    color: #2563eb;
    text-decoration: none;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "›";
    color: #9ca3af;
    font-weight: 600;
}

@media (max-width: 576px) {
    .breadcrumb {
        font-size: 0.8rem;
    }
    
    .breadcrumb-item {
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
}
</style>