<?php
$modalId = 'tableModal'.$table->getId();
?>
<div class="relational-field">
    <input type="hidden" name="relational[<?=$name?>][_tokenRelation]" value="<?=$_tokenRelation?>">
    <p class="text-center">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#<?=$modalId?>">
            Seleziona
        </button>
    </p>

    <!-- Modal -->
    <div class="modal fade  modal-fullscreen-xl" id="<?=$modalId?>" tabindex="-1" role="dialog" aria-labelledby="<?=$modalId?>Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="<?=$modalId?>Label">Seleziona <?=$options['label']?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="relational-table">
                        <?php    
                            echo $table->table();
                            echo $table->scripts();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="related-items row">
    </div>
</div>
