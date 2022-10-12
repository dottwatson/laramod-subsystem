<?php 
$attachments = $model->attachments->filter(function($item)use($name){
    return $item->type == $name;
});
?>

<?php if(!$attachments->count()): ?>
    Nessun allegato presente
<?php else: ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>File</th>
                <th>Dim.</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($attachments as $attachment): ?>
                <tr>
                    <td><?=$attachment->title?> <i class="text-primary fa fa-info-circle" title="<?=$attachment->original_name ?>"></i></td>
                    <td><?=$attachment->readableSize?></td>
                    <td class="text-right"><a download target="_blank" href="<?=route('backend.attachments.download',['id'=>$attachment->id])?>"><i class="fa fa-download"></i></a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
