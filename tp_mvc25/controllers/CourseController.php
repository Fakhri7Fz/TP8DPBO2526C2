<?php

require_once __DIR__ . '/../models/Course.php';
require_once __DIR__ . '/../models/Lecturer.php';
require_once __DIR__ . '/../views/CourseView.php';

// Mengatur proses Course dan mengambil data Lecturer untuk kebutuhan form.
class CourseController
{
    private $course;
    private $lecturer;

    public function __construct()
    {
        // Membuat objek Model Course dan Lecturer
        $this->course = new Course();
        $this->lecturer = new Lecturer();
    }

    // Memvalidasi lalu meminta Model menyimpan Course baru.
    function add($data)
    {
        $message = $this->validateData($data);
        if ($message !== null) {
            return $message;
        }

        $this->course->add($data);
        return null;
    }

    // Memvalidasi data dan memperbarui Course yang dipilih.
    function update($id, $data)
    {
        $message = $this->validateData($data);
        if ($message !== null) {
            return $message;
        }

        if ($this->getById($id) === null) {
            return "Course tidak ditemukan.";
        }

        $this->course->update($id, $data);
        return null;
    }

    // Memastikan Course tersedia sebelum meminta Model menghapusnya.
    function delete($id)
    {
        if ($this->getById($id) === null) {
            return "Course tidak ditemukan.";
        }

        $this->course->delete($id);
        return null;
    }

    // Mengambil satu Course untuk form edit; null menandakan ID tidak ditemukan.
    function getById($id)
    {
        // Mengambil data dari Model
        $data = $this->course->getById($id);

        return $data === false ? null : $data;
    }

    // Mengambil Course dan Lecturer, lalu menyerahkan keduanya kepada View.
    function index($editData = null, $message = null, $formData = [])
    {
        // Mengambil seluruh data course dari Model
        $data = $this->course->getAll();
        $data = $data->fetchAll(PDO::FETCH_ASSOC);

        // Mengambil seluruh data lecturer dari Model
        $dataLecturer = $this->lecturer->getAll();
        $dataLecturer = $dataLecturer->fetchAll(PDO::FETCH_ASSOC);

        // Membuat objek View
        $view = new CourseView();

        // Menampilkan data melalui View
        $view->render($data, $dataLecturer, $editData, $message, $formData);
    }

    private function validateData($data)
    {
        // Memastikan semua field form terisi sebelum operasi database.
        foreach (['lecturer_id', 'course_name', 'course_code', 'credits'] as $field) {
            if (!isset($data[$field]) || !is_scalar($data[$field]) || trim((string) $data[$field]) === '') {
                return "Semua field wajib diisi.";
            }
        }

        // SKS harus berupa bilangan bulat dalam rentang yang disediakan form.
        if (!ctype_digit((string) $data['credits']) || (int) $data['credits'] < 1 || (int) $data['credits'] > 6) {
            return "SKS harus berupa angka antara 1 dan 6.";
        }

        // Pastikan Lecturer pengampu berbentuk ID positif dan benar-benar tersedia.
        if (!ctype_digit((string) $data['lecturer_id']) || (int) $data['lecturer_id'] < 1) {
            return "Lecturer pengampu tidak valid.";
        }

        if ($this->lecturer->getById((int) $data['lecturer_id']) === false) {
            return "Lecturer pengampu tidak ditemukan.";
        }

        return null;
    }
}
