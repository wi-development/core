<?php

namespace WI\Core\Entities\Component;

use Illuminate\Database\Eloquent\Model;

#use WI\Core\Entities\Component\Component;

class Component extends Model
{
    //
    //protected $table = 'roles';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category',
        'type'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //'password', 'remember_token',
    ];

    //Component belongs to many templates (many to many)
    public function templates(){
        return $this->belongsToMany('WI\Core\Entities\Template\Template','template_component');
    }

    public function references(){
        return $this->belongsToMany('WI\Core\Entities\Reference\Reference')->withPivot('order_by_number')->orderBy('order_by_number','ASC');
    }

    //many to many
    public function referencetypes(){
        return $this->belongsToMany('WI\Core\Entities\ReferenceType\ReferenceType','component_referencetype','component_id','referencetype_id')->withPivot('order_by_number')->orderBy('order_by_number','ASC');
        //volgorde maakt dus uit..
    }



}
