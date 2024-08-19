<?php
session_start();
include("../config/koneksi.php");

$survey_id = $_POST['survey_id'];
$answers = $_POST['answers'];

// Validasi: periksa apakah semua pertanyaan dijawab
$queryQuestions = "SELECT * FROM questions WHERE survey_id = '$survey_id'";
$resultQuestions = mysqli_query($connection, $queryQuestions);

$unansweredQuestions = [];

while ($question = mysqli_fetch_assoc($resultQuestions)) {
    $question_id = $question['id'];
    if (empty($answers[$question_id])) {
        $unansweredQuestions[] = $question['question'];  // Kumpulkan pertanyaan yang belum dijawab
    }
}

if (!empty($unansweredQuestions)) {
    $_SESSION['error'] = "Anda belum menjawab pertanyaan: " . implode(", ", $unansweredQuestions);
    header("Location: answer-survey.php?id=$survey_id");
    exit();
}

// Lanjutkan proses penyimpanan jawaban jika semua pertanyaan dijawab
$user_id = rand(1, 10);

$queryResponses = "INSERT INTO responses (survey_id, user_id, created_at, updated_at) 
                   VALUES ('$survey_id', '$user_id', NOW(), NOW())";

if (!$connection->query($queryResponses)) {
    $_SESSION['error'] = "Terjadi kesalahan saat menyimpan jawaban: " . $connection->error;
    header("Location: answer-survey.php?id=$survey_id");
    exit();
}

$response_id = $connection->insert_id;

foreach ($answers as $question_id => $answer_id) {
    $queryAnswers = "INSERT INTO answers (response_id, question_id, answer, created_at, updated_at) 
                     VALUES ('$response_id', '$question_id', '$answer_id', NOW(), NOW())";

    if (!$connection->query($queryAnswers)) {
        $_SESSION['error'] = "Terjadi kesalahan saat menyimpan jawaban: " . $connection->error;
        header("Location: answer-survey.php?id=$survey_id");
        exit();
    }
}

// Jika berhasil
$_SESSION['message'] = "Jawaban Anda telah berhasil disimpan!";
header("Location: /app-survey/index.php");
exit();
