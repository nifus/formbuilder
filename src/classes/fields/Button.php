<?php
namespace Nifus\FormBuilder\Fields;

/**
 * Generate Button
 *
 * Class Radio
 * @package Nifus\FormBuilder\Fields
 */
class Button extends \Nifus\FormBuilder\Fields{

    protected $config=[];


    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function renderLabel(){
        if ( empty($this->config['label']) ){
            return false;
        }
       return parent::renderLabel();
    }

    public function renderElement($response){
        $attrs = $this->renderAttrs();
        $value = isset($this->config['value']) ? $this->config['value'] : '';
        return '<button '.$attrs.'>'.$value.'</button>';
    }





}