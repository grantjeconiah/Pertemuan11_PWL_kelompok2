<?php
/**
 * Mahasiswa.php - CLASS MAHASISWA (Challenge GitHub + OOP, Pertemuan 11)
 * ---------------------------------------------------------------------
 * Satu class = satu file, dan nama file sama dengan nama class.
 * File ini hanya berisi CETAKAN (class). Object dibuat di index.php:
 *
 *     require_once "Mahasiswa.php";
 *     $andi = new Mahasiswa("422025001", "Andi Wijaya", "andi@mail.com");
 *     echo $andi->tampilkanData();
 *
 * ATURAN NIM (9 digit):  42      2025      001
 *                        |        |         |
 *                   kode prodi  angkatan  nomor urut
 *
 * YANG ADA DI CLASS INI:
 *   - 3 property private : nim, nama, email
 *   - constructor        : mengisi property saat "new"
 *   - getter / setter    : getNim, getNama, getEmail, setNama, setEmail
 *   - method             : tampilkanData, getKodeProdi, getNamaProdi,
 *                          getAngkatan, getNoUrut, buatEmailCivitas
 *
 * ENCAPSULATION: property dibuat private, jadi kode di luar class TIDAK bisa
 * mengisinya sembarangan ($andi->nama = "" akan error). Perubahan hanya
 * lewat setter, yang memvalidasi data dulu dan mengembalikan true/false.
 */
class Mahasiswa
{
    // ---------- Konstanta (nilai tetap milik class, dipakai lewat self::) ----------

    // Kode 2 digit di awal NIM -> nama program studi.
    private const DAFTAR_PRODI = [
        "21" => "Teknik Elektro",
        "22" => "Teknik Industri",
        "24" => "Teknik Sipil",
        "41" => "Informatika",
        "42" => "Sistem Informasi",
    ];

    private const DOMAIN_EMAIL = "civitas.ukrida.ac.id";

    // ---------- Property (data milik object) - semuanya private ----------
    private string $nim   = "";
    private string $nama  = "";
    private string $email = "";

    // ---------- Constructor ----------

    /**
     * Dijalankan OTOMATIS saat "new Mahasiswa(...)".
     *
     * Nilai diisi lewat setter (bukan langsung), supaya validasinya ikut jalan.
     * Jika ada data yang tidak valid, object tidak jadi dibuat dan
     * InvalidArgumentException dilempar. Dengan begitu object yang ada
     * selalu berisi data yang valid.
     */
    public function __construct(string $nim, string $nama, string $email)
    {
        if (!$this->setNim($nim)) {
            throw new InvalidArgumentException("NIM harus 9 digit angka, contoh: 422025001.");
        }
        if (!$this->setNama($nama)) {
            throw new InvalidArgumentException("Nama wajib diisi (maksimal 100 karakter).");
        }
        if (!$this->setEmail($email)) {
            throw new InvalidArgumentException("Format email tidak valid.");
        }
    }

    // ---------- Getter (hanya membaca) ----------

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    // ---------- Setter (menulis, dengan validasi) ----------

    /**
     * NIM bersifat private: dipakai constructor saja. Tidak ada setter publik
     * karena NIM adalah identitas dan tidak boleh diubah setelah object dibuat.
     */
    private function setNim(string $nim): bool
    {
        // Harus tepat 9 digit angka.
        if (!preg_match('/^\d{9}$/', $nim)) {
            return false;
        }

        $this->nim = $nim;
        return true;
    }

    /** Mengubah nama. Mengembalikan false jika kosong atau lebih dari 100 karakter. */
    public function setNama(string $nama): bool
    {
        $nama = trim($nama);

        if ($nama === "" || mb_strlen($nama) > 100) {
            return false;
        }

        $this->nama = $nama;
        return true;
    }

    /** Mengubah email. Mengembalikan false jika formatnya tidak valid. */
    public function setEmail(string $email): bool
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $this->email = $email;
        return true;
    }

    // ---------- Method (perilaku object) ----------

    /** Satu baris ringkasan data, contoh: "422025001 - Andi Wijaya (Sistem Informasi, angkatan 2025)". */
    public function tampilkanData(): string
    {
        return $this->nim . " - " . $this->nama
            . " (" . $this->getNamaProdi() . ", angkatan " . $this->getAngkatan() . ")";
    }

    /** 2 digit pertama NIM. Contoh: "42". */
    public function getKodeProdi(): string
    {
        return substr($this->nim, 0, 2);
    }

    /** Nama program studi dari kode di NIM. Contoh: "Sistem Informasi". */
    public function getNamaProdi(): string
    {
        // "??" = pakai teks cadangan jika kode tidak ada di daftar.
        return self::DAFTAR_PRODI[$this->getKodeProdi()] ?? "Program studi tidak dikenal";
    }

    /** Tahun angkatan (4 digit setelah kode prodi). Contoh: 2025. */
    public function getAngkatan(): int
    {
        return (int) substr($this->nim, 2, 4);
    }

    /** Nomor urut (3 digit terakhir NIM). 422025001 -> 1, 422025020 -> 20. */
    public function getNoUrut(): int
    {
        return (int) substr($this->nim, 6, 3);
    }

    /**
     * Menyusun email civitas dari nama depan + NIM.
     * Format: namadepan.nim@civitas.ukrida.ac.id
     * Contoh: "Andi Wijaya" + 422025001 -> andi.422025001@civitas.ukrida.ac.id
     */
    public function buatEmailCivitas(): string
    {
        // Nama depan = kata pertama, huruf kecil, hanya a-z dan 0-9.
        $kata  = preg_split('/\s+/', $this->nama, -1, PREG_SPLIT_NO_EMPTY);
        $depan = preg_replace('/[^a-z0-9]/', '', mb_strtolower($kata[0] ?? "", "UTF-8"));

        return $depan . "." . $this->nim . "@" . self::DOMAIN_EMAIL;
    }
}