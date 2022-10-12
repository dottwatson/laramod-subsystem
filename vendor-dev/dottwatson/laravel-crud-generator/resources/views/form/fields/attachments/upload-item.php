<div class="dz-preview dz-file-preview">
    <div class="row">
        <div class="col-md-1 col-sm-3">
            <img class="img-fluid" data-dz-thumbnail src="<?=asset('img/dropzone-upload-file.png')?>" />
        </div>
        <div class="col-md-9 col-sm-7">
            <div>
                <span class="dz-size" data-dz-size></span> - <span class="dz-filename"><span data-dz-name></span></span>
            </div>
            <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>
            <input type="hidden" class="file-upload-id" name="attachments[<?=$name?>][id][]" value="">
            <div class="attachment-extrafields">
                <?php
                if($extrafieldsForm){
                    echo form($extrafieldsForm);
                }
                ?>
            </div>
        </div>
        <div class="col-md-2 col-sm-2">
            <div><a class="dz-remove btn btn-danger text-white " href="javascript:undefined;" data-dz-remove=""><i class="fa fa-trash"></i></a></div>
            <br><br>
            <div class="dz-success-mark"><span><i class="fa fa-check fa-2x text-success"></i></span></div>
            <div class="dz-error-mark"><span><i class="far fa-times-circle fa-2x text-danger"></i></span></div>
        </div>
    </div>
</div>
