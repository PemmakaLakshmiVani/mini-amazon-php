<?php
require_once __DIR__ . '/../templates/header.php';
require_admin();

// ✅ Fetch all users except admins
$stmt = $pdo->query("SELECT id, name, email, created_at FROM users WHERE role='user' ORDER BY created_at DESC");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 class="mb-4">Registered Customers</h3>

<div class="card shadow-sm p-3">
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['name']) ?></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= $c['created_at'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center">No customers found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<a href="dashboard.php" class="btn btn-secondary mt-3">⬅ Back to Dashboard</a>

<?php require_once __DIR__.'/../templates/footer.php'; ?>
