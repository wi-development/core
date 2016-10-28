@extends('dashboard::layouts.master')

@section('content')



        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <h1 class="page-header text-overflow">Bedrijf</h1>

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
            <li><a href="{{ route('admin::company.index') }}">bedrijven</a></li>
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

                            <a class="btn btn-warning btn-labeled fa fa-save btn-lg" href="{{ route('admin::company.create') }}">Nieuw bedrijf</a>
                        </div>
                    </div>
                    <h3 class="panel-title">
                        Alle bedrijven
                    </h3>

                </div>

                <!-- Foo Table - Filtering -->
                <!--===================================================-->

                <div class="panel-body">
                    @include('flash::message')
                    <table id="demo-foo-filtering" class="table table-bordered table-hover toggle-circle" data-page-size="10">
                        <thead>
                            <tr>
                                <th data-sort-initial="true">Naam</th>
                                <th data-type="numeric">Contact</th>
                                <th data-type="numeric">Plaats</th>
                                <th data-sort-ignore="true" colspan="2">Acties</th>
                            </tr>
                        </thead>
                        <div class="pad-btm form-inline hide">
                            <div class="row">
                                <div class="col-sm-6 text-xs-center hide">
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
                        @foreach($companies as $key => $company)
                            <tr>
                                <td>{{$company->name}}</td>
                                <td>{{$company->person}}</td>

                                <td>{{$company->city}}</td>

                                <td width="10px;">

                                    <a href="{{ route('admin::company.edit',[$company->id]) }}"
                                       class="btn btn-success btn-labeled fa fa-save"
                                    >Wijzigen</a>

                                </td>
                                <td width="10px;">
                                    {{ Form::open(['method'=>'DELETE', 'route'=>array('admin::company.destroy',$company->id,'&name='.$company->name.''),'class'=>'pull-right'])  }}
                                    {{ Form::submit('Delete', ['class' => 'btn btn-cons btn-awesome btn btn-danger']) }}
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