<?php
require "auth.php";
require "db.php";
requireAdmin();

$torrents = $pdo->query("SELECT * FROM torrents ORDER BY created_at DESC")->fetchAll();
$users = $pdo->query("SELECT id, username, created_at, is_admin FROM users")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Panel</title>
  <style>
    body { font-family: Arial; background:#f2f2f2; }
    .box { padding:20px; }
    table { width:100%; background:white; border-collapse:collapse; margin-bottom:20px; }
    th, td { padding:10px; border-bottom:1px solid #ddd; }
    th { background:#333; color:white; }
    a { color:red; text-decoration:none; }
  </style>
</head>

<body>

<div class="box">
  <h2>Admin Panel</h2>

  <p><a href="index.php">← Back to site</a></p>

  <h3>Users</h3>
  <table>
    <tr>
      <th>ID</th>
      <th>Username</th>
      <th>Joined</th>
      <th>Admin</th>
    </tr>

    <?php foreach ($users as $u): ?>
    <tr>
      <td><?= $u['id'] ?></td>
      <td><?= htmlspecialchars($u['username']) ?></td>
      <td><?= htmlspecialchars($u['created_at']) ?></td>
      <td><?= $u['is_admin'] ? "Yes" : "No" ?></td>
    </tr>
    <?php endforeach; ?>
  </table>

  <h3>Torrents</h3>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Size</th>
      <th>Action</th>
    </tr>

    <?php foreach ($torrents as $t): ?>
    <tr>
      <td><?= $t['id'] ?></td>
      <td><?= htmlspecialchars($t['name']) ?></td>
      <td><?= number_format($t['size'] / 1024 / 1024, 2) ?> MB</td>
      <td>
        <a href="delete.php?id=<?= $t['id'] ?>"
           onclick="return confirm('Delete this torrent?')">
          Delete
        </a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>

</div>

</body>
</html>
