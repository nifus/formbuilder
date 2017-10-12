<?php

namespace Nifus\FormBuilder\Render;


class Paragraf extends \Nifus\FormBuilder\Render
{

    public function render($fields = array())
    {
        if (sizeof($fields) > 0) {
            return
                $this->paragrafRender($fields);
        } else {
            return
                $this->formRender($this->paragrafRender()) .
                $this->cssRender() .
                $this->jsRender();
        }
    }

    protected function paragrafRender($fields = array())
    {
        $show_label = isset($this->config['render']['label']) ? $this->config['render']['label'] : true;
        $par = '';
        foreach ($this->fields as $name => $config) {
            if (!in_array($name, $fields)) {
                continue;
            }
            $type = $config['type'];
            $config = $config['config'];
            $elementRender = $this->elementRender($name, $config,$type);


            if ( true===$show_label && (!isset($config['inline']) || false===$config['inline'])){
                $par .= $this->setLine('<p class="' . $name . '">');
                $par .= $this->setLine($elementRender['label'] . '');
                $par .= $this->setLine('</p>');
            }
            if ( !isset($config['inline']) || false===$config['inline'] ){
                $par .= $this->setLine('<p>');
                $par .= $this->setLine($elementRender['element']);
                $par .= $this->setLine('</p>');
            }else{
                $par .= $this->setLine($elementRender['element']);

            }
        }
        if ( sizeof($fields)==0 ){
            $par .= $this->setLine('<p><input type="submit" /></p>');
        }
        return $par;
    }





}