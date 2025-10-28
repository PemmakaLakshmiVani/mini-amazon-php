<?php require_once __DIR__.'/../templates/header.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  check_csrf();
  $email = trim($_POST['email']??''); $pass = $_POST['password']??'';
  $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
  $stmt->execute([$email]);
  $u = $stmt->fetch();
  if ($u && password_verify($pass, $u['password_hash'])) {
    $_SESSION['user'] = $u;
    flash('success','Welcome back, '.$u['name'].'!');
    header('Location: '.BASE_URL); exit;
  } else {
    flash('error','Invalid email or password');
  }
}
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h4 class="mb-3">Login</h4>
        <form method="post">
          <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
          <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
          <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" name="password" required></div>
          <button class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3 small">No account? <a href="register.php">Register</a></p>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
