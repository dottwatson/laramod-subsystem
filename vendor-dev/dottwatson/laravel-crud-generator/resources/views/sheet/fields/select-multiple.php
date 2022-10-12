<?php $values = []; ?>
<div class="row">
    <div class="col-md-12">
        <?php if(isset($options['label'])): ?>
            <strong><?=($options['label'] ?? '')?>: </strong>
        <?php endif ?>
        <?php foreach($options['choices'] as $cKey=>$cValue): ?>
            <?php if(is_array($value) && in_array($cKey,$value)): ?>
                <span class="badge badge-primary"><?=$cValue?></span>
            <?php endif ?>
        <?php endforeach ?>
    </div>
</div>