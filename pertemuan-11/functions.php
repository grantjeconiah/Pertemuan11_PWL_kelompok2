<?php
class MahasiswaHelper
{
    // Menghitung jumlah mahasiswa dalam array
    public function hitungTotal(array $daftarMahasiswa): int
    {
        return count($daftarMahasiswa);
    }

    // Mengecek apakah email menggunakan domain kampus (@kampus.ac.id)
    public function cekStatusEmail(string $emailCivitas): string
    {
        if (str_ends_with($emailCivitas, "@kampus.ac.id")) {
            return "Email Kampus Resmi";
        }
        return "Email Umum";
    }
}