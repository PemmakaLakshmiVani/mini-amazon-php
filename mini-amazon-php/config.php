<?php
// ====== Site Config ======
define('DB_HOST', 'localhost');
define('DB_NAME', 'mini_amazon');
define('DB_USER', 'root');
define('DB_PASS', '');

// Base URL (adjust if not hosted at web root), trailing slash required.
define('BASE_URL', '/mini-amazon-php/');   // ✅ IMPORTANT

// Razorpay test config (optional). Leave empty to disable.
define('RAZORPAY_KEY_ID', '');     // e.g. 'rzp_test_xxxxxx'
define('RAZORPAY_KEY_SECRET', ''); // keep server-side only

// App
define('APP_NAME', 'ShopLite');
define('CURRENCY', '₹'); // change to '$' etc.
