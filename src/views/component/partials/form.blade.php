<div class="col-lg-7">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">{{$frmHeader}}</h3>
        </div>
        <div class="panel-body">
            @include('errors.list')
            @include('flash::message')

            <!--- Name Field --->
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('name', 'Name:',['class' => 'control-label']) !!}
                {!! Form::text('name', null, ['class' => 'form-control']) !!}
            </div>


            <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                {!! Form::label('category', 'Category:',['class' => 'control-label']) !!}
                {!! Form::text('category', null, ['class' => 'form-control']) !!}
            </div>


            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                {!! Form::label('type', 'Type:',['class' => 'control-label']) !!}
                {!! Form::text('type', null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group{{ $errors->has('referencetype_list') ? ' has-error' : '' }}">
                {!! Form::label('referencetype_list[]','Reference Types:',['class' => 'control-label']) !!}
                {!! Form::checkBoxRelatedUnrelated('referencetype_list[]',$component->referencetypes,$component->unrelated_referencetypes,['id'=>'referencetype_list','class'=>'xform-control']) !!}
            </div>
        </div>
        <div class="panel-footer">
            <div class="form-group">
                {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>
</div>
