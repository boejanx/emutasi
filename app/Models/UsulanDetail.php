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
        'siasn_id',
        'unor_id_tujuan',
        'nama_unor_tujuan',
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
