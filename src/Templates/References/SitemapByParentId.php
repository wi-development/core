<?php

namespace WI\Core\Templates\References;

use App\ReferenceTranslation;
use Illuminate\Database\Eloquent\Model;

class SitemapByParentId extends Model
{
    protected $table = 'tmp_reference_sitemaplist_by_parent_id';

    public $timestamps = false;

    protected $fillable = [
        //'sitemap_id',
        'anchortext',
        'amount',
        'sitemap_parent_id',
        //'referencetranslation_id' //?? nodig
    ];
}
