<?php
require "db.php";

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM torrents WHERE id = ?");
$stmt->execute([$id]);
$t = $stmt->fetch();

if (!$t) {
  die("Torrent not found");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title><?= htmlspecialchars($t['name']) ?></title>
</head>

<body>

<div style="background:#003366;color:white;padding:10px;">
  <a href="index.php" style="color:white;text-decoration:none;">
    <strong>Turkey Index</strong>
  </a>
  | <a href="upload.php" style="color:white;">Upload</a>
</div>

<h2><?= htmlspecialchars($t['name']) ?></h2>

<p><strong>Size:</strong> <?= number_format($t['size'] / 1024 / 1024, 2) ?> MB</p>
<p><strong>Added:</strong> <?= $t['created_at'] ?></p>

<p>
  <a href="<?= htmlspecialchars($t['magnet']) ?>">
    🔗 Download Magnet
  </a>
</p>

<p><a href="index.php">← Back</a></p>

</body>
</html>
