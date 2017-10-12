<?php

namespace Nifus\FormBuilder\Fields;

class SelectImages extends \Nifus\FormBuilder\Fields\Select{

    protected $config=[
        'data'=>[]
    ];

    public function setFolder($folder){
        $this->config['data']['folder'] = $folder;
        $files = $this->readAllFiles(public_path().$folder);
        //\Log::info($files);
        $this->setOptions($files['files']);


        return $this;
    }

    public function setDefault($title,$key=0,$add=false)
    {
        $this->config['data']['default'] = ['value'=>$title,'key'=>$key,'add'=>$add];
        return $this;
    }


    public function setOptions(array $options,$type='key_value')
    {
        $this->config['data']['options'] = $options;
        $this->config['data']['type'] = $type;
        return $this;
    }


    public function renderElement($response){
        //\Log::info($response->getData($this->config['name']));
        \Nifus\FormBuilder\Render::jsAdd('image-picker.min','selectImages');
        \Nifus\FormBuilder\Render::cssAdd('image-picker','selectImages');

        $v = \View::make('formbuilder::classes/fields/SelectImages/js') ->with('id_form', $this->builder->form_name );
        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());


        $attrs = $this->renderAttrs();
        $data = $this->selectDataFormat( $response->getData($this->config['name']) );
        $multi='';

        return '<select '.$attrs.' '.$multi.'>'.$data.'</select>';
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

                $data .= $this->generateOptionsValue($config['options'],$select);


        return $data;
    }


    private function generateOptionsValue(array $data,array $select){
        $folder = $this->config['data']['folder'];
        $html = '';
        foreach($data as $file ){
            $key = 'http://'.$_SERVER['HTTP_HOST'].''.$folder.'/'.basename($file);
            $selected = in_array(basename($file),$select) ? 'selected="selected"' : '';
            $html.='<option '.$selected.' data-img-src="'.htmlspecialchars($key).'" value="'.basename($file).'">'.basename($file).'</option>';

        }
        return $html;
    }


    /**
     * scan dir and subdirs
     *
     * @param string $root
     * @return array
     */
    private function readAllFiles($root = '.')
    {

        $files = array('files' => array(), 'dirs' => array());
        $directories = array();
        $last_letter = $root[strlen($root) - 1];
        $root = ($last_letter == '\\' || $last_letter == '/') ? $root : $root . DIRECTORY_SEPARATOR;
        $directories[] = $root;
        while (sizeof($directories)) {
            $dir = array_pop($directories);
            if (false === file_exists($dir)) {
                continue;
            }
            if ($handle = opendir($dir)) {
                while (false !== ($file = readdir($handle))) {
                    if ($file == '.' || $file == '..') {
                        continue;
                    }
                    $file = $dir . $file;
                    if (is_dir($file)) {
                        $directory_path = $file . DIRECTORY_SEPARATOR;
                        array_push($directories, $directory_path);
                        $files['dirs'][] = $directory_path;
                    } elseif (is_file($file)) {
                        $files['files'][] = $file;
                    }
                }
                closedir($handle);
            }
        }
        return $files;
    }



}