<?php
/*
 * Name: Daniel Yu
 * Date: March 25, 2026
 * Description: Order status page – displays the logged-in user's orders from the merch_orders table.
 */
session_start();
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];
$stmt = $pdo->prepare("
    SELECT order_id, item_name, size, quantity, order_date, status
    FROM merch_orders
    WHERE email = ?
    ORDER BY order_date DESC
");
$stmt->execute([$email]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>My Merchandise Orders</h1>
<p>Track the status of your CSS merchandise orders.</p>

<?php if (isset($_GET['success']) && $_GET['success'] == 'order_placed'): ?>
    <p class="success-message">Order placed successfully!</p>
<?php endif; ?>

<?php if (count($orders) === 0): ?>
    <p>You have not placed any merchandise orders yet. <a href="merch-order.php">Order now</a>.</p>
<?php else: ?>
    <table class="simple-table">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Item</th>
                <th>Size</th>
                <th>Qty</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                    <td><?= htmlspecialchars($order['item_name']) ?></td>
                    <td><?= htmlspecialchars($order['size'] ?: '—') ?></td>
                    <td><?= htmlspecialchars($order['quantity']) ?></td>
                    <td><?= htmlspecialchars($order['order_date']) ?></td>
                    <td>
                        <?php
                        $class = match ($order['status']) {
                            'pending' => 'status-pending',
                            'paid' => 'status-paid',
                            'shipped' => 'status-shipped',
                            default => ''
                        };
                        ?>
                        <span class="<?= $class ?>"><?= ucfirst($order['status']) ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>