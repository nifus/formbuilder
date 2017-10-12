<?php

namespace Nifus\FormBuilder\Fields;

class SimpleImageUpload extends \Nifus\FormBuilder\Fields\Hidden{

    protected $config=[
        'multiple'=>'multiple'
    ];




    public function renderElement($response){
        \Nifus\FormBuilder\Render::jsAdd('jquery');

        \Nifus\FormBuilder\Render::jsAdd('imgUpload','SimpleImageUpload');
        $images  = $response->getData($this->config['name']);
        $result = '[';
        if ( !empty($images) ){
            $images = (explode(',',$images)) ;

            foreach( $images as $image ){
            $result.="{'fullUrl':'".\Config::get('app.url').'/media/uploads/'.addslashes(trim($image))."',  'endFile':'".\Config::get('app.url').'/media/uploads/'.addslashes(trim($image))."','hash':1},";
        }
        }
        $result .= ']';




        $v = \View::make('formbuilder::classes/fields/SimpleImageUpload/js')

            ->with('result', $result)
            ->with('id_form', $this->builder->form_name );
        //\Nifus\FormBuilder\Render::setJs($v->render(), $v->getPath());

        return $v->render();
    }



    //  вызывается
     static function upload(){
         $config = \Input::get('upload');

         //\Log::info($_FILES);
         //\Log::info($config);


         if ( !@move_uploaded_file($_FILES['upload']['tmp_name']['file'],public_path().'/media/uploads/'.$_FILES['upload']['name']['file'] ) )
         {
             throw new Exception('Переместить загруженный файл не получилось в ');
         }


         $hash = md5( \Config::get('app.key').filectime( public_path().'/media/uploads/'.$_FILES['upload']['name']['file'] ) );

         $answer = [
             'fullUrl' => \Config::get('app.url').'/media/uploads/'.$_FILES['upload']['name']['file'],
             'fullPath' => $_FILES['upload']['name']['file'],
             'uploadFile'=>$_FILES['upload']['name']['file'],
             'endFile'=>$_FILES['upload']['name']['file'],
             'hash' => $hash,
         ];
         //\Log::info($answer);

         return \Response::json($answer);

    }




}