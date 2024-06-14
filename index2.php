<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_mhs = $_POST['nama'];
    $npm = $_POST['npm'];

    if (!is_numeric($npm)) {
        echo "<script>alert('NPM harus berupa angka'); window.location='index.php';</script>";
    } elseif (strlen($npm) < 13) {
        echo "<script>alert('NPM harus kurang dari 13 digit'); window.location='index.php';</script>";
    } elseif (strlen($npm) > 13) {
        echo "<script>alert('Digit NPM lebih dari 13'); window.location='index.php';</script>";
    } else {
        $query = "INSERT INTO mhs (nama_mhs, npm) VALUES ('$nama_mhs', '$npm')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Data berhasil disimpan'); window.location='index.php';</script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
}
?>