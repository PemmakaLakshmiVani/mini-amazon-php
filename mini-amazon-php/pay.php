<?php require_once __DIR__.'/templates/header.php'; require_login();
if (!RAZORPAY_KEY_ID) { http_response_code(400); die('Razorpay not configured'); }
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id=? AND user_id=?");
$stmt->execute([$id, $_SESSION['user']['id']]);
$order = $stmt->fetch();
if (!$order) { http_response_code(404); die('Order not found'); }

// For demo, we don't create Razorpay Order on server. We'll pass amount and id to client.
?>
<h3>Pay for Order #<?= $order['id'] ?></h3>
<p class="text-muted">This is a demo Razorpay Checkout in test mode. No real charges.</p>
<button id="rzp-button" class="btn btn-primary">Pay <?= money($order['total']) ?></button>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
document.getElementById('rzp-button').onclick = function(e){
  var options = {
    "key": "<?= RAZORPAY_KEY_ID ?>",
    "amount": <?= (int)round($order['total']*100) ?>,
    "currency": "INR",
    "name": "<?= APP_NAME ?>",
    "description": "Order #<?= $order['id'] ?>",
    "handler": function (response){
        // In real app, verify payment on server using response.razorpay_payment_id
        window.location = "order_success.php?id=<?= $order['id'] ?>&paid="+encodeURIComponent(response.razorpay_payment_id);
    },
    "prefill": {},
    "theme": {}
  };
  var rzp1 = new Razorpay(options);
  rzp1.open();
  e.preventDefault();
}
</script>
<?php require_once __DIR__.'/templates/footer.php'; ?>
