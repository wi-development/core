<div class="form-group">BANNER DEFAULT</div>
<div form-media-field
     label-value="label overzicht value"
     button-select-value="Selecteer afbeelding"
     button-upload-value="Upload afbeelding"
     field-id="translations[{{$key}}][media][overzicht]"
     locale="{{$key}}"
     field-name="overzicht"
     related-media="{{isset($reference->translations[$key]->media['overzicht']) ? $reference->translations[$key]->media['overzicht'] : ''}}"
     dropzone-message="Drop file here INPUT TYPE MEDIA 1 translations[{{$key}}][overzicht]"
     xdropzone-mimetypes=".pdf"
     dropzone-max-file-size="50"
     media-type="image"
     style="height: 179px;margin-bottom: 15px;xdisplay:none;"
>
</div>
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







