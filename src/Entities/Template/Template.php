<?php

namespace WI\Core\Entities\Template;

use Illuminate\Database\Eloquent\Model;

#use App\Component;

#use WI\Core\Entities\Component;

class Template extends Model
{
	public $timestamps = false;
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'order_by_number',
        'parent_id'
    ];
	
	/**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //'password', 'remember_token',
    ];
	
	//The Compontents that belongs to the Template
	//manyToMany
    public function components()
    {
        return $this->belongsToMany('WI\Core\Entities\Component\Component','template_component')->withPivot('order_by_number')->orderBy('order_by_number','ASC');
        //return $this->belongsToMany('App\Reference','sitemap_reference')->withPivot('component_id','order_by_number')->orderBy('order_by_number','ASC');
    }
	
	
}
