<?php

namespace Nifus\FormBuilder\Fields;

class Date extends \Nifus\FormBuilder\Fields{

    protected
        $typeField='text',
    $config=[
        'data-date-format'=>'dd-mm-yyyy',
    ];

    public function __construct($typeField,$name='',array $config,$builder){
        parent::__construct($typeField,$name, $config,$builder);
        $this->typeField='text';
        \Nifus\FormBuilder\Render::jsAdd('bootstrap-datepicker','bootstrap-datepicker/js');
        \Nifus\FormBuilder\Render::jsAdd('bootstrap-datepicker.ru','bootstrap-datepicker/js/locales');
        \Nifus\FormBuilder\Render::cssAdd('datepicker3','bootstrap-datepicker/css');
        $v = \View::make('formbuilder::classes/fields/Date/js');
        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());

    }

    public function setFormat($format)
    {
        $this->set('data-date-format',$format);
        return $this;
    }

}