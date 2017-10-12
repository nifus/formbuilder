<?php

namespace Nifus\FormBuilder\Fields;

class Wysiwyg extends \Nifus\FormBuilder\Fields\Textarea{





    public function renderElement($response){
        \Nifus\FormBuilder\Render::jsAdd('summernote','bootstrap-wysiwyg');
        \Nifus\FormBuilder\Render::cssAdd('summernote','bootstrap-wysiwyg');

        $v = \View::make('formbuilder::classes/fields/Wysiwyg/js')->with('id',$this->config['id']);
        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());

        return parent::renderElement($response);
    }

}