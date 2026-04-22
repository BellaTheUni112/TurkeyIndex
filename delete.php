<?php
require "auth.php";
requireAdmin();

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("DELETE FROM torrents WHERE id = ?");
$stmt->execute([$id]);

header("Location: admin.php");
exit;
