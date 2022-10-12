<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>

<?php if ($showLabel && $options['label'] !== false && $options['label_show']): ?>
    <?= Form::customLabel($name, $options['label'], $options['label_attr']); ?>
<?php endif; ?>

<?php if ($showField): ?>
    <?php $renderedField = Form::input($type, $name, $options['value'], $options['attr']); ?>
    <?php if(isset($options['prepend'])): ?>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><?=$options['prepend']?></span>
            </div>
            <?= $renderedField ?>
        </div>
    <?php elseif(isset($options['append'])): ?>
        <div class="input-group">
            <?= $renderedField ?>
            <div class="input-group-append">
                <span class="input-group-text"><?=$options['append']?></span>
            </div>
        </div>
    <?php else: ?>
        <?= $renderedField ?>
    <?php endif; ?>
    <?php include $formBuilderViewPath.'/errors.php'; ?>
    <?php include $formBuilderViewPath.'/help_block.php'; ?>
<?php endif; ?>

<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
    </div>
    <?php endif; ?>
<?php endif; ?>
