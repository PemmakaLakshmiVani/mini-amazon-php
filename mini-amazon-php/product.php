<?php require_once __DIR__.'/templates/header.php';
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT p.*, c.name AS category FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { http_response_code(404); die('Product not found'); }
?>

<!-- 🎨 Custom CSS -->
<style>
  body {
    background: linear-gradient(135deg, #e0eafc, #cfdef3); /* soft blue gradient */
    font-family: 'Poppins', sans-serif;
  }

  .product-page {
    background: linear-gradient(135deg, #ffffff, #f5f7fa); /* light greyish */
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0px 6px 20px rgba(0,0,0,0.1);
  }

  /* Image container with elegant gradient */
  .product-img-container {
    background: linear-gradient(135deg, #d3cce3, #e9e4f0); /* soft purple/grey */
    border-radius: 15px;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 420px;
  }
  .product-img-container img {
    max-height: 380px;
    object-fit: contain;
  }

  .product-title {
    font-weight: bold;
    color: #2d2d2d;
  }

  .product-price {
    font-size: 1.6rem;
    font-weight: 700;
    background: linear-gradient(90deg, #4facfe, #00f2fe); /* cool blue gradient */
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  /* Stylish Add to Cart Button */
  .btn-primary {
    background: linear-gradient(90deg, #667eea, #764ba2); /* soft violet/blue */
    border: none;
    transition: 0.3s;
    padding: 10px 20px;
    font-size: 1.1rem;
    border-radius: 8px;
    box-shadow: 0px 4px 10px rgba(118,75,162,0.3);
  }
  .btn-primary:hover {
    opacity: 0.95;
    transform: scale(1.05);
  }

  /* Quantity box styling */
  input[type="number"] {
    border-radius: 8px;
    border: 1px solid #ccc;
    padding: 6px 10px;
  }
</style>

<div class="row g-4 product-page">
  <!-- Left: Product Image -->
  <div class="col-md-5">
    <div class="product-img-container">
      <img src="<?= $p['image'] ?: 'assets/img/placeholder.svg' ?>" class="img-fluid" alt="">
    </div>
  </div>

  <!-- Right: Product Info -->
  <div class="col-md-7">
    <div class="small text-muted mb-2"><?= htmlspecialchars($p['category']) ?></div>
    <h3 class="mb-3 product-title"><?= htmlspecialchars($p['name']) ?></h3>
    <div class="product-price mb-3"><?= money($p['price']) ?></div>
    <p><?= nl2br(htmlspecialchars($p['description'])) ?></p>

    <form class="d-flex align-items-center gap-2 mt-4" method="post" action="cart.php">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <input type="hidden" name="action" value="add">
      <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
      <input type="number" class="form-control" name="qty" value="1" min="1" style="max-width:120px">
      <button class="btn btn-primary btn-lg">🛒 Add to Cart</button>
    </form>
  </div>
</div>

<?php require_once __DIR__.'/templates/footer.php'; ?>
