<?php

require_once __DIR__ . '/DB.php';

// Menyimpan query CRUD yang berhubungan dengan tabel lecturers.
class Lecturer extends DB
{
    // Mengambil semua Lecturer dan mengurutkannya berdasarkan ID.
    function getAll()
    {
        $query = "SELECT * FROM lecturers ORDER BY id ASC";
        return $this->execute($query);
    }

    // Mengambil satu Lecturer; ID dikirim sebagai parameter query.
    function getById($id)
    {
        $query = "SELECT * FROM lecturers WHERE id = ?";
        $this->execute($query, [$id]);

        return $this->getResult();
    }

    // Menambahkan Lecturer dengan nilai yang diterima dari Controller.
    function add($data)
    {
        $query = "INSERT INTO lecturers (name, nidn, phone, join_date)
                  VALUES (?, ?, ?, ?)";

        return $this->execute($query, [
            $data['name'],
            $data['nidn'],
            $data['phone'],
            $data['join_date']
        ]);
    }

    // Mengubah atribut Lecturer yang memiliki ID tertentu.
    function update($id, $data)
    {
        $query = "UPDATE lecturers
                  SET name = ?, nidn = ?, phone = ?, join_date = ?
                  WHERE id = ?";

        return $this->execute($query, [
            $data['name'],
            $data['nidn'],
            $data['phone'],
            $data['join_date'],
            $id
        ]);
    }

    // Menghapus Lecturer dan mengembalikan false jika relasi Course membatasi penghapusan.
    function delete($id)
    {
        $query = "DELETE FROM lecturers WHERE id = ?";

        try {
            $result = $this->execute($query, [$id]);
            return $result->rowCount() > 0;
        } catch (PDOException $e) {
            // SQLSTATE 23000 menandakan pelanggaran constraint, termasuk FK Course.
            if ($e->getCode() === '23000') {
                return false;
            }

            throw $e;
        }
    }
}
