<?php

require_once __DIR__ . '/Template.php';

// Menyusun tabel Course dan form yang membutuhkan daftar Lecturer pengampu.
class CourseView
{
    // Membuat halaman dari daftar Course, daftar Lecturer, state edit, dan pesan.
    function render($data, $dataLecturer, $editData = null, $message = null, $formData = [])
    {
        $no = 1;
        $dataCourse = "";

        // Membuat baris tabel dan menampilkan nama Lecturer yang diperoleh dari Model.
        foreach ($data as $val) {
            $id = (int) $val['id'];
            $courseName = htmlspecialchars($val['course_name'], ENT_QUOTES, 'UTF-8');
            $courseCode = htmlspecialchars($val['course_code'], ENT_QUOTES, 'UTF-8');
            $credits = htmlspecialchars((string) $val['credits'], ENT_QUOTES, 'UTF-8');
            $lecturerName = htmlspecialchars($val['lecturer_name'], ENT_QUOTES, 'UTF-8');

            $dataCourse .= "
                <tr>
                    <td>" . $no++ . "</td>
                    <td>" . $courseName . "</td>
                    <td>" . $courseCode . "</td>
                    <td>" . $credits . "</td>
                    <td>" . $lecturerName . "</td>
                    <td>
                        <a href='course.php?edit=" . $id . "' class='btn btn-warning'>Edit</a>
                        <a href='course.php?delete=" . $id . "' class='btn btn-danger' onclick=\"return confirm('Apakah Anda yakin ingin menghapus Course ini?');\">Hapus</a>
                    </td>
                </tr>";
        }

        // Mengisi nilai awal dari record edit atau memakai isian terakhir setelah validasi gagal.
        $courseNameValue = $editData['course_name'] ?? '';
        $courseCodeValue = $editData['course_code'] ?? '';
        $creditsValue = $editData['credits'] ?? '';
        $lecturerIdValue = $editData['lecturer_id'] ?? '';
        $courseId = $editData['id'] ?? '';

        // Menentukan apakah tombol form menjalankan operasi tambah atau update.
        $isEdit = $editData !== null;

        if (array_key_exists('course_name', $formData)) {
            $courseNameValue = $formData['course_name'];
        }
        if (array_key_exists('course_code', $formData)) {
            $courseCodeValue = $formData['course_code'];
        }
        if (array_key_exists('credits', $formData)) {
            $creditsValue = $formData['credits'];
        }
        if (array_key_exists('lecturer_id', $formData)) {
            $lecturerIdValue = $formData['lecturer_id'];
        }

        $courseNameValue = htmlspecialchars((string) $courseNameValue, ENT_QUOTES, 'UTF-8');
        $courseCodeValue = htmlspecialchars((string) $courseCodeValue, ENT_QUOTES, 'UTF-8');
        $creditsValue = htmlspecialchars((string) $creditsValue, ENT_QUOTES, 'UTF-8');
        $lecturerIdValue = htmlspecialchars((string) $lecturerIdValue, ENT_QUOTES, 'UTF-8');
        $courseId = htmlspecialchars((string) $courseId, ENT_QUOTES, 'UTF-8');

        $buttonName = $isEdit ? "update" : "add";
        $buttonLabel = $isEdit ? "Update Course" : "Tambah Course";
        $formTitle = $isEdit ? "Edit Course" : "Tambah Course";
        $cancelButton = $isEdit ? "<a href='course.php' class='btn btn-secondary ms-2'>Batal</a>" : "";
        $messageHtml = $message !== null
            ? "<div class='alert alert-warning'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</div>"
            : "";

        // Menyediakan opsi awal saat belum ada Lecturer pengampu yang dipilih.
        $dataLecturerOption = "<option value='' disabled " . ($lecturerIdValue == "" ? "selected" : "") . ">-- Pilih Lecturer --</option>";

        // Menambahkan Lecturer ke dropdown dan mempertahankan pilihan saat edit atau validasi gagal.
        foreach ($dataLecturer as $val) {
            $idLecturer = (int) $val['id'];
            $nameLecturer = htmlspecialchars($val['name'], ENT_QUOTES, 'UTF-8');

            $selected = ((string) $idLecturer === $lecturerIdValue) ? "selected" : "";

            $dataLecturerOption .= "
                <option value='" . $idLecturer . "' " . $selected . ">
                    " . $nameLecturer . "
                </option>";
        }

        // Memuat template Course dan mengganti token dengan data tampilan yang sudah disiapkan.
        $tpl = new Template(__DIR__ . '/../templates/course.html');

        // Mengganti placeholder pada template
        $tpl->replace("JUDUL", "Data Course");
        $tpl->replace("DATA_COURSE", $dataCourse);
        $tpl->replace("DATA_LECTURER_OPTION", $dataLecturerOption);

        $tpl->replace("COURSE_NAME_VALUE", $courseNameValue);
        $tpl->replace("COURSE_CODE_VALUE", $courseCodeValue);
        $tpl->replace("CREDITS_VALUE", $creditsValue);
        $tpl->replace("LECTURER_SELECTED", $lecturerIdValue);

        // Mengganti placeholder mode form
        $tpl->replace("FORM_TITLE", $formTitle);
        $tpl->replace("BUTTON_NAME", $buttonName);
        $tpl->replace("BUTTON_LABEL", $buttonLabel);
        $tpl->replace("COURSE_ID", $courseId);
        $tpl->replace("CANCEL_BUTTON", $cancelButton);
        $tpl->replace("MESSAGE", $messageHtml);

        // Menampilkan halaman
        $tpl->write();
    }
}
