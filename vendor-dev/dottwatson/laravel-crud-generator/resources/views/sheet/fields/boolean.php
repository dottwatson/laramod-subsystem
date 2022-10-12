<div class="row">
    <div class="col-md-12">
        <?php if(isset($options['label'])): ?>
            <strong><?=($options['label'] ?? '')?>: </strong>
        <?php endif ?>
        <?php if($value == 0): ?>
            <i class="fa fa-times text-danger"></i>
        <?php else: ?>
            <i class="fa fa-check text-success"></i>
        <?php endif; ?>
    </div>
</div>