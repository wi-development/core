<?php

namespace App\Templates\References;

use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    protected $table = 'tmp_reference_header';

    public $timestamps = false;

    protected $fillable = [
        'referencetranslation_id',
        'logo',
        'pay_off',

    ];
}
