<?php $value = ''; ?>
<div class="row">
    <div class="col-md-12">
        <?php if(isset($options['label'])): ?>
            <strong><?=($options['label'] ?? '')?>: </strong>
        <?php endif ?>
        <?php foreach($options['choices'] as $cKey=>$cValue): ?>
            <?php if($cKey == $value): ?>
                <?=$cValue;?>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>