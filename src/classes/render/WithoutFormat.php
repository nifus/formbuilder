<?php

namespace Nifus\FormBuilder\Render;


class WithoutFormat extends \Nifus\FormBuilder\Render
{
    public function render($fields = array())
    {
        $view = new \Nifus\FormBuilder\RenderView($this->arrayRender(), $this->jsRender(), $this->cssRender(), $this->errors());
        return $view;
    }

    protected function arrayRender()
    {
        $elements = array();
        foreach ($this->fields as $area) {
            foreach( $area['fields'] as $name => $config){
                $type = $config['type'];
                $config = $config['config'];
                $elements[$name] = $this->elementRender($name, $config,$type);
            }
        }
        return $elements;
    }
}