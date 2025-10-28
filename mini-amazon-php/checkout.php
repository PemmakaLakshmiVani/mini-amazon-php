<?php require_once __DIR__.'/templates/header.php'; require_login();
$items = cart_items($pdo);
$tot = cart_totals($items);
if (!$items) { flash('error','Cart is empty'); header('Location: '.BASE_URL); exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    check_csrf();
    $addr = [
        'line1'=>trim($_POST['line1']??''),
        'line2'=>trim($_POST['line2']??''),
        'city'=>trim($_POST['city']??''),
        'state'=>trim($_POST['state']??''),
        'zip'=>trim($_POST['zip']??''),
        'phone'=>trim($_POST['phone']??''),
    ];
    $method = $_POST['payment_method'] ?? 'cod';
    // Save order
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO addresses (user_id,line1,line2,city,state,zip,phone) VALUES (?,?,?,?,?,?,?)");
    $stmt->execute([$_SESSION['user']['id'],$addr['line1'],$addr['line2'],$addr['city'],$addr['state'],$addr['zip'],$addr['phone']]);
    $address_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO orders (user_id,total,status,payment_method,payment_ref,address_id) VALUES (?,?,?,?,?,?)");
    $status = ($method==='cod' ? 'placed' : 'pending');
    $stmt->execute([$_SESSION['user']['id'], $tot['total'], $status, $method, '', $address_id]);
    $order_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO order_items (order_id,product_id,qty,price) VALUES (?,?,?,?)");
    foreach ($items as $it) { $stmt->execute([$order_id,$it['id'],$it['qty'],$it['price']]); }
    $pdo->commit();

    if ($method === 'cod' || !RAZORPAY_KEY_ID) {
        cart_clear();
        header('Location: '.BASE_URL.'order_success.php?id='.$order_id); exit;
    } else {
        // Razorpay: redirect to a page that launches the Checkout form
        header('Location: '.BASE_URL.'pay.php?id='.$order_id); exit;
    }
}
?>
<div class="row g-4">
  <div class="col-md-7">
    <h4 class="mb-3">Shipping Address</h4>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <div class="mb-2"><label class="form-label">Address Line 1</label><input name="line1" class="form-control" required></div>
      <div class="mb-2"><label class="form-label">Address Line 2</label><input name="line2" class="form-control"></div>
      <div class="row">
        <div class="col-md-6 mb-2"><label class="form-label">City</label><input name="city" class="form-control" required></div>
        <div class="col-md-3 mb-2"><label class="form-label">State</label><input name="state" class="form-control" required></div>
        <div class="col-md-3 mb-2"><label class="form-label">ZIP</label><input name="zip" class="form-control" required></div>
      </div>
      <div class="mb-3"><label class="form-label">Phone</label><input name="phone" class="form-control" required></div>
      <h4 class="mb-2">Payment</h4>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
        <label class="form-check-label" for="cod">Cash on Delivery</label>
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="radio" name="payment_method" id="razorpay" value="razorpay" <?= RAZORPAY_KEY_ID ? '' : 'disabled' ?>>
        <label class="form-check-label" for="razorpay">Razorpay (Test) <?= RAZORPAY_KEY_ID ? '' : '(configure RAZORPAY_KEY_ID in config.php)' ?></label>
      </div>
      <button class="btn btn-success btn-lg">Place Order</button>
    </form>
  </div>
  <div class="col-md-5">
    <h4 class="mb-3">Order Summary</h4>
    <ul class="list-group mb-3">
      <?php foreach ($items as $it): ?>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <div>
          <div class="small text-muted"><?= htmlspecialchars($it['name']) ?> × <?= $it['qty'] ?></div>
        </div>
        <span><?= money($it['price'] * $it['qty']) ?></span>
      </li>
      <?php endforeach; ?>
      <li class="list-group-item d-flex justify-content-between"><span>Subtotal</span><strong><?= money($tot['subtotal']) ?></strong></li>
      <li class="list-group-item d-flex justify-content-between"><span>Shipping</span><strong><?= money($tot['shipping']) ?></strong></li>
      <li class="list-group-item d-flex justify-content-between"><span>Total</span><strong><?= money($tot['total']) ?></strong></li>
    </ul>
  </div>
</div>
<?php require_once __DIR__.'/templates/footer.php'; ?>
