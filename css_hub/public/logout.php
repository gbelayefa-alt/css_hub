<?php
/*
 * Name: Amanda Gbe
 * Date: March 29, 2026
 * Description: This file handles the logout process by destroying the user's session and redirecting them to the login page.
 */
session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit;
?>