<?php
session_start()
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
    <title>List Survey</title>
</head>

<body>
    <div class="container" style="margin-top: 20px">
        <div class="row">
            <div class="col-md-12">
                <h2>Simple SurveyApp</h2>
                <div class="card">
                    <div class="card-header">
                        Daftar semua survey
                    </div>
                    <div class="card-body">
                        <a href="survey/create.php" class="btn btn-md btn-success" style="margin-bottom: 12px">Buat Survey</a>
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-success" role="alert">
                                <?= htmlspecialchars($_SESSION['message']) ?>
                            </div>
                            <?php unset($_SESSION['message']); // Hapus error setelah ditampilkan 
                            ?>
                        <?php endif; ?>
                        <table class="table table-bordered" id="myTable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include("./config/koneksi.php");
                                $no = 1;
                                $query = mysqli_query($connection, "SELECT * FROM survey");
                                while ($row = mysqli_fetch_array($query)) {
                                ?>
                                    <tr>
                                        <td><?php echo $no++ ?></td>
                                        <td><?php echo $row['title'] ?></td>
                                        <td><?php echo $row['description'] ?></td>
                                        <td class="text-center">
                                            <a href="survey/detail.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-primary">Detail</a>
                                            <a href="survey/answer-survey.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-warning">isi survey</a>
                                            <a href="survey/all-answer-survey.php?id=<?php echo $row['id'] ?>" class="btn btn-sm btn-success">Lihat jawaban</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
        <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
            });
        </script>
</body>

</html>