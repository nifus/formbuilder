<?php

namespace Nifus\FormBuilder\Fields;

class Password extends \Nifus\FormBuilder\Fields{

    public function renderElement($response){

        $attrs = $this->renderAttrs();
        $elements='<input type="password"  '.$attrs.' />';
        return $elements;
    }
    
}