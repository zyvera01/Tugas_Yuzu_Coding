<?php
session_start();

if (!empty($_SESSION['login'])) {
    header("Location: dashboard.php");
} else {
    header("Location: login.php");
}

exit;