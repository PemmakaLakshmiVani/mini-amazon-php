<?php
function ensure_session() {
    if (session_status() === PHP_SESSION_NONE) session_start();
}
ensure_session();

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function check_csrf() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
            http_response_code(400);
            die('Bad CSRF token');
        }
    }
}
function flash($key, $msg=null) {
    ensure_session();
    if ($msg !== null) {
        $_SESSION['flash'][$key] = $msg;
        return;
    }
    if (!empty($_SESSION['flash'][$key])) {
        $m = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $m;
    }
    return null;
}
function current_user() { return $_SESSION['user'] ?? null; }
function is_logged_in() { return !empty($_SESSION['user']); }
function is_admin() { return !empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'; }
function require_login() {
    if (!is_logged_in()) { header('Location: '.BASE_URL.'auth/login.php'); exit; }
}
function require_admin() {
    if (!is_admin()) { http_response_code(403); die('Forbidden'); }
}
function money($n) { return CURRENCY . number_format((float)$n, 2); }

function cart_init() { if (!isset($_SESSION['cart'])) $_SESSION['cart'] = []; }
function cart_add($pid, $qty=1) {
    cart_init();
    $pid = (int)$pid; $qty = max(1, (int)$qty);
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;
}
function cart_set($pid, $qty) {
    cart_init();
    $pid = (int)$pid; $qty = (int)$qty;
    if ($qty <= 0) unset($_SESSION['cart'][$pid]); else $_SESSION['cart'][$pid] = $qty;
}
function cart_remove($pid) { cart_init(); unset($_SESSION['cart'][(int)$pid]); }
function cart_clear() { $_SESSION['cart'] = []; }

function cart_items($pdo) {
    cart_init();
    if (empty($_SESSION['cart'])) return [];
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $in = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($in)");
    $stmt->execute($ids);
    $rows = $stmt->fetchAll();
    foreach ($rows as &$r) { $r['qty'] = $_SESSION['cart'][$r['id']] ?? 0; }
    return $rows;
}
function cart_totals($items) {
    $subtotal = 0;
    foreach ($items as $it) $subtotal += $it['price'] * $it['qty'];
    $shipping = ($subtotal > 999 || $subtotal == 0) ? 0 : 49;
    $total = $subtotal + $shipping;
    return compact('subtotal', 'shipping', 'total');
}
