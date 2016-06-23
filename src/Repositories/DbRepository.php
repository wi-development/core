<?php
namespace WI\Core\Repositories;

use WI\Locale\Locale;
#use App\Model;
use Illuminate\Database\Eloquent\Model;//??
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class DbRepository
 * @package App\Repositories
 */
abstract class DbRepository{
    /**
     * Eloquent model
     */
    protected $model;
    public $locale;
    public $enabledLocales;

    /**
     * DbRepository constructor.
     * @param $model
     */
    public function __construct()//Model $model
    {
        //$this->model = $model;
        $this->locale = new Locale();
        $this->enabledLocales = $this->locale->getEnabled();
    }


    public function getEnabledLocales(){
        return $this->enabledLocales;
    }

    /**
     * Fetch a record by id
     * Model is set by model which implements this class
     * @param $id
     */
    public function getById($id){
        return $this->model->findOrFail($id);
    }

    public function create(array $attributes){
        return "DbRepository.create";
    }

    public function update(array $attributes){
        return $this->model->update($attributes);
    }

    public function all($columns = array('*')){
        return $this->model->all($columns);
    }

    public function find($id, $columns = array('*')){
        //$id, $columns = ['*']
        //dc($columns);
        return $this->model->findOrFail($id,$columns);
    }

    public function destroy($ids){
        return "DbRepository.destroy";
    }





    /**
     * Resets the key value for Translations Collection
     *              example: $sitemap->translations[0] => $sitemap->translations[nl]
     * @param $component = Collection which contains translations Collection
     *              example: $sitemap->translations, $post->translations
     * @return translations Collection
     */
    public function resetKeyTranslationCollectionByLocaleIdentifier($component){
        foreach($this->enabledLocales as $key => $enabledLocale) {
            if (isset($component->translations[$key])) {
                $component->translations[$component->translations[$key]->locale->languageCode] = $component->translations[$key];//GOED!
                unset($component->translations[$key]);
            }
            else{//new enabled locale after created sitemapTranslation
                $this->model->setDummyDataForTranslation($component,$enabledLocale);
            }
        }
        //kan uit?
        //return $component->translations;
    }



    /**
     * Updates the thisTranslation_mediaTranslation PIVOT table
     * thisTranslation = sitemaptranslation, posttranslation, referencetranslation
     * @param $translation_id
     * @param $requestTranslation Request Model
     * @param $thisTranslation, Translation Collection
     */
    public function syncThisTranslationMediaTranslation($translation_id, $requestTranslation, $thisTranslation){
        $thisTranslation->mediatranslations()->sync([]);
        if ((array_key_exists('media', $requestTranslation))) {
            $syncArray = [];
            $detachArray = [];
            //dc('test');
            foreach ($requestTranslation['media'] as $formName => $formValue) {
                $cnt = 1;
                foreach ($formValue as $key2 => $mediatranslation_id) {
                    //echo $mediatranslation_id;
                    $syncArray[] = [
                        ''.$this->translationType.'translation_id' => $translation_id,
                        'mediatranslation_id' => (int)$mediatranslation_id,
                        'field_name' => $formName,
                        'order_by_number' => $cnt++
                    ];
                    /*
                    $detachArray[] = [
                        'posttranslation_id' => (int)$postTranslation->id
                    ];*/
                }
            }
            $thisTranslation->mediatranslations()->attach($syncArray);
        }
    }


    /**
     * Group mediatranslation by field_name (intro images, overzicht image)
     * and set Id for ng data object
     * @param $allTranslations, Translation Collection
     */
    public function groupMediaCollectionByFieldName($allTranslations){
        //dc($allTranslations);
        foreach($allTranslations as $key => $translation){
            $mediaCollectionGroupByFormName = [];
            foreach($translation->mediatranslations as $key => $mediatranslation){
                $field_name = $mediatranslation->pivot->field_name;
                if (!(isset($mediaCollectionGroupByFormName[$field_name]))){
                    $mediaCollectionGroupByFormName[$field_name] = (collect(['mediumId'.$mediatranslation->media_id.''=>$mediatranslation]));
                }
                else{
                    $mediaCollectionGroupByFormName[$field_name]['mediumId'.$mediatranslation->media_id.''] = $mediatranslation;
                }
            }
            //dc($mediaCollectionGroupByFormName);
            $translation->media = ($mediaCollectionGroupByFormName);
        }
    }

    //parentTranslation = sitemap
    public function groupThisTranslationMediaCollection($parentTranslation){
        foreach ($parentTranslation as $key => $parentTranslationItem){
            //dc($locationListItem->id);
            $this->groupMediaTranslationCollectionByFieldName($parentTranslationItem->translation);
        }

    }

    public function groupMediaTranslationCollectionByFieldName($translation){
        //dc($translation);
        //foreach($allTranslations as $key => $translation){
            $mediaCollectionGroupByFormName = [];
            foreach($translation->mediatranslations as $key => $mediatranslation){
                $field_name = $mediatranslation->pivot->field_name;
                if (!(isset($mediaCollectionGroupByFormName[$field_name]))){
                    $mediaCollectionGroupByFormName[$field_name] = (collect(['mediumId'.$mediatranslation->media_id.''=>$mediatranslation]));
                }
                else{
                    $mediaCollectionGroupByFormName[$field_name]['mediumId'.$mediatranslation->media_id.''] = $mediatranslation;
                }
            }
            $translation->media = ($mediaCollectionGroupByFormName);
        //}
    }


    /**
     * A custom paginator for custom (non-eloquent) collections
     * (merged collections for example)
     *
     * @param $collection_array
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginator($collection_array, $perPage = 15){
        $request = request();
        $currentPage = $request->query('page');
        if (is_null($currentPage)){
            $currentPage = "1";
        }

        //dc($currentPage);
        //$perPage = 3;
        $offset = ($currentPage * $perPage) - $perPage;
        return new LengthAwarePaginator(
            array_slice($collection_array, $offset, $perPage, true),
            count($collection_array),
            $perPage,
            $currentPage,
            [   'path' => $request->url(),
                //'query' => []
                'query' => $request->query()
            ]);

    }
}