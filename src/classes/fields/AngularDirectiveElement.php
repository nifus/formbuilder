<?php
namespace Nifus\FormBuilder\Fields;

/**
 * Generate Angular directive
 *
 * Class AngularDirectiveElement
 * @package Nifus\FormBuilder\Fields
 */
class AngularDirectiveElement extends \Nifus\FormBuilder\Fields{

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
        return $this->config['value'];
    }





}