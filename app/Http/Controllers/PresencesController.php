<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\pegawai;
use function Illuminate\Support\years;

class PresencesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $pegawaiID = Auth::user()->pegawai_id;
        $absensi = \App\Models\Absensi::where('pegawai_id', $pegawaiID)->get();
        $pegawai = \App\Models\Pegawai::where('id', $pegawaiID)->get();
        return view('pages.presence.index_present', compact('absensi', 'pegawai'));
        //cukup menampilkan data absensi pegawai yang sedang login
        // return view('pages.presence.index_present', compact('absensi'));
    }

    public function showPegawai(string $id) {}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //

    }

    /**
     * Display the specified resource
     */
    public function cekAbsensi()
    {
        //
        $pegawaiID = Auth::user()->pegawai_id;

        // Cek apakah pegawai sudah absen hari ini
        $sudahAbsen = \App\Models\Absensi::where('pegawai_id', $pegawaiID)
            ->whereDate('tanggal', now()->toDateString())
            ->exists();
        //cek apakah pegwai sudah absen pulang
        $sudahPulang = \App\Models\Absensi::where('pegawai_id', $pegawaiID)
            ->whereDate('tanggal', now()->toDateString())
            ->whereNotNull('jam_keluar')
            ->exists();

        return view('absensi.index', compact('sudahAbsen', 'sudahPulang'));
        // return view('absensi.index');
    }
    public function absenPulang(Request $request)
    {
        $pegawaiID = Auth::user()->pegawai_id; // Ambil ID pegawai dari user yang sedang login
        $pegawai = Auth::user()->pegawai;
        if ($pegawaiID) {
            // Cari data absen hari ini untuk pegawai yang sedang login
            $absen = \App\Models\Absensi::where('pegawai_id', $pegawaiID)
                ->whereDate('tanggal', now()->toDateString())
                ->first();

            if ($absen) {
                if ($absen->jam_masuk === null) {
                    return redirect()->route('absensi.index')->with('error', 'Anda belum melakukan absensi masuk hari ini.');
                } else {
                    //cek apakah sudah 8 jam
                    $tanggal = \Carbon\Carbon::parse($absen->tanggal)->format('Y-m-d');
                    $waktuMasuk = \Carbon\Carbon::parse($tanggal . '' . $absen->jam_masuk, 'Asia/Makassar');
                    $waktuBolehPulang = $waktuMasuk->copy()->addHours(8);
                    $waktuSekarang = now('Asia/Makassar');
                    if ($waktuSekarang->lt($waktuBolehPulang)) {
                        return redirect()->back()->with('error', 'Anda Belum Mencapai 8 Jam Kerja. Anda Baru Bisa Pulang Pukul ' . $waktuBolehPulang->format('H:i') . ' WITA');
                    } else {
                        //update data jam keluar
                        $absen->jam_keluar = now('Asia/Makassar')->format('H:i:s'); // Set waktu absen pulang
                        $absen->save();

                        return redirect()->route('absensi.index')
                            ->with('success', 'Anda sudah melakukan absensi pulang hari ini.');
                    }
                }
            } else {
                return redirect()->route('absensi.index')->with('error', 'Anda belum melakukan absensi masuk hari ini.');
            }
        } else {
            return redirect()->route('absensi.index')->with('error', 'Pegawai tidak ditemukan.');
        }
    }
    //merekap absensi seluruh pegawai berdasarkan bulan
    public function rekapBulanan(Request $request)
    {
        //mengambil bulan dan tahun request
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $pegawai = pegawai::withCount([
            'absensis as jumlah_masuk' => function ($query) use ($bulan, $tahun) {
                $query->whereNotNull('jam_keluar')
                    ->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun);
            },
            'absensis as jumlah_tidak_absen' => function ($query) use ($bulan, $tahun) {
                $query->whereNull('jam_keluar')
                    ->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun);
            }
        ])->get();
        //ambil data absensi pegawai berdasarkan bulan dan tahun yang dipilih
        $absensi = \DB::table('pegawais')
            ->leftJoin('absensis', function ($join) use ($bulan, $tahun) {
                $join->on('pegawais.id', '=', 'absensis.pegawai_id')
                    ->whereMonth('absensis.created_at', '=', $bulan)
                    ->whereYear('absensis.created_at', '=', $tahun);
            })
            ->select(
                'pegawais.namaPegawai',
                'pegawais.bidangPenempatan',
                'absensis.jam_masuk',
                'absensis.jam_keluar',
                'absensis.created_at as tanggal_absensi'
            )
            ->get();
        return view('admin.presence.month_present', compact('absensi', 'bulan', 'tahun','pegawai'));
    }
    public function exporExcelBulanan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $namaBulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        // Ambil data pegawai beserta agregat absensi sesuai bulan & tahun yang dipilih
        $pegawai = pegawai::withCount([
            'absensis as jumlah_masuk' => function ($query) use ($bulan, $tahun) {
                $query->where('status', 'Absen')
                    ->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun);
            },
            'absensis as jumlah_tidak_absen' => function ($query) use ($bulan, $tahun) {
                $query->where('status', 'Belum Absen')
                    ->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun);
            }
        ])->get();

        $fileName = 'REKAP_ABSENSI_PEGAWAI_PPNPN_' . strtoupper($namaBulan) . '_' . $tahun . '.xls';

        return response()->streamDownload(function () use ($pegawai, $namaBulan, $tahun) {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
            echo '<style>
            body { font-family: "Times New Roman", Times, serif; font-size: 11pt; }
            .text-center { text-align: center; }
            .bold { font-weight: bold; }
            .kop-1, .kop-2, .kop-3 { font-size: 11pt; font-weight: bold; text-align: center; }
            .kop-alamat { font-size: 10pt; text-align: center; }
            .table-data { border-collapse: collapse; width: 100%; }
            .table-data th, .table-data td { border: 1px solid #000000; padding: 4px; vertical-align: middle; }
          </style>';
            echo '</head>';
            echo '<body>';

            echo '<table>';

            // --- KOP SURAT ---
            echo '<tr><td colspan="4" class="kop-1">KEJAKSAAN REPUBLIK INDONESIA</td></tr>';
            echo '<tr><td colspan="4" class="kop-2">KEJAKSAAN TINGGI BALI</td></tr>';
            echo '<tr><td colspan="4" class="kop-3">KEJAKSAAN NEGERI BANGLI</td></tr>';
            echo '<tr><td colspan="4" class="kop-alamat">Jl. Lettu Lila No. 11 A Kabupaten Bangli 80613</td></tr>';
            echo '<tr><td colspan="4" class="kop-alamat">Telp. (0361)-550136,Fax : (0361)-91048, https://kejari-bangli.kejaksaan.go.id</td></tr>';

            echo '<tr><td colspan="4" style="border-bottom: 3px double #000000; height: 5px;"></td></tr>';
            echo '<tr><td colspan="4" style="height: 15px;"></td></tr>';

            // --- JUDUL REKAP ---
            echo '<tr><td colspan="4" class="text-center bold" style="font-size: 12pt;">REKAP ABSENSI PEGAWAI PPNPN</td></tr>';
            echo '<tr><td colspan="4" class="text-center bold" style="font-size: 12pt;">BULAN ' . strtoupper($namaBulan) . ' TAHUN ' . $tahun . '</td></tr>';
            echo '<tr><td colspan="4" style="height: 15px;"></td></tr>';

            // --- TABEL REKAP ---
            echo '<tr><td colspan="4">';
            echo '<table class="table-data">';
            echo '<thead>
                <tr>
                    <th style="width: 200px; text-align: left;">Nama Pegawai</th>
                    <th style="width: 180px; text-align: left;">Bidang Penempatan</th>
                    <th style="width: 150px; text-align: left;">Jumlah Hari Masuk</th>
                    <th style="width: 150px; text-align: left;">Jumlah Tidak Absen</th>
                </tr>
              </thead>';
            echo '<tbody>';

            foreach ($pegawai as $row) {
                echo '<tr>
                    <td>' . htmlspecialchars($row->namaPegawai) . '</td>
                    <td>' . htmlspecialchars($row->bidangPenempatan) . '</td>
                    <td class="text-center">' . ($row->jumlah_masuk ?? 0) . '</td>
                    <td class="text-center">' . ($row->jumlah_tidak_absen ?? 0) . '</td>
                  </tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</td></tr>';

            // --- TANDA TANGAN ---
            echo '<tr><td colspan="4" style="height: 25px;"></td></tr>';
            echo '<tr>
                <td colspan="2"></td>
                <td colspan="2" class="text-center">Mengetahui Kepala Kejaksaan Negeri Bangli</td>
              </tr>';
            echo '<tr><td colspan="4" style="height: 50px;"></td></tr>';
            echo '<tr>
                <td colspan="2"></td>
                <td colspan="2" class="text-center bold" style="text-decoration: underline;">YETTY HERAWATY, S.H., M.H</td>
              </tr>';
            echo '<tr>
                <td colspan="2"></td>
                <td colspan="2" class="text-center">Jaksa Madya NIP. 197909062002122001</td>
              </tr>';

            echo '</table>';
            echo '</body>';
            echo '</html>';
        }, $fileName, [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
