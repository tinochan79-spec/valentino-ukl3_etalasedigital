<?php
require_once __DIR__ . '/config/kuis_db.php';
require_once __DIR__ . '/middleware/auth_check.php';

check_login();

$stmt = $pdo->query(
    "SELECT projects.*, users.nama AS pembuat
     FROM projects
     JOIN users ON projects.user_id = users.id
     ORDER BY projects.created_at DESC"
);
$projects = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Etalase Digital Stella</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/branding.css">
</head>
<body class="home-page">
<div class="header">
    <a class="site-brand" href="index.php">
        <img src="assets/images/telkom-logo.png" alt="Logo Telkom">
        <h2 class="site-brand-name">Etalase Digital Stella</h2>
    </a>
    <div>
        Halo, <b><?= htmlspecialchars($_SESSION['nama']) ?></b> (<?= htmlspecialchars($_SESSION['role']) ?>) |
        <a href="uploads/project_tamabah.php" class="btn btn-blue">+ Tambah Karya</a>
        <?php if ($_SESSION['role'] === 'super_admin'): ?>
            <a href="admin/users.php" class="btn btn-warning">Kelola Users</a>
        <?php endif; ?>
        <a href="auth/logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>

<div class="grid">
    <?php if (!$projects): ?>
        <p>Belum ada karya digital. Klik “Tambah Karya” untuk mulai mengisi etalase.</p>
    <?php else: ?>
        <?php foreach ($projects as $project): ?>
            <div class="card">
                <img src="uploads/<?= htmlspecialchars($project['gambar']) ?>" alt="Karya">
                <div class="card-body">
                    <h3><?= htmlspecialchars($project['nama_project']) ?></h3>
                    <p><small>Oleh: <b><?= htmlspecialchars($project['pembuat']) ?></b></small></p>
                    <p><?= htmlspecialchars($project['deskripsi']) ?></p>
                    <p><a href="<?= htmlspecialchars($project['link_project']) ?>" target="_blank" rel="noopener noreferrer">Lihat Project Tautan &rarr;</a></p>
                    <?php if ($_SESSION['role'] === 'super_admin' || $_SESSION['role'] === 'admin' || (int) $_SESSION['user_id'] === (int) $project['user_id']): ?>
                        <a href="uploads/project_edit.php?id=<?= (int) $project['id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="uploads/project_hapus.php?id=<?= (int) $project['id'] ?>" onclick="return confirm('Hapus karya ini?')" class="btn btn-danger">Hapus</a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>