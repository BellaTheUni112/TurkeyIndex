<?php
session_start();
require "db.php";

function requireLogin() {
  if (!isset($_SESSION['user'])) {
    die("Login required");
  }
}

function requireAdmin() {
  if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] != 1) {
    die("Admin access only");
  }
}
?>
