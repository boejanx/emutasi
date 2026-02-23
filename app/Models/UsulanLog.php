<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsulanLog extends Model
{
    use SoftDeletes;

    protected $table = 'tb_usulan_log';
    protected $primaryKey = 'id_log';
    protected $guarded = ['id_log'];
    
    public function usulan()
    {
        return $this->belongsTo(Usulan::class, 'id_usulan', 'id_usulan');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
}
