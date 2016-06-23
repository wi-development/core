<div id="mytoolbar"></div>

<div class="form-group">
    {!! Form::label('translations['.$key.'][content]', 'teksxxt:') !!}
    {!! Form::textarea('translations['.$key.'][content]',(((isset($sitemap->translations[$key]->content))) ? htmlspecialchars($reference->translations[$key]->content) : null), ['class' => 'form-control editor','rows' => '5']) !!}
</div>



<div class="form-group">
<br>
    Optioneel (wordt nu niet gebruikt) :
</div>
<div class="form-group">
    {!! Form::label('translations['.$key.'][banner][anchortext]', 'lees meer:') !!}
    {!! Form::text('translations['.$key.'][banner][anchortext]', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('translations['.$key.'][banner][url]', 'url:') !!}
    {!! Form::text('translations['.$key.'][banner][url]', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('translations['.$key.'][banner][target]', 'nieuw venster:') !!}
    {!! Form::text('translations['.$key.'][banner][target]', null, ['class' => 'form-control']) !!}
</div>







