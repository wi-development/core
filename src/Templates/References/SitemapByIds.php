<?php

namespace WI\Core\Templates\References;

use Illuminate\Database\Eloquent\Model;

class SitemapByIds extends Model
{
    protected $table = 'tmp_reference_sitemaplist_by_ids';

    public $timestamps = false;

    protected $fillable = [
        'sitemap_id',
        'anchortext',
        //'referencetranslation_id' //?? nodig
    ];
}
