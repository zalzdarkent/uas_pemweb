<?php
    include "koneksi.php";
    $id = $_GET['id'];
    $process = mysqli_query($koneksi, "DELETE FROM mhs WHERE id = $id") 
    or die (mysqli_error($koneksi));

    if($process){
        echo "
                <script>
                    alert('Data berhasil dihapus');
                    window.location.href='index.php';
                </script>";
    }else echo "<script>
                    alert('Data gagal dihapus')
                    window.location.href='index.php';
                </script>";
?>