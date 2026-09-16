<?php

namespace App\Http\Controllers;

use App\Models\pegawai;
use App\Models\Absensi;
use illuminate\View\View;
use illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private $pegawai;
    private $presensi;
    /**
     * Display a listing of the resource.
     */
    public function construct()
    {
        $this->presensi = Absensi::tablename('absensis');
        $this->pegawai = pegawai::tablename('pegawais');
    }
    public function detailAbsensiTanggal(string $tanggal)
    {
        // Ambil data pegawai beserta absensinya pada tanggal tertentu
        $absensis = \DB::table('pegawais')
            ->leftJoin('absensis', function ($join) use ($tanggal) {
                $join->on('pegawais.id', '=', 'absensis.pegawai_id')
                    ->whereDate('absensis.created_at', '=', $tanggal); // atau filter berdasarkan kolom tanggal di presences
            })
            ->select(
                'pegawais.namaPegawai',
                'pegawais.bidangPenempatan',
                'absensis.jam_masuk',
                'absensis.jam_keluar',
                'absensis.created_at as tanggal_absensi'
            )
            ->get();

        return view('admin.presence.date_present', compact('absensis', 'tanggal'));
    }
    public function index()
    {
        //menampilkan index dashboard admin
        return view('admin.admin_index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function showListPegawai()
    {

        //menampilkan daftar pegawai
        $pegawais = pegawai::latest()->paginate(10);
        return view('admin.show_pegawai', compact('pegawais'));
    }
    public function create()
    {
        //
        return view('admin.add_pegawai');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //menambahkan pegawai baru ke database
        $request->validate(
            [
                'nik' => 'required|unique:pegawais,nik',
                'namaPegawai' => 'required',
                'tanggalLahir' => 'required|date',
                'usia' => 'required|integer',
                'jenisKelamin' => 'required',
                'alamat' => 'required',
                'agama' => 'required',
                'statusPernikahan' => 'required',
                'kewarganegaraan' => 'required',
                'bidangPenempatan' => 'required',
                'lamaBekerja' => 'required',
                'gaji' => 'required',
            ],
            [
                'nik.required' => 'NIK harus diisi.',
                'nik.unique' => 'NIK sudah digunakan oleh pegawai lain.',
                'namaPegawai.required' => 'Nama pegawai harus diisi.',
                'tanggalLahir.required' => 'Tanggal lahir harus diisi.',
                'tanggalLahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
                'usia.required' => 'Usia harus diisi.',
                'usia.integer' => 'Usia harus berupa angka.',
                'jenisKelamin.required' => 'Jenis kelamin harus dipilih.',
                'alamat.required' => 'Alamat harus diisi.',
                'agama.required' => 'Agama harus dipilih.',
                'statusPernikahan.required' => 'Status pernikahan harus dipilih.',
                'kewarganegaraan.required' => 'Kewarganegaraan harus dipilih.',
                'bidangPenempatan.required' => 'Bidang penempatan harus diisi.',
                'lamaBekerja.required' => 'Lama bekerja harus diisi.',
                'gaji.required' => 'Gaji harus diisi.',
            ]
        );

        //create new pegawai
        pegawai::create([
            'nik' => $request->nik,
            'namaPegawai' => $request->namaPegawai,
            'tanggalLahir' => $request->tanggalLahir,
            'usia' => $request->usia,
            'jenisKelamin' => $request->jenisKelamin,
            'alamat' => $request->alamat,
            'agama' => $request->agama,
            'statusPernikahan' => $request->statusPernikahan,
            'kewarganegaraan' => $request->kewarganegaraan,
            'bidangPenempatan' => $request->bidangPenempatan,
            'lamaBekerja' => $request->lamaBekerja,
            'gaji' => $request->gaji,
        ]);
        return redirect()->route('pegawai_present')->with('success', 'Pegawai berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        return view('admin.edit_pegawai', [
            'pegawai' => pegawai::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate(
            [
                'nik' => 'required|unique:pegawais,nik,' . $id,
                'namaPegawai' => 'required',
                'tanggalLahir' => 'required|date',
                'usia' => 'required|integer',
                'jenisKelamin' => 'required',
                'alamat' => 'required',
                'agama' => 'required',
                'statusPernikahan' => 'required',
                'kewarganegaraan' => 'required',
                'bidangPenempatan' => 'required',
                'lamaBekerja' => 'required',
                'gaji' => 'required',
            ],
            [
                'nik.required' => 'NIK harus diisi.',
                'nik.unique' => 'NIK sudah digunakan oleh pegawai lain.',
                'namaPegawai.required' => 'Nama pegawai harus diisi.',
                'tanggalLahir.required' => 'Tanggal lahir harus diisi.',
                'tanggalLahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
                'usia.required' => 'Usia harus diisi.',
                'usia.integer' => 'Usia harus berupa angka.',
                'jenisKelamin.required' => 'Jenis kelamin harus dipilih.',
                'alamat.required' => 'Alamat harus diisi.',
                'agama.required' => 'Agama harus dipilih.',
                'statusPernikahan.required' => 'Status pernikahan harus dipilih.',
                'kewarganegaraan.required' => 'Kewarganegaraan harus dipilih.',
                'bidangPenempatan.required' => 'Bidang penempatan harus diisi.',
                'lamaBekerja.required' => 'Lama bekerja harus diisi.',
                'gaji.required' => 'Gaji harus diisi.',
            ]
        );
        Pegawai::where('id', $id)->update([
            'nik' => $request->nik,
            'namaPegawai' => $request->namaPegawai,
            'tanggalLahir' => $request->tanggalLahir,
            'usia' => $request->usia,
            'jenisKelamin' => $request->jenisKelamin,
            'alamat' => $request->alamat,
            'agama' => $request->agama,
            'statusPernikahan' => $request->statusPernikahan,
            'kewarganegaraan' => $request->kewarganegaraan,
            'bidangPenempatan' => $request->bidangPenempatan,
            'lamaBekerja' => $request->lamaBekerja,
            'gaji' => $request->gaji,
        ]);
        return redirect()->route('pegawai_present')->with('success', 'Data pegawai berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $id = pegawai::findOrFail($id);
        $id->delete();
        return redirect()->route('pegawai_present')->with('success', 'Data pegawai berhasil dihapus!');
    }

    public function showAbsensiPegawaiToday()
    {
        // menampilkan pegawai yang absen hari ini
        $presensi = DB::table('pegawais')
            ->leftJoin('absensis', function ($join) {
                $join->on('pegawais.id', '=', 'absensis.pegawai_id')
                    ->whereDate('absensis.tanggal', now()->toDateString());
            })
            ->select(
                'pegawais.namaPegawai',
                'pegawais.bidangPenempatan',
                'absensis.jam_masuk',
                'absensis.jam_keluar',
                'absensis.status'
            )
            ->get();
        return view('admin.presence.present', compact('presensi'));
    }
    //export data tabel ke format excel
    public function exportExcel()
    {
        // 1. Ambil data pegawai & absensi dari database
        $pegawai = \DB::table('pegawais')
            ->leftJoin('absensis', 'pegawais.id', '=', 'absensis.pegawai_id')
            ->select(
                'pegawais.namaPegawai',
                'pegawais.bidangPenempatan',
                'absensis.jam_masuk',
                'absensis.jam_keluar'
            )
            ->get();

        $fileName = 'REKAP_ABSENSI_PEGAWAI_PPNPN_' . date('Y-m-d') . '.xls';

        // 2. Gunakan Response Stream Download bawaan Laravel
        return response()->streamDownload(function () use ($pegawai) {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
            echo '<style>
                body { font-family: "Times New Roman", Times, serif; }
                .text-center { text-align: center; }
                .bold { font-weight: bold; }
                .kop-1 { font-size: 14pt; font-weight: bold; }
                .kop-2 { font-size: 14pt; font-weight: bold; }
                .kop-3 { font-size: 14pt; font-weight: bold; }
                .kop-alamat { font-size: 10pt; }
                .judul { font-size: 12pt; font-weight: bold; text-align: center; }
              </style>';
            echo '</head>';
            echo '<body>';

            echo '<table>';

            // --- KOP SURAT ---
            echo '<tr><td colspan="5" class="text-center kop-1">KEJAKSAAN REPUBLIK INDONESIA</td></tr>';
            echo '<tr><td colspan="5" class="text-center kop-2">KEJAKSAAN TINGGI BALI</td></tr>';
            echo '<tr><td colspan="5" class="text-center kop-3">KEJAKSAAN NEGERI BANGLI</td></tr>';
            echo '<tr><td colspan="5" class="text-center kop-alamat">Jl. Lettu Lila No. 11 A Kabupaten Bangli 80613</td></tr>';
            echo '<tr><td colspan="5" class="text-center kop-alamat">Telp. (0361)-550136,Fax : (0361)-91048, https://kejari-bangli.kejaksaan.go.id</td></tr>';

            // Garis Pembatas Kop
            echo '<tr><td colspan="5" style="border-bottom: 3px double #000000; height: 10px;"></td></tr>';
            echo '<tr><td colspan="5" style="height: 15px;"></td></tr>';

            // --- JUDUL REKAP ---
            echo '<tr><td colspan="5" class="judul">REKAP ABSENSI PEGAWAI PPNPN</td></tr>';
            echo '<tr><td colspan="5" style="height: 15px;"></td></tr>';

            // --- TABEL DATA ---
            echo '<tr>
                <td style="width: 5%;"></td>
                <td class="bold text-center" style="border:1px solid #000; width: 30%;">Nama Pegawai</td>
                <td class="bold text-center" style="border:1px solid #000; width: 25%;">Bidang Penempatan</td>
                <td class="bold text-center" style="border:1px solid #000; width: 20%;">Jam Masuk</td>
                <td class="bold text-center" style="border:1px solid #000; width: 20%;">Jam Pulang</td>
              </tr>';

            foreach ($pegawai as $row) {
                $jamMasuk = $row->jam_masuk ?? '';
                $jamPulang = $row->jam_pulang ?? '';

                echo '<tr>
                    <td></td>
                    <td style="border:1px solid #000; text-align:left;">' . htmlspecialchars($row->namaPegawai) . '</td>
                    <td style="border:1px solid #000; text-align:left;">' . htmlspecialchars($row->bidangPenempatan) . '</td>
                    <td style="border:1px solid #000; text-align:center;">' . htmlspecialchars($jamMasuk) . '</td>
                    <td style="border:1px solid #000; text-align:center;">' . htmlspecialchars($jamPulang) . '</td>
                  </tr>';
            }

            // --- TANDA TANGAN ---
            echo '<tr><td colspan="5" style="height: 30px;"></td></tr>';
            echo '<tr>
                <td colspan="3"></td>
                <td colspan="2" style="text-align:left;">Mengetahui Kepala Kejaksaan Negeri Bangli</td>
              </tr>';
            echo '<tr><td colspan="5" style="height: 50px;"></td></tr>';
            echo '<tr>
                <td colspan="3"></td>
                <td colspan="2" class="bold" style="text-align:left; text-decoration: underline;">YETTY HERAWATY, S.H., M.H</td>
              </tr>';
            echo '<tr>
                <td colspan="3"></td>
                <td colspan="2" style="text-align:left;">Jaksa Madya NIP. 197909062002122001</td>
              </tr>';

            echo '</table>';
            echo '</body>';
            echo '</html>';
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);
    }
}
