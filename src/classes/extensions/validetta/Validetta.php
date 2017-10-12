<?php
namespace Nifus\FormBuilder\Extensions;

use \Nifus\FormBuilder\Extension as Extension;

class Validetta extends Extension
{



    public function loadAsset()
    {
        //\Nifus\FormBuilder\Render::jsAdd('jquery');

        // валидация
        \Nifus\FormBuilder\Render::jsAdd('jquery.validate.min', 'validate');
        $lang = \App::getLocale();
        if ($lang != 'en') {
            \Nifus\FormBuilder\Render::jsAdd('languages/validettaLang-' . \App::getLocale(), 'validate');
        }
        \Nifus\FormBuilder\Render::cssAdd('validetta', 'validate');

        $v = \View::make('formbuilder::classes/extensions/validetta/js')
            ->with('formName', $this->builder->form_name);
        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());


    }

    public function configField($config)
    {

        $result = '';
        if (!isset($config['data-required'])) {
            return [];
        }
        //$result .= 'required,';
        $types = explode('|', $config['data-required']);
        foreach ($types as $t) {
            if ( preg_match('#^required$#iUs',$t,$search) ){
                $result .= 'required';
            }elseif ( preg_match('#^(min|max):([0-9]*)$#iUs',$t,$search) ){
                $result .= $search[1].'Length[' . $search[2] . '],';
            }elseif(preg_match('#^email$#iUs',$t,$search) ){
                $result .= 'email,';
            }elseif ( preg_match('#^(minSelected|maxSelected):([0-9]*)$#iUs',$t,$search) ){
                $result .= $search[1].'[' . $search[2] . '],';
            }elseif ( preg_match('#^customReg:(.*)$#iUs',$t,$search) ){
                $result .= 'customReg[' . $search[1] . '],';
            }elseif ( preg_match('#^numeric#iUs',$t,$search) ){
                $result .= 'number,';
            }else{
                $result .= $t.',';
            }
        }
        return ['data-validetta' => $result];

    }
}