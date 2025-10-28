<?php require_once __DIR__.'/templates/header.php'; require_login();
$id = (int)($_GET['id'] ?? 0);
$paid = $_GET['paid'] ?? '';
if ($paid) {
  $stmt = $pdo->prepare("UPDATE orders SET status='paid', payment_ref=? WHERE id=? AND user_id=?");
  $stmt->execute([$paid, $id, $_SESSION['user']['id']]);
}
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id=? AND user_id=?");
$stmt->execute([$id, $_SESSION['user']['id']]);
$order = $stmt->fetch();
if (!$order) { http_response_code(404); die('Order not found'); }
cart_clear();
?>
<div class="text-center py-5">
  <div class="display-6 mb-3">🎉 Thank you!</div>
  <p>Your order <strong>#<?= $order['id'] ?></strong> is <?= htmlspecialchars($order['status']) ?>.</p>
  <a class="btn btn-primary mt-3" href="<?= BASE_URL ?>">Continue shopping</a>
  <a class="btn btn-outline-secondary mt-3" href="<?= BASE_URL ?>orders.php">View orders</a>
</div>
<?php require_once __DIR__.'/templates/footer.php'; ?>
