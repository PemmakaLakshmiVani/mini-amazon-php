<?php require_once __DIR__.'/../templates/header.php'; require_admin();
$id = (int)($_GET['id'] ?? 0);
$product = ['name'=>'','category_id'=>'','description'=>'','price'=>'','stock'=>'','image'=>''];
if ($id) {
  $stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
  $stmt->execute([$id]);
  $product = $stmt->fetch();
  if (!$product) { die('Not found'); }
}
$cats = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD']==='POST') {
  check_csrf();
  $name = trim($_POST['name']??'');
  $category_id = (int)($_POST['category_id']??0);
  $price = (float)($_POST['price']??0);
  $stock = (int)($_POST['stock']??0);
  $description = trim($_POST['description']??'');
  $image = trim($_POST['image']??'');
  if ($id) {
    $stmt = $pdo->prepare("UPDATE products SET name=?, category_id=?, description=?, price=?, stock=?, image=? WHERE id=?");
    $stmt->execute([$name,$category_id,$description,$price,$stock,$image,$id]);
    flash('success','Product updated');
  } else {
    $stmt = $pdo->prepare("INSERT INTO products (name, category_id, description, price, stock, image) VALUES (?,?,?,?,?,?)");
    $stmt->execute([$name,$category_id,$description,$price,$stock,$image]);
    $id = $pdo->lastInsertId();
    flash('success','Product created');
  }
  header('Location: products.php'); exit;
}
?>
<h3 class="mb-3"><?= $id ? 'Edit' : 'Add' ?> Product</h3>
<form method="post">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <div class="row">
    <div class="col-md-8">
      <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" value="<?= htmlspecialchars($product['name']) ?>" required></div>
      <div class="mb-3">
        <label class="form-label">Category</label>
        <select class="form-select" name="category_id" required>
          <option value="">Choose...</option>
          <?php foreach ($cats as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $product['category_id']==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" rows="5" name="description"><?= htmlspecialchars($product['description']) ?></textarea></div>
    </div>
    <div class="col-md-4">
      <div class="mb-3"><label class="form-label">Price</label><input type="number" step="0.01" class="form-control" name="price" value="<?= htmlspecialchars($product['price']) ?>" required></div>
      <div class="mb-3"><label class="form-label">Stock</label><input type="number" class="form-control" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required></div>
      <div class="mb-3"><label class="form-label">Image URL (relative, e.g. assets/img/phone.svg)</label><input class="form-control" name="image" value="<?= htmlspecialchars($product['image']) ?>"></div>
      <button class="btn btn-success w-100"><?= $id ? 'Update' : 'Create' ?></button>
    </div>
  </div>
</form>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
