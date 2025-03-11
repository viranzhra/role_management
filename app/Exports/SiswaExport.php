<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SiswaExport implements FromQuery, WithHeadings, WithStyles
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    protected $kelasId;

    public function __construct($kelasId = null)
    {
        $this->kelasId = $kelasId;
    }

    public function query()
    {
        $query = Siswa::with('user', 'kelas')
            ->select('siswa.nisn', 'users.name as nama', 'kelas.nama_kelas')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->join('kelas', 'siswa.kelas_id', '=', 'kelas.id')
            ->orderBy('users.name', 'ASC');

        if ($this->kelasId) {
            $query->where('kelas_id', $this->kelasId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama Siswa',
            'Kelas',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Styling untuk header
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'D3D3D3', // Warna abu-abu muda
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Border untuk seluruh tabel
        $sheet->getStyle('A1:C' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        return [
            // Set lebar kolom
            'A' => ['width' => 15],
            'B' => ['width' => 30],
            'C' => ['width' => 20],
        ];
    }
}
