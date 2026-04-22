<?php
session_start();
require "db.php";

$user = $_SESSION['user'] ?? null;

$error = "";
$success = "";

if (!$user) {
  $error = "You must be logged in to upload.";
}

elseif (strtotime($user['created_at']) > strtotime('-14 days')) {
  $error = "Account must be at least 14 days old.";
}

elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name = trim($_POST['name']);
  $magnet = trim($_POST['magnet']);
  $size = (int)$_POST['size'];

  if (!$name || !$magnet) {
    $error = "Missing fields.";
  }

  elseif (!preg_match('/btih:/', $magnet)) {
    $error = "Invalid magnet link.";
  }

  else {
    $stmt = $pdo->prepare(
      "SELECT COUNT(*) FROM uploads
       WHERE user_id = ?
       AND created_at > NOW() - INTERVAL 1 HOUR"
    );
    $stmt->execute([$user['id']]);
    $count = $stmt->fetchColumn();

    if ($count >= 5) {
      $error = "Rate limit exceeded (5 uploads/hour).";
    } else {

      preg_match('/btih:([a-fA-F0-9]+)/', $magnet, $matches);
      $hash = $matches[1] ?? '';

      try {
        $stmt = $pdo->prepare(
          "INSERT INTO torrents (name, info_hash, magnet, size)
           VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$name, $hash, $magnet, $size]);

        // Log upload
        $stmt = $pdo->prepare(
          "INSERT INTO uploads (user_id) VALUES (?)"
        );
        $stmt->execute([$user['id']]);

        $success = "Upload successful!";
      } catch (PDOException $e) {
        $error = "Duplicate or invalid torrent.";
      }
    }
  }
}
?>

<!DOCTYPE html>
<html>
<body>

<div style="background:#003366;color:white;padding:10px;">
  <a href="index.php" style="color:white;text-decoration:none;">
    <strong>Turkey Index</strong>
  </a>
</div>

<h2>Upload Torrent</h2>

<?php if ($error): ?>
  <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if ($success): ?>
  <p style="color:green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if ($user): ?>
<form method="POST">
  Name:<br>
  <input name="name"><br><br>

  Magnet:<br>
  <input name="magnet"><br><br>

  Size (bytes):<br>
  <input name="size"><br><br>

  <button type="submit">Upload</button>
</form>
<?php endif; ?>

</body>
</html>
