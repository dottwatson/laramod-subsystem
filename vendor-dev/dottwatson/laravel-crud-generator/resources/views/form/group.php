<?php if($fields || $groups || $tabBars): ?>
    <div class="col col-md-<?=($options['size'] ?? 12)?>">
        <div class="card">
            <?php if($group->getLabel()): ?>
                <div class="card-header">
                    <h5><?=$group->getLabel()?></h5>
                </div>
            <?php endif ?>
            <div class="card-body">
                
                <?php foreach($fields as $field): ?>
                    <?= $field->render() ?>
                <?php endforeach; ?>
                
                <?php if($groups): ?>
                    <div class="row mt-3 mb-3">
                        <?php foreach($groups as $group): ?>
                            <?= $group->render() ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if($tabBars): ?>
                    <?php foreach($tabBars as $tabBar): ?>
                        <?php if($tabBar->getTabs()): ?>
                        <?= $tabBar->render() ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>