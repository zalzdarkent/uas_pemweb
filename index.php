
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORM</title>
    <link href="Library/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="Library/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="Library/assets/styles.css" rel="stylesheet" media="screen">
    <script src="Library/vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
</head>

<body>
    <div class="span9" id="content">
        <!-- morris stacked chart -->
        <div class="row-fluid">
            <!-- block -->
            <div class="block">
                <div class="navbar navbar-inner block-header">
                    <div class="muted pull-left">Form Data Mahasiswa</div>
                </div>
                <div class="block-content collapse in">
                    <div class="span12">
                        <form action="index2.php" method="POST" class="form-horizontal" enctype="multipart/form-data">
                            <fieldset>
                                <legend>Input Data Mahasiswa</legend>
                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">Nama Mahasiswa</label>
                                    <div class="controls">
                                        <input type="text" class="input-xlarge focused" id="nama" name="nama">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">NPM Mahasiswa</label>
                                    <div class="controls">
                                        <input type="text" class="input-xlarge focused" id="prodi" name="npm">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="fileInput">Unggah File</label>
                                    <div class="controls">
                                        <input type="file" class="input-file" id="file" name="file" accept="image/*,application/pdf" onchange="previewFile()">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="filePreview">Pratinjau File</label>
                                    <div class="controls">
                                        <img id="filePreview" src="#" alt="Pratinjau File" style="display: none; max-width: 200px; max-height: 200px;" />
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <button type="reset" class="btn">Cancel</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row-fluid">
            <!-- block -->
            <div class="block">
                <div class="navbar navbar-inner block-header">
                    <div class="muted pull-left">Data Mahasiswa</div>
                </div>
                <div class="block-content collapse in">
                    <div class="span12">
                        <form action="export_excel.php" method="post" class="float-right">
                            <button type="submit" class="btn btn-success">Export to Excel</button>
                        </form>
                        <form action="" method="get">
                            <input type="text" name="cari" id="cari" placeholder="Cari data mahasiswa" value="<?php if(isset($_GET['cari'])){ echo $_GET['cari'];}?>">
                            <button type="submit">Cari</button>
                        </form>
                        <!-- <input class="float-right mr-2" type="text" id="search" placeholder="Cari data mahasiswa" onkeyup="searchTable()"> -->
                        <table class="table" id="data-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NPM Mahasiswa</th>
                                    <th>File</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include('koneksi.php');
                                if (isset($_GET['cari'])) {
                                    $search = $_GET['cari'];
                                    if (!is_numeric($search)) {
                                        echo "<script>alert('NPM harus berupa angka'); window.location='index.php';</script>";
                                    } elseif (strlen($search) < 13) {
                                        echo "<script>alert('NPM kurang dari 13 digit'); window.location='index.php';</script>";
                                    } elseif (strlen($search) > 13) {
                                        echo "<script>alert('Digit NPM lebih dari 13'); window.location='index.php';</script>";
                                    } else {
                                        $query = "SELECT * FROM mhs WHERE npm LIKE '%" . $search . "%'";   
                                    } 
                                } else {
                                    $query = "SELECT * FROM mhs";
                                }

                                $process = mysqli_query($koneksi, $query)
                                    or die(mysqli_error($koneksi));
                                while ($data = mysqli_fetch_assoc($process)) {
                                ?>
                                    <tr>
                                        <td><?php echo $data['id'] ?></td>
                                        <td><?php echo $data['nama_mhs'] ?></td>
                                        <td><?php echo $data['npm'] ?></td>
                                        <td>
                                            <?php if ($data['file']) : ?>
                                                <a href="upload/<?php echo $data['file']; ?>" target="_blank">Lihat File</a>
                                            <?php else : ?>
                                                Tidak ada file
                                            <?php endif; ?>
                                        </td>
                                        <td><a href="edit.php?id=<?php echo $data['id']; ?>">Edit</a> |
                                            <a href="#" onclick="confirmDestroy(<?php echo $data['id']; ?>)">Hapus</a>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /block -->
        </div>
    </div>
    <script>
        function searchTable() {
            const input = document.getElementById('search');
            const filter = input.value.toLowerCase();
            const table = document.getElementById('data-table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const cells = rows[i].getElementsByTagName('td');
                let match = false;
                if (cells.length > 0) {
                    const name = cells[1].innerText.toLowerCase();
                    const prodi = cells[2].innerText.toLowerCase();
                    if (name.includes(filter) || prodi.includes(filter)) {
                        match = true;
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }

        function previewFile() {
            const preview = document.getElementById('filePreview');
            const file = document.getElementById('file').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function() {
                preview.src = reader.result;
                preview.style.display = 'block';
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function resetPreview() {
            const preview = document.getElementById('filePreview');
            preview.src = '#';
            preview.style.display = 'none';
        }

        function confirmDestroy(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                window.location.href = 'hapus_data.php?id=' + id;
            }
        }
    </script>
</body>

</html>