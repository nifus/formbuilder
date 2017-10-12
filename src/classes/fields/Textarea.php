<?php
namespace Nifus\FormBuilder\Fields;

/**
 * Generate Textarea
 *
 * Class Radio
 * @package Nifus\FormBuilder\Fields
 */
class Textarea extends \Nifus\FormBuilder\Fields{



    public function renderElement($response){
        $attrs = $this->renderAttrs();
        $data = $response->getData($this->config['name']);

        $elements='<textarea  '.$attrs.' >'.$data.'</textarea>';

        return $elements;
    }





}