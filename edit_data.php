<?php
    include("koneksi.php");
    $id = $_GET['id'];
    $proses_ambil = mysqli_query($koneksi, "SELECT * FROM mhs WHERE id = $id")
                    or die(mysqli_error($koneksi));
?>