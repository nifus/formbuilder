<?php

namespace Nifus\FormBuilder\Fields;

class DateTimeBootstrap extends \Nifus\FormBuilder\Fields{

    protected
        //$typeField='text',
    $config=[
        'data-format'=>'dd-mm-yyyy HH:mm',
    ];

    public function __construct($typeField,$name='',array $config,$builder){
        parent::__construct($typeField,$name, $config,$builder);
       // $this->typeField='text';
        \Nifus\FormBuilder\Render::jsAdd('moment-with-langs','bootstrap-datetimepicker/js');
        \Nifus\FormBuilder\Render::jsAdd('bootstrap-datetimepicker','bootstrap-datetimepicker/js');
        \Nifus\FormBuilder\Render::jsAdd('bootstrap-datetimepicker.ru','bootstrap-datetimepicker/js/locales');
        \Nifus\FormBuilder\Render::cssAdd('bootstrap-datetimepicker.min','bootstrap-datetimepicker/css');


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


         return
            '<div class="input-group date" id="timepicker"><input type="text" '.$attrs.' /><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                    </span></div>';
    }


    public function setFormat($format)
    {
        $this->set('data-format',$format);
        return $this;
    }

}