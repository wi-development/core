@extends('dashboard::layouts.master')

@section('content')

    <!--Page Title-->
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <div id="page-title">
        <h1 class="page-header text-overflow">Pagina wijzigen</h1>

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
        <li><a href="{{ route('admin::reference.index') }}">template overzicht</a></li>
        <li class="active">reference wijzigen</li>
    </ol>
    <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <!--End breadcrumb-->




    <!--Page content-->
    <!--===================================================-->
    <div id="page-content">
        <div class="row">

            <?php
            $frmHeader = "Wijzigen '{$reference->translations->first()->name}' reference";
            ?>
            @include('errors.list')
            <!-- BASIC FORM ELEMENTS -->
            {{ Form::model($reference,['method'=>'PATCH', 'route'=>array('admin::reference.update',$reference->id), 'class'=>'form-horizontal form-padding']) }}
                {{--@include('errors.reference')--}}
                @include('core::reference.partials.form', ['submitButtonText' => 'Publiceren','frmHeader' => ''.$frmHeader.''])
            {{ Form::close() }}
            <!-- END BASIC FORM ELEMENTS -->

        </div>
    </div>
    <!--===================================================-->
    <!--End page content-->

@endsection

