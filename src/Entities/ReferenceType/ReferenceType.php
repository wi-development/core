<?php

namespace WI\Core\Entities\ReferenceType;

use Illuminate\Database\Eloquent\Model;

class ReferenceType extends Model
{
    protected $table = 'referencetypes';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'category',
        'type',
        'model',
        'slug',
        'db_table_name'
    ];



    //many to many
    public function components(){
        //return $this->belongsToMany('App\Component')->withPivot('order_by_number')->orderBy('order_by_number','ASC');

        return $this->belongsToMany('WI\Core\Entities\Component\Component','component_referencetype','referencetype_id','component_id')->withPivot('order_by_number')->orderBy('order_by_number','ASC');
    }

    //relations
    public function references(){
        return $this->hasMany('App\Reference','referencetype_id');
    }




}
