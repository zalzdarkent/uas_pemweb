<?php
    $koneksi = mysqli_connect('localhost', 'root', '', 'mahasiswa');
    
    if (mysqli_connect_errno()) {
        echo "Gagal tembus database: " . mysqli_connect_error();
        exit();
    }
?>