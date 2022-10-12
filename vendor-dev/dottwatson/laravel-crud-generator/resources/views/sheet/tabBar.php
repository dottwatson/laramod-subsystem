<?php if($tabs): ?>
    <ul class="nav nav-tabs" id="tabs<?=$tabBar->getIdentifier()?>" role="tablist">
        <?php $cnt = 0 ?>
        <?php foreach($tabs as $tab): ?>
            <?=$tab->render('nav',$cnt == 0) ?>
            <?php $cnt++ ?>
        <?php endforeach; ?>
    </ul>
    <div class="tab-content" id="tabsContents<?=$tabBar->getIdentifier()?>">
        <?php $cnt = 0 ?>
        <?php foreach($tabs as $tab): ?>
            <?=$tab->render('content',$cnt == 0) ?>
            <?php $cnt++ ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>