<?php
namespace Nifus\FormBuilder;

class Field extends \Controller {

    public function Js($ext){

    }
	public function Index($ext,$action)
	{
        $class = 'Nifus\FormBuilder\Fields\\' . ucfirst($ext);
        if (!class_exists($class)) {
            die();
        }
        return $class::$action();
    }




}