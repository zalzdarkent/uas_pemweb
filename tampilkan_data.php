<?php
    include "koneksi.php";
    $process = mysqli_query($koneksi, "SELECT * FROM mhs") 
    or die (mysqli_error($koneksi));
?>