<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsDepartement extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public static function forDropdown()
    {
        $data = Self::pluck('name', 'id');
        return $data;
    }
}
