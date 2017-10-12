<?php
namespace Nifus\FormBuilder\Extensions;


class Ajax extends Validetta
{

    static function form()
    {
        return true;
    }

    public function loadAsset()
    {
       // \Nifus\FormBuilder\Render::jsAdd('jquery');
        \Nifus\FormBuilder\Render::jsAdd('jquery.form','ajax');
        // валидация
        \Nifus\FormBuilder\Render::jsAdd('jquery.validate.min', 'validate');
        $lang = \App::getLocale();
        if ($lang != 'en') {
            \Nifus\FormBuilder\Render::jsAdd('languages/validettaLang-' . \App::getLocale(), 'validate');
        }
        \Nifus\FormBuilder\Render::cssAdd('validetta', 'validate');


        $config = $this->builder->ajax;

        $url = isset($config['url']) ? $config['url'] : '';
        /*if (!isset($config) || !is_array($config)) {
            return false;
        }*/


        $v = \View::make('formbuilder::classes/extensions/ajax/js')
            ->with('formName', $this->builder->form_name )
            ->with('formAction', $url);
        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());


    }


}