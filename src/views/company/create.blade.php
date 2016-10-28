@extends('dashboard::layouts.master')
@section('content')

    <!--Page Title-->
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div id="page-title">
        <h1 class="page-header text-overflow">Bedrijf toevoegen</h1>

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
        <li><a href="{{route('admin::company.all.index')}}">Alle bedrijven</a></li>
        <li class="active">pagina wijzigen</li>
    </ol>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--End breadcrumb-->




    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="row">

            <!-- BASIC FORM ELEMENTS -->
            <?php
            $frmHeader = "Bedrijfsgegevens";
            ?>

            {{ Form::open(['route'=>array('admin::company.store'), 'class'=>'forxm-horizontal foxrm-padding']) }}

            @include('core::company.partials.form', ['submitButtonText' => 'Bedrijf toevoegen','frmHeader'=>''.$frmHeader.''])


            {{ Form::close() }}
            <!-- END BASIC FORM ELEMENTS -->

        </div>
    </div>
    <!--===================================================-->
    <!--End page content-->



@endsection
