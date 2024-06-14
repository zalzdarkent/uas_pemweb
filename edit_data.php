<?php
include("koneksi.php");

$id = $_POST['id'] ?? null;

if ($id === null) {
    echo "<script>
            alert('ID tidak ditemukan.');
            window.location.href = 'index.php';
          </script>";
    exit;
}

$query = "SELECT * FROM mhs WHERE id = $id";
$data = mysqli_query($koneksi, $query);
$mhs = mysqli_fetch_assoc($data);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_file'])) {
    $id = $_POST['id'];
    $file_lama = $mhs['file'];

    if (!empty($file_lama)) {
        unlink("upload/$file_lama");
        $query = "UPDATE mhs SET file=NULL WHERE id=$id";
        if (mysqli_query($koneksi, $query)) {
            echo "File berhasil dihapus.";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['delete_file'])) {
    $nama_mhs = $_POST['nama'];
    $npm = $_POST['npm'];
    $file_lama = $mhs['file'];

    if ($npm != $mhs['npm']) {
        $query_cek_npm = "SELECT * FROM mhs WHERE npm='$npm' AND id != $id";
        $result_cek_npm = mysqli_query($koneksi, $query_cek_npm);
        if (mysqli_num_rows($result_cek_npm) > 0) {
            echo "<script>
                    alert('NPM sudah ada. Harap gunakan NPM yang unik.');
                    window.location.href = 'edit.php?id=$id';
                  </script>";
            exit;
        }
    }

    $target_dir = "upload/";
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    if (!empty($_FILES["file"]["name"])) {
        if ($_FILES["file"]["size"] > 500000) {
            echo "<script>
                        alert('Ukuran file terlalu besar.');
                        window.location.href = 'edit.php?id=$id';
                      </script>";
            $uploadOk = 0;
        }

        if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "pdf") {
            echo "<script>
                        alert('Hanya format JPG, JPEG, PNG, dan PDF yang diperbolehkan.');
                        window.location.href = 'edit.php?id=$id';
                      </script>";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                if (!empty($file_lama)) {
                    unlink($target_dir . $file_lama);
                }
                $query = "UPDATE mhs SET nama_mhs='$nama_mhs', npm='$npm', file='" . basename($_FILES["file"]["name"]) . "' WHERE id=$id";
            }
        }
    } else {
        $query = "UPDATE mhs SET nama_mhs='$nama_mhs', npm='$npm' WHERE id=$id";
    }

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil diubah'); window.location='index.php';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>