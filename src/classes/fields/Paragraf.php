<?php
namespace Nifus\FormBuilder\Fields;

/**
 * Generate Paragraf
 *
 * Class Paragraf
 * @package Nifus\FormBuilder\Fields
 */
class Paragraf extends \Nifus\FormBuilder\Fields{
    public
        $breakLine = true;

    protected $config=['label'=>null];

    public function setValue($value)
    {
        $this->label = $value;
        return $this;
    }

    public function renderLabel(){

        return null;
    }

    public function renderElement($response){

        return '<p>'.$this->config['label'].'</p>';
    }





}