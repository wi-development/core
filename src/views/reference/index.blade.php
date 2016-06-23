@extends('dashboard::layouts.master')

@section('content')

        <!--Page Title-->
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <div id="page-title">
            <h1 class="page-header text-overflow">Dashboard</h1>

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
        </ol>
        <!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
        <!--End breadcrumb-->




        <!--Page content-->
        <!--===================================================-->
        <div id="page-content">

            @include('flash::message')



            <div class="panel">

                <div class="panel-heading">
                    <div class="panel-control">
                        <div class="btn-group">

                            <button data-target="#demo-chat-body" data-toggle="collapse" type="button" class="btn btn-default hidden"><i class="fa fa-chevron-down"></i></button>
                            <button data-toggle="dropdown" class="btn btn-default" type="button"><i class="fa fa-gear"></i></button>

                            <a class="btn btn-warning btn-labeled fa fa-save btn-lg" href="{{ route('admin::reference.create') }}">New reference</a>

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
                        Alle references
                    </h3>

                </div>

                <!-- Foo Table - Filtering -->
                <!--===================================================-->

                <div class="panel-body">
                    @include('flash::message')
                    <table id="demo-foo-filtering" class="table table-bordered table-hover toggle-circle" data-page-size="10">
                        <thead>
                        <tr>
                            <th data-type="numeric">#</th>
                            <th>reference {id,t_id}</th>
                            <th>Component</th>
                            <th>ReferenceType</th>
                            <th data-type="numeric">translation id</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th data-type="numeric" class="hidden-xs hidden-sm hidden-md">Created at by</th>
                            <th data-sort-ignore="true">edit</th>

                        </tr>
                        </thead>
                        <div class="pad-btm form-inline">
                            <div class="row">
                                <div class="col-sm-6 text-xs-center">
                                    <div class="form-group">
                                        <label class="control-label">Components</label>
                                        <select id="demo-foo-filter-status" class="form-control">
                                            <option value="">Toon alles</option>
                                            <option value="header">Header</option>
                                            <option value="banners homepage">Banners homepage</option>
                                            <option value="slider">Slider</option>
                                            <option value="banners links">Banners links</option>
                                            <option value="banners rechts">Banners rechts</option>
                                            <option value="footer">Footer</option>
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
                        @foreach($references as $key => $reference)
                            <?php $cnt = ($key+1);if ($pagination)$cnt = $cnt+(($references->currentPage()-1)*$references->perPage());?>
                            <tr>
                                <td>{{$cnt}}</td>
                                <td>{{$reference->id}}, {{$reference->system_name}}</td>
                                <td>{{($reference->components->implode('name', ', '))}}</td>
                                <td>{{($reference->referencetype->name)}} <br>translations->{{($reference->referencetype->slug)}}()</td>
                                @foreach($reference->translations as $key1 => $translation)
                                    <td>{{$translation->id}}</td>
                                    <td>{{$translation->name}}</td>
                                    <td>{{str_limit($translation->content, $limit = 100, $end = '...')}}</td>
                                @endforeach

                                <td data-value="{{$reference->updated_at->timestamp}}" class="hidden-xs hidden-sm hidden-md">
                                    {{$reference->created_at->diffForHumans()}}
                                    {{$reference->created_at->formatLocalized('%A %d %B %Y')}} by
                                    {{$reference->updated_by_user_id}}
                                </td>
                                <td>
                                    <a href="{{route('admin::reference.edit', ['id' => $reference->id])}}"
                                       class="btn btn-primary btn-labeled fa fa-save"
                                    >Edit</a>

                                    {{ Form::open(['method'=>'DELETE', 'route'=>array('admin::reference.destroy',$reference->id,'&name='.$reference->system_name.'')])  }}
                                    {{ Form::submit('Deletex', ['class' => 'btn btn-danger btn-xs']) }}
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
        <!--===================================================-->
        <!--End page content-->


    </div>

    @endsection

@section('scripts.footer')
    <!--FooTable [ OPTIONAL ]-->
    <script src="{{config('wi.dashboard.theme_path')}}/vendor/fooTable/dist/footable.all.min.js"></script>

    <!--FooTable Example [ SAMPLE ]-->
    <script src="{{config('wi.dashboard.theme_path')}}/js/demo/tables-footable.js"></script>
@endsection
