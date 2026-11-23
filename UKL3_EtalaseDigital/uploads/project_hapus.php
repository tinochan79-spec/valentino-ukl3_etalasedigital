<?php
require_once __DIR__ . '/../config/kuis_db.php';
require_once __DIR__ . '/../middleware/auth_check.php';

check_login('../auth/login.php');

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $project = $stmt->fetch();

    if ($project) {
        // Pengecekan Otorisasi: Siswa hanya dapat menghapus miliknya sendiri
        if ($_SESSION['role'] === 'siswa' && $project['user_id'] != $_SESSION['user_id']) {
            die("Akses Ditolak!");
        }

        // Hapus file dari folder uploads/
        $imagePath = __DIR__ . '/' . basename($project['gambar']);
        if (is_file($imagePath)) {
            unlink($imagePath);
        }

        // Hapus baris data dari database
        $del = $pdo->prepare("DELETE FROM projects WHERE id = :id");
        $del->execute([':id' => $id]);
    }
}

header('Location: ../index.php');
exit();
?>

7. Fitur Super Admin: Manajemen Users 