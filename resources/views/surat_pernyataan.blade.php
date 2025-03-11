<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pernyataan</title>
</head>
<body>
    <h1>Surat Pernyataan</h1>
    <p>Nama: {{ $siswa->nama }}</p>
    <p>NIS: {{ $siswa->nis }}</p>
    <p>Kelas: {{ $siswa->kelas }}</p>
    <p>Total Poin: {{ $siswa->total_poin }}</p>
    <p>
        Dengan ini dinyatakan bahwa siswa atas nama tersebut telah melanggar aturan karena total poin
        kehadirannya melebihi batas yang telah ditentukan.
    </p>
</body>
</html>
