<div class="form-group">
    {!! Form::label('translations['.$key.'][sitemaplistbyparentid][anchortext]', 'lees meer:') !!}
    {!! Form::text('translations['.$key.'][sitemaplistbyparentid][anchortext]', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    <br>
    Optioneel (wordt nu niet gebruikt) :
</div>


<!-- Anchortext Field --->
<div class="form-group">
    {!! Form::label('translations['.$key.'][sitemaplistbyparentid][sitemap_parent_id]', 'parent_id:') !!}
    {!! Form::text('translations['.$key.'][sitemaplistbyparentid][sitemap_parent_id]', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('translations['.$key.'][sitemaplistbyparentid][amount]', 'aantal:') !!}
    {!! Form::text('translations['.$key.'][sitemaplistbyparentid][amount]', null, ['class' => 'form-control']) !!}
</div>








