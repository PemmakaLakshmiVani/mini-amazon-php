<?php require_once __DIR__.'/../templates/header.php'; require_admin();
if (($_POST['action'] ?? '') === 'status') {
  check_csrf();
  $stmt = $pdo->prepare("UPDATE orders SET status=? WHERE id=?");
  $stmt->execute([$_POST['status'] ?? 'placed', (int)$_POST['id']]);
  flash('success','Order updated');
  header('Location: orders.php'); exit;
}
$rows = $pdo->query("SELECT o.*, u.name AS customer FROM orders o JOIN users u ON u.id=o.user_id ORDER BY o.id DESC")->fetchAll();
?>
<h3>Orders</h3>
<div class="table-responsive">
  <table class="table align-middle">
    <thead><tr><th>ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Payment</th><th>Created</th><th></th></tr></thead>
    <tbody>
      <?php foreach ($rows as $r): ?>
      <tr>
        <td><?= $r['id'] ?></td>
        <td><?= htmlspecialchars($r['customer']) ?></td>
        <td><?= money($r['total']) ?></td>
        <td><?= htmlspecialchars($r['status']) ?></td>
        <td><?= htmlspecialchars($r['payment_method']) ?> <?= $r['payment_ref'] ? '· ' . htmlspecialchars($r['payment_ref']) : '' ?></td>
        <td><?= htmlspecialchars($r['created_at']) ?></td>
        <td class="text-end">
          <form method="post" class="d-inline">
            <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
            <input type="hidden" name="action" value="status">
            <input type="hidden" name="id" value="<?= $r['id'] ?>">
            <select name="status" class="form-select form-select-sm d-inline w-auto">
              <?php foreach (['placed','paid','shipped','delivered','cancelled'] as $st): ?>
                <option <?= $r['status']===$st?'selected':'' ?> value="<?= $st ?>"><?= $st ?></option>
              <?php endforeach; ?>
            </select>
            <button class="btn btn-sm btn-outline-primary">Update</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
