<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsDestination extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public static function forDropdown()
    {
        $data = Self::select(\DB::raw("CONCAT(kota, ' - ', kecamatan ) as name, id"))->pluck('name', 'id');
        return $data;
    }
}
