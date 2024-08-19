<?php
session_start();

// Include koneksi database
include("../config/koneksi.php");

// Get data dari form
$title       = $_POST['title'];
$description = $_POST['description'];
$questions   = $_POST['question'];
$options     = $_POST['option'];

// Validasi agar title dan description tidak kosong
if (empty($title) || empty($description)) {
    $_SESSION['error'] = "Judul dan Deskripsi tidak boleh kosong!";
    $_SESSION['form_data'] = $_POST; // Simpan data form di sesi
    header("Location: create.php");
    exit();
}

// Validasi agar setiap pertanyaan dan opsi tidak kosong
foreach ($questions as $index => $question) {
    if (empty($question)) {
        $_SESSION['error'] = "Pertanyaan tidak boleh kosong pada nomor " . ($index + 1);
        $_SESSION['form_data'] = $_POST; // Simpan data form di sesi
        header("Location: create.php");
        exit();
    }

    // Validasi agar setiap pertanyaan memiliki minimal dua opsi jawaban
    if (!isset($options[$index]) || count($options[$index]) < 2) {
        $_SESSION['error'] = "Setiap pertanyaan harus memiliki minimal dua opsi jawaban pada nomor " . ($index + 1);
        $_SESSION['form_data'] = $_POST; // Simpan data form di sesi
        header("Location: create.php");
        exit();
    }

    // Validasi agar tidak ada opsi jawaban yang kosong
    foreach ($options[$index] as $optionIndex => $option) {
        if (empty($option)) {
            $_SESSION['error'] = "Opsi jawaban pada pertanyaan nomor " . ($index + 1) . " tidak boleh kosong.";
            $_SESSION['form_data'] = $_POST; // Simpan data form di sesi
            header("Location: create.php");
            exit();
        }
    }
}

// Query insert data ke dalam tabel survey
$query = "INSERT INTO survey (title, description, created_at, updated_at) VALUES ('$title', '$description', NOW(), NOW())";

if ($connection->query($query)) {
    $survey_id = $connection->insert_id; // Mendapatkan ID survey yang baru ditambahkan

    // Mulai insert ke tabel questions dan options
    foreach ($questions as $key => $question) {
        // Insert pertanyaan ke tabel questions
        $question_query = "INSERT INTO questions (survey_id, question, created_at, updated_at) VALUES ('$survey_id', '$question', NOW(), NOW())";

        if ($connection->query($question_query)) {
            $question_id = $connection->insert_id; // Mendapatkan ID question yang baru ditambahkan

            // Insert opsi ke tabel options
            foreach ($options[$key] as $option) {
                $option_query = "INSERT INTO options (question_id, option_text, created_at, updated_at) VALUES ('$question_id', '$option', NOW(),NOW())";

                if (!$connection->query($option_query)) {
                    $_SESSION['error'] = "Terjadi kesalahan: " . $connection->error;
                    $_SESSION['form_data'] = $_POST; // Simpan data form di sesi
                    header("Location: create.php");
                    exit();
                }
            }
        } else {
            $_SESSION['error'] = "Terjadi kesalahan: " . $connection->error;
            $_SESSION['form_data'] = $_POST; // Simpan data form di sesi
            header("Location: create.php");
            exit();
        }
    }

    // Bersihkan data sesi setelah sukses menyimpan
    $_SESSION['message'] = "Survey berhasil dibuat";
    unset($_SESSION['form_data']);
    unset($_SESSION['error']);
    header("location: /app-survey/index.php");
    exit();
} else {
    $_SESSION['error'] = "Data Gagal Disimpan!";
    $_SESSION['form_data'] = $_POST; // Simpan data form di sesi
    header("Location: create.php");
    exit();
}
