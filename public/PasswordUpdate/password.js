
$(function () {

    $('input[data-password]').each(function(i){
        var data_value = $(this).attr('data-value-exists');
        if ( data_value ){
            $(this).hide().attr('disabled','disabled').before('<div><a href="javascript: void(0)" data-password-show="'+$(this).attr('name')+'">Изменить пароль</a></div>');
        }
    });
    $('div').on('click','a[data-password-show]',function(){
        $(this).hide();
        var name = $(this).attr('data-password-show');
        $('input[name="'+name+'"]').show().removeAttr('disabled')
    })
});