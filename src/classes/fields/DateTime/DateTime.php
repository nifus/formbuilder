<?php

namespace Nifus\FormBuilder\Fields;

class DateTime extends \Nifus\FormBuilder\Fields{

    protected
        //$typeField='text',
    $config=[
        'data-format'=>'Y-m-d H:i',
    ];

    public function __construct($typeField,$name='',array $config,$builder){
        parent::__construct($typeField,$name, $config,$builder);
       // $this->typeField='text';
        \Nifus\FormBuilder\Render::jsAdd('jquery.datetimepicker','datetimepicker');
        \Nifus\FormBuilder\Render::cssAdd('jquery.datetimepicker','datetimepicker');


    }



    public function renderElement($response){
        $v = \View::make('formbuilder::classes/fields/DateTime/js');
        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());

        $attrs = $this->renderAttrs();
        $value = $response->getData($this->config['name']);
        if ( !is_null($value) ){
            $attrs.='value="'.htmlspecialchars($value).'"';
            $attrs.='data-value="'.htmlspecialchars($value).'"';
        }


         return '<input type="text" '.$attrs.' />';
    }


    public function setFormat($format)
    {
        $this->set('data-format',$format);
        return $this;
    }

}