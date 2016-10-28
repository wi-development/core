<?php

namespace WI\Core\Repositories\Reference;

use WI\Locale;
use WI\Core\Repositories\DbRepository;
use WI\Core\Entities\Reference\Reference;

#use App\Referencetranslation;
#TODP
#use ReferenceRepositoryInterface;


/**
 * @property Reference model
 */
class DbReferenceRepository extends DbRepository implements ReferenceRepositoryInterface
{


    /**
     * @var Reference
     */
    protected $model;
    protected $translationType; //reference, post, reference || used for mediatranslation pivot table


    /**
     * DbReferenceRepository constructor.
     */
    public function __construct(Reference $reference)
    {
        parent::__construct();
        $this->model = $reference;
        $this->translationType = 'reference';//put in model?
    }



    /**
     * get and prepare the choosen Reference collection with active languages
     * including related nested collections: ReferenceTranslation and Locale
     *
     * @param $id if null get dummy data
     * @param $enabledLocales
     * @return mixed
     */
    public function getSelectedReferences($id,$referenceType){
        if ($id != null){
            $input = "";
            $reference = Reference::with('translations.locale','referencetype',
                'translations.'.$referenceType->slug.'',
                'translations.mediatranslations'
                //,'translations.mediatranslations.images'//!!
                //,'translations.mediatranslations.files'

                )->with(
                array('translations'=>function($query) use ($input){
                    $query->whereHas('locale', function ($q) { // ...1 subquery to filter the photos by related tags' name
                        $q->where('status','<>' ,'disabled');
                    });
                })
            )->findOrFail($id);
        }
        else{
            $reference = new Reference();
            $reference->setRelation('referencetype',$referenceType);
        }

        $this->resetKeyTranslationCollectionByLocaleIdentifier($reference);
        return $reference;
    }


}



