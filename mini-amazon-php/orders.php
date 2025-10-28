<?php require_once __DIR__.'/templates/header.php'; require_login();
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC");
$stmt->execute([$_SESSION['user']['id']]);
$orders = $stmt->fetchAll();
?>
<h3>My Orders</h3>
<?php if (!$orders): ?>
  <div class="alert alert-info">No orders yet.</div>
<?php else: ?>
  <div class="table-responsive">
    <table class="table">
      <thead><tr><th>#</th><th>Total</th><th>Status</th><th>Payment</th><th>Placed</th></tr></thead>
      <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?= $o['id'] ?></td>
          <td><?= money($o['total']) ?></td>
          <td><?= htmlspecialchars($o['status']) ?></td>
          <td><?= htmlspecialchars($o['payment_method']) ?> <?= $o['payment_ref'] ? '· ' . htmlspecialchars($o['payment_ref']) : '' ?></td>
          <td><?= htmlspecialchars($o['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
<?php require_once __DIR__.'/templates/footer.php'; ?>
