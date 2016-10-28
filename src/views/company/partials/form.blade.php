

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


            <div class="form-group{{ $errors->has('person') ? ' has-error' : '' }}">
                {{ Form::label('person', 'Person:',['class' => 'control-label']) }}
                {{ Form::text('person', null, ['class' => 'form-control']) }}
            </div>


                <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                    {{ Form::label('address', 'Address:',['class' => 'control-label']) }}
                    {{ Form::text('address', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                    {{ Form::label('zipcode', 'Zipcode:',['class' => 'control-label']) }}
                    {{ Form::text('zipcode', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                    {{ Form::label('city', 'City:',['class' => 'control-label']) }}
                    {{ Form::text('city', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    {{ Form::label('email', 'Email:',['class' => 'control-label']) }}
                    {{ Form::text('email', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                    {{ Form::label('phone', 'Phone:',['class' => 'control-label']) }}
                    {{ Form::text('phone', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                    {{ Form::label('mobile', 'Mobile:',['class' => 'control-label']) }}
                    {{ Form::text('mobile', null, ['class' => 'form-control']) }}
                </div>


                <div class="form-group{{ $errors->has('fax') ? ' has-error' : '' }}">
                    {{ Form::label('fax', 'Fax:',['class' => 'control-label']) }}
                    {{ Form::text('fax', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('formname') ? ' has-error' : '' }}">
                    {{ Form::label('formname', 'Formname:',['class' => 'control-label']) }}
                    {{ Form::text('formname', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('formemail') ? ' has-error' : '' }}">
                    {{ Form::label('formemail', 'Formemail:',['class' => 'control-label']) }}
                    {{ Form::text('formemail', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('kvk') ? ' has-error' : '' }}">
                    {{ Form::label('kvk', 'Kvk:',['class' => 'control-label']) }}
                    {{ Form::text('kvk', null, ['class' => 'form-control']) }}
                </div>


                <div class="form-group{{ $errors->has('facebook') ? ' has-error' : '' }}">
                    {{ Form::label('facebook', 'Facebook:',['class' => 'control-label']) }}
                    {{ Form::text('facebook', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('twitter') ? ' has-error' : '' }}">
                    {{ Form::label('twitter', 'Twitter:',['class' => 'control-label']) }}
                    {{ Form::text('twitter', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('linkedin') ? ' has-error' : '' }}">
                    {{ Form::label('linkedin', 'Linkedin:',['class' => 'control-label']) }}
                    {{ Form::text('linkedin', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group{{ $errors->has('terms_and_conditions') ? ' has-error' : '' }}">
                    {{ Form::label('terms_and_conditions', 'Terms_and_conditions:',['class' => 'control-label']) }}
                    {{ Form::text('terms_and_conditions', null, ['class' => 'form-control']) }}
                </div>


        </div>
        <div class="panel-footer">
            <div class="form-group">
                {{ Form::submit($submitButtonText, ['class' => 'btn btn-primary']) }}
            </div>
        </div>
    </div>
</div>




