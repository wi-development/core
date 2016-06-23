@extends('dashboard::layouts.master')
@section('content')

    <!--Page Title-->
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div id="page-title">
        <h1 class="page-header text-overflow">Reference toevoegen</h1>

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
        <li><a href="{{route('admin::dashboard')}}">Dashboard</a></li>
        <li><a href="{{route('admin::component.index')}}">Alle components</a></li>
        <li class="active">Selecteer een reference..</li>
    </ol>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--End breadcrumb-->


    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="panel-body demo-nifty-modal">

            <!--Static Examplel-->
            <div class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" data-dismiss="modal" class="close"><span>×</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title">Selecteer een component</h4>
                        </div>

                        {{ Form::open(['method'=>'GET', 'route'=>array('admin::reference.create'), 'class'=>'forxm-horizontal foxrm-padding']) }}

                        <div class="modal-body">
                        <div class="form-group">
                            {!! Form::label('component_id', 'component:') !!}
                            {!! Form::select('component_id', $component_list,Request::get('component_id'), ['class' => 'form-control']) !!}
                        </div>


                        @if (Request::has('component_id'))

                            component is gekozen nu reference type id
                            <div class="form-group">
                                {!! Form::label('referencetype_id', 'reference type:') !!}
                                {!! Form::select('referencetype_id', $referencetype_list,Request::get('referencetype_id'), ['class' => 'form-control']) !!}
                            </div>

                        @endif


                        </div>

                        <div class="modal-footer">
                            {!! Form::submit('Kies component', ['class' => 'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}
















                    </div>
                </div>
            </div>

        </div>



    </div>
    <!--===================================================-->
    <!--End page content-->

@endsection
