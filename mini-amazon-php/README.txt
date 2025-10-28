HOW TO RUN (Localhost, XAMPP/WAMP/LAMP)
--------------------------------------
1) Create MySQL database:
   - Create a database named: mini_amazon
   - Import file: sql/schema.sql

2) Configure DB in config.php if needed (DB_USER/DB_PASS). Optionally set RAZORPAY_KEY_ID.

3) Move the project folder `mini-amazon-php` into your web server root:
   - XAMPP on Windows: C:\xampp\htdocs\
   - WAMP: C:\wamp64\www\
   - Linux: /var/www/html/

4) Visit: http://localhost/mini-amazon-php/
   - Admin login: admin@example.com / admin123
   - Create a user account from Signup page for placing orders.

NOTES
-----
- This is an educational mini‑clone (no real payments). Razorpay is test‑mode only.
- Image uploads are simplified. You can paste relative URL (e.g., assets/img/yourimage.svg) when creating products.
- Security basics included: prepared statements, password hashing, CSRF tokens.
- Improve for production: server‑side payment verification, file uploads with validation, rate limiting, etc.
