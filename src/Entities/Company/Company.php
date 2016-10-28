<?php

namespace WI\Core\Entities\Company;

use Illuminate\Database\Eloquent\Model;

#use App\Component;

#use WI\Core\Entities\Component;

class Company extends Model
{
	//public $timestamps = false;

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'person',
		'contact',
		'address',
		'zipcode',
		'city',
		'email',
		'phone',
		'mobile',
		'fax',
		'formname',
		'formemail',
		'kvk',
		'facebook',
		'twitter',
		'linkedin',
		'terms_and_conditions',
		'created_at',
		'updated_at'
    ];
	
	/**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        //'password', 'remember_token',
    ];
	
	//The Companies that belongs to the Company
	//manyToMany
    public function userUIT()
    {
        return $this->belongsTo('WI\Core\Entities\Company\Company');
        //return $this->belongsToMany('App\Reference','sitemap_reference')->withPivot('component_id','order_by_number')->orderBy('order_by_number','ASC');
    }
	
	
}
