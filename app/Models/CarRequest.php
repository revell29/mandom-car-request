<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarRequest extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];

    public function employee()
    {
        return $this->belongsTo(MsEmployee::class, 'employee_id', 'id');
    }

    public function departement()
    {
        return $this->belongsTo(MsDepartement::class, 'departement_id', 'id');
    }

    public function supir()
    {
        return $this->belongsTo(MsSupir::class, 'supir_id');
    }

    public function mobil()
    {
        return $this->belongsTo(MsMobil::class, 'mobil_id');
    }
}
