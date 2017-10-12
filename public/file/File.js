
$(function () {

    $('a[data-delete-file]').on('click',function(){
        var file = $(this).attr('data-delete-file');
        var path = $(this).attr('data-path-file');
        var hash = $(this).attr('data-hash-file');
        var group = $(this).parents('div.control-group');
        var box = $(this).parents('div.col-md-2');
        box.remove();
        group.find('input[type=file]').show();
        $.ajax({
            type: "POST",
            url: '/formbuilder/file/delete',
            data: "file="+file+"&path="+path+"&hash="+hash,
            dataType: "json",
            success: function(){

            }
        });

        $('input[data-count-files]').each(function(){
            var limit = $(this).attr('data-count-files');
            var multiple = $(this).attr('multiple');

            var group = $(this).parents('div.control-group');
            var files = group.find('div.col-md-2').length;


            if (limit!=0 && limit>=files){
                $(this).hide().attr('disabled','disabled');
            }else{
                if ( multiple==undefined && files>0 ){
                    $(this).hide().attr('disabled','disabled');
                }else{
                    $(this).show().removeAttr('disabled');
                }
            }
        });

    });

    $('input[data-count-files]').each(function(){
        var limit = $(this).attr('data-count-files');
        var multiple = $(this).attr('multiple');
        var group = $(this).parents('div.control-group');
        var files = group.find('div.col-md-2').length;

        if (limit!=0 && limit>=files){
            $(this).hide().attr('disabled','disabled');
        }else{
            if ( multiple==undefined && files>0 ){
                $(this).hide().attr('disabled','disabled');
            }else{
                $(this).show().removeAttr('disabled');
            }
        }
    });

});