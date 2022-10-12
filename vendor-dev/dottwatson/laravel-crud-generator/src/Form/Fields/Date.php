<?php
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Form\Field;
use Illuminate\Support\Carbon;


class Date extends Field {

    protected $fixedType = 'date';

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['prepend'] = '<i class="fa fa-calendar"></i>';

        $currentValue = $this->options['value'];

        if($currentValue){
            if(is_a($currentValue,Carbon::class)){
                $currentValue = $currentValue->format('Y-m-d');
            }
            else{
                $currentValue = Carbon::parse($currentValue)->format('Y-m-d');
            }
            
            $this->options['value'] = $currentValue;
        }

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
