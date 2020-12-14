<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MsSupir extends Model
{
    protected $guarded = [];

    public static function forDropdown()
    {
        $data = Self::select(\DB::raw("CONCAT(nip, ' - ', nama ) as name, id"))->pluck('name', 'id');
        return $data;
    }
}
