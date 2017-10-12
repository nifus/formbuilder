<?php
namespace Nifus\FormBuilder\Fields;

/**
 * Generate RadioButton
 *
 * Class Radio
 * @package Nifus\FormBuilder\Fields
 */
class Radios extends \Nifus\FormBuilder\Fields{

    public function setOptions(array $options)
    {
        $this->config['data']['options'] = $options;
        return $this;
    }

    public function setDefault($default)
    {
        $this->config['data']['default'] = $default;
        return $this;
    }

    protected function renderAttrs(){
        $attrs = '';
        foreach($this->config as $k=>$v ){
            if ( !is_null($v) && !in_array($k,['data','id','value','type']) ){
                $attrs.=$k.'="'.$v.'" ';
            }
        }
        return $attrs;
    }

    public function renderElement($response){
        $attrs = $this->renderAttrs();
        $data = $response->getData($this->config['name']);
        $data = is_null($data ) ? ( isset($this->config['data']['default']) ? $this->config['data']['default'] : '' ) : $data;
        $elements = '';
        foreach( $this->config['data']['options'] as $key=>$value ){
            $elements .= '<div class="radio">';
            $checked = ($key==$data) ? 'checked="checked"' : '';
            $elements.='<label><input type="radio" '.$attrs.' '.$checked.' value="'.htmlspecialchars($key).'" />&nbsp;'.$value.'</label>';
            $elements.='</div>';
        }


        return $elements;
    }





}