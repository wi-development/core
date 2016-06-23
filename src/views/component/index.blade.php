@extends('dashboard::layouts.master')
@section('content')


        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <h1 class="page-header text-overflow">Components</h1>

            <!--Searchbox-->
            <div class="searchbox">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search..">
							<span class="input-group-btn">
								<button class="text-muted" type="button"><i class="fa fa-search"></i></button>
							</span>
                </div>
            </div>
        </div>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End page title-->


        <!--Breadcrumb-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <ol class="breadcrumb">
            <li><a href="{{ route('admin::dashboard') }}">dashboard</a></li>
            <li><a href="{{ route('admin::template.index') }}">component overzicht</a></li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->




        <!--Page content-->
        <!--===================================================-->
        <div id="page-content">





            <div class="panel">

                <div class="panel-heading">
                    <div class="panel-control">
                        <div class="btn-group">

                            <button data-target="#demo-chat-body" data-toggle="collapse" type="button" class="btn btn-default hidden"><i class="fa fa-chevron-down"></i></button>
                            <button data-toggle="dropdown" class="btn btn-default" type="button"><i class="fa fa-gear"></i></button>

                            <a class="btn btn-warning btn-labeled fa fa-save btn-lg" href="{{ route('admin::component.create') }}">New Component</a>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="#">Available</a></li>
                                <li><a href="#">Busy</a></li>
                                <li><a href="#">Away</a></li>
                                <li class="divider"></li>
                                <li><a data-target="#demo-chat-body" class="disabled-link" href="#" id="demo-connect-chat">Connect</a></li>
                                <li><a data-target="#demo-chat-body" href="#" id="demo-disconnect-chat">Disconect</a></li>
                            </ul>
                        </div>
                    </div>
                    <h3 class="panel-title">
                        Alle components
                    </h3>

                </div>

                <!-- Foo Table - Filtering -->
                <!--===================================================-->

                <div class="panel-body">
                    @include('flash::message')
                    <table id="demo-foo-filtering" class="table table-bordered table-hover toggle-circle" data-page-size="10">
                        <thead>
                        <tr>
                            <th data-sort-initial="true">Name</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th data-sort-ignore="true">edit</th>
                        </tr>
                        </thead>
                        <div class="pad-btm form-inline">
                            <div class="row">
                                <div class="col-sm-6 text-xs-center">
                                    <div class="form-group">
                                        <label class="control-label">Status</label>
                                        <select id="demo-foo-filter-status" class="form-control">
                                            <option value="">Toon alles</option>
                                            <option value="online">Online</option>
                                            <option value="pending">Wacht op goedkeuring</option>
                                            <option value="concept">Concept</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-xs-center text-right">
                                    <div class="form-group">
                                        <input id="demo-foo-search" type="text" placeholder="Search" class="form-control" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <tbody>
                        @foreach($components as $key => $component)

                            <tr>
                                <td>{{$component->name}}</td>
                                <td>{{$component->category}}</td>
                                <td>{{$component->type}}</td>
                                <td>
                                    <a href="{{ route('admin::component.edit',[$component->id]) }}"
                                       class="btn btn-primary btn-labeled fa fa-save"
                                    >Wijzigen</a>

                                    {{ Form::open(['method'=>'DELETE', 'route'=>array('admin::component.destroy',$component->id,'&name='.$component->name.'')])  }}
                                    {{ Form::submit('Delete', ['class' => 'btn btn-danger btn-xs']) }}
                                    {{ Form::close() }}

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="6">
                                <div class="text-right">
                                    <ul class="pagination"></ul>
                                </div>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!--===================================================-->
                <!-- End Foo Table - Filtering -->

            </div>









        </div>
        <!--===================================================-->
        <!--End page content-->




@endsection

@section('scripts.footer')
    <!--FooTable [ OPTIONAL ]-->
    <script src="{{config('wi.dashboard.theme_path')}}/vendor/fooTable/dist/footable.all.min.js"></script>

    <!--FooTable Example [ SAMPLE ]-->
    <script src="{{config('wi.dashboard.theme_path')}}/js/demo/tables-footable.js"></script>
@endsection