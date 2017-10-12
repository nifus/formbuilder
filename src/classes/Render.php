<?php

namespace Nifus\FormBuilder;

/**
 * Класс отвечает за отображение формы
 *
 * Class Render
 * @package Nifus\FormBuilder
 */
class Render
{
    static
        $assetJs = [],
        $assetCss = [],
        $staticJs = '',
        $staticCss = '';

    protected
        $withOutForm='',
        $fields,
        $builder,
        $response;

    public function __construct($builder,$response)
    {
        $this->builder = $builder;
        $this->response = $response;
        $this->loadExtensions();
    }

    static function jsAdd($file, $ext = null)
    {
        if (isset(self::$assetJs[$file])) {
            return false;
        }
        self::$assetJs[$file] = $ext;
    }

    static function cssAdd($file, $ext = null)
    {
        if (isset(self::$assetCss[$file])) {
            return false;
        }
        self::$assetCss[$file] = $ext;
    }

    static function setJs($js, $path = null)
    {
        if (is_null($path)) {
            self::$staticJs .= "\n\r" . $js . "\n\r";
        } else {
            self::$staticJs .= "\n\r" . "<!-- include " . $path . "--> \n\r" . $js . "\n\r";
        }
    }

    /**
     * Загружаем расширения
     */
    protected function loadExtensions()
    {
        if ( !is_array($this->builder->extensions)) {
            return false;
        }
        foreach ($this->builder->extensions as $ext) {
            $class = 'Nifus\FormBuilder\Extensions\\' . $ext;
            if (!class_exists($class)) {
                throw new RenderException('Не найден класс ' . $class);
            }
            $ext = new $class($this->builder);
            $ext->loadAsset();
            //$class::autoload($this);
        }
        return true;
    }



    function renderAssets(){
        return $this->cssRender() .$this->jsRender();
    }





    protected function setLine($str)
    {
        return $str . "\n";
    }

    /**
     * @param $name
     * @param $config
     * @param $type
     * @return array
     * @throws RenderException
     */
    protected function elementRender($name, $config,$type)
    {
        $class = 'Nifus\FormBuilder\Fields\\' . ucfirst($type);
        if (!class_exists($class)) {
            throw new RenderException('Не найден класс ' . $class);
        }
        $element = new $class($type,$name, $config,$this->builder);
        return [
            'label' => $element->renderLabel(),
            'element' => $element->renderElement($this->response),
            'comment'=>$element->comment,
            'break_line'=>$element->breakLine,
            'render_with_out_form'=>$element->renderWithOutForm($this->response),
            'field_type'=>$element->getType()
        ];
    }


    protected function formRender($content)
    {
        $formAttrs = [
            'enctype' => $this->builder->enctype,
            'method' => $this->builder->method,
            'id' => $this->builder->form_name,
            'name' => $this->builder->form_name,
        ];
        $action = $this->builder->action;
        if (isset($action)) {
            $formAttrs['url'] = $action;
        }
        if ( !is_null($this->builder->class_form) ) {
            $formAttrs['class'] = $this->builder->class_form;
        }
        return \Form::open($formAttrs) . $content . \Form::close();;
    }

    public function cssRender()
    {
        $result = '';
        foreach (self::$assetCss as $file => $ext) {
            if (is_null($ext)) {
                $result .= \HTML::style(asset('packages/nifus/formbuilder/' . $file . '.css'));
            } else {
                $result .= \HTML::style(asset('packages/nifus/formbuilder/' . $ext . '/' . $file . '.css'));

            }
        }
        // $result.=self::$assetCss;
        return $result;
    }

    public function jsRender()
    {
        $result = '';

        foreach (self::$assetJs as $file => $ext) {
            if (is_null($ext)) {
                $result .= \HTML::script(asset('packages/nifus/formbuilder/' . $file . '.js'));
                self::$assetJs[$file] = false;
            } elseif (false === $ext) {
                continue;
            } else {
                $result .= \HTML::script(asset('packages/nifus/formbuilder/' . $ext . '/' . $file . '.js'));
                self::$assetJs[$file] = false;
            }
        }
        $result .= self::$staticJs . "
<script>$(document).ready(function() {
   $('#" . $this->builder->form_name . "').append($('<input type=\"hidden\" name=\"" . $this->builder->form_name . "_formbuildersubmit\" value=\"1\">'));
   ";
        if ( false!=$this->builder->getId() ){

            $result.="$('#".$this->builder->form_name."').append($('<input type=\"hidden\" name=\"".$this->builder->form_name."_formbuilderid\" value=\"".$this->builder->getId()."\">'));";
        }
        $result .= "
});
</script>";
        self::$staticJs = '';
        return $result;
    }



    public function withOutFormRender(){
        return $this->withOutForm;
    }
    public function setWithOutForm($html){
        $this->withOutForm .= $html;
    }




    public function setFields(array $fields ){
        $this->fields = $fields;
    }

    protected function errors(){
        return $this->builder->errors();
    }


}
