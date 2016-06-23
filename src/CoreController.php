<?php
namespace WI\Core;
use App\Http\Controllers\Controller;
use WI\User\User;

class CoreController extends Controller {


  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index()
  {
    $users = User::all();
    return view('core::index',compact('core'));
  }
}