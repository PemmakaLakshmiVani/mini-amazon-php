<?php 
require_once __DIR__ . '/../templates/header.php'; 
require_admin();

// ✅ Count stats
$stats = [
    'products' => $pdo->query("SELECT COUNT(*) AS c FROM products")->fetch()['c'] ?? 0,   
    'orders'   => $pdo->query("SELECT COUNT(*) AS c FROM orders")->fetch()['c'] ?? 0,   
    'users'    => $pdo->query("SELECT COUNT(*) AS c FROM users WHERE role='user'")->fetch()['c'] ?? 0, 
]; 
?> 

<h3 class="mb-4">Admin Dashboard</h3> 

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm p-3 text-center">
            <div class="small text-muted">Products</div>
            <div class="display-6 fw-bold"><?= (int)$stats['products'] ?></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-3 text-center">
            <div class="small text-muted">Orders</div>
            <div class="display-6 fw-bold"><?= (int)$stats['orders'] ?></div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm p-3 text-center">
            <div class="small text-muted">Customers</div>
            <div class="display-6 fw-bold"><?= (int)$stats['users'] ?></div>
        </div>
    </div>
</div> 

<div class="mt-4 d-flex flex-wrap gap-2">
    <a class="btn btn-primary" href="products.php">📦 Manage Products</a>   
    <a class="btn btn-outline-secondary" href="orders.php">🛒 View Orders</a>   
    <a class="btn btn-success" href="customers.php">👥 View Customers</a> 
</div> 

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
