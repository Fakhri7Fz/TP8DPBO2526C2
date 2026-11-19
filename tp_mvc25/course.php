<?php

// Entry point untuk fitur Course: meneruskan request ke controller dan menampilkan halaman.
require_once __DIR__ . '/controllers/CourseController.php';

$course = new CourseController();

// Menyimpan pesan validasi, data edit, dan isian form yang perlu ditampilkan kembali.
$message = null;
$editData = null;
$formData = [];

// Memproses penambahan Course dari form.
if (isset($_POST['add'])) {
    $data = [
        'lecturer_id' => trim((string) ($_POST['lecturer_id'] ?? '')),
        'course_name' => trim((string) ($_POST['course_name'] ?? '')),
        'course_code' => trim((string) ($_POST['course_code'] ?? '')),
        'credits' => trim((string) ($_POST['credits'] ?? ''))
    ];

    $message = $course->add($data);
    if ($message === null) {
        header("location:course.php");
        exit;
    }
    $formData = $data;
// Memproses perubahan Course berdasarkan ID yang dikirim dari form edit.
} else if (isset($_POST['update'])) {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $data = [
        'lecturer_id' => trim((string) ($_POST['lecturer_id'] ?? '')),
        'course_name' => trim((string) ($_POST['course_name'] ?? '')),
        'course_code' => trim((string) ($_POST['course_code'] ?? '')),
        'credits' => trim((string) ($_POST['credits'] ?? ''))
    ];

    if ($id === false || $id === null || $id < 1) {
        $message = "ID Course tidak valid.";
    } else {
        $message = $course->update($id, $data);
        if ($message === null) {
            header("location:course.php");
            exit;
        }
        $editData = $course->getById($id);
    }
    $formData = $data;
// Memproses penghapusan Course berdasarkan ID pada URL.
} else if (isset($_GET['delete'])) {
    $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);

    if ($id === false || $id === null || $id < 1) {
        $message = "ID Course tidak valid.";
    } else {
        $message = $course->delete($id);
        if ($message === null) {
            header("location:course.php");
            exit;
        }
    }
// Mengambil data Course yang dipilih untuk mengisi kembali form edit.
} else if (isset($_GET['edit'])) {
    $id = filter_var($_GET['edit'], FILTER_VALIDATE_INT);

    if ($id === false || $id === null || $id < 1) {
        $message = "ID Course tidak valid.";
    } else {
        $editData = $course->getById($id);
        if ($editData === null) {
            $message = "Course tidak ditemukan.";
        }
    }
}

// Menampilkan daftar Course, opsi Lecturer, dan state form jika diperlukan.
$course->index($editData, $message, $formData);
