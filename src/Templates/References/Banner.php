<?php

namespace WI\Core\Templates\References;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'tmp_reference_banner';

    public $timestamps = false;

    protected $fillable = [
        'anchortext',
        'url',
        'target',
        'referencetranslation_id' //?? nodig
    ];
}
