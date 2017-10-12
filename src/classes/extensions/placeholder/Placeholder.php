<?php
namespace Nifus\FormBuilder\Extensions;

use \Nifus\FormBuilder\Extension as Extension;

class Placeholder extends Extension{

    public function loadAsset(){
        //Render::jsAdd('jquery.form','ajax');
    }


    public function configField($config){
        if ( !isset($config['placeholder']) && isset($config['label']) &&  !is_null($config['label']) ){
            return ['placeholder'=>$config['label']];
        }elseif ( isset($config['placeholder']) ){
            return ['placeholder'=>$config['placeholder']];
        }else{
            return [];
        }
    }

}