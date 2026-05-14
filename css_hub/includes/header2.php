<!-- 
  Authors: Amanda Gbe and Daniel Yu
  Date: March 30, 2026
  Description: Header for dashboard pages – same navbar structure.
-->
<?php
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Web Hub</title>
    <link rel="stylesheet" href="../css/hstyle.css">
    <script src="../js/main.js" defer></script>
</head>

<body>
    <header>
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <nav class="navbar">
            <div class="logo-container">
                <img src="../images/pattern.png" alt="CSS Hub Logo" class="nav-logo">
                <a href="../public/index.php" class="logo">CSS Hub</a>
            </div>
            <ul class="nav-links">
                <?php if (isset($_SESSION['email'])): ?>
                    <li><a href="../public/dash.php" class="<?= $current_page == 'dash.php' ? 'active' : '' ?>">Dashboard</a></li>
                <?php endif; ?>
                <li><a href="../public/events.php" class="<?= $current_page == 'events.php' ? 'active' : '' ?>">Events</a></li>
                <li><a href="../public/resources.php" class="<?= $current_page == 'resources.php' ? 'active' : '' ?>">Resources</a></li>
                <li class="dropdown">
                    <a href="#" id="formsToggle" class="nav-link <?php if ($current_page == 'forms.php' || $current_page == 'suggestions.php') echo 'active'; ?>">
                        Get Involved ▾
                    </a>
                    <div class="dropdown-content" id="formsMenu">
                        <a href="../public/forms.php">Forms</a>
                        <a href="../public/suggestions.php">Suggestions</a>
                    </div>
                </li>
                <li><a href="../public/merch-order.php" class="<?= $current_page == 'merch-order.php' ? 'active' : '' ?>">Merchandise</a></li>
                <?php if (isset($_SESSION['email'])): ?>
                    <?php if ($_SESSION['email'] == 'gbea@mcmaster.ca'): ?>
                        <li class="dropdown">
                            <a href="#" id="adminToggle" class="nav-link <?php if (strpos($current_page, 'admin_') === 0) echo 'active'; ?>">
                                Admin ▾
                            </a>
                            <div class="dropdown-content" id="adminMenu">
                                <a href="../admin/admin_suggestions.php">Event Suggestions</a>
                                <a href="../admin/admin_orders.php">Orders</a>
                                <a href="../admin/admin_help.php">Help Messages</a>
                            </div>
                        </li>
                    <?php endif; ?>
                    <li class="dropdown hamburger-menu">
                        <a href="#" id="hamburgerToggle" class="nav-link">☰</a>
                        <div class="dropdown-content" id="hamburgerDropdown">
                            <a href="../public/team.php">Meet the Team</a>
                            <a href="../public/contact.php">Contact Us</a>
                            <a href="../public/logout.php">Logout</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="../public/login.php" class="<?= $current_page == 'login.php' ? 'active' : '' ?>">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>