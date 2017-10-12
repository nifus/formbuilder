<script>
    $(document).ready(function() {

        $("#{{$form}} *[data-mask]").each(function(i){

            $(this).mask( $(this).attr('data-mask'), {maxlength: false} )
        });
    });

</script>