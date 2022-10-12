<div class="row">
    <div class="col-md-12">
        <?php if(isset($options['label'])): ?>
            <strong><?=($options['label'] ?? '')?>: </strong>
        <?php endif ?>
        <?php $value = ($value != '')?$value:($options['default'] ?? '') ?>
        <?=nl2br($value)?>
    </div>
</div>