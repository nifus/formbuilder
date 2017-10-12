<?php
namespace Nifus\FormBuilder\Extensions;

use \Nifus\FormBuilder\Extension as Extension;

/**
 * Визуальное ограничение по количеству символов
 * Class MaxLength
 * @package Nifus\FormBuilder\Extensions
 */
class MaxLength extends Extension
{



    public function loadAsset()
    {
        //\Nifus\FormBuilder\Render::jsAdd('jquery');
        \Nifus\FormBuilder\Render::jsAdd('bootstrap-maxlength.min','MaxLength');


        $v = \View::make('formbuilder::classes/extensions/maxLength/js') ->with('element', $this->builder->form_name );

        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());

    }

    public function configField($config)
    {
        $result = '';
        if (!isset($config['load_chain'])) {
            return [];
        }

        foreach ($config['load_chain'] as $key=>$value) {
            $result['data-'.$key]=$value;
        }

        $result['data-source']=$config['name'];
        //dd($result);
        return $result;


    }
}