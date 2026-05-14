<?php
/*
 * Name: Daniel Yu
 * Date: April 2, 2026
 * Description: Admin panel - view all orders and update status.
 */
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';

$admin_email = 'gbea@mcmaster.ca'; // Change to your admin email
if (!isset($_SESSION['email']) || $_SESSION['email'] != $admin_email) {
    header('Location: ../public/login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM merch_orders ORDER BY order_date DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Admin – Merchandise Orders</h1>
<p>Update order status below.</p>

<table class="simple-table">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Email</th>
            <th>Item</th>
            <th>Size</th>
            <th>Qty</th>
            <th>Order Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['order_id'] ?></td>
                <td><?= htmlspecialchars($order['email']) ?></td>
                <td><?= htmlspecialchars($order['item_name']) ?></td>
                <td><?= htmlspecialchars($order['size'] ?: '—') ?></td>
                <td><?= $order['quantity'] ?></td>
                <td><?= $order['order_date'] ?></td>
                <td class="status-cell status-<?= $order['status'] ?>">
                    <?= ucfirst($order['status']) ?>
                </td>
                <td>
                    <form method="post" action="../handlers/update_status.php" style="display: inline;">
                        <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                        <select name="status" class="status-select">
                            <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="paid" <?= $order['status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                            <option value="shipped" <?= $order['status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once '../includes/footer.php'; ?>