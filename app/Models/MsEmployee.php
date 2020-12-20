<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MsEmployee extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $hidden = ['password'];

    public static function forDropdown()
    {
        $auth = auth()->user()->departement_id;
        if (auth()->user()->role == 'superadmin') {
            $data = Self::pluck('name', 'id');
        } else {
            $data = Self::where('departement_id', $auth)->pluck('name', 'id');
        }
        return $data;
    }

    public function departement()
    {
        return $this->belongsTo(MsDepartement::class, 'departement_id');
    }
}
