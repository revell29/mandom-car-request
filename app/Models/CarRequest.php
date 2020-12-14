<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarRequest extends Model
{
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(MsEmployee::class, 'employee_id', 'id');
    }

    public function departement()
    {
        return $this->belongsTo(MsDepartement::class, 'departement_id', 'id');
    }
}
