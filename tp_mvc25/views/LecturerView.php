<?php

require_once __DIR__ . '/Template.php';

// Menyusun tabel dan form Lecturer sebelum mengirimkannya melalui Template.
class LecturerView
{
    // Membuat halaman dari daftar data, state edit, pesan, dan isian form.
    function render($data, $editData = null, $message = null, $formData = [])
    {
        $no = 1;
        $dataLecturer = "";

        // Membuat satu baris tabel untuk setiap Lecturer dan menampilkan semua atributnya.
        foreach ($data as $val) {
            $id = (int) $val['id'];
            $name = htmlspecialchars($val['name'], ENT_QUOTES, 'UTF-8');
            $nidn = htmlspecialchars($val['nidn'], ENT_QUOTES, 'UTF-8');
            $phone = htmlspecialchars($val['phone'], ENT_QUOTES, 'UTF-8');
            $joinDate = htmlspecialchars($val['join_date'], ENT_QUOTES, 'UTF-8');

            $dataLecturer .= "
                <tr>
                    <td>" . $no++ . "</td>
                    <td>" . $id . "</td>
                    <td>" . $name . "</td>
                    <td>" . $nidn . "</td>
                    <td>" . $phone . "</td>
                    <td>" . $joinDate . "</td>
                    <td>
                        <a href='index.php?edit=" . $id . "' class='btn btn-warning'>Edit</a>
                        <a href='index.php?delete=" . $id . "' class='btn btn-danger' onclick=\"return confirm('Apakah Anda yakin ingin menghapus Lecturer ini?');\">Hapus</a>
                    </td>
                </tr>";
        }

        // Nilai dari record edit menjadi nilai awal; isian form yang gagal validasi menggantikannya.
        $isEdit = $editData !== null;
        $nameValue = $editData['name'] ?? '';
        $nidnValue = $editData['nidn'] ?? '';
        $phoneValue = $editData['phone'] ?? '';
        $joinDateValue = $editData['join_date'] ?? '';
        $lecturerId = $editData['id'] ?? '';

        if (array_key_exists('name', $formData)) {
            $nameValue = $formData['name'];
        }
        if (array_key_exists('nidn', $formData)) {
            $nidnValue = $formData['nidn'];
        }
        if (array_key_exists('phone', $formData)) {
            $phoneValue = $formData['phone'];
        }
        if (array_key_exists('join_date', $formData)) {
            $joinDateValue = $formData['join_date'];
        }

        // Meng-escape nilai sebelum disisipkan ke atribut HTML.
        $nameValue = htmlspecialchars((string) $nameValue, ENT_QUOTES, 'UTF-8');
        $nidnValue = htmlspecialchars((string) $nidnValue, ENT_QUOTES, 'UTF-8');
        $phoneValue = htmlspecialchars((string) $phoneValue, ENT_QUOTES, 'UTF-8');
        $joinDateValue = htmlspecialchars((string) $joinDateValue, ENT_QUOTES, 'UTF-8');
        $lecturerId = htmlspecialchars((string) $lecturerId, ENT_QUOTES, 'UTF-8');

        // Menentukan label dan tombol berdasarkan apakah form sedang mengedit record.
        $formTitle = $isEdit ? "Edit Lecturer" : "Tambah Lecturer";
        $buttonName = $isEdit ? "update" : "add";
        $buttonLabel = $isEdit ? "Update Lecturer" : "Tambah Lecturer";
        $cancelButton = $isEdit ? "<a href='index.php' class='btn btn-secondary ms-2'>Batal</a>" : "";
        $messageHtml = $message !== null
            ? "<div class='alert alert-warning'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</div>"
            : "";

        // Memuat template Lecturer dan mengganti seluruh token yang digunakan halaman.
        $tpl = new Template(__DIR__ . '/../templates/lecturer.html');

        // Mengganti placeholder pada template
        $tpl->replace("JUDUL", "Data Lecturer");
        $tpl->replace("DATA_LECTURER", $dataLecturer);
        $tpl->replace("FORM_TITLE", $formTitle);
        $tpl->replace("BUTTON_NAME", $buttonName);
        $tpl->replace("BUTTON_LABEL", $buttonLabel);
        $tpl->replace("CANCEL_BUTTON", $cancelButton);
        $tpl->replace("LECTURER_ID", $lecturerId);
        $tpl->replace("NAME_VALUE", $nameValue);
        $tpl->replace("NIDN_VALUE", $nidnValue);
        $tpl->replace("PHONE_VALUE", $phoneValue);
        $tpl->replace("JOIN_DATE_VALUE", $joinDateValue);
        $tpl->replace("MESSAGE", $messageHtml);

        // Menampilkan halaman
        $tpl->write();
    }
}
