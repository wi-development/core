<?php

namespace WI\Core\Entities\Reference;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $fillable = [
        'system_name',
        'created_by_user_id',
        'updated_by_user_id',
        'referencetype_id'
    ];

    //
    public function translations(){
        return $this->hasMany('WI\Core\Entities\Reference\Referencetranslation');
    }

    public function translation()
    {
        return $this->hasOne('WI\Core\Entities\Reference\ReferenceTranslation')->where('locale_id',config('app.locale_id'));
    }
    public function components(){
        //component_reference
        return $this->belongsToMany('WI\Core\Entities\Component\Component')->withPivot('order_by_number')->orderBy('order_by_number','ASC');
    }


    public function componentsTest(){
        return $this->belongsToMany('WI\Core\Entities\Component\Component','sitemap_reference')->withPivot('sitemap_id','component_id','order_by_number')->orderBy('order_by_number','ASC');
        //return $this->belongsToMany('App\Component')->withPivot('order_by_number')->orderBy('order_by_number','ASC');
    }

    public function componentsTest1(){
        //return $this->hasOne('App\Component','sitemap_reference');
        return $this->hasMany('WI\Core\Entities\Component\Component')->withPivot('order_by_number')->orderBy('order_by_number','ASC');
    }


    public function sitemaps(){
        return $this->belongsToMany('App\Sitemap')->withPivot('component_id','order_by_number')->orderBy('order_by_number','ASC');
    }

    //relations belongs to one reference type
    public function referencetype(){
        return $this->belongsTo('WI\Core\Entities\ReferenceType\ReferenceType','referencetype_id');			//foreign key belongsTo
    }



    //when locale is enabled after create
    public function setDummyDataForTranslation($reference,$enabledLocale)
    {

        //dc($reference);

        $reference->translations[$enabledLocale->languageCode] = new ReferenceTranslation();

        //used for create() in update()
        //$reference->translations[$enabledLocale->languageCode]->post_id = $reference->id;
        //$reference->translations[$enabledLocale->languageCode]->locale_id = $enabledLocale->id;

        //used to set dummy data in form
        $reference->translations[$enabledLocale->languageCode]->name = 'new name, new enabled locale [MODEL REFERENCE]' . $enabledLocale->languageCode . '';
        $reference->translations[$enabledLocale->languageCode]->content = 'new content, new enabled locale [MODEL] ' . $enabledLocale->languageCode . '';



        //TEMP
//dd($reference);


        //post_type
        switch ($reference->referencetype->slug) {
            case "banner":
                $reference->translations[$enabledLocale->languageCode]['banner'] = [
                    'anchortext' => 'anchor text [DUMMY] '.$enabledLocale->languageCode.''
                    , 'url' => 'www.url.' . $enabledLocale->languageCode . ''
                    , 'target' => '_blank - ' . $enabledLocale->languageCode . ''
                ];
                break;
            case "vacancies":
                $reference->translations[$enabledLocale->languageCode]['vacancies'] = [
                    'mail_to' => 'test@test.' . $enabledLocale->languageCode . ''
                    , 'vacancies_test_body' => 'new body, new enabled locale ' . $enabledLocale->languageCode . ''
                ];
                break;
            case "sitemaplistbyids":
                $reference->translations[$enabledLocale->languageCode]['sitemaplistbyids'] = [
                    'anchortext' => 'anchor text [DUMMY] '.$enabledLocale->languageCode.''
                    , 'sitemap_id' => 1
                ];
                break;



            default:
        }

    }   

    /**
     * Get a list of component ids associated with the current template
     * triggered by by method component_list()
     * @return array
     */
    public function getComponentListAttribute(){
        //debugbar()->alert('MESSAGE');
        //dc($this->components->lists('id')->all());
        return $this->components->lists('id')->all();
        //return $this->tags->lists('id')->toArray();

    }

    public function getReferenceTypetListAttribute(){
        return $this->components->lists('id')->all();
    }


}
