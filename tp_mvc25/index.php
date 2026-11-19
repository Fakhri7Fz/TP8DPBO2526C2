<?php

// Entry point untuk fitur Lecturer: meneruskan request ke controller dan menampilkan halaman.
require_once __DIR__ . '/controllers/LecturerController.php';

$lecturer = new LecturerController();

// Menyimpan pesan validasi, data edit, dan isian form yang perlu ditampilkan kembali.
$message = null;
$editData = null;
$formData = [];

// Memproses penambahan data Lecturer dari form.
if (isset($_POST['add'])) {
  $data = [
    'name' => trim((string) ($_POST['name'] ?? '')),
    'nidn' => trim((string) ($_POST['nidn'] ?? '')),
    'phone' => trim((string) ($_POST['phone'] ?? '')),
    'join_date' => trim((string) ($_POST['join_date'] ?? ''))
  ];

  $message = $lecturer->add($data);
  if ($message === null) {
    header("location:index.php");
    exit;
  }
  $formData = $data;
// Memproses perubahan data Lecturer berdasarkan ID dari form edit.
} else if (isset($_POST['update'])) {
  $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
  $data = [
    'name' => trim((string) ($_POST['name'] ?? '')),
    'nidn' => trim((string) ($_POST['nidn'] ?? '')),
    'phone' => trim((string) ($_POST['phone'] ?? '')),
    'join_date' => trim((string) ($_POST['join_date'] ?? ''))
  ];

  if ($id === false || $id === null || $id < 1) {
    $message = "ID Lecturer tidak valid.";
  } else {
    $message = $lecturer->update($id, $data);
    if ($message === null) {
      header("location:index.php");
      exit;
    }
    $editData = $lecturer->getById($id);
  }
  $formData = $data;
// Memproses penghapusan data Lecturer berdasarkan ID pada URL.
} else if (isset($_GET['delete'])) {
  $id = filter_var($_GET['delete'], FILTER_VALIDATE_INT);

  if ($id === false || $id === null || $id < 1) {
    $message = "ID Lecturer tidak valid.";
  } else {
    $message = $lecturer->delete($id);
    if ($message === null) {
      header("location:index.php");
      exit;
    }
  }
// Mengambil data Lecturer yang dipilih agar form dapat menampilkan nilai lama.
} else if (isset($_GET['edit'])) {
  $id = filter_var($_GET['edit'], FILTER_VALIDATE_INT);

  if ($id === false || $id === null || $id < 1) {
    $message = "ID Lecturer tidak valid.";
  } else {
    $editData = $lecturer->getById($id);
    if ($editData === null) {
      $message = "Lecturer tidak ditemukan.";
    }
  }
}

// Menampilkan daftar Lecturer beserta state form atau pesan jika ada.
$lecturer->index($editData, $message, $formData);
