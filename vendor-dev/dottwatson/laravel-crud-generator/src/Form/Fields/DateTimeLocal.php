<?php
namespace Dottwatson\CrudGenerator\Form\Fields;

use Dottwatson\CrudGenerator\Form\Field;
use Illuminate\Support\Carbon;


class DateTimeLocal extends Field {

    protected $fixedType = 'datetime-local';

    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['prepend'] = '<i class="fa fa-calendar"></i>&nbsp;&nbsp;<i class="fa fa-clock"></i>';

        $currentValue = $this->options['value'];

        if($currentValue){
            if(is_a($currentValue,Carbon::class)){
                $currentValue = $currentValue->format('Y-m-d\\TH:i:s');
            }
            else{
                $currentValue = Carbon::parse($currentValue)->format('Y-m-d\\TH:i:s');
            }
            
            $this->options['value'] = $currentValue;
        }

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
