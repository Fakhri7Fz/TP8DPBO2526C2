<?php

require_once __DIR__ . '/DB.php';

// Menyimpan query CRUD yang berhubungan dengan tabel courses.
class Course extends DB
{
    // Mengambil Course bersama nama Lecturer agar nama pengampu dapat ditampilkan.
    function getAll()
    {
        $query = "SELECT courses.*, lecturers.name AS lecturer_name
                  FROM courses
                  JOIN lecturers ON courses.lecturer_id = lecturers.id
                  ORDER BY courses.id ASC";

        return $this->execute($query);
    }

    // Mengambil satu Course berdasarkan ID untuk mengisi form edit.
    function getById($id)
    {
        $query = "SELECT * FROM courses WHERE id = ?";
        $this->execute($query, [$id]);

        return $this->getResult();
    }

    // Menambahkan Course beserta ID Lecturer pengampunya.
    function add($data)
    {
        $query = "INSERT INTO courses (lecturer_id, course_name, course_code, credits)
                  VALUES (?, ?, ?, ?)";

        return $this->execute($query, [
            $data['lecturer_id'],
            $data['course_name'],
            $data['course_code'],
            $data['credits']
        ]);
    }

    // Mengubah atribut Course dan Lecturer pengampu untuk ID yang dipilih.
    function update($id, $data)
    {
        $query = "UPDATE courses
                  SET lecturer_id = ?, course_name = ?, course_code = ?, credits = ?
                  WHERE id = ?";

        return $this->execute($query, [
            $data['lecturer_id'],
            $data['course_name'],
            $data['course_code'],
            $data['credits'],
            $id
        ]);
    }

    // Menghapus satu Course berdasarkan ID.
    function delete($id)
    {
        $query = "DELETE FROM courses WHERE id = ?";
        return $this->execute($query, [$id]);
    }
}
