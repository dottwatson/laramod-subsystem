<div class="fresh-uploads mb-3" id="fresh<?=$uniqId?>">
    <div class="dzone<?=$uniqId?> dropzone"  data-dropzone-options='<?=json_encode($dropzoneSettings,JSON_FORCE_OBJECT)?>' data-dropzone-url="<?=$uploadUrl?>">
        <div class="dz-message d-flex flex-column">
            <i class="fa fa-upload fa-2x"></i>
            Drag &amp; Drop here or click
        </div>
        <div class="dropzone-previews"></div>
    </div>
</div>    
