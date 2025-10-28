<?php require_once __DIR__.'/templates/header.php';

$q = trim($_GET['q'] ?? '');
$cat = (int)($_GET['cat'] ?? 0);

// Categories
$cats = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

// Products query
$where = []; $params = [];
if ($cat) { $where[] = "category_id = ?"; $params[] = $cat; }
if ($q !== '') { $where[] = "p.name LIKE ?"; $params[] = "%$q%"; } // fixed ambiguous column
$sql = "SELECT p.*, c.name AS category 
        FROM products p 
        JOIN categories c ON c.id = p.category_id";
if ($where) $sql .= " WHERE " . implode(" AND ", $where);
$sql .= " ORDER BY p.id DESC LIMIT 60";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!-- 🎨 Elegant Custom CSS -->
<style>
  body {
    background: linear-gradient(135deg, #e0eafc, #f5f7fa); /* soft blue to grey */
    font-family: 'Poppins', sans-serif;
  }

  /* Sidebar Category Card */
  .category-card {
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
  }

  .category-card .card-header {
    background: linear-gradient(90deg, #4facfe, #00f2fe);
    color: #fff;
    font-weight: bold;
    text-align: center;
    padding: 12px;
    font-size: 18px;
    letter-spacing: 0.5px;
  }

  .category-list {
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .category-item {
    display: block;
    padding: 10px 18px;
    font-weight: 500;
    border-radius: 20px;
    background: #f1f3f6;
    color: #333;
    text-decoration: none;
    text-align: center;
    transition: all 0.3s;
  }

  .category-item:hover {
    background: #dfe9f3;
    color: #1a73e8;
    transform: translateY(-2px);
  }

  .category-item.active {
    background: linear-gradient(90deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0px 4px 12px rgba(102,126,234,0.4);
  }

  /* Product Card */
  .product-card {
    border-radius: 15px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: #ffffff;
    border: none;
  }
  .product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 8px 20px rgba(0,0,0,0.12);
  }

  /* Product Image background */
  .product-img-container {
    background: linear-gradient(135deg, #eef2f3, #dfe9f3);
    border-radius: 15px;
    padding: 15px;
    height: 220px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .product-img-container img {
    max-height: 180px;
    object-fit: contain;
  }

  .btn-primary {
    background: linear-gradient(90deg, #667eea, #764ba2);
    border: none;
    transition: 0.3s;
    border-radius: 8px;
    padding: 6px 14px;
  }
  .btn-primary:hover {
    opacity: 0.95;
    transform: scale(1.05);
  }

  /* 🎨 Floating gif in circular style */
  .floating-gif-circle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    z-index: 999;
    animation: bounce 2.5s infinite;
  }

  @keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-12px); }
  }
</style>

<div class="row g-4">
  <!-- Sidebar -->
  <aside class="col-lg-3">
    <div class="category-card">
      <div class="card-header">📂 Categories</div>
      <div class="category-list">
        <a href="index.php" class="category-item <?= $cat==0?'active':'' ?>">All</a>
        <?php foreach ($cats as $c): ?>
          <a href="index.php?cat=<?= $c['id'] ?>" class="category-item <?= $cat==$c['id']?'active':'' ?>">
            <?= htmlspecialchars($c['name']) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </aside>

  <!-- Products -->
  <section class="col-lg-9">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
      <?php foreach ($products as $p): ?>
      <div class="col">
        <div class="card h-100 product-card shadow">
          <a href="product.php?id=<?= $p['id'] ?>">
            <div class="product-img-container">
              <img src="<?= $p['image'] ?: 'assets/img/placeholder.svg' ?>" alt="">
            </div>
          </a>
          <div class="card-body d-flex flex-column">
            <div class="small text-muted"><?= htmlspecialchars($p['category']) ?></div>
            <h6 class="card-title"><?= htmlspecialchars($p['name']) ?></h6>
            <div class="mt-auto d-flex justify-content-between align-items-center">
              <div class="fw-bold"><?= money($p['price']) ?></div>
              <form method="post" action="cart.php">
                <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                <button class="btn btn-sm btn-primary">Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (!$products): ?>
      <div class="col"><div class="alert alert-info">No products found.</div></div>
      <?php endif; ?>
    </div>
  </section>
</div>

<!-- Floating Shopping GIF -->
<img src="assets/img/shopping.gif" class="floating-gif-circle" alt="Shopping">

<?php require_once __DIR__.'/templates/footer.php'; ?>
