<?php

namespace App\Models;

use App\Traits\ModelHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class pegawai extends Model
{
    //
    use HasFactory;
    /**
     * @var list<string>
     */
    protected $table = "pegawais";
    protected $fillable = [
        'nik',
        'namaPegawai',
        'tanggalLahir',
        'usia',
        'jenisKelamin',
        'alamat',
        'agama',
        'statusPernikahan',
        'kewarganegaraan',
        'bidangPenempatan',
        'lamaBekerja',
        'gaji',
    ];
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'pegawai_id');
    }
}
