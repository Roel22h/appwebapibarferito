<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class TUsuario extends Model
{
    protected $table = 'tusuario';
    protected $primaryKey = 'codigoUsuario';
    public $incrementing = false;
    public $timestamps = true;
}
