<script type="text/javascript">

    Autocomplete={
        init  : function(){
            //  var filters = $('#{{$id_form}}');
            var filters = $('#{{$id_form}} input[data-provide]');
            filters.each(function(){
                var url = $(this).attr('data-autocomplete-url');
                var id = $(this).attr('id');
                $('#'+id).autocomplete({
                    minChars:2,
                    serviceUrl: url
                });

            })
        }
    };

    $(document).ready(function() {
        Autocomplete.init();

    });
    /*
    $(document).ready(function() {

        $.get('example_collection.json', function(data){
            $("#name").typeahead({ source:data });
        },'json');
        Autocomplete.init('{{$id_form}}');

    });*/
</script>


