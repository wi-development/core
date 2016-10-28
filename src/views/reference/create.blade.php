@extends('dashboard::layouts.master')

@section('content')

        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <h1 class="page-header text-overflow">Bookmark {{--$viewInfo['mainHeader']--}} toevoegen</h1>

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
            <li class="hidden"><a href="{{route('admin::component.index')}}">Alle components</a></li>
            <li><a href="{{ route('admin::reference.component.index',['component_name' => 'bookmarks']) }}">bookmark overzicht</a></li>
            <li class="active">bookmark wijzigen</li>
            <li class="hidden active">reference toevoegen</li>
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->




        <!--Page content-->
        <!--===================================================-->
        <div id="page-content">
            <div class="row">
                <?php
                $frmHeader = "Bookmark toevoegen";
                ?>
                <!-- BASIC FORM ELEMENTS -->
                @if(isset($reference->test_dummy_data) && ($reference->test_dummy_data)) {{--for dummy data--}}

                    {{ Form::model($reference,['method'=>'POST', 'route'=>array('admin::reference.store'), 'class'=>'forxm-horizontal foxrm-padding']) }}
                @else
                    {{ Form::open(['route'=>array('admin::reference.store'), 'class'=>'form-horizontal form-padding']) }}
                @endif

                    @include('errors.reference')

                @include('core::reference.partials.form', ['submitButtonText' => 'Publiceren','frmHeader' => ''.$frmHeader.''])
               {!! Form::close() !!}
                <!-- END BASIC FORM ELEMENTS -->

            </div>
        </div>
        <!--===================================================-->
        <!--End page content-->




@endsection
