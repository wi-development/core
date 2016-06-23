<h2>reference type banner</h2>
<div class="form-group">BANNER DEFAULT</div>
<div class="form-group">
    {!! Form::label('translations['.$key.'][banner][anchortext]', '[banner][anchortext]:') !!}
    {!! Form::text('translations['.$key.'][banner][anchortext]', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('translations['.$key.'][banner][url]', '[banner][url]:') !!}
    {!! Form::text('translations['.$key.'][banner][url]', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('translations['.$key.'][banner][target]', '[banner][target]:') !!}
    {!! Form::text('translations['.$key.'][banner][target]', null, ['class' => 'form-control']) !!}
</div>

