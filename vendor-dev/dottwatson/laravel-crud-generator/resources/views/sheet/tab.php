<?php if($fields || $groups || $tabBars): ?>
    <?php if($component == 'nav'): ?>
        <li class="nav-item"><a class="nav-link <?=($active)?'active':'' ?>" id="tab<?=$tab->getIdentifier()?>" data-toggle="tab" href="#tabContainer<?=$tab->getIdentifier()?>" role="tab" aria-controls="tab<?=$tab->getIdentifier()?>" aria-selected="<?=($active)?'true':'false' ?>"><?=$tab->getLabel()?></a></li>
    <?php endif; ?>
    <?php if($component == 'content'): ?>
        <div class="tab-pane p-4 fade <?=($active)?'show active':'' ?>" id="tabContainer<?=$tab->getIdentifier()?>" role="tabpanel" aria-labelledby="tab<?=$tab->getIdentifier()?>">
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
                    <?php if($tagBar->getTabs()): ?>
                    <?= $tabBar->render() ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>    
    <?php endif; ?>
<?php endif; ?>