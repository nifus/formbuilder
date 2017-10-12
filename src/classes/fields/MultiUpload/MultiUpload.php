<?php

namespace Nifus\FormBuilder\Fields;

class MultiUpload extends \Nifus\FormBuilder\Fields\Hidden{

    protected $config=[
        'multiple'=>'multiple'
    ];




    public function renderElement($response){
        \Nifus\FormBuilder\Render::jsAdd('jquery');

        \Nifus\FormBuilder\Render::jsAdd('js/vendor/jquery.ui.widget','multiUpload');
        \Nifus\FormBuilder\Render::jsAdd('js/load-image.min','multiUpload');
        \Nifus\FormBuilder\Render::jsAdd('js/canvas-to-blob.min','multiUpload');
        \Nifus\FormBuilder\Render::jsAdd('js/bootstrap.min','multiUpload');
        \Nifus\FormBuilder\Render::jsAdd('js/jquery.iframe-transport','multiUpload');
        \Nifus\FormBuilder\Render::jsAdd('js/jquery.fileupload','multiUpload');
        \Nifus\FormBuilder\Render::jsAdd('js/jquery.fileupload-process','multiUpload');
        \Nifus\FormBuilder\Render::jsAdd('js/jquery.fileupload-image','multiUpload');
        \Nifus\FormBuilder\Render::jsAdd('js/jquery.fileupload-audio','multiUpload');
        \Nifus\FormBuilder\Render::jsAdd('js/jquery.fileupload-video','multiUpload');
        \Nifus\FormBuilder\Render::jsAdd('js/jquery.fileupload-validate','multiUpload');



        \Nifus\FormBuilder\Render::cssAdd('css/jquery.fileupload','multiUpload');

        $v = \View::make('formbuilder::classes/extensions/multiUpload/js') ->with('id_form', $this->builder->form_name );
        \Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());
        return
            '<br><span class="btn btn-success fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Добавить файлы...</span>
        <input type="file" multiple id="fileupload">
        '.parent::renderElement($response).'
    </span>
    <br>
    <br>
    <div id="progress" class="progress">
        <div class="progress-bar progress-bar-success"></div>
    </div>
    <div id="files" class="files"></div>
    <br>';
    }

    protected function getDefaultConfig()
    {
        return [
            'data'=>['type'=>'key_value','value'=>'{{title}}']
        ];
    }

    //  вызывается
    static function upload(){
        $upload_handler = new \UploadHandler([
            'upload_dir' => public_path().'/media/multiUpload/',
            'upload_url'=> '/media/multiUpload/'
        ]);


    }




}