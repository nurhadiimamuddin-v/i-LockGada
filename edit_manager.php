

<?php
// edit_Manager.php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_manager'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $pegadaian_id = $_POST['pegadaian_id'];

    // Validasi data
    if (!empty($id) && !empty($nama) && !empty($username) && !empty($pegadaian_id)) {
        $stmt = $conn->prepare("UPDATE Manager SET nama=?, username=?, pegadaian_id=? WHERE id_manager=?");
        $stmt->bind_param("ssii", $nama, $username, $pegadaian_id, $id);
        
        if ($stmt->execute()) {
            header("Location: manager.php?status=success");
        } else {
            header("Location: manager.php?status=error&message=" . urlencode($conn->error));
        }
        exit();
    } else {
        header("Location: manager.php?status=error&message=Data+tidak+lengkap");
        exit();
    }
} else {
    header("Location: manager.php");
    exit();
}
?>