<script>
    $(function () {
        var o2more = new One2More();
        o2more.init('{{$id_form}}');
        o2more.setCols({{$cols}});
        o2more.setData({{$data}});
        o2more.render();

    });
</script>