<?php require_once __DIR__.'/templates/header.php'; check_csrf();
$action = $_POST['action'] ?? null;
if ($action === 'add') {
    cart_add($_POST['product_id'] ?? 0, $_POST['qty'] ?? 1);
    flash('success', 'Added to cart'); header('Location: '.BASE_URL.'cart.php'); exit;
}
if ($action === 'set') {
    cart_set($_POST['product_id'] ?? 0, $_POST['qty'] ?? 1);
}
if ($action === 'remove') {
    cart_remove($_POST['product_id'] ?? 0);
}
$items = cart_items($pdo);
$tot = cart_totals($items);
?>
<h3 class="mb-4">Your Cart</h3>
<?php if (!$items): ?>
  <div class="alert alert-info">Cart is empty. <a href="<?= BASE_URL ?>">Continue shopping</a>.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table align-middle">
      <thead><tr><th>Product</th><th width="120">Price</th><th width="140">Qty</th><th width="120">Subtotal</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($items as $it): ?>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-3">
              <img src="<?= $it['image'] ?: 'assets/img/placeholder.svg' ?>" width="60" class="rounded border bg-white p-1 object-fit-contain" style="height:60px">
              <a href="product.php?id=<?= $it['id'] ?>" class="link-body-emphasis text-decoration-none"><?= htmlspecialchars($it['name']) ?></a>
            </div>
          </td>
          <td><?= money($it['price']) ?></td>
          <td>
            <form method="post" class="d-flex align-items-center gap-2">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="action" value="set">
              <input type="hidden" name="product_id" value="<?= $it['id'] ?>">
              <input type="number" name="qty" class="form-control" style="width:90px" min="0" value="<?= $it['qty'] ?>">
              <button class="btn btn-sm btn-outline-secondary">Update</button>
            </form>
          </td>
          <td><?= money($it['price'] * $it['qty']) ?></td>
          <td>
            <form method="post">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="action" value="remove">
              <input type="hidden" name="product_id" value="<?= $it['id'] ?>">
              <button class="btn btn-sm btn-outline-danger">Remove</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="row">
    <div class="col-md-4 ms-auto">
      <ul class="list-group mb-3">
        <li class="list-group-item d-flex justify-content-between"><span>Subtotal</span><strong><?= money($tot['subtotal']) ?></strong></li>
        <li class="list-group-item d-flex justify-content-between"><span>Shipping</span><strong><?= money($tot['shipping']) ?></strong></li>
        <li class="list-group-item d-flex justify-content-between"><span>Total</span><strong><?= money($tot['total']) ?></strong></li>
      </ul>
      <a class="btn btn-success w-100" href="checkout.php">Proceed to Checkout</a>
    </div>
  </div>
<?php endif; ?>
<?php require_once __DIR__.'/templates/footer.php'; ?>
