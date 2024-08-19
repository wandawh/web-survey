<?php

include("../config/koneksi.php");

$id = $_GET['id'];

// Query untuk mendapatkan detail survey
$querySurvey = "SELECT * FROM survey WHERE id = $id LIMIT 1";
$resultSurvey = mysqli_query($connection, $querySurvey);
$survey = mysqli_fetch_array($resultSurvey);

// Query untuk mendapatkan pertanyaan yang terkait dengan survey
$queryQuestions = "SELECT * FROM questions WHERE survey_id = $id";
$resultQuestions = mysqli_query($connection, $queryQuestions);

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <title>Detail Survey</title>
</head>

<body>

    <div class="container" style="margin-top: 20px">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2>Simple SurveyApp</h2>
                <div class="card">
                    <div class="card-header">
                        Detail Survey
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" value="<?= htmlspecialchars($survey['title']) ?>" readonly class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" readonly rows="2"><?= htmlspecialchars($survey['description']) ?></textarea>
                        </div>
                        <?php $index = 1 ?>
                        <?php while ($question = mysqli_fetch_array($resultQuestions)) : ?>

                            <div class="form-group">
                                <label>Pertanyaan <?= $index++ ?> :</label>
                                <textarea class="form-control" readonly rows="2"><?= htmlspecialchars($question['question']) ?></textarea>
                            </div>

                            <?php
                            // Query untuk mendapatkan opsi terkait dengan pertanyaan ini
                            $questionId = $question['id'];
                            $queryOptions = "SELECT * FROM options WHERE question_id = $questionId";
                            $resultOptions = mysqli_query($connection, $queryOptions);
                            ?>

                            <label>Pilihan</label>
                            <ul class="list-group">
                                <?php while ($option = mysqli_fetch_array($resultOptions)) : ?>
                                    <li class="list-group-item"><?= htmlspecialchars($option['option_text']) ?></li>
                                <?php endwhile; ?>
                            </ul>
                        <?php endwhile; ?>

                        <a href="/app-survey/index.php" class="btn btn-success mt-3">Kembali</a>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>