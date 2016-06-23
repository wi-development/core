{!! Form::hidden('active_language_tab', (session()->has('active_language_tab') ? session()->get('active_language_tab') : 'nl'), ['class' => 'form-control','id' => 'active_language_tab']) !!}





<div class="col-lg-7">
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-control">
                @if ($returnToSitemap != null)
                <i class="fa fa-hand-o-left fa-lg fa-fw"></i>
                    <a href="{{route('admin::sitemap.edit', ['id' => $returnToSitemap->id])}}">
                        <span class="label label-purple">
                        terug naar '{{$returnToSitemap->translations->first()->name}}'
                        </span>
                    </a>

                @endif
            </div>
            <h3 class="panel-title">{{$frmHeader}}</h3>

        </div>
        <div class="panel-body">






            <div class="form-tab" ng-app="ng.wi.cms">

                @include('flash::message')

                        <!-- Nav tabs || $key == $translation->locale->identifier -->
                <ul class="nav nav-tabs nav-justified-off" role="tablist">
                    @foreach($enabledLocales as $locale)
                        <?php

                        $key = $locale->languageCode;
                        $tClass = "";
                        if ($key==(session()->has('active_language_tab') ? session()->get('active_language_tab') : 'nl')){$tClass = " active";}
                        if (empty($reference->translations[''.$locale->languageCode.'']->id)){$tClass .= " new-locale";}
                        if (array_key_exists($key,$errors->getMessages())){$tClass .= " has-error";}
                        ?>
                        <li role="presentation" class="{{$tClass}}"><a href="#{{$key}}" aria-controls="{{$key}}" role="tab" data-toggle="tab">
                                {{$locale->name}}
                            </a></li>
                    @endforeach
                </ul>



                <!-- Tab panes -->
                <div ng-controller="ModalDemoCtrl" class="tab-content">
                    <?php
                    //foreach($sitemap->translations as $key => $translation){
                    foreach($enabledLocales as $locale){
                    $key = $locale->languageCode;
                    $language_id = $key;//for error list //or $key
                    $tClass = "";if ($key==(session()->has('active_language_tab') ? session()->get('active_language_tab') : 'nl')){$tClass = " active";}?>

                    <div role="tabpanel" class="tab-pane{{$tClass}} ng-cloak-uit" id="{{$key}}">

                        <div class="panel-body">


                            @include('errors.referencetranslation')

                                    <!--- Name Field --->
                            <div class="form-group">
                                {!! Form::label('translations['.$key.'][name]', 'naam:') !!}
                                {!! Form::text('translations['.$key.'][name]', null, ['class' => 'form-control']) !!}
                            </div>


                            <!-- Content Field
                            staat in db_template_name
                            <div class="form-group">
                                {!! Form::label('translations['.$key.'][content]', 'Content x'.$key.'') !!}
                                {!! Form::text('translations['.$key.'][content]', null, ['class' => 'form-control']) !!}
                            </div>
                            -->
                            {{--$reference->referencetype--}}

                            @include('core::reference.'.$reference->referencetype->db_template_name.'')


                            {{--$post_type--}}
                            {{--@include('admin.sitemap.'.$template->db_table_name.'')--}}

                        </div>






                    </div>
                    <?php
                    }//endforeach ?>









                </div>







            </div>




        </div>
    </div>
</div>
<div class="col-lg-5">
    <div class="panel">


        <div class="panel">
            <div class="modal-headexr panel-heading">
                <div class="panel-control">
                    <i class="fa fa-thumbs-o-up fa-lg fa-fw"></i>



                    <?php
/*
                    if (!(isset($sitemap->status))){
                        $statusValue = 'new';
                        $statusButtonValue = 'hidden';
                    }
                    else{
                        $statusValue = $sitemap->status;
                        $statusButtonValue = 'bg-gray-dark';
                        if ($sitemap->status == 'pending_review'){
                            $statusButtonValue = 'bg-warning';
                            $statusValue = 'wacht op goedkeuring';
                        }
                        if ($sitemap->status == 'online'){
                            $statusButtonValue = 'bg-success';
                        }

                    }*/
                    ?>




                    <span class="badge bg-success">success</span>
                    <span class="label label-purple">Administrator</span>
                </div>
                <h3 class="panel-title">Publiceren</h3>


            </div>

            <div class="panel-body" style="padding-top: 0px; padding-bottom: 0px;">
                <div class="panel-body">

                    <div class="form-group">
                        {!! Form::label('referencetype_id', 'referencetype:') !!}
                        {!! Form::select('referencetype_id', $referencetype_list,($reference->referencetype->id ? $reference->referencetype->id : null), ['class' => 'form-control','autocomplete'=>'off']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('component_list[]', 'Hier mag "'.$reference->referencetype->name.'" staan: (gebruikt in components)') !!}
                        {!! Form::select('component_list[]', $component_list,($reference->choosen_component_id ? $reference->choosen_component_id : null), ['class' => 'form-control','multiple','autocomplete'=>'off']) !!}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Close</button>
                {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary btn-default pull-right ']) !!}
            </div>
        </div>






    </div>

</div>


@section('css.head')
    @include('core::partials.head_tinymce')
@endsection

@section('scripts.footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.js"></script>
@endsection




