/**
 * Контрол загрузки изображений
 *
 */
var imgUpload={


        //  точка входа
    'init' : function(){
        this.url = false;
        this.idButton = false;
        this.idButtonValue = false;
        this.loadStop = false;
        this.result = {}
        return this;
    },


    'create' : function(url,config,result,url_delete)
    {

        imgUpload.url = url;
        imgUpload.url_delete = url_delete;
       /* if ( $('#'+config['form']+).length==0 )
        {
            alert('Форма '+config['form']+' не найдена!');
        }
        imgUpload.idButton = idButton*/
        imgUpload.config = config
        //  создаём скрытую форму загрузки фоток
        //  ей нужно передовать ширину, высоту, размер, каталог, кроп нужен или нет

            //  создаём  форму и настраиваем её на отправку через аякс
        $('body').append('<form style="visibility:hidden" method="post" id="'+imgUpload.config['form']+'" action="'+imgUpload.url+'"><input  type="file"   name="upload[file]" id="'+imgUpload.config['file']+'" /><input type="hidden" name="upload[dir]" value="'+imgUpload.config['dir']+'"/><input type="hidden" name="upload[size]" value="'+imgUpload.config['size']+'"/><input type="hidden" name="upload[resize]" value="'+imgUpload.config['typeResize']+'"/><input type="hidden" name="upload[width]" value="'+imgUpload.config['width']+'"/><input type="hidden" name="upload[height]" value="'+imgUpload.config['height']+'"/></form>');
        var options = {
            'dataType':'json',
            'type': "POST",
            'url' : imgUpload.url,
            'success': function(res){

                if ( res['error'] )
                {
                    alert(res['error'])
                    $('#'+imgUpload.config['wait']).css('display','none');
                    imgUpload.showResult();
                }
                else
                {
                        //  выведем результат
                    imgUpload.showResult(res);
                }

            },
            'error': function(event, jqXHR, ajaxSettings) {
               // alert(ajaxSettings)
            }
        }
        $("#"+imgUpload.config['form']).ajaxForm(options);

            //  настраиваем события так, чтобы при выборе загружаемого файла форма сразу же была отправленна
        $('#'+imgUpload.config['button']).click( function(){

            imgUpload.loadStop = false;
            $('#'+imgUpload.config['file']).click();
        })

            //  файл выбран, нужно отправлять форму
        $('#'+imgUpload.config['file']).change( function(){
            if ( !$('#'+imgUpload.config['file']).val() )
            {
                return false
            }
                //  файл выбран, отправляем форму
            $('#'+imgUpload.config['form']).submit();


            $('#'+imgUpload.config['wait']).show();
            $('#'+imgUpload.config['button']).hide();
            $('#'+imgUpload.config['buttonStop']).css('display','inline').click( function(event)
            {
                    //  прерываем загрузку
                $('#'+imgUpload.config['wait']).hide();
                $('#'+imgUpload.config['button']).show();
                $('#'+imgUpload.config['buttonStop']).css('display','none')

                    //  загрузка прервана, нам пофигу что вернёт сервер
                imgUpload.loadStop = true;
            });


        })

        //  отображаем результат
        for( i in result){
          imgUpload.showResult(result[i]);
        }

        return this;
    },

        //  выводим загруженный результат
    'showResult' : function(res)
    {
        if ( imgUpload.loadStop == true )
        {
            return false;
        }
        imgUpload.result = {'endFile':res['endFile'],'hash':res['hash']};

        $('#'+imgUpload.config['wait']).hide();

        $('#'+imgUpload.config['container']).html('<input type="hidden" name="'+imgUpload.config['returnName']+'" value="'+res['endFile']+'" /><img src="'+res['fullUrl']+'" id="'+imgUpload.config['image']+'"  alt=" " style="display:block;width:'+imgUpload.config['width']+'px;height:'+imgUpload.config['height']+'px"/>');

        $('input[name="'+imgUpload.config['returnName']+'"]').change()

        $('#'+imgUpload.config['container']).css('display','inline');
        $('#'+imgUpload.config['button']).val( imgUpload.idButtonValue )  ;


        $('#'+imgUpload.config['button']).val('Удалить')
        $('#'+imgUpload.config['button'] ).unbind();
        $('#'+imgUpload.config['button']).click( function(){
            imgUpload.deleteFile( imgUpload.result.endFile,imgUpload.result.hash) ;


            $('#'+imgUpload.config['button'] ).val('Загрузить фото')
            $('#'+imgUpload.config['button'] ).unbind();
            $('#'+imgUpload.config['button'] ).click( function(){

                imgUpload.loadStop = false;
                $('#'+imgUpload.config['file']).click();
            })


        })
    },
    'deleteFile' : function(file,hash){

         $.ajax( {
                    'dataType':'xml',
                    'type': "POST",
                    'url': imgUpload.url,
                    'data':{'action':'delete','hash':hash,'safeDir':imgUpload.config['dir'],'name':file},
                    'success': function(xml){
                        res = App.get('system').executeResult( xml );
                        $('#'+imgUpload.config['container']).html('')
                        imgUpload.result = {}
                    },
                });
    },





}


