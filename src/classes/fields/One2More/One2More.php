<?php

namespace Nifus\FormBuilder\Fields;

class One2More extends \Nifus\FormBuilder\Fields{

    protected $form;
    public function __construct($typeField,$name='', array $config,$builder){
        parent::__construct($typeField,$name, $config,$builder);
        \Nifus\FormBuilder\Render::jsAdd('One2More','One2More');
    }



    public function setValues($values){
        $this->config['values']=$values;
        return $this;
    }

    public function setClass($class)
    {
        $this->config['data']['class'] = $class;
        return $this;
    }

    public function setFields($fields){

        $this->config['fields'] = $fields;
        return $this;
    }

    public function renderWithOutForm($response){
        $rows = $this->__getData();
        $v = \View::make('formbuilder::classes/fields/One2More/js')
            ->with('cols',  json_encode($this->config['values']) )
            ->with('data',  json_encode($rows) )
            ->with('id_form', $this->config['name']);
        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());

        $this->configFields();
        $names = $this->getNamesFields();

        $fields = $this->config['fields'];
        $this->config['form']= \Nifus\FormBuilder\FormBuilder::create($this->config['name'])->setRender('bootstrap3')
            ->setFields($fields);
        $form = $this->config['form']->render();
        // = ($render_views->bootstrap3Render($names));

        return '<div  class="modal fade" id="modal_sub_data" role="dialog" aria-hidden="true" tabindex="-1"  >
            <div class="modal-dialog" id="modal_'.$this->config['name'].'">
              <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">'.$this->config['label'].'</h4>
                  </div>
                <form  method="post" id="'.$this->config['name'].'" novalidate>
                <div class="modal-body"><div class="row">'.$form.'</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                      <button type="button" class="btn btn-primary" data-action="save" data-elements="'.$this->config['name'].'">Сохранить</button>
                    </div>
                </div>
                </form>
                </div>
          </div>
        </div>';
    }

    public function renderElement($response){
        return '
            <div><div class="container" id="container_'.$this->config['name'].'"></div></div>
            <button type="button" class="btn btn-primary" data-action="create" data-form="'.$this->config['name'].'">Добавить</button>
         ';
    }

    private function __getData(){
        $data = $this->config['data'];
        $method = $data['method'];
        $model = $this->builder->model;
        $rows = [];
        $id = $this->builder->getId();
        if ( $id>0 ){
            $instance = $model::find($id);
            $result = $instance->$method()->get();
            $values = $this->config['fields'];
            $i=0;

            foreach( $result as $row ){
                $j=0;
                foreach( $values as $object ){
                    $key = $object->name;
                    $rows[$i][$key] = ['value'=>$row->$key,'name'=>$key,'label'=>$object->label];
                    $j++;
                }
                $i++;
            }
        }
        return $rows;
    }

    private function configFields(){
        $name_main_form = ($this->builder->form_name);
        $name_sub_form = ($this->config['name']);
        $fields = $this->config['fields'];
        foreach($fields as $field ){
            $field->name = $field->name;
            $field->set('data-name',$field->name);
            $field->set('data-form',$name_sub_form);
            $field->set('data-label',$field->label);
        }
    }

    private function getNamesFields(){
        $names=[];
        $fields = $this->config['fields'];
        foreach($fields as $field ){
            $name= $field->name;
            $names[]=$name;
        }
        return $names;
    }

}