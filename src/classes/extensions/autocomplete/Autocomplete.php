<?php
namespace Nifus\FormBuilder\Extensions;

use \Nifus\FormBuilder\Extension as Extension;

/**
 *
 * Class AutoLoad
 * @package Nifus\FormBuilder\Extensions
 */
class Autocomplete extends Extension
{

    static function form()
    {
        return true;
    }

    public function loadAsset()
    {

        //$config = $this->builder->load_chain;


        /*if (!isset($config) || !is_array($config)) {
            return false;
        }*/
        \Nifus\FormBuilder\Render::jsAdd('jquery.autocomplete','Autocomplete');
        \Nifus\FormBuilder\Render::cssAdd('autocomplete','Autocomplete');

        $v = \View::make('formbuilder::classes/extensions/autocomplete/js')
            ->with('id_form', $this->builder->form_name );

        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());

    }

    public function configField($config)
    {
        $result = '';
        if (!isset($config['autocomplete'])) {
            return [];
        }



        $result['data-provide']='typeahead';
        $result['data-autocomplete-url']=$config['autocomplete']['url'];
        //dd($result);
        return $result;


    }
}