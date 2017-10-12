<?php

namespace Nifus\FormBuilder\Fields;

class Select extends \Nifus\FormBuilder\Fields{

    protected
        $config=[
            'disabled'=>[],
            'data'=>[]
        ];

    public function setOrder($key,$sort){
        $order = isset($this->config['data']['order_rules']) ? $this->config['data']['order_rules'] : [];
        $order[$key]=$sort;
        $this->config['data']['order_rules'] = $order;
        return $this;
    }

    public function setOrders($rules){
        foreach( $rules as $rule ){
            foreach( $rule as $key=>$sort){
                $this->setOrder($key,$sort);
            }
        }
        return $this;
    }



    public function setMethod($method,$closure=null)
    {
        $this->config['data']['method'] = $method;
        $this->config['data']['closure'] = $closure;
        $this->config['data']['type'] = 'model';
        return $this;
    }
    public function setDefault($title,$key=0,$add=false)
    {
        $this->config['data']['default'] = ['value'=>$title,'key'=>$key,'add'=>$add];
        return $this;
    }

    public function setDisabled($disabled)
    {
        $this->config['disabled'] = $disabled;
        return $this;
    }

    public function setSize($size)
    {
        $this->config['data']['size'] = $size;
        return $this;
    }
    public function setMultiple($flag=false)
    {
        $this->config['data']['multiple'] = $flag;
        return $this;
    }

    public function setOptions(array $options,$type='key_value')
    {
        $this->config['data']['options'] = $options;
        $this->config['data']['type'] = $type;
        return $this;
    }

    public function setValue($value)
    {
        $this->config['data']['value'] = $value;
        return $this;
    }

    public function setGroup($key){
        $this->config['data']['group'] = $key;
        return $this;
    }


    public function renderElement($response){

        $attrs = $this->renderAttrs();

        $data = $response->getData($this->config['name']);

        $data_select = $this->selectDataFormat( $data );

        $multi='';
        if ( $this->isMultiple( $this->config['data']) ){
            $size= isset( $this->config['data']['size']) ? $this->config['data']['size'] : 5;
            $multi = 'multiple="multiple" size="'.$size.'"';
            $attrs .= ' data-value="'.( is_array($data) ? implode(',',$data) : '').'"';
        }else{
            $attrs .= ' data-value="'.$data.'"';
        }

        return '<select '.$attrs.' '.$multi.'>'.$data_select.'</select>';
    }

    protected function getDefaultConfig()
    {
        return [
            'data'=>['type'=>'key_value','value'=>'{{title}}']
        ];
    }


    private function isMultiple($config){

        if ( isset($config['multiple']) ){
            return true;
        }
        if ( !isset($config['method']) ){
            return false;
        }
        $model = $this->builder->model;
        $object = new $model;
        $f = $object->$config['method']();
        if (  $f instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany  ){
            if ( isset($config['multiple']) && false===$config['multiple'] ){
                return false;
            }
            return true;
        }
        return false;
    }

    private function selectDataFormat($data=null){

        $config = $this->config['data'];

        $select = !is_null($data) ? $data : ( isset($config['default']['key']) ? $config['default']['key'] : null)  ;

        if ( !is_array($select) ){
            $select=[$select];
        }
        $data='';
        $config['type'] = (isset($config['type'])) ? $config['type'] :
            (isset($config['method']) ? 'model' : null );

        if ( isset($config['default']['add']) && true===$config['default']['add'] ){
            $data .= '<option value="'.$config['default']['key'].'">'.$config['default']['value'].'</option>';
        }
        switch($config['type']){
            case('value'):
                $data .= $this->generateOptionsValue($config['options'],$select);
                break;
            case('key_value'):
                $data .= $this->generateOptionsKeyValue($config['options'],$select);
                break;
            case('model'):
                $data .= $this->generateOptionsModel($config,$select);
                break;
        }
        return $data;
    }

    private function generateOptionsKeyValue(array $data,array $select){
        $disabled_values = $this->config['disabled'];

        $html = '';
        foreach($data as $key=>$value ){
            if ( is_array($value) ){
                $html.='<optgroup  label="'.htmlspecialchars($key).'">';
                foreach($value as $key2=>$value2 ){
                    $selected = in_array($key2,$select) ? 'selected="selected"' : '';
                    $disabled = in_array($key,$disabled_values) ? 'disabled="disabled"' : '';
                    $html.='<option '.$disabled.'  '.$selected.' value="'.htmlspecialchars($key2).'">'.htmlspecialchars($value2).'</option>';
                }
                $html.='</optgroup>';
            }else{
                $selected = in_array($key,$select) ? 'selected="selected"' : '';
                $disabled = in_array($key,$disabled_values) ? 'disabled="disabled"' : '';
                $html.='<option '.$disabled.' '.$selected.' value="'.htmlspecialchars($key).'">'.htmlspecialchars($value).'</option>';
            }
        }
        return $html;
    }

    private function generateOptionsValue(array $data,array $select){
        $html = '';
        $disabled_values = $this->config['disabled'];
        foreach($data as $key=>$value ){
            if ( is_array($value) ){
                $html.='<optgroup  label="'.htmlspecialchars($key).'">';
                foreach($value as $key2=>$value2 ){
                    $selected = in_array($key2,$select) ? 'selected="selected"' : '';
                    $disabled = in_array($key2,$disabled_values) ? 'disabled="disabled"' : '';
                    $html.='<option '. $disabled .' '.$selected.' value="'.htmlspecialchars($value2).'">'.htmlspecialchars($value2).'</option>';
                }
                $html.='</optgroup>';
            }else{
                $disabled = in_array($key,$disabled_values) ? 'disabled="disabled"' : '';
                $selected = in_array($key,$select) ? 'selected="selected"' : '';
                $html.='<option '. $disabled .' '.$selected.' value="'.htmlspecialchars($value).'">'.htmlspecialchars($value).'</option>';
            }
        }
        return $html;
    }

    private function generateOptionsModel(array $config,array $select){
        $html = '';
        $model = $this->builder->model;
        $object = new $model;
        $f = $object->$config['method']();


        if (  $f instanceof \Illuminate\Database\Eloquent\Relations\BelongsTo  ){
            //  связь один ко многим
            return $this->generateOptionsModelBelongsTo($f,$select);
        }elseif (  $f instanceof \Illuminate\Database\Eloquent\Relations\belongsToMany  ){
            //  связь многие ко многим
            return $this->generateOptionsModelBelongsToMany($f,$select);
        }

        return $html;
    }


    private function generateOptionsModelBelongsTo($object,$select){
        $disabled_values = $this->config['disabled'];
        $config = $this->config['data'];
        $values = [];
        if ( isset($config['value']) ){
            preg_match_all('#\{([^}]*)\}#iUs',$config['value'],$find);
            if (sizeof($find[1])>0){
                $values=$find[1];
            }else{
                $values=[$config['value']];
                $config['value']='{'.$config['value'].'}';
            }
        }else{
            $values = ['title'];
        }
        $html = '';

        //  получаем модель связанную
        $related = $object->getRelated();
        $mainKey = $related->getKeyName();
        $values[]=$mainKey;
        if ( isset($config['value']) ){
            preg_match_all('#\{([^}]*)\}#iUs',$config['value'],$find);
            if (sizeof($find[1])>0){
                $values=$find[1];
            }else{
                $values=[$config['value']];
                $config['value']='{'.$config['value'].'}';
            }
        }else{
            $values = ['title'];
        }
        $sql = $related;
        if ( isset($config['closure']) ){
            $sql = $config['closure']($sql);
        }
        if ( isset($config['order_rules']) ){
            foreach( $config['order_rules'] as $orderKey=>$type ){
                $sql = $sql->orderBy($orderKey,$type);
            }
        }
        $items = $sql->get();

        foreach($items as $item ){
            $selected = in_array($item->$mainKey,$select) ? 'selected="selected"' : '';
            $value = $this->config['data']['value'];

            foreach($values as $sqlValue ){

                $value = str_replace('{'.$sqlValue.'}',$item->$sqlValue,$value);
            }
            $disabled = in_array($item->$mainKey,$disabled_values) ? 'disabled="disabled"' : '';
            $html.='<option '.$disabled.' '.$selected.' value="'.htmlspecialchars($item->$mainKey).'">'.htmlspecialchars($value).'</option>';
        }
        return $html;
    }

    private function generateOptionsModelBelongsToMany($object,$select){
        $disabled_values = $this->config['disabled'];
        $config = $this->config['data'];
        $html = '';
        //  получаем модель связанную
        $related = $object->getRelated();
        $table = $object->getTable();
        $key = $related->getKeyName();
        $values[]=$key;
        $order = [];
        if ( isset($config['value']) ){
            preg_match_all('#\{([^}]*)\}#iUs',$config['value'],$find);
            if (sizeof($find[1])>0){
                $values=$find[1];
            }else{
                $values=[$config['value']];
                $config['value']='{'.$config['value'].'}';
            }
        }else{
            $values = ['title'];
        }
        $sql = $related;
        if ( isset($config['order_rules']) ){
            foreach( $config['order_rules'] as $orderKey=>$type ){
                $sql->orderBy($orderKey,$type);
            }
        }

        if ( isset($config['group']) ){
            $roots = $sql->where($config['group'],0)->get();
            foreach( $roots as $root ){
                $value = $config['value'];
                foreach($values as $sqlValue ){
                    $value = str_replace('{'.$sqlValue.'}',$root->$sqlValue,$value);
                }
                $html.='<optgroup label="'.$value.'">';
                $subs = $sql->where($config['group'],$root->$key)->get();
                foreach( $subs as $sub ){
                    $selected = in_array($sub->$key,$select) ? 'selected="selected"' : '';
                    $value = $config['value'];
                    foreach($values as $sqlValue ){
                        $value = str_replace('{'.$sqlValue.'}',$sub->$sqlValue,$value);
                    }
                    $disabled = in_array($sub->$key,$disabled_values) ? 'disabled="disabled"' : '';
                    $html.='<option '.$disabled.' '.$selected.' value="'.htmlspecialchars($sub->$key).'">'.htmlspecialchars($value).'</option>';
                }
                $html.='</optgroup>';
            }
        }else{
            $items = $sql->get();

            foreach($items as $item ){
                $selected = in_array($item->$key,$select) ? 'selected="selected"' : '';
                $value = $config['value'];
                foreach($values as $sqlValue ){
                    $value = str_replace('{'.$sqlValue.'}',$item->$sqlValue,$value);
                }
                $disabled = in_array($item->$key,$disabled_values) ? 'disabled="disabled"' : '';

                $html.='<option '.$disabled.' '.$selected.' value="'.htmlspecialchars($item->$key).'">'.htmlspecialchars($value).'</option>';
            }
        }
        return $html;
    }

    private function getGroupArray($items,$group_key){
        $result = [];
        foreach($items as $item ){

        }
    }

}