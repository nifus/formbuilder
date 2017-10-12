<?php
namespace Nifus\FormBuilder\Fields;

/**
 * Generate CheckBox
 *
 * Class Radio
 * @package Nifus\FormBuilder\Fields
 */
class Checkbox extends \Nifus\FormBuilder\Fields{

    protected

        $config = ['data'=>['hidden'=>true]];

    public function setOptions(array $options)
    {
        $this->config['data']['options'] = $options;
        return $this;
    }

    public function setHidden( $value){
        $this->config['data']['hidden'] = $value;
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
            if ( !is_null($v) && !in_array($k,['data']) ){
                $attrs.=$k.'="'.$v.'" ';
            }
        }
        return $attrs;
    }
    public function renderElement($response){
        $attrs = $this->renderAttrs();
        $data = $response->getData($this->config['name']);
        $data = is_null($data ) ? ( isset($this->config['data']['default']) ? $this->config['data']['default'] : '' ) : $data;
        $elements = [];
        if ( isset( $this->config['data']['options']) ){
            foreach( $this->config['data']['options'] as $key=>$value ){
                $checked = ($key==$data) ? 'checked="checked"' : '';
                $elements[]='<input type="checkbox" '.$attrs.' '.$checked.' value='.$key.' />&nbsp;'.$value.'';
            }
        }else{
            if ( $this->config['data']['hidden']==true ){
                $hidden = '<input type="hidden" name="'.$this->config['name'].'" value="0" />';
            }else{
                $hidden='';
            }

            $checked = ( !empty($data) ) ? 'checked="checked"' : '';
            $elements[]='<label>'.$hidden.'<input type="checkbox" '.$attrs.' '.$checked.' value="1" /> &nbsp; '.$this->config['label'].'</label>&nbsp;';

        }
        if ( sizeof($elements)==1 ){
            return implode('',$elements);
        }
        return $elements;

    }

    public function renderLabel(){
        return false;
    }




}