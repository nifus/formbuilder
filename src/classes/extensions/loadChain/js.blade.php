<script type="text/javascript">
    var Chain={
        init:function(form){

            var form = $('#'+form);
            form.find('*[data-receiver]').each(function(){
                var el = $(this).attr('data-receiver');
                form.find('*[name='+el+']').attr('disabled','disabled');
            });

            var source = form.find('*[data-source]');
            source.each( function(){
                var value = $(this).attr('data-value');
                var type = $(this).attr('data-format')
                var url =  $(this).attr('data-url');
                var receiver_id= $(this).attr('data-receiver');
                var  receiver = form.find('*[name='+receiver_id+']');
                if ( receiver.is(':disabled') && receiver.attr('data-value')!=''){
                    Chain.ajax(type,value,receiver,url );
                }
                /* if ( $(this).attr('disabled')!='disabled' ){
                    $(this).trigger('change');
                }*/
            })

            source.on('change',function()
            {
                var id =  $(this).val();
                if ( id>0 ){
                    var url =  $(this).attr('data-url');
                    var receiver_id= $(this).attr('data-receiver');
                    var type = $(this).attr('data-format')
                    var  receiver = form.find('*[name='+receiver_id+']');
                    Chain.ajax(type,id,receiver,url );
                }else{
                    var receiver_id= $(this).attr('data-receiver');
                    form.find('*[name='+receiver_id+']').attr('disabled','disabled').html('');
                    form.find('*[name='+receiver_id+']').trigger('change');
                }
            });

            /*
            source.each( function(){
                if ( $(this).attr('disabled')!='disabled' ){
                    $(this).trigger('change');
                }
            })*/

        },

        ajax:function(type,id,receiver,url){
            var receiver = receiver;
            receiver.attr('disabled','disabled');

            $.ajax({
                dataType: type,
                type: "post",
                url: url,
                data: { id: id }
            }).done(function( data ) {
                    var select = '';
                    for (var i = 0; i < data.length; i++) {
                        select += '<option value="' + data[i].id + '">' +
                            data[i].title + '</option>';
                    }
                    receiver.html(select).removeAttr('disabled');
                    var value = receiver.attr('data-value');
                    if ( value!='' ){
                        receiver.find("option[value='"+value+"']").attr("selected", "selected");
                    }
                });
        }
    };

    $(document).ready(function() {
        Chain.init('{{$id_form}}');
    });
</script>


