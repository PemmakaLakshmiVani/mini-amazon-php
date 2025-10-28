<?php require_once __DIR__.'/../templates/header.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  check_csrf();
  $name = trim($_POST['name']??'');
  $email = trim($_POST['email']??'');
  $pass = $_POST['password']??'';
  if (!$name || !$email || strlen($pass)<6) {
    flash('error','Please fill all fields. Password must be 6+ chars.');
  } else {
    $hash = password_hash($pass, PASSWORD_DEFAULT);
    try {
      $stmt = $pdo->prepare("INSERT INTO users (name,email,password_hash,role) VALUES (?,?,?,'user')");
      $stmt->execute([$name,$email,$hash]);
      flash('success','Account created. You can login now.');
      header('Location: '.BASE_URL.'auth/login.php'); exit;
    } catch (PDOException $e) {
      flash('error','Email already exists.');
    }
  }
}
?>
<div class="row justify-content-center">
  <div class="col-md-6 col-lg-5">
    <div class="card shadow-sm">
      <div class="card-body p-4">
        <h4 class="mb-3">Create account</h4>
        <form method="post">
          <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
          <div class="mb-3"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
          <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
          <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" name="password" required></div>
          <button class="btn btn-primary w-100">Register</button>
        </form>
        <p class="mt-3 small">Already have an account? <a href="login.php">Login</a></p>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
