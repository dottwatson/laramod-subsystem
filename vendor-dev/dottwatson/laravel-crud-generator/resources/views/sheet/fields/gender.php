<?php $options['choices'] = config('common.genders') ?>
<div class="row">
    <div class="col-md-12">
        <?php if(isset($options['label'])): ?>
            <strong><?=($options['label'] ?? '')?>: </strong>
        <?php endif ?>
        <?php foreach($options['choices'] as $cKey=>$cValue): ?>
            <?php if($cKey == $value): ?>
                <?php $value = $cValue; break; ?>
            <?php endif ?>
        <?php endforeach ?>
        <?=$cValue?>
    </div>
</div>
