<?php require_once __DIR__.'/../init.php'; ?>
<?php $user = current_user(); ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/css/style.css" rel="stylesheet">
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">🛍️ <?= APP_NAME ?></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="nav">
        <form class="d-flex ms-auto" method="get" action="<?= BASE_URL ?>index.php">
          <input class="form-control me-2" name="q" type="search" placeholder="Search products...">
          <button class="btn btn-outline-secondary">Search</button>
        </form>
        <ul class="navbar-nav ms-3">
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>cart.php">Cart
            <span class="badge text-bg-primary">
              <?= array_sum($_SESSION['cart'] ?? []) ?>
            </span></a>
          </li>
          <?php if ($user): ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#"><?= htmlspecialchars($user['name']) ?></a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="<?= BASE_URL ?>orders.php">My Orders</a></li>
                <?php if (is_admin()): ?>
                <li><a class="dropdown-item" href="<?= BASE_URL ?>admin/dashboard.php">Admin</a></li>
                <?php endif; ?>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= BASE_URL ?>auth/logout.php">Logout</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item"><a class="btn btn-primary ms-2" href="<?= BASE_URL ?>auth/login.php">Login</a></li>
            <li class="nav-item"><a class="btn btn-outline-primary ms-2" href="<?= BASE_URL ?>auth/register.php">Signup</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <main class="py-4">
    <div class="container">
      <?php if ($m = flash('success')): ?>
        <div class="alert alert-success"><?= htmlspecialchars($m) ?></div>
      <?php endif; ?>
      <?php if ($m = flash('error')): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($m) ?></div>
      <?php endif; ?>
