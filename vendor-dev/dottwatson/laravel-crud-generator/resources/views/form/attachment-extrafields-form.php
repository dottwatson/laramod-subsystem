<div>
    <?php if ($showFields): ?>
        <?php if($fields): ?>
            <?php foreach ($fields as $field): ?>
                <?php if( ! in_array($field->getName(), $exclude) ): ?>
                    <?= $field->render() ?>
                <?php endif ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>