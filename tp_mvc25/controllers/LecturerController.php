<?php

require_once __DIR__ . '/../models/Lecturer.php';
require_once __DIR__ . '/../views/LecturerView.php';

// Mengatur proses Lecturer dan menghubungkan Model dengan View.
class LecturerController
{
    private $lecturer;

    public function __construct()
    {
        // Membuat objek Model Lecturer
        $this->lecturer = new Lecturer();
    }

    // Memvalidasi lalu meminta Model menyimpan Lecturer baru.
    function add($data)
    {
        $message = $this->validateData($data);
        if ($message !== null) {
            return $message;
        }

        $this->lecturer->add($data);
        return null;
    }

    // Memvalidasi data dan meminta Model memperbarui Lecturer yang dipilih.
    function update($id, $data)
    {
        $message = $this->validateData($data);
        if ($message !== null) {
            return $message;
        }

        if ($this->getById($id) === null) {
            return "Lecturer tidak ditemukan.";
        }

        $this->lecturer->update($id, $data);
        return null;
    }

    // Menghapus Lecturer; Model menolak penghapusan yang melanggar relasi Course.
    function delete($id)
    {
        if ($this->getById($id) === null) {
            return "Lecturer tidak ditemukan.";
        }

        if (!$this->lecturer->delete($id)) {
            return "Lecturer tidak dapat dihapus karena masih memiliki Course.";
        }

        return null;
    }

    // Mengambil satu Lecturer untuk form edit; null menandakan ID tidak ditemukan.
    function getById($id)
    {
        $data = $this->lecturer->getById($id);
        return $data === false ? null : $data;
    }

    // Mengambil daftar Lecturer dan menyerahkan data serta state form kepada View.
    function index($editData = null, $message = null, $formData = [])
    {
        // Mengambil seluruh data lecturer dari Model
        $data = $this->lecturer->getAll();
        $data = $data->fetchAll(PDO::FETCH_ASSOC);

        // Membuat objek View
        $view = new LecturerView();

        // Menampilkan data melalui View
        $view->render($data, $editData, $message, $formData);
    }

    private function validateData($data)
    {
        // Semua atribut wajib diisi sebelum data diteruskan ke Model.
        foreach (['name', 'nidn', 'phone', 'join_date'] as $field) {
            if (!isset($data[$field]) || !is_scalar($data[$field]) || trim((string) $data[$field]) === '') {
                return "Semua field wajib diisi.";
            }
        }

        // Nomor telepon disimpan sebagai teks, tetapi hanya boleh berisi digit.
        if (!ctype_digit((string) $data['phone'])) {
            return "Nomor telepon hanya boleh berisi angka.";
        }

        return null;
    }
}
