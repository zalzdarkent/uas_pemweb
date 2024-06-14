<?php
include("koneksi.php");

$id = $_GET['id'];

$ambil_data = mysqli_query($koneksi, "SELECT * FROM mhs WHERE id = '$id'");
$mhs = mysqli_fetch_array($ambil_data);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="Library/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="Library/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="Library/assets/styles.css" rel="stylesheet" media="screen">
    <script src="Library/vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    <title>Document</title>
</head>

<body>
    <div class="span9" id="content">
        <div class="row-fluid">
            <!-- block -->
            <div class="block">
                <div class="navbar navbar-inner block-header">
                    <div class="muted pull-left">Form Input Nilai Mahasiswa</div>
                </div>
                <div class="block-content collapse in">
                    <div class="span12">
                        <form action="edit_data.php" method="POST" class="form-horizontal" enctype="multipart/form-data">
                            <fieldset>
                                <legend>Input Nilai Mahasiswa</legend>
                                <input type="hidden" name="id" value="<?php echo $mhs['id'] ?>">
                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">Nama Mahasiswa</label>
                                    <div class="controls">
                                        <input type="text" class="input-xlarge focused" id="nama" name="nama" value="<?php echo $mhs['nama_mhs'] ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="focusedInput">NPM Mahasiswa</label>
                                    <div class="controls">
                                        <input type="text" class="input-xlarge focused" id="npm" name="npm" value="<?php echo $mhs['npm'] ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="fileInput">Unggah File</label>
                                    <div class="controls">
                                        <input type="file" class="input-file" id="file" name="file" accept="image/*,application/pdf" onchange="previewFile()">
                                        <?php if ($mhs['file']) : ?>
                                            <br><small>File yang sudah diunggah: <?php echo $mhs['file']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($mhs['file']) : ?>
                                    <div class="control-group" id="previewContainer">
                                        <label class="control-label" for="filePreview">Pratinjau File</label>
                                        <div class="controls">
                                            <img id="filePreview" src="upload/<?php echo $mhs['file']; ?>" alt="Pratinjau File" style="max-width: 200px; max-height: 200px;" />
                                        </div>
                                        <div class="controls">
                                            <button type="button" class="btn btn-danger" onclick="deleteFile()">Hapus File</button>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <div class="control-group" id="previewContainer" style="display:none;">
                                        <label class="control-label" for="filePreview">Pratinjau File</label>
                                        <div class="controls">
                                            <img id="filePreview" src="" alt="Pratinjau File" style="max-width: 200px; max-height: 200px;" />
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <button type="reset" class="btn" onclick="resetPreview()">Cancel</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewFile() {
            const preview = document.getElementById('filePreview');
            const file = document.getElementById('file').files[0];
            const reader = new FileReader();
            const previewContainer = document.getElementById('previewContainer');

            reader.addEventListener("load", function() {
                preview.src = reader.result;
                previewContainer.style.display = 'block';
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function deleteFile() {
            if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
                const preview = document.getElementById('filePreview');
                const previewContainer = document.getElementById('previewContainer');
                const deleteButton = document.getElementById('deleteButton');

                const form = new FormData();
                form.append('delete_file', true);
                form.append('id', document.querySelector('input[name="id"]').value);

                fetch('', {
                    method: 'POST',
                    body: form
                }).then(response => response.text()).then(data => {
                    alert(data);
                    preview.src = '';
                    previewContainer.style.display = 'none';
                    deleteButton.style.display = 'none';
                }).catch(error => {
                    console.error('Error:', error);
                });
            }
        }

        function resetPreview() {
            window.location.href = 'index.php';
        }
    </script>
</body>

</html>