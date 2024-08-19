<?php
session_start();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <title>Buat Survey</title>
</head>

<body>

    <div class="container" style="margin-top: 20px">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>Simple SurveyApp</h2>
                <div class="card">
                    <div class="card-header">
                        Buat survey baru
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($_SESSION['error']) ?>
                            </div>
                            <?php unset($_SESSION['error']); // Hapus error setelah ditampilkan 
                            ?>
                        <?php endif; ?>
                        <form action="create-method.php" method="POST">

                            <div class="form-group">
                                <label>Judul survey<span class="text-danger">*</span></label>
                                <input type="text" name="title" placeholder="Masukan title" class="form-control" autocomplete="yes" value="<?= isset($_SESSION['form_data']['title']) ? htmlspecialchars($_SESSION['form_data']['title']) : '' ?>">
                            </div>

                            <div class="form-group">
                                <label>Deskripsi<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="description" placeholder="Masukan deskripsi" rows="2"><?= isset($_SESSION['form_data']['description']) ? htmlspecialchars($_SESSION['form_data']['description']) : '' ?></textarea>
                            </div>

                            <div id="questions-container">
                                <?php
                                $index = 1;
                                if (isset($_SESSION['form_data']['question']) && is_array($_SESSION['form_data']['question'])):
                                    foreach ($_SESSION['form_data']['question'] as $index => $question): ?>
                                        <div class="question-group">
                                            <div class="form-group">
                                                <label>Pertanyaan <?= $index + 1 ?><span class="text-danger">*</span></label>
                                                <textarea class="form-control" name="question[]" placeholder="Masukan pertanyaan" rows="2"><?= htmlspecialchars($question) ?></textarea>
                                            </div>
                                            <?php if (isset($_SESSION['form_data']['option'][$index]) && is_array($_SESSION['form_data']['option'][$index])): ?>
                                                <?php foreach ($_SESSION['form_data']['option'][$index] as $option): ?>
                                                    <div class="form-group">
                                                        <label>Pilihan <span class="text-danger">*</span></label>
                                                        <input type="text" name="option[<?= $index ?>][]" placeholder="Masukan opsi jawaban" class="form-control" value="<?= htmlspecialchars($option) ?>">
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach;
                                else: ?>
                                    <div class="question-group">
                                        <div class="form-group">
                                            <label>Pertanyaan<span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="question[]" placeholder="Masukan pertanyaan" rows="2"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Pilihan<span class="text-danger">*</span></label>
                                            <input type="text" name="option[0][]" placeholder="Masukan opsi jawaban" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Pilihan<span class="text-danger">*</span></label>
                                            <input type="text" name="option[0][]" placeholder="Masukan opsi jawaban" class="form-control">
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Button to add question and options -->
                            <button type="submit" class="btn btn-success">Save</button>
                            <button type="button" id="add-question" class="btn btn-primary">Tambah Pertanyaan</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            let questionCount = <?= isset($_SESSION['form_data']['question']) ? count($_SESSION['form_data']['question']) : 0 ?>;

            $('#add-question').click(function() {
                questionCount++;

                const questionHtml = `
            <div class="question-group">
                <div class="form-group">
                    <label>Pertanyaan <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="question[]" placeholder="Masukan pertanyaan" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>Pilihan <span class="text-danger">*</span></label>
                    <input type="text" name="option[${questionCount}][]" placeholder="Masukan opsi jawaban" class="form-control">
                </div>
                <div class="form-group">
                    <label>Pilihan <span class="text-danger">*</span></label>
                    <input type="text" name="option[${questionCount}][]" placeholder="Masukan opsi jawaban" class="form-control">
                </div>
            </div>
        `;

                $('#questions-container').append(questionHtml);
            });
        });
    </script>
</body>

</html>