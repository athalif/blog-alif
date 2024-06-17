<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_artikel = isset($_POST['id_artikel']) ? intval($_POST['id_artikel']) : 0;
    $nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
    $komentar = isset($_POST['komentar']) ? trim($_POST['komentar']) : '';

    if ($id_artikel && $nama && $komentar) {
        $query = "INSERT INTO comments (id_artikel, nama, komentar) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            die("Error saat mempersiapkan statement: " . $conn->error);
        }

        $stmt->bind_param("iss", $id_artikel, $nama, $komentar);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: blogpost.php?id=" . $id_artikel);
            exit();
        } else {
            die("Error statement: " . $stmt->error);
        }
    } else {
        die("Data input tidak valid");
    }
} else {
    die("Metode permintaan tidak valid");
}
?>
