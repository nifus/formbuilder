<?php

namespace Nifus\FormBuilder\Fields;

class Number extends \Nifus\FormBuilder\Fields{

    public function renderElement($response){
        $attrs = $this->renderAttrs();
        $value = $response->getData($this->config['name']);
        if ( !is_null($value) ){
            $attrs.='value="'.htmlspecialchars($value).'"';
        }
        return '<input type="number"  '.$attrs.' />';
    }
}