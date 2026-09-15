<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'namaPegawai',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',   
    ];
    public function pegawai()
    {
        // Parameter kedua ('pegawai_id') disesuaikan dengan nama kolom foreign key di tabel absensis
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id');
    }
}
