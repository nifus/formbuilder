<?php
namespace Nifus\FormBuilder;

class Response
{

    private
        $method = 'config',
        $format = 'save',
        $builder, $model = false;

    function __construct($builder)
    {
        $this->builder = $builder;

    }

    function setMethod($method){
        $this->method=$method;
    }

    function setFormat($format){
        $this->format=$format;
    }

    /**
     * Проверяем форму
     */
    public function fails($fields)
    {
        $rules = $data_config = $errors_msg = $labels = [];

        foreach ($fields as $area) {
            foreach ($area['fields'] as $name => $config) {
                $config = $config['config'];
                if (!isset($config['data-required'])) {
                    continue;
                }
                if (isset($config['data-error-msg'])) {
                    $errors_msg[$name] = $config['data-error-msg'];
                }
                if ( isset($config['label']) ){
                    $labels[$name] = $config['label'];
                }
                $rules[$name][] = 'required';
                $parameters = explode(',', $config['data-required']);
                foreach ($parameters as $parametr) {
                    $rules[$name][] = $parametr;
                }
                $data_config[$name] = $this->findResponseData4Key($name);
            }
        }

        $check = \Validator::make(
            $data_config,
            $rules
        );

        if (false === $check->fails()) {
            return false;
        }
        $errors = $check->failed();
        $messages = $check->messages();

        foreach ($errors as $key => $info) {
            $system_msg = $messages->first($key);
            $system_msg = preg_replace('#' . $key . '#iUs', $labels[$key], $system_msg);
            $msg = isset($errors_msg[$key]) ? $errors_msg[$key] : $system_msg;

            $this->builder->setError($msg);
        }
        return true;
    }

    private function getModel(){
        $id = $this->findResponseData4Key($this->builder->form_name . '_formbuilderid');
        $model = $this->builder->model;

        if (!is_null($id)) {
            $model = $model::find($id);
        } else {
            $model = new $model();
        }

        return $model;
    }

    private function getDataValue($config){

        $config = $config['config'];

        $name = $config['name'] = preg_replace('#\[\]#', '', $config['name']);

        if (empty($name)) {
            return null;
        }

        if (isset($config['data']['method'])) {
            if (!isset($config['data']['class'])) {
                $object = new $this->builder->model;
            } else {
                $object = new $config['data']['class'];
            }
            $f = $object->$config['data']['method']();

            //  пропускаем
            if ($f instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                return null;
            }
            if ($f instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                return null;
            }
        }

        if (isset($config['upload']) && true === $config['upload'] && isset($_FILES[$config['name']])) {
            if (\Input::hasFile($config['name'])) {
                $name = $this->uploadFiles($config);
                if (is_array($name)) {
                    foreach ($name as $file) {
                        if (isset($config['width']) && isset($config['height'])) {
                            $destination_path = public_path() . '/' . $config['data-path'];
                            $this->resizeImage($destination_path . '/' . $file, $config['width'], $config['height']);
                        }
                    }
                } elseif (!empty($name)) {
                    if (isset($config['width']) && isset($config['height'])) {
                        $destination_path = public_path() . '/' . $config['data-path'];
                        $this->resizeImage($destination_path . '/' . $name, $config['width'], $config['height']);
                    }
                } else {
                    throw new \Exception('Неудалось загрузить файлы');
                }
                return $name;
            } else {
                return  '';
            }
        } elseif (!isset($config['upload'])) {
            return $this->findResponseData4Key($name);
        }
    }


    /**
     *
     */
    function save($fields)
    {
        $model = $this->getModel();
        if ( is_null($model) ){
            throw new Exception('Модель не найдена');
        }
        $data_config = $this->builder->data;
        try {
            $result = [];
            foreach ($fields as $area) {
                foreach ($area['fields'] as $name => $config) {

                    $value = $this->getDataValue($config);

                    if ( is_null($value) ){
                        continue;
                    }
                    $model->$name = $value;
                }
            }
            $id = $model->getKey();
            if (!is_null($id)) {
                $model->update();
                \Event::fire('fb.'.$this->builder->form_name.'.update', array($model));
            }else{
                $model->save();
                \Event::fire('fb.'.$this->builder->form_name.'.save', array($model));

            }
           $this->saveBelongsToMany($fields,$model);
           $this->saveHasMany($fields,$model);

        } catch (\Exception $e) {
            $this->builder->setError($e->getMessage());
            return false;
        }
        return $model;
    }

    private function saveHasMany($fields,$model){
        $id = $model->getKey();
        //  Один ко многим
        foreach ($fields as $area) {
            foreach ($area['fields'] as $name => $config) {
                $config = $config['config'];
                if (isset($config['data']['method'])) {
                    $f = $model->$config['data']['method']();
                    //  пропускаем
                    if (!$f instanceof \Illuminate\Database\Eloquent\Relations\HasMany) {
                        continue;
                    }

                    $key = $f->getPlainForeignKey();
                    $model = $f->getRelated();
                    $class_name = get_class($model);

                    $rows = $this->findResponseData4Key($name);
                    $o = $class_name::where($key, $id)->get();
                    foreach ($o as $obj) {
                        $obj->delete();
                    }


                    $inc = [];
                    if (is_array($rows)) {
                        foreach ($rows as $v => $values) {
                            foreach ($values as $i => $value) {
                                $inc[$i][$v] = $value;
                                $inc[$i][$key] = $id;
                            }
                        }

                        foreach ($inc as $array) {
                            $class_name::create($array);
                        }
                    }
                }
            }
        }
    }

    private function saveBelongsToMany($fields,$model){
        $id = $model->getKey();

        foreach ($fields as $area) {
            foreach ($area['fields'] as $name => $config) {
                $config = $config['config'];
                if (isset($config['data']['method'])) {
                    $f = $model->$config['data']['method']();
                    //  пропускаем
                    if (!$f instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                        continue;
                    }
                    $key = $f->getForeignKey();
                    $prime_key = $f->getOtherKey();
                    $rows = $this->findResponseData4Key($name);
                    $inc = [];
                    if (is_array($rows)) {
                        foreach ($rows as $v) {
                            $inc[$id][] = $v;
                        }
                        foreach ($inc as $id_model => $keys) {
                            $f = $model::find($id_model);
                            $f->$config['data']['method']()->sync([]);
                            $f->$config['data']['method']()->sync($keys);
                        }
                    }
                }
            }
        }
    }

    private function resizeImage($file, $width, $height)
    {
        $file = \Image::make($file);
        if (is_null($file)) {
            return false;
        }
        $height_source = $file->height();
        $width_source = $file->width();

        $min = min($width_source, $height_source);


        $koof = $min / $width;
        $small_w = round($width_source / $koof);
        $small_h = round($height_source / $koof);


        if ($small_w < $small_h) {
            $padding = round(($small_h - $height) / 2);
            $file->resize($small_w, $small_h)
                ->crop($width, $height, 0, intval($padding))
                ->save();
        } elseif ($small_w > $small_h) {
            $padding = round(($small_w - $width) / 2);
            $file->resize($small_w, $small_h)
                ->crop(($width), $height, intval($padding), 0)
                ->save();
        } else {
            $file->resize($small_w, $small_h)->save();
        }
    }

    private function uploadFiles($config)
    {
        $object = \Input::file($config['name']);

        $destination_path = public_path() . '/' . $config['data-path'];
        $names = [];
        if (is_array($object)) {
            foreach ($object as $file) {
                if (isset($config['origin_name'])) {
                    $file_name = $file->getClientOriginalName();
                } else {
                    $file_name = $this->unicName($destination_path, $file->getClientOriginalExtension());
                }
                if (isset($config['exts']) && !in_array($file->getClientOriginalExtension(), $config['exts'])) {
                    continue;
                }
                $file->move($destination_path, $file_name);
                $names[] = $file_name;
            }
        } else {
            if (isset($config['origin_name'])) {
                $file_name = $object->getClientOriginalName();
            } else {
                $file_name = $this->unicName($destination_path, $object->getClientOriginalExtension());
            }
            if (isset($config['exts'])) {
                if (in_array($object->getClientOriginalExtension(), $config['exts'])) {
                    $object->move($destination_path, $file_name);
                    $names = $file_name;
                } else {
                    $names = null;
                }
            } else {
                $object->move($destination_path, $file_name);
                $names = $file_name;
            }
        }


        return $names;
    }

    private function unicName($path, $ext = '')
    {
        $base = time().rand(1,100);
        while (2 < 3) {
            $new = (isset($ext)) ? $path . '/' . $base . '.' . $ext : $path . '/' . $base;
            if (!file_exists($new)) {
                break;
            }
            $base++;
        }
        return (isset($ext)) ? $base . '.' . $ext : $base;
    }

    /**
     * Возвращаем данные переданные формой
     */
    public function responseData()
    {
        $fields = $this->config['fields'];
        $data = $this->findResponseAllData();
        $response = [];

        foreach ($data as $key => $value) {
            if (!isset($fields[$key])) {
                continue;
            }
            $response[$key] = $value;
        }

        return $response;
    }

    /**
     * Ищем по ключу данные переданные в запросе
     * @param $key
     * @return null
     */
    protected function findResponseData4Key($key)
    {
        $key = preg_replace('#\[\]#', '', $key);
        switch ($this->builder->method) {
            case('post'):
                return (isset($_POST[$key])) ? $_POST[$key] : null;
                break;
            case('get'):
                return (isset($_GET[$key])) ? $_GET[$key] : null;
                break;
        }
        return null;
    }

    /**
     * Возвращаем масси данных с которым нужно работать
     * @return mixed
     */
    protected function findResponseAllData()
    {
        switch ($this->builder->method) {
            case('post'):
                return $_POST;
                break;
            case('get'):
                return $_GET;
                break;
        }
    }

    function setError($error)
    {
        $this->errors[] = $error;
    }


    public function isCreate()
    {
        $id = $this->builder->getId();
        if (!$id) {
            return true;
        }
        return false;
    }

    public function getDate($key){
        foreach ($this->builder->fields as $fields) {
            foreach ($fields as $title => $conf) {

                if ( isset($conf[$key]) ) {
                    $configKey = $conf[$key];
                    $config = $configKey['config'];
                    $key = preg_replace('#\[\]#', '', $key);
                    return $this->data[$key];
                }
            }
        }
    }

    public function getModelData($key)
    {

        $model = $this->builder->model;

        if (is_null($model)) {
            return null;
        }

        if (false === $this->model) {
            $model = $model::find($this->builder->getId());
            if (is_null($model)) {
                return null;
            }
            $this->model = $model;
        }


        foreach ($this->builder->fields as $fields) {
            foreach ($fields as $title => $conf) {
                if ( isset($conf[$key]) ) {
                    $configKey = $conf[$key];
                    $config = $configKey['config'];

                    $key = preg_replace('#\[.*\]#', '', $key);
                    if (isset($config['data']['method'])) {

                        $rel = $this->model->$config['data']['method']();
                        if ($rel instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {

                            return $rel->getRelatedIds();
                        }
                    }
                    return $this->model->$key;
                }else{
                    $key = preg_replace('#\[.*\]#', '', $key);
                    return $this->model->$key;
                }
            }
            //\Log::info( $fields );


        }


    }


    public function getData($key)
    {

        if ( $this->isSubmit() ) {
            if ( $this->builder->clear_submit_data==true ){
                return null;
            }
            return $this->findResponseData4Key($key);
        } elseif( !is_null($this->builder->model) ) {
            return $this->getModelData($key);
        } else{

            return $this->builder->data[$key];
        }
    }


    /**
     * Проверяем отправку формы
     */
    public function isSubmit()
    {
        return !is_null($this->findResponseData4Key($this->builder->form_name . '_formbuildersubmit')) ? true : false;
    }


}