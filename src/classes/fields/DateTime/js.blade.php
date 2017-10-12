<script>
    $(function () {
        $('*[data-format]').each(function(){
            var format = $(this).attr('data-format');
            $(this).datetimepicker({format:format});
        })
    });
</script>