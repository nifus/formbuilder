<?php

namespace Nifus\FormBuilder\Fields;

class File extends \Nifus\FormBuilder\Fields{

    protected $config=[
        'upload'=>true,
        'data-count-files'=>0
    ];

    static function delete(){
        $path = public_path().'/'.$_POST['path'];
        if ( md5( filesize($path.'/'.$_POST['file']) )==$_POST['hash'] ){
            unlink($path.'/'.$_POST['file']);
        }
        return \Response::json([]);
    }

    public function renderElement($response){
        \Nifus\FormBuilder\Render::jsAdd('File','file');

        $path = public_path().'/'.$this->config['data-path'];
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/'.$this->config['data-path'];
        $elements = '';
        $attrs = $this->renderAttrs();
        $files = $response->getModelData($this->config['name']);

        if ( is_array($files) ){
            $elements.='
                <div class="row">';
            foreach( $files as $file ){
                if ( empty($file) || !file_exists($path.'/'.$file ) ){
                    continue;
                }
                $hash = md5( filesize($path.'/'.$file) );
                $elements.='

                  <div class="col-xs-6 col-md-2">
                    <a target="_blank" class="thumbnail" href="'.$url.'/'.$file.'"><img src="'.$url.'/'.$file.'" style="width:60px;"></a>
                     <p><a href="javascript:void(0)" class="btn btn-danger" role="button" data-delete-file="'.$file.'" data-hash-file="'.$hash.'" data-path-file="'.$this->config['data-path'].'">Удалить файл</a> </p>
                  </div>

               ';
            }
            $elements.='</div><br/><div><input style="display:none" type="file"  '.$attrs.' /></div>';
        }elseif( !empty($files) && file_exists($path.'/'.$files) ){
            $hash = md5( filesize($path.'/'.$files) );
            $elements.='<div class="row">
              <div class="col-xs-6 col-md-2">
                <a target="_blank" class="thumbnail" href="'.$url.'/'.$files.'"><img src="'.$url.'/'.$files.'" style="width:60px;"></a>
                 <p><a href="javascript:void(0)" class="btn btn-danger" role="button" data-delete-file="'.$files.'" data-hash-file="'.$hash.'" data-path-file="'.$this->config['data-path'].'">Удалить файл</a> </p>
              </div>

              </div><br/><input style="display:none" disabled type="file"  '.$attrs.' />';
        }else{
            $elements='<input type="file"  '.$attrs.' />';
        }

        return $elements;
    }

    /**
     * генерируем уникальные поля
     * @return $this
     */
    public function setOriginName(){
        $this->config['origin_name'] = true;
        return $this;
    }

    public function setExts($exts)
    {
        $this->config['exts'] = $exts;
        return $this;
    }

    public function setSize($width,$height)
    {
        $this->config['width'] = $width;
        $this->config['height'] = $height;
        return $this;
    }

    public function setMultiple()
    {
        $this->config['multiple'] = 'multiple';
        return $this;
    }
    public function setPath($path){
        $this->config['data-path'] = $path;
        return $this;
    }

    public function setCountFiles($count){
        $this->config['data-count-files'] = $count;
        return $this;
    }
}