<script type="text/javascript">
    $('#{{$formName}}Message').hide().addClass('hide');

    {{$formName}} = {
        send:false,
        ini:function(){
            {{$formName}}.validate();
            {{$formName}}.ajax();
        },
        validate:function(){
            if ( $("#{{$formName}} *[data-validetta]").length>0 ){
                $("#{{$formName}}").validetta({
                    realTime     : true,
                    conditional : {
                        visible : function() {
                            if ( !$(this).is(':visible') ){
                                return true;
                            }
                            if ( $(this).is(':visible') &&  $(this).val()!='' ){
                                return true;
                            }
                            return false;
                        },
                        select_value : function() {
                            if ( $(this).val()=='' ){
                                return false;
                            }
                            return true;
                        }

                    }
                });
            }
        },
        ajax:function(){
            var options = {
                @if( !empty($formAction) )
                'url': '{{$formAction}}',
                @endif
                'dataType': 'json',
                'type': 'post',
                'success': function (json) {
                    if (json && json.error) {
                            $('#{{$formName}}Message').html(json.error).show().removeClass('hide').addClass('alert-danger').addClass('alert-success');
                            $('#{{$formName}} [name=' + json.field + ']').focus();
                            $( document ).trigger( "{{$formName}}.ajax.answer",[json] );
                            $(document ).trigger( "ajax.answer",['{{$formName}}',true, json] );
                    } else if (json && json.msg) {
                            $('#{{$formName}}Message').html(json.msg).show().removeClass('hide').addClass('alert-success').addClass('alert-danger');
                            $( document).trigger("save_form", [json.id ] );
                            $( document ).trigger( "ajax.answer",['{{$formName}}',false, json] );
                            $( document ).trigger( "{{$formName}}.ajax.answer",[json] );
                    } else if (json && json.url) {
                           window.location = json.url;
                    } else if (json && json.reload){
                        window.location.reload(true);
                    } else{
                        $( document ).trigger( "{{$formName}}.ajax.answer",[json] );
                    }
                },
                'error': function (event, jqXHR, ajaxSettings) {
                    $('#{{$formName}}Message').html(json.ajaxSettings).show().removeClass('hide');
                    //$( "body" ).trigger( "ajax.answer",['form_id':'{{$formName}}','error':true,'answer':json.ajaxSettings] );

                return false;
                }
            }
            $("#{{$formName}}").ajaxForm(options);
        }
    }
    $(document).ready(function() {
        {{$formName}}.ini();
    });
</script>
