<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsulanPesan extends Model
{
    use HasFactory;

    protected $table = 'tb_usulan_pesan';
    protected $primaryKey = 'id_pesan';
    
    protected $fillable = [
        'id_usulan',
        'id_user',
        'pesan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'id_usulan', 'id_usulan');
    }
}
