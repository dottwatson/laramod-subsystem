<?php 
$options['choices'] = [];

$countries = App\Models\Country::all();
foreach($countries as $country){
    $options['choices'][$country->id] = "{$country->name} ({$country->iso_3166_1_alpha_3})";
}
?>
<div class="row">
    <div class="col-md-12">
        <?php if(isset($options['label'])): ?>
            <strong><?=($options['label'] ?? '')?>: </strong>
        <?php endif ?>
        <?php foreach($options['choices'] as $cKey=>$cValue): ?>
            <?php if($cKey == $value): ?>
                <?php $value = $cValue; break; ?>
            <?php endif ?>
        <?php endforeach ?>
        <?=$value?>
    </div>
</div>
