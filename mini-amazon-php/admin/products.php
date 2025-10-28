<?php require_once __DIR__.'/../templates/header.php'; require_admin();
if (($_POST['action'] ?? '') === 'delete') {
  check_csrf();
  $stmt = $pdo->prepare("DELETE FROM products WHERE id=?");
  $stmt->execute([(int)$_POST['id']]);
  flash('success','Product deleted');
  header('Location: products.php'); exit;
}
$rows = $pdo->query("SELECT p.*, c.name AS category FROM products p JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC")->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Products</h3>
  <a class="btn btn-success" href="product_form.php">+ Add Product</a>
</div>
<div class="table-responsive">
  <table class="table align-middle">
    <thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Image</th><th width="160"></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['name']) ?></td>
        <td><?= htmlspecialchars($r['category']) ?></td>
        <td><?= money($r['price']) ?></td>
        <td><?= (int)$r['stock'] ?></td>
        <td><?php if ($r['image']): ?><img src="../<?= $r['image'] ?>" width="60"><?php endif; ?></td>
        <td class="text-end">
          <a class="btn btn-sm btn-outline-primary" href="product_form.php?id=<?= $r['id'] ?>">Edit</a>
          <form method="post" class="d-inline">
            <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= $r['id'] ?>">
            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')">Delete</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
