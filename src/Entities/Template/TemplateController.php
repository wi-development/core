<?php
namespace WI\Core\Entities\Template;

use App\Http\Controllers\Controller;
use WI\Core\Entities\Component\Component;


use Illuminate\Http\Request;
use App\Http\Requests\TemplateRequest;
use DB;
use Flash;



class TemplateController extends Controller {


  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index()
  {
    $templates = Template::all();
    return view('core::template.index',compact('templates'));
  }


  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //$components = Component::lists('name','id');
    $unrelated_components = Component::whereHas('templates', function ($query) use ($id) {
      $query->where('templates.id', $id);
    }, '=', 0)->get();



    //$template = Template::findOrFail($id);
    $template = Template::with(['components' => function($q) {  // 1 query for photos with...
      /*$q->whereHas('components', function ($q) { // ...1 subquery to filter the photos by related tags' name
          $q->where('templates.id', '1');
          //$q->where('status', 'enabled');
      });*/
    }])->findOrFail($id);//->get()



    $template->unrelated_components = $unrelated_components;

    //$merge_components = collect(['component' => $template->components,'unrelated_component' => $template->unrelated_components]);
    $template_list = Template::lists('name','id');
    $template_list->prepend('Root');

    return view('core::template.edit',compact('template','template_list'));
    foreach ($template->components as $key => $component){
      dc($component->pivot->order_by_number);
      dc($component->name);
      dc($component->order_by_number);
    }
    //dd($template->components->all());
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(TemplateRequest $request, $id)
  {
    $template = Template::findOrFail($id);
    DB::transaction(function () use ($request,$template) {
      try {
        $template->update($request->all());
        if ($request->has('component_list')) {
          $syncArray = [];
          foreach ($request->input('component_list') as $key => $id) {
            //$syncArray[$id] = ['order_by_name' => $request->input('order_by_name')[$id]];
            $syncArray[$id] = ['order_by_number' => $key];
          }
          //$preparedRequest = $request->input('component_list');
          $preparedRequest = $syncArray;
          //dc($preparedRequest);
          $template->components()->sync($preparedRequest);
          Flash::success('Your Template has been updated!');
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
    $template = new Template();

    //$components = Component::lists('name','id');
    $components = Component::all();
    $template->components = [];
    $template->unrelated_components = $components;
    //dc($template);
    $template_list = Template::lists('name','id');
    $template_list->prepend('Root');
    return view('core::template.create',compact('template','template_list'));
  }



  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(TemplateRequest $request)
  {
    //rollback on error
    DB::transaction(function () use ($request) {
      try
      {
        $created_template = Template::create($request->all());
        $created_template->components()->attach($request->input('component_list'));
        Flash::success('Your Template has been created!');
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

    DB::transaction(function () use ($request,$id) {
      try
      {
        $test  = Template::destroy($id);
        Flash::success('Template \' '.$request['name'].' \'  '.$test.' is succesfully deleted');
      }
      catch (\Exception $e)
      {
        Flash::error('Niet gelukt: '.$e->getMessage());
      };
    });
    return redirect()->back();
  }

}