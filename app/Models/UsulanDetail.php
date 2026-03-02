<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tb_usulan_detail';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_usulan',
        'nip',
        'nama',
        'jabatan',
        'lokasi_awal',
        'lokasi_tujuan',
        'status',
        'catatan',
        'pns_id',
        'siasn_id',
        'unor_id_tujuan',
        'nama_unor_tujuan',
        'tempat_lahir',
        'tanggal_lahir',
        'pangkat_akhir',
        'gol_ruang_akhir',
        'tmt_gol_akhir',
        'pendidikan_terakhir_nama',
        'jabatan_nama',
        'unor_nama',
        'unor_induk_nama',
        'jenis_jabatan_baru',
        'jabatan_baru_id',
        'jabatan_baru_nama',
        'sub_unor_id',
        'sub_unor_nama',
    ];

    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'id_usulan', 'id_usulan');
    }

    public function berkas()
    {
        return $this->hasMany(UsulanBerkas::class, 'id_usulan_detail', 'id_detail');
    }
}
