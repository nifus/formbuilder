<script type="text/javascript">
    $(document).ready(function() {
        $('input[maxlength]').each(function(i){
            var max = $(this).attr('maxlength');
            $(this).maxlength({
                alwaysShow: true,
                threshold: max,
                warningClass: "label label-success",
                limitReachedClass: "label label-important",
                separator: ' из ',
                preText: 'Введено ',
                postText: ' символов',
                validate: true
            });
        });
    });
</script>


