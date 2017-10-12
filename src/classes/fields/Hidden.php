<?php

namespace Nifus\FormBuilder\Fields;

class Hidden extends \Nifus\FormBuilder\Fields{

    protected function getDefaultConfig()
    {
         return ['inline'=>true];
    }
    public function renderLabel(){
        return false;
    }
    public function renderElement($response){

        $attrs = $this->renderAttrs();
        //$data = $response->getData($this->name);
        $elements='<input type="hidden"  '.$attrs.' />';
        return $elements;
    }
}