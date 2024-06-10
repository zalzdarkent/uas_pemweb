<?php
include "koneksi.php";

$nama_mhs = $_POST['nama'];
$npm = $_POST['npm'];

$target_dir = "upload/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

$cekNPM = "SELECT * FROM mhs WHERE npm = '$npm'";
$data = mysqli_query($koneksi, $cekNPM);

if (mysqli_num_rows($data) > 0) {
    echo "<script>
            alert('NPM sudah ada. Harap gunakan NPM yang unik.');
            window.location.href = 'index.php';
          </script>";
    $uploadOk = 0;
}

if ($_FILES["file"]["size"] > 500000) {
    echo "<script>
            alert('Ukuran file terlalu besar.');
            window.location.href = 'index.php';
          </script>";
    $uploadOk = 0;
}

if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "pdf") {
    echo "<script>
            alert('Hanya format JPG, JPEG, PNG, dan PDF yang diperbolehkan.');
            window.location.href = 'index.php';
          </script>";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "<script>
            alert('File tidak terunggah.');
            window.location.href = 'index.php';
          </script>";
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $insertQuery = "INSERT INTO mhs (nama_mhs, npm, file) VALUES ('$nama_mhs', '$npm', '" . basename($_FILES["file"]["name"]) . "')";
        if (mysqli_query($koneksi, $insertQuery)) {
            echo "<script>
                    alert('Data berhasil ditambahkan.');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            $errorMsg = "Error: " . $insertQuery . "<br>" . mysqli_error($koneksi);
            echo "<script>
                    alert('$errorMsg');
                    window.location.href = 'index.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Terjadi kesalahan saat mengunggah file.');
                window.location.href = 'index.php';
              </script>";
    }
}