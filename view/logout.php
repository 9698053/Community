<?php
/**
 * View - Logout
 * File: view/logout.php
 */

session_start();

// Distruggi la sessione
session_unset();
session_destroy();

// Redirect al login
header('Location: login.php');
exit();
?>