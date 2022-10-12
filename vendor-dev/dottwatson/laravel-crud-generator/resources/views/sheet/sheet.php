<?php if($fields): ?>
    <div class="card-body">
        <?php foreach ($fields as $field): ?>
            <?= $field->render() ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if($groups): ?>
    <div class="row mt-3 mb-3">
        <?php foreach($groups as $group): ?>
            <?=$group->render()?>
        <?php endforeach ?>
    </div>
<?php endif; ?>
<?php if($tabBars): ?>
        <?php foreach($tabBars as $tabBar): ?>
            <?=$tabBar->render()?>
        <?php endforeach ?>
<?php endif; ?>
