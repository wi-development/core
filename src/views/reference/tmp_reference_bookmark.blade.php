<div form-media-field
     label-value="afbeelding van de banner"
     button-select-value="Selecteer afbeelding"
     button-upload-value="Upload afbeelding"
     field-id="translations[{{$key}}][media][overzicht]"
     locale="{{$key}}"
     field-name="overzicht"
     related-media="{{isset($reference->translations[$key]->media['overzicht']) ? $reference->translations[$key]->media['overzicht'] : ''}}"
     dropzone-message="Sleep hier je afbeelding in"
     xdropzone-mimetypes=".pdf"
     dropzone-max-file-size="2"
     media-type="image"
     class="form-group"
     style="height: 179px;margin-bottom: 15px;xdisplay:none;"
>
</div>




<div class="form-group">
    {!! Form::label('translations['.$key.'][banner][url]', 'url:') !!}
    {!! Form::text('translations['.$key.'][banner][url]', null, ['class' => 'form-control']) !!}
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
    {!! Form::label('translations['.$key.'][banner][target]', 'nieuw venster:') !!}
    {!! Form::text('translations['.$key.'][banner][target]', null, ['class' => 'form-control']) !!}
</div>







