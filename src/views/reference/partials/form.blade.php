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
    <script>
        var apiRoute = '{{route('admin::api.media.modal')}}';
        var formMediaTemplateUrl = '/js/wi/angular/form_media_field.html';
        var modalCreateMediaTemplateUrl = '/js/wi/angular/dropzone1.php';


        ///admin/media/modal_select_media
        //var modalSelectMediaUrl = '/admin/media/modal_select_media';
        var modalSelectMediaUrl = '{{route('admin::api.modal.select.media')}}';

        var modalCreateMediaUrl = '{{route('admin::api.modal.create.media')}}';
        var mediaUploadUrl = '{{route('admin::media.upload')}}';

        var formMediaFieldEditMediaUrl = '{{route('admin::media.index')}}';



        //alert(modalSelectMediaUrl);

        //templateUrl: '/admin/media/modal_select_media',
        //alert(apiRoute);
    </script>
    <script src="{{config('wi.dashboard.theme_path')}}/js/jquery_ui_1_11_4/jquery-ui.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-animate.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-sanitize.js"></script>
    <script src="//angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.14.3.js"></script>

    <script src="/js/wi/angular/angular-dragdrop.js"></script>
    <script src="/js/wi/angular/myAngular.js"></script>
    <script src="/js/wi-form.js"></script>
@endsection

@section('scripts.footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/dropzone.js"></script>
    <script>

        /*
        //

        $(function () {
            $window = $(window), $("body.home").length && (tinymce.init({
                selector: "textarea",
                width: 752,
                height: 261,
                resize: 1,
                plugins: ["advlist", "autolink", "lists", "link", "image", "charmap", "print", "preview", "anchor", "searchreplace", "visualblocks", "code", "fullscreen", "insertdatetime", "media", "table", "contextmenu", "paste", "imagetools"],
                toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image"
            }), tinymce.init({
                selector: "textarea",
                width: "100%",
                resize: !1,
                plugins: ["autoresize", "advlist", "autolink", "lists", "link", "image", "charmap", "print", "preview", "anchor", "searchreplace", "visualblocks", "code", "fullscreen", "insertdatetime", "media", "table", "contextmenu", "paste", "imagetools"],
                autoresize_max_height: 161,
                menubar: !1,
                elementpath: !1,
                statusbar: !1,
                toolbar: "bold italic | alignleft aligncenter alignright alignjustify",
                content_style: "body { padding-bottom: 0 !important; }"
            })), $("body.pricing").length && (tinymce.PluginManager.load("moxiemanager", "/pro-demo/moxiemanager/plugin.min.js"), tinymce.PluginManager.load("powerpaste", "/pro-demo/powerpaste/plugin.min.js"), tinymce.PluginManager.load("tinymcespellchecker", "/pro-demo/tinymcespellchecker/plugin.min.js"), tinymce.PluginManager.load("a11ychecker", "/pro-demo/a11ychecker/plugin.min.js"), tinymce.PluginManager.load("mentions", "/pro-demo/mentions/plugin.min.js"), tinymce.init({
                selector: "textarea",
                plugins: ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu moxiemanager imagetools powerpaste tinymcespellchecker a11ychecker mentions"],
                mentions_fetch: function (e, n) {
                    var t = ["Andrew Roberts", "Amy Chen", "Tim Thatcher", "Jeff Olson", "John Hummelstad", "David Spreng", "Gary Kovacs", "Misha Logvinov", "Michael Fromin", "Lisa Newsome", "Ketaki Joshi", "Jennifer Knowlton", "Wynne Vick", "Robert Collings", "Jessica Lee", "Colin Westacott", "Ken Hodges", "Ivan White", "Richard Garcia", "Shirin Abbaszadeh", "Joakim Lindkvist", "Johan Sörlin", "Damien Fitzpatrick", "Brett Henderson", "David Wood", "Andrew Herron", "Jack Mason", "Dylan Just", "Morgan Smith", "Malcolm Sharman", "Mark Terry", "Mike Chau", "Maurizio Napoleoni", "Mark Ludlow", "Andreas Huemer", "Joshua Haines", "George Wilson", "Luke Butt", "David Sakko", "Jeremy Carver", "Dayne Lean", "James Johnson", "Ben Kolera", "Sneha Choudhary", "Anna Harrison", "Bill Roberts", "Therese Lavelle", "Irene Goot", "Mai Tran", "John Doe", "Jane Doe"];
                    t = $.map(t, function (e) {
                        var n = e.replace(/ /g, "").toLowerCase();
                        return {id: n, name: n, fullName: e}
                    }), t = $.grep(t, function (n) {
                        return 0 === n.name.indexOf(e.term)
                    }), n(t)
                },
                toolbar: "insertfile a11ycheck undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image",
                autosave_ask_before_unload: !1,
                content_style: "h1 {font-size: 32px; color: #0089ee}",
                width: 752,
                height: 261,
                resize: !1,
                powerpaste_allow_local_images: !0,
                spellchecker_rpc_url: "https://spelling.tinymce.com/ephox-spelling",
                spellchecker_api_key: "h22wb7h8xi78b4fyo46hhx5k7fbh46vt5f6yqmvd492iy00c"
            })), $(".not-found").length && ga("send", {
                hitType: "event",
                eventCategory: "Website",
                eventAction: "404",
                eventLabel: document.location.href
            }), $("body.custom-builds").length && ($(".checkbox").checkbox(), $(".switch")["switch"](), $(".select-none").on("click", function () {
                $(this).siblings(".checkbox").removeClass("selected")
            }), $(".select-all").on("click", function () {
                $(this).siblings(".checkbox").addClass("selected")
            }), $(".custom-builds-submit-row button").on("click", function () {
                var e = {core: "core_standalone"};
                $(".custom-builds .checkbox").each(function () {
                    var n = $(this);
                    n.attr("data-name") && n.hasClass("selected") && (e[n.attr("data-name")] = !0)
                });
                var n = $(".custom-builds-form").empty();
                Object.keys(e).forEach(function (t) {
                    $('<input type="hidden" value="' + e[t] + '" name="' + t + '">').appendTo(n)
                }), n.submit()
            })), $("body.language-packages").length && ($(".checkbox").checkbox(), $("tr").on("click", function (e) {
                "TD" === $(e.target).prop("tagName") && $(this).find(".checkbox").toggleClass("selected")
            }), $(".select-none").on("click", function () {
                $(".checkbox").removeClass("selected")
            }), $(".select-all").on("click", function () {
                $(".checkbox").addClass("selected")
            }), $(".language-packages-submit-row button:last-child").on("click", function () {
                var e = $(".language-packages-form").empty();
                $(".checkbox").each(function () {
                    var n = $(this);
                    n.hasClass("selected") && $('<input type="hidden" value="' + n.attr("data-value") + '" name="' + n.attr("data-name") + '">').appendTo(e)
                }), e.submit()
            })), $(".download-track").on("click", function () {
                ga("send", {
                    hitType: "event",
                    eventCategory: "Website",
                    eventAction: "download",
                    eventLabel: $(this).attr("data-version")
                })
            }), $(".click-track").on("click", function () {
                ga("send", {hitType: "event", eventCategory: "Website", eventAction: "click", eventLabel: $(this).text()})
            })
        });

        */

    </script>
@endsection




