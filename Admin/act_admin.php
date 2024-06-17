<?php
include '../connection.php';

if (isset($_POST['bedit'])) {
    $id = $_POST['tid'];
    $email = $_POST['temail'];
    $username = $_POST['tusername'];
    $password = $_POST['tpassword'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $query = "UPDATE user SET email='$email', username='$username', password='$password_hash' WHERE id_admin=$id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script> 
        alert('Data berhasil diperbarui');
        window.location.href = 'viewAdmin.php';
        </script>";
    } else {
        echo "<script> 
        alert('Data gagal di perbarui');
        window.location.href = 'viewAdmin.php';
        </script>";
    }
}

if (isset($_POST['bdelete'])) {
    $hapus = mysqli_query($conn, "DELETE FROM user WHERE id_admin = '$_POST[id_admin]'");

    if ($hapus) {
        echo "<script> 
        alert('Data admin berhasil dihapus');
        document.location='viewAdmin.php';
        </script>";
    } else {
        echo "<script> 
        alert('Data admin gagal dihapus');
        document.location='viewAdmin.php';
        </script>";
    }
}
?>