<?php

namespace Nifus\FormBuilder\Render;


class Bootstrap3 extends \Nifus\FormBuilder\Render
{

    public function render($fields = array())
    {
        if (sizeof($fields) > 0) {
            return
                $this->bootstrap3Render($fields);
        } else {

            return
                $this->formRender($this->bootstrap3Render([])) .
                $this->withOutFormRender() .
                $this->cssRender() .
                $this->jsRender();
        }
    }

    function bootstrap3Render($fields)
    {
        $table = '';
        $render_config = $this->builder->render;
        if (isset($render_config['cols'])) {
            if ($render_config['cols'] == 'inline') {
                $col = 'col-md-1';
            } else {
                $col = 'col-md-' . round(12 / $render_config['cols']);
            }
        } else {
            $col = 'col-md-6';
        }


        foreach ($this->fields as $area) {
            if (sizeof($this->fields) > 1) {
                $table .= $this->setLine('<div class="panel panel-default">');
                $table .= $this->setLine('<div class="panel-heading">');
                $table .= $this->setLine($area['title']);
                $table .= $this->setLine('</div>');
                $table .= $this->setLine('<div class="panel-body">');

            }

            foreach ($area['fields'] as $name => $config) {
                $close = true;
                if (sizeof($fields) > 0 && !in_array($name, $fields)) {
                    continue;
                }
                $type = $config['type'];
                $config = $config['config'];
                $elementRender = $this->elementRender($name, $config, $type);


                if ($elementRender['field_type'] == 'hidden') {
                        $close = false;
                } elseif (true === $elementRender['break_line']) {
                    $table .= $this->setLine('<div class="col-md-10">');
                } else {
                    if ($elementRender['field_type'] != 'checkbox') {
                        $table .= $this->setLine('<div class="form-group">');
                    } else {
                        $table .= $this->setLine('<div class="form-group ">');

                    }

                }
                if (is_array($elementRender['element'])) {
                    // 4 checkbox &&  radio
                    $table .= $this->setLine('<div class="'.$elementRender['field_type'].'">');
                    foreach ($elementRender['element'] as $i => $element) {
                        $table .= $elementRender['element'][$i];
                    }
                    $table .= $this->setLine('</div>');
                } else {
                    if (!is_null($elementRender['label'])) {
                        $table .= $this->setLine($elementRender['label']);
                    }
                    $table .= $this->setLine($elementRender['element']);

                    if (isset($elementRender['comment']) && !empty($elementRender['comment'])) {
                        $table .= $this->setLine('<small>' . $elementRender['comment'] . '</small>');
                    }
                }
                if ( false!=$close){
                    $table .= $this->setLine('</div>');
                }
            }
            if (sizeof($this->fields) > 1) {
                $table .= $this->setLine('</div>');
                $table .= $this->setLine('</div>');
            }
        }
        return $table;
    }


}