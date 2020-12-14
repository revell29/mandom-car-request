<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsMobil extends Model
{
    protected $guarded = [];

    public static function forDropdown()
    {
        $data = Self::select(\DB::raw("CONCAT(merek_mobil, ' - (', no_polisi,')' ) as name, id"))->pluck('name', 'id');
        return $data;
    }
}
