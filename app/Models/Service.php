<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{

    protected $table = 'benefits';

    protected $fillable = [

        'title',
        'subtitle'
    ];
}
