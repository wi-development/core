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
use Carbon\Carbon;
use Datatables;
use WI\User\Permission;

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


		//dc($previousComponentId);
	    //dc($choosen_component_id);
	    //dc($choosen_referencetype_id);

        //new component is choosen, select new referencetype
        if ($previousComponentId != $choosen_component_id){
            //
	        //EVEN UITGEZET??
	        //$choosen_referencetype_id = null;
        }

        //dc('-----------------');

		//dc($choosen_component_id);
	    //dc($choosen_referencetype_id);
        //component and referencetype are choosen
        if (($choosen_component_id != null) && ($choosen_referencetype_id != null)) {
            //dc('creat');

	        return $this->create($request);
        }

	    //dd('hier');
        $component_list = Component::lists('name','id');

        //component is choosen, get referencetypes
        if ($choosen_component_id != null){
            $referenceType = Component::with('referencetypes')->findOrFail($choosen_component_id)->referencetypes;
            $referencetype_list = $referenceType->lists('name','id'); //selected = Reference->getReferenceTypetListAttribute()
        }

        return view('core::reference.select',compact('component_list','referencetype_list'));

    }



	private function getCreateViewInfo($referenceType){
		return [
			'mainHeader' => $referenceType->name
		];
	}


	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {




        dc('real create');

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
	    //$viewInfo = $this->getCreateViewInfo($referenceType);
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




    private function createPermissionByReferenceType($created_reference){
    	//$referenceType = 12 = bookmarkx


	    //bookmarkx
	    if ($created_reference->referenceType->id == 12){
	    	$permission_name = str_slug($created_reference->translation->name);
			//dc($permission_name);
		    $permission = [
		    	'name'=>'view_bookmark_reference_'.$created_reference->id.'',
			    'label'=>'Toon '.$created_reference->translation->name.'',
			    'description' => 'Mag gebruiker bookmark \''.$created_reference->translation->name.'\' bekijken?',
			    'permissiontype_id' => 3//bookmarks
		    ];
		    $permission = Permission::create($permission);
		    //dc($permission);
	    }

    }

    private function savePermissionByReferenceType($saved_reference){

	    $permission = Permission::where('name','view_bookmark_reference_'.$saved_reference->id.'');
	    //dc($permission->get());
	    //dc('view_bookmark_reference_'.$saved_reference->id.'');
	    //dc($permission_exists = $permission->exists());
		//dc($saved_reference->referenceType->id);

	    $permission_exists = $permission->exists();
	    if ($saved_reference->referenceType->id == 12){
		    $permission_name = str_slug($saved_reference->translation->name);
		    //dc($permission_name);
		    $permissionValues = [
			    'name'=>'view_bookmark_reference_'.$saved_reference->id.'',
			    'label'=>'Toon '.$saved_reference->translation->name.'',
			    'description' => 'Mag gebruiker bookmark \''.$saved_reference->translation->name.'\' bekijken?',
			    'permissiontype_id' => 3//bookmarks
		    ];
		    if ($permission_exists){
		    	$permission->update($permissionValues);
		    }
		    else{
			    $permission = Permission::create($permissionValues);
		    }
	    }
    }

	private function deletePermissionByReferenceType($deleted_reference){
		//dc($deleted_reference);
		//dc($deleted_reference->id);
		$permission = Permission::where('name','view_bookmark_reference_'.$deleted_reference->id.'');


		$permission_exists = $permission->exists();
		//dc($deleted_reference->referenceType->id);
		if ($deleted_reference->referenceType->id == 12){ //niet echt nodig
			if ($permission_exists){
				$permissionId = ($permission->first()->id);
				//dc($permission->id);
				Permission::destroy($permissionId);
			}

		}
//		dc($permission_exists);
//		dd('stop deletePermission');
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


	            //create permissions for bookmark reference
	            $this->createPermissionByReferenceType($created_reference);
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


    private function getEditViewInfo($reference){
    	return [
    		'mainHeader' => $reference->referencetype->name
	    ];
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

	    $viewInfo = $this->getEditViewInfo($reference);
	    //dc($reference);
        return view('core::reference.edit',compact('reference','enabledLocales','component_list','referencetype_list','returnToSitemap','viewInfo'));
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

	            //save or create permissions for bookmark reference
	            $this->savePermissionByReferenceType($reference);


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
	    $action = DB::transaction(function () use ($id) {
		    try {
			    $reference = Reference::with('translation')->findOrFail($id);
			    $this->deletePermissionByReferenceType($reference);
			    Reference::destroy($id);

			    if (request()->ajax()) {
				    $data = ['status' => 'succes', 'statusText' => 'Ok', 'responseText' => 'Referentie \''.$reference->translation->name.'\' is verwijderd'];
				    return response()->json($data, 200);
			    }
			    Flash::success('De gebruiker ' . $id . '');
		    }
		    catch (\Exception $e) {
			    if (request()->ajax()){
				    $data = ['status' => 'succes', 'statusText' => 'Fail', 'responseText' => '' . $e->getMessage() . ''];
				    return response()->json($data, 400);
			    }
			    Flash::error('Delete is mislukt!<br>' . $e->getMessage() . ' ' . $id . '');
		    }
	    });

	    if(request()->ajax()){
		    return $action;
	    }
	    return redirect()->back();









    }





    /*
     * CUSTOM (bookmarks)
     * */

	public function getComponentIndex($component_name){


		//<a class="btn-link" href="{{route('admin::reference.create.fromsitemap', ['sitemap_id' => $sitemap->id,'component_id' => $component->id,'referencetype_id' => $referencetype->id])}}">'{{$referencetype->name}}' toevoegen</a><br>

		//dc($sitemap_parent_id);
		$tableConfig = [];
		$tableConfig['allowSortable'] = false;
		$test = 'header test';
		//$tableConfig['header'] = ''.$component_name.'';
		$tableConfig['header'] = 'Bookmark overzicht';
		$tableConfig['customSearchColumnValues'] = "['online','pending_review','concept']";



		//CREATE route reference.create.fromComponentIndex[{component_id}/{referencetype_id}]
			//eigenlijk referencetype = bookmarks pakken, maar hoe dan component_id???
			$components = Component::with('referencetypes')->where('name',''.$component_name.'')->get();

			$aantal_components = (count($components)); //component_name

			//Altijd dit of 0
			$component_id = 0;
			$referencetype_id = 0;
			if ($aantal_components == 1){
				//dc('er is 1 '.$component_name.' is gevonden');
				$component_id = $components[0]->id;
				//dc($components[0]->referencetypes);
				$aantal_referencetypes = (count($components[0]->referencetypes));
				if ($aantal_referencetypes == 1){
					$referencetype_id = $components[0]->referencetypes[0]->id;
				}else{
					dc('meerdere referencetypes voor component '.$component_name.', toon keuze menu type banner');
				}
			}
			elseif ($aantal_components > 1) {
				dd('meerdere components met dezelfde naam ('.$component_name.')?');
			}
			else{
				dd('component \''.$component_name.'\' bestaat niet?');
			}

			if (($component_id != 0) && ($referencetype_id != 0)){

			}
			else{
				dd('toon keuze ofzo');
			}
		//END CREATE


		$allowed_child_templates = [];//onzin
		$breadcrumbAsHTML = 'onzin';
		$sitemap = collect();
		$sitemap->id = 1;
		return view('core::reference.componentIndex',compact('sitemap','component_name','breadcrumbAsHTML','tableConfig','component_id','referencetype_id'));
		//dc($sitemap->template->id);
		$template = "TEMPLASTE";

		//Gz18jBTf9sSi8epjsVzZy1UlbR2RPJpBx6IxNTEc

	}


	public function componentIndexData(Request $request,$component_name)
	{
		/*
		 * get sitemap with urlpath etc
		 * */

		//$charts = Chart::with('company','updated_by_user')->get();



		//DIT MOET GEWOON VIA REFERENCETYPE = BOOKMARK
		$references = Reference::with(['translation','referencetype','updated_by_user'])
			->whereHas('components', function ($component) use ($component_name) { // ...1 subquery to filter the photos by related tags' name
				$component->where('name', ''.$component_name.'');
			})->get();



		//dd($references);

		$datatable =  Datatables::of($references);

		$datatable->addColumn('action', function ($reference) {
			$r = "<table><tr><td style='border:none'>";
			$r .= '<a href="'.route('admin::reference.edit',['id'=>$reference->id]).'" class="btn btn-success btn-labeled fa fa-pencil mar-rgt">Wijzigen</a>';
			$r .= "</td><td  style='border:none'>";
			$r .= "<a class=\"btn btn-danger btn-labeled fa fa-trash-o\" onclick=\"wiDeleteReference(".$reference->id.")\">Verwijderen TD</a>";
			$r .= '</td></td></table>';
			return $r;
		});

		$var = '';
		$datatable->editColumn('status', function ($test) use ($var) {

			$statusValue = $test->status;
			$labelValue = 'label-'.$test->status.'';

			//label-concept
			//label-pending_review
			if ($test->status == 'pending_review'){
				$statusValue = 'pending';
			}
			if ($test->status == 'public'){
				//$statusValue = 'pun';
				$labelValue = 'label-success';
			}
			if ($test->status == 'blueprint'){
				//$statusValue = 'pun';
				$labelValue = 'label-info';
			}
			return "<span class=\"labelx badge label-table ".$labelValue."\">".$statusValue."</span>";
		});

		$now = new Carbon();
		$datatable->editColumn('updated_at', function ($test) use ($now) {

			//$test->updated_at ? with(new Carbon($test->updated_at))->diffForHumans() : ''
			$updated_at = with(new Carbon($test->translation->updated_at));
			if (($updated_at->isSameDay($now)) || $updated_at->isYesterday()){
				$retval = $updated_at->diffForHumans();
			}
			else{
				$retval = "".$test->translation->updated_at->formatLocalized('%A %d %B');
			}
			return $retval;

			$retval .= " op ".$test->translation->updated_at->formatLocalized('%A %d %B');
			$retval .= "<div class=\"extraDataUIT\" style='display:none;'>";
			$retval .= "<br><date><i class=\"fa fa-clock-o\" aria-hidden=\"true\"></i> ";
			$retval .= $test->updated_at->formatLocalized('%a %d %B');
			$retval .= $test->updated_at->format(', h:i');
			$retval .= "</date>";
			$retval .= "</div>";
			return $retval;
		});


		return $datatable->make(true);
	}


	public function createCalledComponentIndex($component_id,$referencetype_id)
	{

		//dd('createCalledComponentIndex');

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

			/*
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
			*/
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

		//dc($reference->referencetype);


		$viewInfo = $this->getCreateViewInfo($reference->referencetype);
		//waar mag deze referenceType allemaal staan
		//referenceType = standaard banner, mag staan in Components: banner rechts, banner links
		$component_list = ReferenceType::with('components')->findOrFail($reference->referencetype->id)->components->lists('name','id');
		$returnToSitemap = null;
		return view('core::reference.create',compact('reference','enabledLocales','component_list','referencetype_list','returnToSitemap','viewInfo'));
	}



}
