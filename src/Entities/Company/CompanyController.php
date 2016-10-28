<?php
namespace WI\Core\Entities\Company;

use App\Http\Controllers\Controller;
use WI\Core\Entities\Component\Component;


use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use DB;
use Flash;
use WI\User\Role;
use Datatables;
use Carbon\Carbon;

class CompanyController extends Controller {


  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index()
  {


  	$companies = Company::all();
    //dc($companies);
	  return view('core::company.index',compact('companies'));
  }




	/*
   * INDEX CONTROLLERS
   */

	public function getAllIndex($sitemap_id = 0){
		//dc($sitemap_parent_id);
		$tableConfig = [];
		$tableConfig['allowSortable'] = false;
		$test = 'header test';
		$tableConfig['header'] = 'Bedrijven';
		//$tableConfig['header'] = 'Alle pagina\'s van \''.$sitemap->translation->name.'\'';
		//$allowed_child_templates = Template::where('parent_id',$sitemap->template->id)->get();
		//$tableConfig['customSearchColumnValues'] = "['online','pending_review','concept']";

		//$roles = Role::all();
		//$roles = ($roles->implode('name','\',\''));
		$tableConfig['customSearchColumnValues'] = "[]";

		//dc($tableConfig);
		if(request()->ajax()){
		}
		else{
		}


		$allowed_child_templates = [];//onzin
		$breadcrumbAsHTML = 'onzin';
		$sitemap = collect();
		$sitemap->id = 1;
		return view('core::company.index',compact('sitemap','allowed_child_templates','breadcrumbAsHTML','tableConfig'));
		//dc($sitemap->template->id);
		$template = "TEMPLASTE";

		//Gz18jBTf9sSi8epjsVzZy1UlbR2RPJpBx6IxNTEc

	}



	/**
	 * Process datatables ajax request.
	 * Used by 'admin.sitemap.menuIndex' via 'this.getMenuIndex()'
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function allIndexData(Request $request,$sitemap_parent_id = 0)
	{
		/*
		 * get sitemap with urlpath etc
		 * */
		//dc($request->all());
		//$charts = Chart::with('company')->get();
		//$users = User::with('roles','locale','company')->get();


/*
		$users = User::leftJoin('role_user','role_user.user_id', '=', 'users.id')
			->rightJoin('roles', 'roles.id','=','role_user.role_id')
			->leftJoin('companies', 'companies.id','=','users.company_id');
		//->leftJoin('sitemaps as sctest', 'sc.sitemap_category_id','=','sctest.id')
		//->leftJoin('sitemaptranslations as sctest_st','sctest.id','=','sctest_st.sitemap_id')

		$users->select([
			'users.id',
			'users.name as user_name',
			'companies.name as company_name',
			'users.updated_at',
			DB::raw("GROUP_CONCAT(roles.name SEPARATOR ', ') as roles_name"),
		]);
		$users->groupBy('users.id');
*/
		//$companies = Company::get();

		$companies = Company::select(['id', 'name', 'person', 'city', 'updated_at']);
		//$users->where('users.id',44);

		//$users = $users->get();
		//dc($users[0]);
		//return "view";





		//$users  = User::with('roles');
		//dc($users);
		//return "view";

		//pluck('name')

		//dd($users[0]->roles->implode('label',','));
		//dd($users[0]->roles->pluck('label')->all());
		//dd($users[0]->roles[0]->label);

		$datatable =  Datatables::of($companies);

		//dd($datatable);

		$datatable->addColumn('action', function ($companies) {
			//<input class="btn btn-cons btn-awesome btn btn-cons btn-awesome btn btn-danger" value="Delete" type="submit">
			$r = "<table><tr><td style='border:none'>";
			$r .= '<a href="'.route('admin::company.edit',['id'=>$companies->id]).'" class="btn btn-success btn-labeled fa fa-pencil mar-rgt">Wijzigen</a>';
			$r .= '</td><td>';
			$r .= "<a class=\"btn btn-danger btn-labeled fa fa-trash-o\" onclick=\"wiDeleteCompany(".$companies->id.")\">Verwijderen</a>";
			$r .= '</td></td></table>';
			return $r;
		});

		$var = '';
		$datatable->editColumn('updated_at', function ($companies) use ($var) {
			//dd($user->updated_at);
			//return 'test';
			//$retval = $user->user_updated_at ? with(new Carbon($user->user_updated_at))->diffForHumans() : '';
			//dd($retval);
			$retval = $companies->updated_at ? with(new Carbon($companies->updated_at))->diffForHumans() : '';
			$retval .= "<div class=\"extraDataxUIT\" style='display:none;'>";
			$retval .= "<br><date><i class=\"fa fa-clock-o\" aria-hidden=\"true\"></i> ";
			$retval .= $companies->updated_at->formatLocalized('%a %d %B');
			$retval .= $companies->updated_at->format(', h:i');
			$retval .= "</date>";
			$retval .= "</div>";

			//dc($user->updated_at);
			//dc($user->updated_at);

			return $retval;
			return $user->roles->implode('label',',');
		});


		//$datatable->filter(function ($query) use ($request) {
		/*			if ($request->has('name')) {
						$query->where('name', 'like', "%{$request->get('name')}%");
					}

					if ($request->has('email')) {
						$query->where('email', 'like', "%{$request->get('email')}%");
					}*/
		//});

		// Global search function
		//if ($keyword = $request->get('search')['value']) {
		//dc($keyword);
		// override users.name global search
		//$datatable->filterColumn('name', 'where', 'like', "$keyword%");
		//$datatable->filterColumn('users_name', 'where', 'like', "%TEST%");

		// override users.id global search - demo for concat
		//$datatable->filterColumn('users.id', 'whereRaw', "CONCAT(users.id,'-',users.id) like ? ", ["%$keyword%"]);
		//}

		return $datatable->make(true);

		dd('asdf');
		//dc($datatable);
		$test = $datatable->make(true);
		//return "view";
		return $test;



		/*sitemap_category*/
		$sitemaps = Sitemap::leftJoin('sitemap_category as sc','sc.sitemap_id', '=', 'sitemaps.id')
			->leftJoin('sitemaps as sctest', 'sc.sitemap_category_id','=','sctest.id')
			->leftJoin('sitemaptranslations as sctest_st','sctest.id','=','sctest_st.sitemap_id')

			/*sitemap_relations*/
			->join('sitemaptranslations as st','st.sitemap_id','= ','sitemaps.id')
			->join('templates as t','t.id','=','sitemaps.template_id')
			->join('users as u','u.id','=','sitemaps.updated_by_user_id')

			/*sitemap_tree*/
			->leftJoin('sitemaps as down1','down1.id','=','sitemaps.parent_id')
			->leftJoin('sitemaptranslations as st1','st1.sitemap_id','=','down1.id')

			->leftJoin('sitemaps as down2','down2.id','=','down1.parent_id')
			->leftJoin('sitemaptranslations as st2','st2.sitemap_id','=','down2.id')

			->leftJoin('sitemaps as down3','down3.id','=','down2.parent_id')
			->leftJoin('sitemaptranslations as st3','st3.sitemap_id','=','down3.id');

		/**Locale*/
		$sitemaps->where('st.locale_id',1)
			->whereRaw('(st1.locale_id = 1 OR st1.locale_id IS NULL)')
			->whereRaw('(st2.locale_id = 1 OR st2.locale_id IS NULL)')
			->whereRaw('(st3.locale_id = 1 OR st3.locale_id IS NULL)')
			->whereRaw('(sctest_st.locale_id = 1  OR sctest_st.locale_id IS null)');
		#AND t.type != 'post'
		$sitemaps->groupBy('sitemaps.id');

		if ($sitemap_parent_id != 0){
			$sitemaps->where('sitemaps.parent_id',$sitemap_parent_id);
		}

		/*order by*/
		//$sitemaps->orderBy('path');

		//uit
		if ($path = $request->get('path')) {
			//app('debugbar')->warning($locaties);
			//$sitemaps->orderBy('ABS(path)'); // additional users.name search
		}


		$sitemaps->select([
			'sitemaps.id',
			DB::raw('CONCAT(\'{"id":\',sitemaps.id,\',\',\'"order_by_number":\',sitemaps.order_by_number,\'}\') as reorderData_id_AND_order_by_number'),
			'sitemaps.created_at',
			'sitemaps.updated_at',
			'sitemaps.status',
			'sitemaps.order_by_number',
			't.name as templateName',
			'u.name as usersname',
			'sitemaps.depth',
			DB::raw('GROUP_CONCAT(DISTINCT(`st`.`name`)) as `testname` ,
                    GROUP_CONCAT(`sctest_st`.`name`) as `tName` ,
                    GROUP_CONCAT(`sctest_st`.`slug`) as `tSlug`'),
			//'st.name as testname',
			//'sctest_st.name as tName',
			//'sctest_st.slug as tSlug',
			//1.2.4.5 for display, hmm..
			DB::raw('CONCAT(\'0\',
                IF (down3.order_by_number IS NULL,\'\',CONCAT(\'.\',down3.order_by_number)),
                IF (down2.order_by_number IS NULL,\'\',CONCAT(\'.\',down2.order_by_number)),
                IF (down1.order_by_number IS NULL,\'\',CONCAT(\'.\',down1.order_by_number)),
                IF (sitemaps.order_by_number IS NULL,\'\',CONCAT(\'.\',sitemaps.order_by_number))
                ) as path'
			),
			//1235 for sorting
			DB::raw("CONCAT('0',
                IF (down3.order_by_number IS NOT NULL AND down3.order_by_number < 10 ,CONCAT('.',down3.order_by_number),''),
                IF (down3.order_by_number IS NOT NULL AND down3.order_by_number > 9 ,CONCAT('.9.',down3.order_by_number),''),

                IF (down2.order_by_number IS NOT NULL AND down2.order_by_number < 10 ,CONCAT('.',down2.order_by_number),''),
                IF (down2.order_by_number IS NOT NULL AND down2.order_by_number > 9 ,CONCAT('.9.',down2.order_by_number),''),

                IF (down1.order_by_number IS NOT NULL AND down1.order_by_number < 10 ,CONCAT('.',down1.order_by_number),''),
                IF (down1.order_by_number IS NOT NULL AND down1.order_by_number > 9 ,CONCAT('.9.',down1.order_by_number),''),

                IF (sitemaps.order_by_number IS NOT NULL AND sitemaps.order_by_number < 10 ,CONCAT('.',sitemaps.order_by_number),''),
                IF (sitemaps.order_by_number IS NOT NULL AND sitemaps.order_by_number > 9 ,CONCAT('.9.',sitemaps.order_by_number),'')
                ) as path1
            "),
			//slug1/slug2/category!!/slug3
			DB::raw('CONCAT(
                IF (st3.slug IS NULL OR st3.slug = \'\' ,\'\',CONCAT(\'/\',st3.slug,\'\')),
                IF (st2.slug IS NULL OR st2.slug = \'\',\'\',CONCAT(\'/\',st2.slug,\'\')),
                IF (st1.slug IS NULL OR st1.slug = \'\',\'\',CONCAT(\'/\',st1.slug,\'\')),
                IF (t.name = \'Nieuwsbericht\',
	                IF (sitemaps.depth = 2 AND sctest_st.slug IS NOT NULL,CONCAT(\'/\',sctest_st.slug),\'\')
,               \'\'),
                IF (st.slug IS NULL OR st.slug = \'\',\'/\',CONCAT(\'/\',st.slug,\'\'))
                ) as urlPath'
			)
		]);

		//dc($sitemaps->get());
		//return "view";
		//dc($sitemaps->get());
		//dc($sitemaps->pluck('testname')->all());
		//return "view";



		$datatable =  Datatables::of($sitemaps);
		return $datatable->make(true);
		$datatable->setRowId('sortable_'.'{{$id}}');
		//$datatable->orderColumn('path');
		// $datatable->orderColumn('path', 'email $1');


		//kanweg
		$datatable->addColumn('action', function ($sitemap) {

			$r = "<a class=\"btn btn-success btn-labeled-x\" href=\"".route('admin::sitemap.edit',['id'=>$sitemap->id])."\" >
                    <i class=\"fa fa-pencil fa-1x\"></i> editxxx</a> <br>";


			$r .= "<a class=\"btn btn-primary btn-labeled-x setTable\" onclick=\"wiLoad(".$sitemap->id.")\">
                    <i class=\"fa fa-level-down fa-1x\"></i>  sub pagins's</a> <br>";

			$r .= "<a class=\"btn btn-warning btn-labeled-x setTable\" onclick=\"wiDuplicate(".$sitemap->id.")\">
                    <i class=\"fa fa-copy fa-1x\"></i> copy</a> <br>";

			$r .= "<a class=\"btn btn-danger btn-labeled-x setTable\" onclick=\"wiDelete(".$sitemap->id.")\">
                    <i class=\"fa fa-trash fa-1x\"></i> delete</a> <br>";

			$r .= "<a class=\"btn btn-default btn-md btn-labeled-x\" href=\"".$sitemap->urlPath."\" target=\"_blank\">
                    <i class=\"fa fa-search fa-1x\"></i> preview</a>";

			return $r;
		});



		/*$datatable->editColumn('path', function ($test) {
			return $test->path;
		});
	*/
		$datatable->editColumn('testname', function ($test) {



			$r = "<div class=\"extraData\" style='display:none;'>";

			//$r .= "<span class='pulxl-right'>".$test->urlPath."</span><br>";
			$r .= "<a class=\"btn btn-success btn-labeled-x\" href=\"".route('admin::sitemap.edit',['id'=>$test->id])."\" >
                    <i class=\"fa fa-pencil fa-1x\"></i> edit</a> ";


			$r .= "<a class=\"btn btn-primary btn-labeled-x setTable\" onclick=\"wiLoad(".$test->id.")\">
                    <i class=\"fa fa-level-down fa-1x\"></i>  sub pagins's</a> ";

			$r .= "<a class=\"btn btn-warning btn-labeled-x setTable\" onclick=\"wiDuplicate(".$test->id.")\">
                    <i class=\"fa fa-copy fa-1x\"></i> copy</a> ";

			$r .= "<a class=\"btn btn-danger btn-labeled-x setTable\" onclick=\"wiDeleteSitemap(".$test->id.")\">
                    <i class=\"fa fa-trash fa-1x\"></i> deleteX</a> ";

			$r .= "<a class=\"btn btn-default btn-md btn-labeled-x\" href=\"".$test->urlPath."\" target=\"_blank\">
                    <i class=\"fa fa-search fa-1x\"></i> preview</a> ";

			//$r .= "<a class=\"btn btn-default btn-md btn-labeled-x\" href=\"".$test->urlPath."\" target=\"_blank\"
			//        data-placement=\"right\" data-toggle=\"tooltip\" data-original-title=\"".$test->urlPath."\">
			//        <i class=\"fa fa-info fa-1x\"></i> info</a> ";

			$r .= "</div>";


			/*
						$urlString = str_replace('/',' » ',$test->urlPath);
						$urlString = htmlentities($urlString, ENT_QUOTES);

						$pos = strrpos($urlString, "»");
						if ($pos === false) { // note: three equal signs
							// not found...
							$pos1 = 'asdf';
						}
						else{
							$pos1 = ($pos+1);
							//$pos++;
							$urlString = substr_replace($urlString, '<strong>', ($pos1), 0);

						}
						//unset($pos);
			*/


			$urlStringStart = str_replace('/',' » ',(str_limit($test->urlPath, strrpos($test->urlPath, "/"),' » ')));

			return "".$urlStringStart."<strong>".$test->testname."</strong><br><br>".$r." ";
		});

		$datatable->editColumn('status', function ($test) use ($sitemap_parent_id) {

			$statusValue = $test->status;
			if ($test->status == 'pending_review'){
				$statusValue = 'pending';
			}
			return "<span class=\"labelx badge label-table label-".$test->status."\">".$statusValue."</span>";
		});


		$datatable->editColumn('created_at', function ($test) {

			//$retval .= $test->created_at ? with(new Carbon($test->created_at))->format('l jS \\of F Y h:i:s A') : '';
			//Carbon::setLocale('fr');
			//$retval .= $test->created_at->formatLocalized('%l %jS \\of %F %Y h:i:s %A')."<br>";


			$retval = $test->created_at ? with(new Carbon($test->created_at))->diffForHumans() : '';
			$retval .= "<div class=\"extraData\" style='display:none;'>";
			$retval .= "<br><date><i class=\"fa fa-clock-o\" aria-hidden=\"true\"></i> ";
			$retval .= $test->created_at->formatLocalized('%a %d %B');
			$retval .= $test->created_at->format(', h:i');
			$retval .= "</date>";
			$retval .= "</div>";
			return $retval;
		});

		$datatable->editColumn('updated_at', function ($test) {
			$retval = $test->updated_at ? with(new Carbon($test->updated_at))->diffForHumans() : '';
			$retval .= "<div class=\"extraData\" style='display:none;'>";
			$retval .= "<br><date><i class=\"fa fa-clock-o\" aria-hidden=\"true\"></i> ";
			$retval .= $test->updated_at->formatLocalized('%a %d %B');
			$retval .= $test->updated_at->format(', h:i');
			$retval .= "</date>";
			$retval .= "</div>";
			return $retval;
		});

		if ($status = $request->get('status')) {
			app('debugbar')->warning($status);
			//console.info('zoeke status');
			//$datatable->where('sitemaps.status', 'like', "%{$status}%"); // additional users.name search
		}


		if ($status = $request->get('pathxx')) {
			//app('debugbar')->warning($locaties);
			//$datatable->orderBy('ABS(path)'); // additional users.name search
		}


		$datatable->orderColumn('path', 'path1 $1');
		//$datatable->orderColumn();


		return $datatable->make(true);
	}



	/**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {


    //$company = Company::findOrFail($id);
    $company = Company::findOrFail($id);//->get()

    return view('core::company.edit',compact('company'));
    //dd($company->components->all());
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(CompanyRequest $request, $id)
  {

  	$company = Company::findOrFail($id);
    DB::transaction(function () use ($request,$company) {
      try {
        $company->update($request->all());
	      Flash::success(''.$company->name.' is aangepast');
      }
      catch (\Exception $e)
      {
        Flash::error('Niet gelukt: '.$e->getMessage());
        //send mail with subject "db import failed" and body of $e->getMessage()
      }
    });
    return redirect()->back();
  }



  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //dc("asdf");
	  return view('core::company.create');

  	$company = new Company();

    //$components = Component::lists('name','id');
    $components = Company::all();
	  return "view";
	  $company->components = [];
    $company->unrelated_components = $components;
    //dc($company);
    $company_list = Company::lists('name','id');
    $company_list->prepend('Root');
    return view('core::company.create',compact('company','company_list'));
  }



  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(CompanyRequest $request)
  {
    //rollback on error
    DB::transaction(function () use ($request) {
      try
      {
        $created_company = Company::create($request->all());
        //$created_company->components()->attach($request->input('component_list'));
        Flash::success('Your Company has been created!');
      }
      catch (\Exception $e)
      {
        Flash::error('Niet gelukt: '.$e->getMessage());
      }
    });
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

	  //Auth::logout();
	  $action = DB::transaction(function () use ($id) {
		  try {
			  $company = Company::findOrFail($id);
			  //EVEN UIT
			  Company::destroy($id);
			  if (request()->ajax()) {
				  $data = ['status' => 'succes', 'statusText' => 'Ok', 'responseText' => 'Bedrijf \''.$company->name.'\' is verwijderd.'];
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

}