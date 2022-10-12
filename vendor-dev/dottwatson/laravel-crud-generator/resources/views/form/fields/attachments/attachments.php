<?php if ($options['wrapper'] !== false): ?>
    <div <?= $options['wrapperAttrs'] ?> >
<?php endif; ?>


<div class="card uploads" id="<?=$uniqId?>">
    <div class="card-header">
        <div class="float-left">
            <?php 
            $uploadLimit    = $handler->getConfig('limit'); 
            $badgeLimitText = $uploadLimit == 1000000 ? '&infin;':$uploadLimit;

            if($attachments->count() == 0){
                $uploadBadgeColor = 'bg-gray';
            }
            elseif($attachments->count() >= $uploadLimit){
                $uploadBadgeColor = 'badge-success';
            }
            else{
                $uploadBadgeColor = 'bg-warning';
            }
            ?>
            <span class="badge <?=$uploadBadgeColor?>"><?=$attachments->count()?>&nbsp;/&nbsp;<?=$badgeLimitText?></span>
            <?php if ($options['label'] != ''): ?>
                <?= Form::customLabel($name, $options['label'], $options['label_attr']) ?>
            <?php endif; ?>
        </div>
        <div class="float-right">
            <button class="btn btn-primary btn-xs" type="button" id="add<?=$uniqId?>" data-toggle="collapse" data-target="#collapsibelfilesUploader<?=$uniqId?>" aria-expanded="false" aria-controls="collapsibelfilesUploader<?=$uniqId?>"><i class="fa fa-plus"></i></button>
        </div>
    </div>
        <div class="card-body">
            <input type="hidden" name="_tokenAttachment" value="<?=$_tokenAttachment?>">
            <div id="collapsibelfilesUploader<?=$uniqId?>" class="collapse">
                <?php include(__DIR__.'/upload-area.php'); ?>
            </div>    
            <div class="already-uploaded-files" id="files<?=$uniqId?>">
                <div class="row already-uploaded-file">
                    <?php include(__DIR__.'/files.php') ?>
                </div>
            </div>
            <div class="d-none dz-model-template" id="model<?=$uniqId?>">
                <?php include(__DIR__.'/upload-item.php'); ?>
            </div>
        </div>
    </div>
<?php include $formBuilderViewPath.'/help_block.php'; ?>

<?php if ($options['wrapper'] !== false): ?>
    </div>
<?php endif; ?>
