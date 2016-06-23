

<div class="col-lg-7">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{$frmHeader}}</h3>
        </div>
        <div class="panel-body">
            @include('errors.list')
            @include('flash::message')


                    <!--- Name Field -->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {{ Form::label('name', 'Name:',['class' => 'control-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control']) }}
            </div>


            <div class="form-grou{{ $errors->has('order_by_number') ? ' has-error' : '' }}p">
                {{ Form::label('order_by_number', 'Order by:',['class' => 'control-label']) }}
                {{ Form::text('order_by_number', null, ['class' => 'form-control']) }}
            </div>

            <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
                {{ Form::label('parent_id', 'Parent template:',['class' => 'control-label']) }}
                {{ Form::select('parent_id', $template_list,null, ['class' => 'form-control']) }}
            </div>


            <div class="form-group{{ $errors->has('component_list') ? ' has-error' : '' }}">
                {{ Form::label('component_list[]','Components:',['class' => 'control-label']) }}

                {!! Form::checkBoxRelatedUnrelated('component_list[]',$template->components,$template->unrelated_components,['id'=>'component_list','class'=>'xform-control']) !!}
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-group">
                {{ Form::submit($submitButtonText, ['class' => 'btn btn-primary']) }}
            </div>
        </div>
    </div>
</div>




