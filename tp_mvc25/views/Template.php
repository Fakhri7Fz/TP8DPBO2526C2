<?php

// Membaca file HTML, mengganti token, lalu mengirim hasilnya sebagai halaman.
class Template
{
    var $filename = '';
    var $content = '';

    // Menyimpan lokasi template dan memuat seluruh isinya ke memori.
    function __construct($filename = '')
    {
        $this->filename = $filename;
        $this->content = implode('', file($filename));
    }

    // Menghapus token DATA_ yang tidak diisi agar tidak tampil sebagai teks mentah.
    function clear()
    {
        $this->content = preg_replace("/DATA_[A-Z|_|0-9]+/", "", $this->content);
    }

    // Membersihkan token DATA_ tersisa dan mencetak HTML yang sudah diproses.
    function write()
    {
        $this->clear();
        print $this->content;
    }

    // Membersihkan token DATA_ tersisa dan mengembalikan HTML sebagai string.
    function getContent()
    {
        $this->clear();
        return $this->content;
    }

    // Mengganti token template dengan nilai teks, angka, array, atau HTML dari View.
    function replace($old = '', $new = '')
    {
        if (is_int($new)) {
            $value = sprintf("%d", $new);
        } elseif (is_float($new)) {
            $value = sprintf("%f", $new);
        } elseif (is_array($new)) {
            $value = '';

            foreach ($new as $item) {
                $value .= $item . ' ';
            }
        } else {
            $value = $new;
        }

        $this->content = preg_replace("/$old/", $value, $this->content);
    }
}
