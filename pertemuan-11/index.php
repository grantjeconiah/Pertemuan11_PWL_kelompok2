<?php
// Catatan: nama file harus sama persis (huruf besar/kecil) dengan nama class.
require_once "mahasiswa.php";

// Fungsi bantu supaya output aman di HTML.
function e(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES, "UTF-8");
}

// Object Mahasiswa
$andi  = new Mahasiswa("422025001", "Andi Wijaya", "andi@mail.com");
$budi  = new Mahasiswa("412024015", "Budi Santoso", "budi@mail.com");
$citra = new Mahasiswa("222023020", "Citra Lestari", "citra@mail.com");
$dewi  = new Mahasiswa("992025003", "Dewi Anggraini", "dewi@mail.com"); 

$daftar = [$andi, $budi, $citra, $dewi];

// Setter
$hasilSetNama  = $andi->setNama("Andi Wijaya Putri");   // valid
$hasilNamaKosong = $budi->setNama("   ");               // tidak valid -> false, nama tetap
$hasilSetEmail = $citra->setEmail("bukan-email");       // tidak valid -> false, email tetap

// Error Handling
$pesanError = [];
$percobaan = [
    ["12345",     "Eko",  "eko@mail.com"],   // NIM bukan 9 digit
    ["422025002", "",     "eko@mail.com"],   // nama kosong
    ["422025002", "Eko",  "eko-at-mail"],    // email salah
];
foreach ($percobaan as [$nim, $nama, $email]) {
    try {
        new Mahasiswa($nim, $nama, $email);
    } catch (InvalidArgumentException $ex) {
        $pesanError[] = "new Mahasiswa(\"$nim\", \"$nama\", \"$email\") → " . $ex->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo Class Mahasiswa</title>

</head>
<body>

<h1>Demo Class Mahasiswa</h1>

<h2>1. Show Data</h2>
<ul>
    <?php foreach ($daftar as $m): ?>
        <li><?= e($m->tampilkanData()) ?></li> <!-- Tampilin data mahasiswa jadi bullet point -->
    <?php endforeach; ?>
</ul>

<h2>2. Object Detail</h2>
<table>
    <tr>
        <th>NIM</th><th>Nama</th><th>Email</th><th>Kode Prodi</th>
        <th>Program Studi</th><th>Angkatan</th><th>No. Urut</th><th>Email Civitas</th>
    </tr>
    <?php foreach ($daftar as $m): ?>
        <tr>
            <td><?= e($m->getNim()) ?></td>
            <td><?= e($m->getNama()) ?></td>
            <td><?= e($m->getEmail()) ?></td>
            <td><?= e($m->getKodeProdi()) ?></td>
            <td><?= e($m->getNamaProdi()) ?></td>
            <td><?= e($m->getAngkatan()) ?></td>
            <td><?= e($m->getNoUrut()) ?></td>
            <td><?= e($m->buatEmailCivitas()) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>3. Setter and Validation</h2>
<ul>
    <li>
        <code>$andi->setNama("Andi Wijaya Putri")</code> →
        <span><?= $hasilSetNama ? "true" : "false" ?></span>
        (Nama: <?= e($andi->getNama()) ?>)
    </li>
    <li>
        <code>$budi->setNama("   ")</code> →
        <span><?= $hasilNamaKosong ? "true" : "false" ?></span>
        (Nama: <?= e($budi->getNama()) ?>)
    </li>
    <li>
        <code>$citra->setEmail("bukan-email")</code> →
        <span><?= $hasilSetEmail ? "true" : "false" ?></span>
        (Email: <?= e($citra->getEmail()) ?>)
    </li>
</ul>

<h2>4. Invalid Data → InvalidArgumentException</h2>
<ul>
    <?php foreach ($pesanError as $pesan): ?>
        <li><?= e($pesan) ?></li>
    <?php endforeach; ?>
</ul>

<h2>5. Encapsulation Error Test</h2>
<?php
try {
    $andi->nama = ""; // Sengaja error
} catch (Error $err) {
    echo '<p>' . e($err->getMessage()) . '</p>';
}
?>

</body>
</html>