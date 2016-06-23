<?php

namespace WI\Core\Entities\Component;

use App\Http\Controllers\Controller;
use App\Http\Requests\ComponentRequest;

use Illuminate\Http\Request;
use App\Http\Requests;

use WI\Core\Entities\ReferenceType\ReferenceType;

use Flash;
use DB;





class ComponentController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $components = Component::all();
    return view('core::component.index',compact('components'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $component = Component::with(['referencetypes' => function($q) {  // 1 query for photos with...
      /*$q->whereHas('components', function ($q) { // ...1 subquery to filter the photos by related tags' name
          $q->where('templates.id', '1');
          //$q->where('status', 'enabled');
      });*/
    }])->findOrFail($id);//->get()

    $unrelated_reference_types = ReferenceType::whereHas('components', function ($query) use ($id) {
      $query->where('components.id', $id);
    }, '=', 0)->get();

    //dc($unrelated_reference_types);
    //dc($component->referencetypes);
    $component->unrelated_referencetypes = $unrelated_reference_types;
    return view('core::component.edit',compact('component'));
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(ComponentRequest $request, $id)
  {
    DB::transaction(function () use ($request,$id) {
      try {
        $component = Component::findOrFail($id);
        $component->update($request->all());

        if ($request->has('referencetype_list')) {
          $syncArray = [];
          foreach ($request->input('referencetype_list') as $key => $id) {
            //$syncArray[$id] = ['order_by_name' => $request->input('order_by_name')[$id]];
            $syncArray[$id] = ['order_by_number' => $key];
          }
          //$preparedRequest = $request->input('component_list');
          $preparedRequest = $syncArray;
          //dd($preparedRequest);
          $component->referencetypes()->sync($preparedRequest);
          Flash::success('Your Component \''.$request['name'].'\' has been updated!');
        }
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
    $component = new Component();
    //dc($component);
    $referencetypes = ReferenceType::all();
    $component->referencetypes = [];
    $component->unrelated_referencetypes = $referencetypes;
    //dc($component);

    return view('core::component.create',compact('component'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(ComponentRequest $request)
  {
    //rollback on error
    DB::transaction(function () use ($request) {
      try
      {
        $created_component = Component::create($request->all());
        $created_component->referencetypes()->attach($request->input('referencetype_list'));
        Flash::success('Your Component has been created!');

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
  public function destroy($id,Request $request)
  {



    try
    {
      Component::destroy($id);
      Flash::success('Component '.$request['name'].' is succesfully deleted');
    }
    catch (\Exception $e)
    {
      Flash::error('Niet gelukt: '.$e->getMessage());
      //sfr('- Niet gelukt: '.$e->getMessage());
      //send mail with subject "db import failed" and body of $e->getMessage()
    };
    return redirect()->route('admin::component.index');
    //return redirect('admin/component');
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





}
