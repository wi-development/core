<?php

namespace WI\Core\Entities\Reference;

use Illuminate\Database\Eloquent\Model;

#TODO ??
#use App\Templates\References\SitemapByParentId;

class ReferenceTranslation extends Model
{
    //
	protected $table = 'referencetranslations';

	protected $fillable = [
			'reference_id',
			'locale_id',
			'name',
			'content'
	];



	public $tonnytest;
	public $tonnytest1;

	public function reference(){
		return $this->belongsTo('WI\Core\Entities\Reference');
	}
	//
	public function locale(){
		return $this->belongsTo('WI\Locale\Locale');
	}


	public function mediatranslations()
	{
		return $this->belongsToMany('WI\Media\MediaTranslation', 'referencetranslation_mediatranslation', 'referencetranslation_id','mediatranslation_id')
			->withPivot('field_name','order_by_number')->orderBy('pivot_order_by_number','ASC');
	}

	public function mediatranslation()
	{
		return $this->belongsToMany('App\MediaTranslation', 'referencetranslation_mediatranslation', 'referencetranslation_id','mediatranslation_id')
			->withPivot('field_name','order_by_number')
			->where('locale_id',config('app.locale_id'))
			->orderBy('pivot_order_by_number','ASC');
	}

	//templates
	//templates method name = template->slug
	public function header()
	{
		return $this->hasOne('App\Templates\References\Header','referencetranslation_id');
	}

	public function banner()
	{
		return $this->hasOne('WI\Core\Templates\References\Banner','referencetranslation_id');
	}



	public function sitemaplistbyparentid()
	{
		return $this->hasOne('App\Templates\References\SitemapByParentId','referencetranslation_id');
	}

	//parentidandi
	public function sitemaplistbyids()
	{
		return $this->hasOne('App\Templates\References\SitemapByIds','referencetranslation_id');
	}

}
