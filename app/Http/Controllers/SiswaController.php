<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\User;
use App\Models\Kehadiran;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
// use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{

    public function export(Request $request)
    {
        if (!auth()->user()->can('export siswa')) {
            abort(403, 'Tidak diizinkan');
        }

        // Ambil parameter filter kelas
        $kelasId = $request->query('kelas_id');

        // Tentukan nama file berdasarkan filter
        $fileName = $kelasId
            ? 'data_siswa_kelas_' . \App\Models\Kelas::find($kelasId)->nama_kelas . '.xlsx'
            : 'data_siswa_all.xlsx';

        // Filter data siswa berdasarkan kelas jika kelas_id diberikan
        $export = $kelasId
            ? new SiswaExport($kelasId)
            : new SiswaExport();

        return Excel::download($export, $fileName);
    }

    public function import(Request $request)
    {
        // Cek apakah pengguna memiliki izin untuk mengimpor data siswa
        if (!auth()->user()->can('import siswa')) {
            abort(403, 'Tidak diizinkan');
        }

        try {
            $data = $request->input('data'); // Mengambil data yang dikirimkan

            // Proses data (misalnya simpan ke database)
            foreach ($data as $item) {
                // Validasi dan simpan data ke database
                Siswa::create([
                    'nisn' => $item['nisn'],
                    'name' => $item['name'],
                    'email' => $item['email'],
                    'kelas' => $item['kelas'],
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Data berhasil diimpor!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengimpor data!'], 500);
        }
    }

    public function previewImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');
        $data = Excel::toArray(new SiswaImport, $file);
        $data = $data[0]; // Ambil sheet pertama

        return view('siswa.index', compact('data', 'file'));
    }

    public function index()
    {
        if (!auth()->user()->can('view siswa')) {
            abort(403, 'Tidak diizinkan');
        }

        $kelas = Kelas::where('nama_kelas', '!=', 'Lulus')->get(); // Hanya kelas yang belum lulus
        $users = User::all();
        return view('siswa.index', compact('kelas', 'users'));
    }

    // public function getData()
    // {
    //     if (!auth()->user()->can('view siswa')) {
    //         abort(403, 'Tidak diizinkan');
    //     }

    //     // Ambil pengguna yang sedang login
    //     $user = auth()->user();

    //     // Jika pengguna adalah admin, ambil semua data siswa
    //     if ($user->hasRole('Admin')) {
    //         $siswa = Siswa::with(['user', 'kelas'])->select('id', 'user_id', 'nisn', 'kelas_id');
    //     } else {
    //         // Jika bukan admin, tampilkan data hanya milik pengguna yang login
    //         $siswa = Siswa::with(['user', 'kelas'])
    //             ->where('user_id', $user->id)
    //             ->select('id', 'user_id', 'nisn', 'kelas_id');
    //     }

    //     return DataTables::of($siswa)
    //         ->addColumn('name', function ($row) {
    //             return $row->user ? $row->user->name : 'Tidak ada nama';
    //         })
    //         ->addColumn('kelas', function ($row) {
    //             return $row->kelas ? $row->kelas->nama_kelas : 'Tidak ada kelas';
    //         })
    //         ->addColumn('email', function ($siswa) {
    //             return $siswa->user->email; // Ambil email dari relasi user
    //         })
    //         ->addColumn('action', function ($row) {
    //             $editButton = '';
    //             $deleteButton = '';

    //             // Cek hak akses untuk tombol edit
    //             if (auth()->user()->can('edit siswa')) {
    //                 $editButton = '<button class="btn btn-sm btn-primary btn-edit" 
    //                         data-id="' . $row->id . '" 
    //                         data-user_name="' . ($row->user ? $row->user->name : '') . '" 
    //                         data-nisn="' . $row->nisn . '" 
    //                         data-kelas_id="' . $row->kelas_id . '">
    //                     Edit
    //                 </button>';
    //             }

    //             // Cek hak akses untuk tombol hapus
    //             if (auth()->user()->can('delete siswa')) {
    //                 $deleteButton = '<button class="btn btn-sm btn-danger btn-delete" 
    //                                 data-id="' . $row->id . '">Hapus</button>';
    //             }

    //             // Gabungkan tombol Edit dan Hapus menggunakan flexbox
    //             return '<div class="d-flex justify-content-between">
    //             ' . $editButton . ' ' . $deleteButton . '
    //             </div>';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }

    public function getData()
    {
        if (!auth()->user()->can('view siswa')) {
            abort(403, 'Tidak diizinkan');
        }

        $user = auth()->user();

        // Ambil data siswa sesuai peran pengguna
        if ($user->hasRole('Admin')) {
            $siswa = Siswa::with(['user', 'kelas.jurusan'])->select('id', 'user_id', 'nisn', 'kelas_id');
        } else {
            $siswa = Siswa::with(['user', 'kelas.jurusan'])
                ->where('user_id', $user->id)
                ->select('id', 'user_id', 'nisn', 'kelas_id');
        }

        return DataTables::of($siswa)
            ->addColumn('name', function ($row) {
                return $row->user ? $row->user->name : 'Tidak ada nama';
            })
            ->addColumn('kelas', function ($row) {
                return $row->kelas ? $row->kelas->nama_kelas : 'Tidak ada kelas';
            })
            ->addColumn('jurusan', function ($row) {
                return $row->kelas && $row->kelas->jurusan ? $row->kelas->jurusan->nama_jurusan : 'Tidak ada jurusan';
            })
            ->addColumn('email', function ($siswa) {
                return $siswa->user->email;
            })
            ->addColumn('action', function ($row) {
                $editButton = '';
                $deleteButton = '';

                if (auth()->user()->can('edit siswa')) {
                    $editButton = '<button class="btn btn-sm btn-primary btn-edit" 
                        data-id="' . $row->id . '" 
                        data-user_name="' . ($row->user ? $row->user->name : '') . '" 
                        data-nisn="' . $row->nisn . '" 
                        data-kelas_id="' . $row->kelas_id . '">
                    Edit
                </button>';
                }

                if (auth()->user()->can('delete siswa')) {
                    $deleteButton = '<button class="btn btn-sm btn-danger btn-delete" 
                                data-id="' . $row->id . '">Hapus</button>';
                }

                return '<div class="d-flex justify-content-between">' . $editButton . ' ' . $deleteButton . '</div>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getDetail()
    {
        // Ambil pengguna yang sedang login
        $user = auth()->user();

        // Ambil data siswa yang terhubung dengan pengguna yang sedang login
        $siswa = Siswa::with(['user', 'kelas'])
            ->where('user_id', $user->id) // Pastikan hanya siswa yang login
            ->first(); // Ambil hanya satu data siswa

        // Jika tidak ada data siswa, kembalikan pesan 'Data siswa tidak ada'
        if (!$siswa) {
            return response()->json(['message' => 'Data siswa tidak ada'], 404);
        }

        // Ambil nama pengguna yang sedang login
        $namaSiswa = $siswa->user ? $siswa->user->name : 'Nama tidak ditemukan'; // pastikan mengambil nama sesuai user yang login

        // Ambil kelas siswa
        $kelasSiswa = $siswa->kelas ? $siswa->kelas->nama_kelas : 'Kelas tidak ditemukan'; // Ambil nama kelas jika ada

        // Hitung total poin dari absensi siswa
        $totalPoin = Kehadiran::where('siswa_id', $siswa->id)
            ->sum('poin'); // Menjumlahkan semua poin

        // Jika totalPoin masih 0 atau tidak ada data, pastikan response mengirimkan data poin
        if ($totalPoin === null) {
            $totalPoin = 0; // Set total poin ke 0 jika tidak ada poin yang ditemukan
        }

        // Kembalikan data siswa beserta total poinnya
        return response()->json([
            'success' => true,
            'data' => [
                'nama' => $namaSiswa, // Mengirimkan nama siswa yang sedang login
                'nisn' => $siswa->nisn,
                'kelas' => $kelasSiswa, // Mengirimkan nama kelas siswa
                'total_poin' => $totalPoin // Total poin yang dihitung dari Kehadiran
            ]
        ]);
    }

    // public function index()
    // {
    //     if (!auth()->user()->can('view siswa')) {
    //         abort(403, 'Tidak diizinkan');
    //     }

    //     $kelas = Kelas::all();
    //     $users = User::all(); 
    //     return view('siswa.index', compact('kelas', 'users'));
    // }

    // public function getData()
    // {
    //     $siswa = Siswa::with(['user', 'kelas'])->select('id', 'user_id', 'nisn', 'kelas_id');

    //     return DataTables::of($siswa)
    //         ->addColumn('name', function ($row) {
    //             return $row->user ? $row->user->name : 'Tidak ada nama';
    //         })
    //         ->addColumn('kelas', function ($row) {
    //             return $row->kelas ? $row->kelas->nama_kelas : 'Tidak ada kelas';
    //         })
    //         ->addColumn('email', function ($siswa) {
    //             return $siswa->user->email; // Ambil email dari relasi user
    //         })
    //         ->addColumn('action', function ($row) {
    //             $editButton = '';
    //             $deleteButton = '';

    //             // Cek hak akses untuk tombol edit
    //             if (auth()->user()->can('edit siswa')) {
    //                 $editButton = '<button class="btn btn-sm btn-primary btn-edit" 
    //                         data-id="' . $row->id . '" 
    //                         data-user_name="' . ($row->user ? $row->user->name : '') . '" 
    //                         data-nisn="' . $row->nisn . '" 
    //                         data-kelas_id="' . $row->kelas_id . '">
    //                     Edit
    //                 </button>';
    //             }

    //             // Cek hak akses untuk tombol hapus
    //             if (auth()->user()->can('delete siswa')) {
    //                 $deleteButton = '<button class="btn btn-sm btn-danger btn-delete" 
    //                                 data-id="' . $row->id . '">Hapus</button>';
    //             }

    //             // Gabungkan tombol Edit dan Hapus menggunakan flexbox
    //             return '<div class="d-flex justify-content-between">
    //             ' . $editButton . ' ' . $deleteButton . '
    //             </div>';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true);
    // }


    public function create()
    {
        if (!auth()->user()->can('create siswa')) {
            abort(403, 'Tidak diizinkan');
        }

        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        // Cek izin
        if (!auth()->user()->can('create siswa')) {
            abort(403, 'Tidak diizinkan');
        }

        // Validasi data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nisn' => 'required|unique:siswa,nisn',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        // Buat akun user baru dengan password default "siswa123"
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt('siswa123'), // Enkripsi password default
        ]);

        // Berikan role 'Siswa' ke user
        $siswaRole = Role::firstOrCreate(['name' => 'Siswa']);
        $user->assignRole($siswaRole);

        // Berikan izin default ke role 'Siswa'
        $siswaRole->givePermissionTo(['view siswa', 'delete siswa', 'view kelas']);

        // Buat data siswa
        Siswa::create([
            'user_id' => $user->id,
            'nisn' => $validated['nisn'],
            'kelas_id' => $validated['kelas_id'],
        ]);

        // Redirect dengan pesan sukses
        return redirect()->route('siswa.index')->with('success', 'Data siswa dan akun berhasil ditambahkan. Password default adalah "siswa123".');
    }

    // public function store(Request $request)
    // {
    //     if (!auth()->user()->can('create siswa')) {
    //         abort(403, 'Tidak diizinkan');
    //     }

    //     $validated = $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'nisn' => 'required|unique:siswa,nisn',
    //         'kelas_id' => 'required|exists:kelas,id',
    //     ]);

    //     Siswa::create($validated);

    //     return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    // }

    public function edit(Siswa $siswa)
    {
        if (!auth()->user()->can('edit siswa')) {
            abort(403, 'Tidak diizinkan');
        }

        $siswa = Siswa::with(['user', 'kelas'])->select('siswa.*');

        $kelas = Kelas::all();
        return view('siswa.index', compact('siswa', 'kelas'));
    }

    // public function update(Request $request, $id)
    // {
    //     if (!auth()->user()->can('edit siswa')) {
    //         abort(403, 'Tidak diizinkan');
    //     }

    //     $request->validate([
    //         // 'user_id' => 'required|exists:users,id',
    //         'nisn' => 'required|string|max:10',
    //         'kelas_id' => 'required|exists:kelas,id',
    //     ]);

    //     $siswa = Siswa::findOrFail($id);
    //     $siswa->nisn = $request->nisn;
    //     $siswa->kelas_id = $request->kelas_id;
    //     $siswa->save();

    //     return response()->json(['success' => true, 'message' => 'Data siswa berhasil diperbarui.']);
    //     // return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    // } 



    public function update(Request $request, $id)
    {
        // Lakukan validasi
        $validatedData = $request->validate([
            'nisn' => 'required|string|max:15',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa = Siswa::find($id);

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan.'], 404);
        }

        // Update data siswa
        $siswa->nisn = $validatedData['nisn'];
        $siswa->kelas_id = $validatedData['kelas_id'];

        if ($siswa->save()) {
            return response()->json(['success' => true, 'message' => 'Data siswa berhasil diperbarui.']);
        }

        return response()->json(['success' => false, 'message' => 'Gagal menyimpan data siswa.'], 500);
    }


    // public function destroy(Siswa $siswa)
    // {
    //     if (!auth()->user()->can('delete siswa')) {
    //         abort(403, 'Tidak diizinkan');
    //     }

    //     \Log::info('Mencoba menghapus siswa dengan ID: ' . $siswa->id);

    //     try {
    //         $siswa->delete();  // Pastikan delete dipanggil

    //         \Log::info('Siswa dengan ID ' . $siswa->id . ' berhasil dihapus.');

    //         return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    //     } catch (\Exception $e) {
    //         \Log::error('Gagal menghapus siswa: ' . $e->getMessage());
    //         return redirect()->route('siswa.index')->with('error', 'Gagal menghapus data siswa!');
    //     }
    // }


    public function destroy(Siswa $siswa)
    {
        if (!auth()->user()->can('delete siswa')) {
            abort(403, 'Tidak diizinkan');
        }

        $siswa->delete();

        return response()->json(['success' => 'Data siswa berhasil dihapus']);
        // return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
