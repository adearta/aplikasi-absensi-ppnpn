<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return view('admin.presence.month_present', compact('absensi', 'bulan', 'tahun'));
    }
    public function exporExcelBulanan(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $namaBulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F');

        $pegawai = \DB::table('pegawais')
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

        $fileName = 'REKAP_ABSENSI_' . strtoupper($namaBulan) . '_' . $tahun . '.xls';

        return response()->streamDownload(function () use ($pegawai, $namaBulan, $tahun) {
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
            echo '<tr><td colspan="6" class="text-center kop-1">KEJAKSAAN REPUBLIK INDONESIA</td></tr>';
            echo '<tr><td colspan="6" class="text-center kop-2">KEJAKSAAN TINGGI BALI</td></tr>';
            echo '<tr><td colspan="6" class="text-center kop-3">KEJAKSAAN NEGERI BANGLI</td></tr>';
            echo '<tr><td colspan="6" class="text-center kop-alamat">Jl. Lettu Lila No. 11 A Kabupaten Bangli 80613</td></tr>';
            echo '<tr><td colspan="6" class="text-center kop-alamat">Telp. (0361)-550136,Fax : (0361)-91048, https://kejari-bangli.kejaksaan.go.id</td></tr>';

            echo '<tr><td colspan="6" style="border-bottom: 3px double #000000; height: 10px;"></td></tr>';
            echo '<tr><td colspan="6" style="height: 15px;"></td></tr>';

            // --- JUDUL REKAP BULANAN ---
            echo '<tr><td colspan="6" class="judul">REKAP ABSENSI PEGAWAI PPNPN - BULAN ' . strtoupper($namaBulan) . ' ' . $tahun . '</td></tr>';
            echo '<tr><td colspan="6" style="height: 15px;"></td></tr>';

            // --- TABEL DATA ---
            echo '<tr>
                <td style="width: 5%;"></td>
                <td class="bold text-center" style="border:1px solid #000; width: 15%;">Tanggal</td>
                <td class="bold text-center" style="border:1px solid #000; width: 25%;">Nama Pegawai</td>
                <td class="bold text-center" style="border:1px solid #000; width: 20%;">Bidang Penempatan</td>
                <td class="bold text-center" style="border:1px solid #000; width: 15%;">Jam Masuk</td>
                <td class="bold text-center" style="border:1px solid #000; width: 15%;">Jam Pulang</td>
              </tr>';

            foreach ($pegawai as $row) {
                $tgl = $row->tanggal_absensi ? \Carbon\Carbon::parse($row->tanggal_absensi)->format('d-m-Y') : '-';
                $jamMasuk = $row->jam_masuk ?? '-';
                $jamKeluar = $row->jam_keluar ?? '-';

                echo '<tr>
                    <td></td>
                    <td style="border:1px solid #000; text-align:center;">' . $tgl . '</td>
                    <td style="border:1px solid #000; text-align:left;">' . htmlspecialchars($row->namaPegawai) . '</td>
                    <td style="border:1px solid #000; text-align:left;">' . htmlspecialchars($row->bidangPenempatan) . '</td>
                    <td style="border:1px solid #000; text-align:center;">' . htmlspecialchars($jamMasuk) . '</td>
                    <td style="border:1px solid #000; text-align:center;">' . htmlspecialchars($jamKeluar) . '</td>
                  </tr>';
            }

            // --- TANDA TANGAN ---
            echo '<tr><td colspan="6" style="height: 30px;"></td></tr>';
            echo '<tr>
                <td colspan="4"></td>
                <td colspan="2" style="text-align:left;">Mengetahui Kepala Kejaksaan Negeri Bangli</td>
              </tr>';
            echo '<tr><td colspan="6" style="height: 50px;"></td></tr>';
            echo '<tr>
                <td colspan="4"></td>
                <td colspan="2" class="bold" style="text-align:left; text-decoration: underline;">YETTY HERAWATY, S.H., M.H</td>
              </tr>';
            echo '<tr>
                <td colspan="4"></td>
                <td colspan="2" style="text-align:left;">Jaksa Madya NIP. 197909062002122001</td>
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
