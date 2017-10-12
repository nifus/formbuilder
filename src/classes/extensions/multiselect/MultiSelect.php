<?php
namespace Nifus\FormBuilder\Extensions;

use \Nifus\FormBuilder\Extension as Extension;

class MultiSelect extends Extension{

    public function loadAsset(){
        \Nifus\FormBuilder\Render::jsAdd('js/bootstrap-multiselect','MultiSelect');
        \Nifus\FormBuilder\Render::cssAdd('css/bootstrap-multiselect','MultiSelect');
        $v = \View::make('formbuilder::classes/extensions/multiselect/js');
        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());
    }


    public function configField($config){
        if ( !isset($config['placeholder']) && isset($config['label'])  ){
            return ['placeholder'=>$config['label']];
        }else{
            return ['placeholder'=>$config['placeholder']];
        }
    }

}