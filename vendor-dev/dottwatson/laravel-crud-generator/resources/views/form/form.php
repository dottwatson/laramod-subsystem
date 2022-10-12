<div class="mt-3 mb-3">
    <?php if ($showStart): ?>
        <?= Form::open($formOptions) ?>
    <?php endif; ?>
    <?php if($actions): ?>
        <div class="row">
            <?php if(isset($actions['back'])): ?>
                <div class="col text-left"><?= $actions['back']->render() ?></div>
            <?php endif; ?>
            <?php if(isset($actions['submit']) || isset($actions['reset'])): ?>
                <div class="col text-right">
                    <?php if(isset($actions['submit'])): ?>
                        <?= $actions['submit']->render() ?>
                    <?php endif; ?>
                    <?php if(isset($actions['reset'])): ?>
                        <?= $actions['reset']->render() ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if ($showFields): ?>
        <?php if($fields): ?>
            <div class="card-body">
                <?php foreach ($fields as $field): ?>
                    <?php if( ! in_array($field->getName(), $exclude) ): ?>
                        <?= $field->render() ?>
                    <?php endif ?>
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
    <?php endif; ?>
    <?php if($actions): ?>
        <div class="row">
            <?php if(isset($actions['back'])): ?>
                <div class="col text-left"><?= $actions['back']->render() ?></div>
            <?php endif; ?>
            <?php if(isset($actions['submit']) || isset($actions['reset'])): ?>
                <div class="col text-right">
                    <?php if(isset($actions['submit'])): ?>
                        <?= $actions['submit']->render() ?>
                    <?php endif; ?>
                    <?php if(isset($actions['reset'])): ?>
                        <?= $actions['reset']->render() ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php if ($showEnd): ?>
        <?= Form::close() ?>
    <?php endif; ?>
</div>