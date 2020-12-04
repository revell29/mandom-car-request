<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsEmployee extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function departement()
    {
        return $this->belongsTo(MsDepartement::class, 'departement_id');
    }
}
