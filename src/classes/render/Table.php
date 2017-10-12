<?php

namespace Nifus\FormBuilder\Render;


class Table extends \Nifus\FormBuilder\Render
{

    public function execute($fields = array())
    {
        return
            $this->formRender($this->tableRender()) .
            $this->cssRender() .
            $this->jsRender();
    }

    protected function tableRender()
    {
        $table = $this->setLine('<table class="formBuilder">');
        foreach ($this->fields as $name => $config) {
            $type = $config['type'];
            $config = $config['config'];
            $elementRender = $this->elementRender($name, $config,$type);
            $table .= $this->setLine('<tr class="' . $name . '">');
            $table .= $this->setLine('<td>');
            $table .= $this->setLine($elementRender['label'] . '');
            $table .= $this->setLine('</td>');
            $table .= $this->setLine('<td>');
            $table .= $this->setLine($elementRender['element']);
            $table .= $this->setLine('</td>');
            $table .= $this->setLine('</tr>');
        }
        $table .= $this->setLine('<tr>');
        $table .= $this->setLine('<td colspan="2"><input type="submit" /></td>');
        $table .= $this->setLine('</tr>');
        $table .= $this->setLine('</table>');


        return $table;
    }





}