<?php
namespace Nifus\FormBuilder\Extensions;

use \Nifus\FormBuilder\Extension as Extension;

/**
 * Загружаем по цепочке
 * Class LoadChain
 * @package Nifus\FormBuilder\Extensions
 */
class LoadChain extends Extension
{

    static function form()
    {
        return true;
    }

    public function loadAsset()
    {

        $v = \View::make('formbuilder::classes/extensions/loadChain/js') ->with('id_form', $this->builder->form_name );

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