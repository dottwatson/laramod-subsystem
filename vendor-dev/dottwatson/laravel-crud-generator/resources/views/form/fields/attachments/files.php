<?php if(!$attachments->count()): ?>
    <?php if(!$owner): ?>
        <div class="text-center w-100">Nessun allegato presente</div>
    <?php endif; ?>
<?php else: ?>
    <table class="table table-striped table-hover table-sm">
        <thead>
            <tr>
                <th style="width:80px;">&nbsp;</th>
                <th>File</th>
                <th style="width:120px;">Dim.</th>
                <th style="width:120px;">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($attachments as $attachment): ?>
                <?php 
                $removeId   = "remove{$name}{$attachment->getKey()}";
                $removeName = "removed_attachments[{$name}][]";
                $tokenFile  = $instance->getTokenFile($attachment); 
                if($extrafieldsForm){
                    $uniqSheetId    = uniqid('sh');
                    $sheetClass     = $handler->getconfig('crud.sheet',Dottwatson\CrudGenerator\Sheet\AttachmentExtraFieldsSheet::class);
                    $sheet          = (new $sheetClass)->setModel($attachment);

                    $uniqEditId    = uniqid('ea');
                    $editForm = $form->getFormBuilder()->create(\Dottwatson\CrudGenerator\Crud\Forms\AttachmentExtraFieldsEditForm::class,[]);
                    $editForm->setModel($attachment)->fromExtrafieldsForm($extrafieldsForm);                          
                }
                ?>
                <tr>
                    <td>
                        <div class="input-group-append mt-1">
                            <div class="input-group-text bg-danger px-2 py-1">
                                <label class="form-check-label ml-0 mr-4" for="<?=$removeId?>"><i class="fa fa-trash"></i></label>
                                <input type="checkbox"  id="<?=$removeId?>"  class="form-check-input ml-4" name="<?=$removeName?>" value="<?=$attachment->getKey() ?>">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="mt-2">
                            <?=$attachment->title?>
                            <br><small class="text-muted"><?=$attachment->original_name ?></small>
                        </div>
                        <?php if($extrafieldsForm): ?>
                            <div class="collapse" id="<?=$uniqSheetId?>">
                                <?=$sheet->render() ?>
                            </div>
                            <div class="collapse" id="<?=$uniqEditId?>">
                                <?=form($editForm)?>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="mt-2"><?=$attachment->readableSize?></div>
                    </td>
                    <td class="text-right">
                        <div class="btn btn-group">
                            <?php if($extrafieldsForm): ?>
                                <button type="button" data-toggle="collapse" data-target="#<?=$uniqSheetId?>" class="btn btn-primary btn-sm"><i class="fa fa-chevron-down"></i></button>
                                <button  type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#<?=$uniqEditId?>"><i class="fa fa-edit"></i></button>
                            <?php endif; ?>
                            <a download class="btn btn-primary btn-sm" target="_blank" href="<?=$instance->getFileUrl('download',['_tokenFile'=>$tokenFile])?>"><i class="fa fa-download"></i></a>
                        </div>    
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
