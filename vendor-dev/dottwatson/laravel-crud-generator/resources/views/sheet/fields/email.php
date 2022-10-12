<div class="row">
    <div class="col-md-12">
        <?php if(isset($options['label'])): ?>
            <strong><?=($options['label'] ?? '')?>: </strong>
        <?php endif ?>
        <a href="mailto://<?=$value?>"><?=$value?></a>
    </div>
</div>