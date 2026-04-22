<?php
session_start();
require "db.php";

$user = $_SESSION['user'] ?? null;

$q = $_GET['q'] ?? "";

// Pagination
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

// Fetch torrents
$stmt = $pdo->prepare(
  "SELECT * FROM torrents
   WHERE name LIKE ?
   ORDER BY created_at DESC
   LIMIT $limit OFFSET $offset"
);
$stmt->execute(["%$q%"]);
$torrents = $stmt->fetchAll();

// Count total
$countStmt = $pdo->prepare(
  "SELECT COUNT(*) FROM torrents WHERE name LIKE ?"
);
$countStmt->execute(["%$q%"]);
$total = $countStmt->fetchColumn();
$totalPages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Turkey Index</title>
  <style>
    body { font-family: Arial; background:#f2f2f2; margin:0; }

    .nav {
      background:#003366;
      color:white;
      padding:10px;
    }

    .nav a {
      color:white;
      margin-right:10px;
      text-decoration:none;
    }

    .right {
      float:right;
    }

    .container { padding:15px; }

    table {
      width:100%;
      border-collapse:collapse;
      background:white;
    }

    th { background:#e6e6e6; padding:8px; text-align:left; }
    td { padding:8px; border-bottom:1px solid #ddd; }

    tr:nth-child(even) { background:#f9f9f9; }

    .pagination { margin-top:15px; }
  </style>
</head>

<body>

<div class="nav">
  <a href="index.php"><strong>Turkey Index</strong></a>
  <a href="upload.php">Upload</a>

  <span class="right">
    <?php if ($user): ?>
      Logged in as <?= htmlspecialchars($user['username']) ?>
      | <a href="logout.php" style="color:white;">Logout</a>
    <?php else: ?>
      <a href="login.php" style="color:white;">Login</a>
      | <a href="register.php" style="color:white;">Register</a>
    <?php endif; ?>
  </span>
</div>

<div class="container">

<form method="GET">
  <input type="text" name="q" placeholder="Search..." value="<?= htmlspecialchars($q) ?>">
  <button type="submit">Search</button>
</form>

<br>

<table>
<tr>
  <th>Name</th>
  <th>Size</th>
  <th>Added</th>
</tr>

<?php foreach ($torrents as $t): ?>
<tr>
  <td>
    <a href="view.php?id=<?= $t['id'] ?>">
      <?= htmlspecialchars($t['name']) ?>
    </a>
  </td>
  <td><?= number_format($t['size'] / 1024 / 1024, 2) ?> MB</td>
  <td><?= $t['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<div class="pagination">

  <?php if ($page > 1): ?>
    <a href="?q=<?= urlencode($q) ?>&page=<?= $page - 1 ?>">← Prev</a>
  <?php endif; ?>

  | Page <?= $page ?> of <?= $totalPages ?> |

  <?php if ($page < $totalPages): ?>
    <a href="?q=<?= urlencode($q) ?>&page=<?= $page + 1 ?>">Next →</a>
  <?php endif; ?>

</div>

</div>

</body>
</html>
