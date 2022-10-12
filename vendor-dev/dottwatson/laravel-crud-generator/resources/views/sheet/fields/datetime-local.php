<div class="row">
    <div class="col-md-12">
        <?php if(isset($options['label'])): ?>
            <strong><?=($options['label'] ?? '')?>: </strong>
        <?php endif ?>
        <?=Carbon\Carbon::parse($value)->format('d/m/Y H:i:s') ?>
    </div>
</div>