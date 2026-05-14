<?php
/*
 * Name: Daniel Yu and Amanda Gbe
 * Date: March 25, 2026
 * Description: Merchandise order form – processes order via POST to submit_order.php.
 */
session_start();
require_once '../includes/header.php';   // adjusted path
require_once '../includes/db.php';        // for session check (optional)

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}
?>

<h1>Order CSS Merchandise</h1>
<p>Show your CSS pride! Fill out the form below to place an order.</p>

<form method="post" action="../handlers/submit_order.php">
    <div class="merch-grid">

    <div class="merch-card" data-value="T-shirt">
        <img src="../images/tshirt.png">
        <h3>T-Shirt</h3>
        <p>$20</p>
    </div>

    <div class="merch-card" data-value="Blue McMaster Hoodie">
        <img src="../images/hoodie1.png">
        <h3>Blue McMaster Hoodie</h3>
        <p>$55</p>
    </div>
    <div class="merch-card" data-value="Grey Hoodie">
        <img src="../images/hoodie2.png">
        <h3>Grey Hoodie</h3>
        <p>$55</p>
    </div>
    <div class="merch-card" data-value="Comp Sci Hoodie">
        <img src="../images/hoodie3.png">
        <h3>Comp Sci Hoodie</h3>
        <p>$55</p>
    </div>
    <div class="merch-card" data-value="White Logo Hoodie">
        <img src="../images/hoodie4.png">
        <h3>White Logo Hoodie</h3>
        <p>$55</p>
    </div>
    <div class="merch-card" data-value="White Hoodie">
        <img src="../images/hoodie5.png">
        <h3>White Hoodie</h3>
        <p>$55</p>
    </div>
    <div class="merch-card" data-value="Black Logo Hoodie">
        <img src="../images/hoodie6.png">
        <h3>Black Logo Hoodie</h3>
        <p>$55</p>
    </div>
    <div class="merch-card" data-value="Sweatshirt">
        <img src="../images/sweatshirt.png">
        <h3>Sweatshirt</h3>
        <p>$45</p>
    </div>

    <div class="merch-card" data-value="Sticker Pack">
        <img src="../images/stickers.png">
        <h3>Sticker Pack</h3>
        <p>$5</p>
    </div>

</div>

<!-- hidden input to replace dropdown -->
<input type="hidden" name="item_name" id="selectedItem" required>
        </div>
        <div class="form-group">
            <label for="size">Size</label>
            <select id="size" name="size">
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Qty</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" required>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit">Submit Order</button>
    </div>
</form>

<script src="../js/validation.js"></script>
<?php require_once '../includes/footer.php'; ?>