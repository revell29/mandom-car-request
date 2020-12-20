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

    public function destinasi()
    {
        return $this->belongsTo(MsDestination::class, 'destination', 'id');
    }

    public static function chartTransaction()
    {
        $data = Self::select(
            \DB::raw(" COUNT(IF(s.type='internal',1, null)) as supir_internal"),
            \DB::raw(" COUNT(IF(s.type='eksternal',1, null)) as supir_eksternal"),
            \DB::raw('YEAR(date) as year, MONTH(date) as month'),
            \DB::raw("DATE_FORMAT(date, '%Y-%m-%d') as date_new")
        )->join('ms_supirs as s', 's.id', 'car_requests.supir_id');

        $data = $data->groupBy('month')->get();
        return [
            'internal' => collect($data)->pluck('supir_internal', 'date_new'),
            'eksternal' => collect($data)->pluck('supir_eksternal', 'date_new'),
        ];
    }
}
