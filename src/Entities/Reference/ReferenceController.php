<?php

namespace WI\Core\Entities\Reference;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReferenceRequest;


#use App\Reference;
use Illuminate\Support\Facades\Route;
use WI\Core\Entities\Reference\Reference;

#use App\Component;
use WI\Core\Entities\Component\Component;

#use App\ReferenceType;
use WI\Core\Entities\ReferenceType\ReferenceType;

#use App\Sitemap;
#TODO

use WI\Core\Repositories\Reference\ReferenceRepositoryInterface;
#TDO

use Debugbar;
use DB;
use Flash;
use Illuminate\Pagination\LengthAwarePaginator;
use WI\Sitemap\Sitemap;

class ReferenceController extends Controller
{

    private $reference;
    //private $locale;

    public function __construct(ReferenceRepositoryInterface $reference)
    {
        $this->reference = $reference;


        //$this->sitemap->getEnabledLocales()


        //$this->sitemap->enabledLocales;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        //echo $this->reference->getTEST();
        //dd('test');
        $references = Reference::whereHas('translations', function($referenceTranslation) {  // 1 query for photos with...
            //$q->select('referencetranslations.id','referencetranslations.reference_id', 'referencetranslations.locale_id', 'referencetranslations.name');
            //$referenceTranslation->where('referencetranslations.name','LIKE','%Slider%');       // TODO nu alle locales
            //$referenceTranslation->orWhere('referencetranslations.content','LIKE','%Deutsch%'); // TODO nu alle locales
            }
            ,'>', 0 //count
            )
            ->with(['translations' => function($hasMany) {  // 1 query for photos with...
                $hasMany->whereHas('locale', function ($locale) { // ...1 subquery to filter the photos by related tags' name
                    $locale->where('languageCode', ''.app()->getLocale().'');
                });
                $hasMany->with('locale');
            },'components','referencetype'])
            //->whereIn('id', [1, 5])
            //->paginate(100);
            ->get();
        //dc($references);

            //->get();
        $pagination = ($references instanceof LengthAwarePaginator);

        return view('core::reference.index',compact('references','pagination'));
    }


    public function selectReferenceTypeBeforeCreate(Request $request)
    {
        $previousComponentId = $request->old('component_id');

        $choosen_component_id = $request->get('component_id');// from select
        $choosen_referencetype_id = $request->get('referencetype_id');// select

        $request->flashOnly('component_id'); //flash input to the session


        //new component is choosen, select new referencetype
        if ($previousComponentId != $choosen_component_id){
            $choosen_referencetype_id = null;
        }

        //component and referencetype are choosen
        if (($choosen_component_id != null) && ($choosen_referencetype_id != null)) {
            //dc('creat');
            return $this->create($request);
        }


        $component_list = Component::lists('name','id');

        //component is choosen, get referencetypes
        if ($choosen_component_id != null){
            $referenceType = Component::with('referencetypes')->findOrFail($choosen_component_id)->referencetypes;
            $referencetype_list = $referenceType->lists('name','id'); //selected = Reference->getReferenceTypetListAttribute()
        }

        return view('core::reference.select',compact('component_list','referencetype_list'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


        //dc('real create');

        //get choosen referencetype
        try{
            //from select
            if (!(is_null($request->input('referencetype_id')))) {
                $choosen_component_id = $request->input('component_id');
                $choosen_referencetype_id = $request->input('referencetype_id');
                $choosenReferenceType = ReferenceType::findOrFail($choosen_referencetype_id);
            }
            //from url
            else{
                //TODO
                $choosen_template_slug = $request->route()->parameter('template_slug') ? $request->route()->parameter('template_slug') : null;
                $choosenReferenceType = '';///TODOTemplate::where('slug',$choosen_template_slug)->firstOrFail();
            }
        }catch (\Exception $e){
            Flash::error('Error: '.$e->getMessage().' <br><br>referenceType bestaat niet!');
            return redirect()->route('admin::reference.create');
        }



        //$choosen_component_

        //components


        $enabledLocales = $this->reference->getEnabledLocales();
        $reference = $this->reference->getSelectedReferences(null,$choosenReferenceType);//error null
        $reference->test_dummy_data = true;

        $reference->choosen_component_id = $choosen_component_id; //select selected
        //dc($reference);

        //$referencetype_list
        $referencetype_list = ReferenceType::lists('name','id'); //selected = Reference->getReferenceTypetListAttribute()

        //waar mag deze referenceType allemaal staan
        //referenceType = standaard banner, mag staan in Components: banner rechts, banner links
        $component_list = ReferenceType::with('components')->findOrFail($reference->referencetype->id)->components->lists('name','id');

        $returnToSitemap = null; //voor nu
        return view('core::reference.create',compact('reference','enabledLocales','component_list','referencetype_list','returnToSitemap'));

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCalledFromSitemap($sitemap_id,$component_id,$referencetype_id)
    {
        //dc($sitemap_id.'/'.$component_id.'/'.$referencetype_id);
        //dc('real create');
        //return "view";
        //get choosen referencetype
        try{


            //from route
            $choosen_component_id = $component_id;
            $choosen_referencetype_id = $referencetype_id;
            $choosenReferenceType = ReferenceType::findOrFail($choosen_referencetype_id);
            //dd('jaja');
            $returnToSitemap = $sitemap_id;
            if ($sitemap_id != null){
                //dc('call from sitemap: '.$sitemapid.'');
                $sitemap = Sitemap::with(['translations' => function ($q) {  // 1 query for photos with...
                    $q->whereHas('locale', function ($q) { // ...1 subquery to filter the photos by related tags' name
                        $q->where('languageCode', '' . app()->getLocale() . '');
                        //$q->where('status', 'enabled');
                    });
                }
                ]);
                $returnToSitemap = $sitemap->findOrFail($sitemap_id);
            }
        }catch (\Exception $e){
            Flash::error('Error: '.$e->getMessage().' <br><br>referenceType bestaat niet!');
            return redirect()->route('admin::reference.create');
        }



        //$choosen_component_

        //components


        $enabledLocales = $this->reference->getEnabledLocales();
        $reference = $this->reference->getSelectedReferences(null,$choosenReferenceType);//error null
        $reference->test_dummy_data = false;

        $reference->choosen_component_id = $choosen_component_id; //select selected
        //dc($reference);

        //$referencetype_list
        $referencetype_list = ReferenceType::lists('name','id'); //selected = Reference->getReferenceTypetListAttribute()

        //waar mag deze referenceType allemaal staan
        //referenceType = standaard banner, mag staan in Components: banner rechts, banner links
        $component_list = ReferenceType::with('components')->findOrFail($reference->referencetype->id)->components->lists('name','id');

        return view('core::reference.create',compact('reference','enabledLocales','component_list','referencetype_list','returnToSitemap'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReferenceRequest $request)
    {


        $request->merge(array(
            'created_by_user_id' => auth()->user()->id,
            'updated_by_user_id' => auth()->user()->id
        ));

        DB::transaction(function () use ($request) {
            try
            {
                $enabledLocales = $this->reference->getEnabledLocales();

                $created_reference = Reference::create($request->all());
                $referenceType = $created_reference->referencetype;

                $created_reference = $this->reference->getSelectedReferences($created_reference->id,$referenceType);

                foreach($enabledLocales as $key => $enabledLocale){
                    $localeRequest = array_add($request->translations[$enabledLocale->languageCode]
                                    ,'locale_id', //no foreign key in view
                                    $enabledLocale->id);

                    if (isset($localeRequest['content'])){
                        $localeRequest['content'] = clean($request->translations[$enabledLocale->languageCode]['content']);
                    }
                    //translation
                    $created_translation = $created_reference->translations()->create($localeRequest);
                    //dc($created_translation);

                    //dc($referenceType->slug);
                    //translation->template
                    $created_translation->{$referenceType->slug}()->create($request->translations[$enabledLocale->languageCode][$referenceType->slug]);

                    //mediatranslation
                    $this->reference->syncThisTranslationMediaTranslation(
                        $created_translation->id,
                        $request->translations[$enabledLocale->languageCode],
                        $created_translation);

                }

                $preparedRequest = [];
                foreach($request->input('component_list') as $key => $id) {
                    $preparedRequest[$id] = ['order_by_number' => $key];
                }
                $created_reference->components()->attach($preparedRequest);
            }
            catch (\Exception $e)
            {
                dd($e->getMessage());
                //send mail with subject "db import failed" and body of $e->getMessage()
            }
        });

        Flash::success('Your Reference translation has been created!');
        //return redirect()->to('admin/reference');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,$sitemapid=null)
    {
        /*
        Debugbar::startMeasure('render','Time for rendering');
        Debugbar::stopMeasure('render');
        Debugbar::addMeasure('now', LARAVEL_START, microtime(true));
        Debugbar::measure('My long operation', function() use ($id,$enabledLocales){
            // Do something…
            $reference = $this->reference->getSelectedReferences($id,$enabledLocales);
        });
        */



        $returnToSitemap = $sitemapid;
        if ($sitemapid != null){
            //dc('call from sitemap: '.$sitemapid.'');
            $sitemap = Sitemap::with(['translations' => function ($q) {  // 1 query for photos with...
                $q->whereHas('locale', function ($q) { // ...1 subquery to filter the photos by related tags' name
                    $q->where('languageCode', '' . app()->getLocale() . '');
                    //$q->where('status', 'enabled');
                });
                }
            ]);
            $returnToSitemap = $sitemap->findOrFail($sitemapid);
        }




        //$template = Sitemap::with('template')->findOrFail($id)->template;
        $referenceType = Reference::with('referencetype')->findOrFail($id)->referencetype;

        $enabledLocales = $this->reference->getEnabledLocales();

        $reference = $this->reference->getSelectedReferences($id,$referenceType);
        //dd('test 1');

        $this->reference->groupMediaCollectionByFieldName($reference->translations);
        //dc($reference);

            //$referencetype_list
            $referencetype_list = ReferenceType::lists('name','id'); //selected = Reference->getReferenceTypetListAttribute()


        //waar mag deze referenceType allemaal staan
        //referenceType = standaard banner, mag staan in Components: banner rechts, banner links
        $component_list = ReferenceType::with('components')->findOrFail($reference->referencetype->id)->components->lists('name','id');
        //dc(ReferenceType::with('components')->findOrFail($reference->referencetype->id)->components);
        //$component_list = Component::lists('name','id'); //selected = Reference->getComponentListAttribute()
        //

        //dc($reference);
        //return "view edit";

        return view('core::reference.edit',compact('reference','enabledLocales','component_list','referencetype_list','returnToSitemap'));
        //return view('user::edit',compact('user','roles','locales'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->merge(array('updated_by_user_id' => auth()->user()->id));
        $retval = DB::transaction(function () use ($request,$id) {
            try
            {
                $enabledLocales = $this->reference->getEnabledLocales();

                $referenceType = Reference::with('referencetype')->findOrFail($id)->referencetype;

                $reference = $this->reference->getSelectedReferences($id,$referenceType);

                $reference->update($request->all());
                //dd($reference);
                foreach($enabledLocales as $key => $enabledLocale){

                    $localeRequest = array_add($request->translations[$enabledLocale->languageCode]
                        ,'locale_id', //no foreign key in view
                        $enabledLocale->id);

                    //dc($localeRequest);
                    //translation
                    if (isset($localeRequest['content'])){
                        $localeRequest['content'] = clean($request->translations[$enabledLocale->languageCode]['content']);
                    }


                    $reference->translations[$enabledLocale->languageCode]->update($localeRequest);

                    //translation->template
                    $reference->translations[$enabledLocale->languageCode]->{$referenceType->slug}->update($request->translations[$enabledLocale->languageCode][$referenceType->slug]);

                    //mediatranslation
                    $this->reference->syncThisTranslationMediaTranslation($reference->translations[$enabledLocale->languageCode]->id,$request->translations[$enabledLocale->languageCode],$reference->translations[$enabledLocale->languageCode]);
                }

                $preparedRequest = [];
                foreach($request->input('component_list') as $key => $id) {
                    $preparedRequest[$id] = ['order_by_number' => $key];
                }
                $reference->components()->sync($preparedRequest);
                Flash::success('Your Banner translation has been updated!');

            }
            catch (\Exception $e)
            {
                dc($e->getCode().' - '.$e->getMessage());
                Flash::error('Your Banner translation has NOT been updated! <br>'.$e->getMessage().'<br>'.$e->getCode().'');
                //dc($e->getMessage());
            }
        });

        //return "VIEW";
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        //
        DB::transaction(function () use ($request,$id) {
            try
            {
                $test  = Reference::destroy($id);
                Flash::success('Reference \' '.$request['name'].' \'  '.$test.' is succesfully deleted');
            }
            catch (\Exception $e)
            {
                Flash::error('Niet gelukt: '.$e->getMessage());
            };
        });
        return redirect()->back();
        return "view destroy";
    }
}
