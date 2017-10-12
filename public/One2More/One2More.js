

var One2More = function () {
    this.modal = false;
    this.htmlTmpl = false;
    this.form = false;
    this.data = new Array();
    this.cols = new Array();

    this.setCols = function (cols) {
        this.cols = cols;
    };

    this.setData = function (data) {
        this.data = data;
    };

    this.init = function (form) {
        this.modal = $('#modal_' + form).find('div.modal-body div.row');
        this.htmlTmpl = this.modal.html();
        this.modal.html('');
        this.form = form;
        var One2More = this;
        $('body').on('click', 'button[data-action]', function () {
            var action = $(this).attr('data-action');
            var url = $(this).attr('data-url');
            var id = $(this).attr('data-id');
            if (action == 'delete') {
                One2More.delete($(this).attr('data-id'))
            }
            if (action == 'edit') {
                One2More.edit(id);
            }
            if (action == 'create') {
                One2More.edit(null);
            }
            if (action == 'save') {

               //$('#sbt_form').submit();
            }

        });
        $('#modal_sub_data').find('button[data-action="save"]').click(function(){
            form = $('#modal_sub_data').find('form');
            var valid = new Validator( $('#'+One2More.form).attr('id') );
            if ( valid.check() ){
                One2More.save($(this));
            }else{
                return false;
            }
        })
        //this.valid();
    };



    /*this.valid = function(){
        var One2More = this;
    }*/

    this.save = function(button) {
        var name_form = button.attr('data-elements');
        var i = button.attr('data-id');
        var object = this;
        if (i == undefined) {
            var i = object.data.length;
        }
        object.data[i] = new Array();
        $('*[data-form="' + name_form + '"]').each(function (index) {
            if ($(this).is(':radio') || $(this).is(':checkbox')) {
                if ($(this).is(':checked')) {
                    object.data[ i ][ $(this).attr('data-name') ] = {'value': $(this).val(), 'name': $(this).attr('data-name'), 'label': $(this).attr('data-label')};
                }

            } else {
                object.data[ i ][ $(this).attr('data-name') ] = {'value': $(this).val(), 'name': $(this).attr('data-name'), 'label': $(this).attr('data-label')};

            }
        });
        object.render();
        $('#modal_sub_data').modal('toggle');
    };


    this.delete = function (id) {
        delete this.data[id];
        this.render();
    };

    this.edit = function (id) {
        this.modal.html(this.htmlTmpl);
        $('#modal_sub_data').modal('show');
        if (id != null) {
            var data = this.data[id];

            $('*[data-form="' + this.form + '"]').each(function (index) {
                name = $(this).attr('data-name')
                if (!data[name]) {
                    //console.log(data[name]);
                    $(this).removeAttr('checked');
                    return true;
                }

                if ($(this).is(':radio') || $(this).is(':checkbox')) {


                    if ($(this).val() == data[name].value) {
                        $(this).attr('checked', 'checked');
                    } else {
                        $(this).removeAttr('checked');
                    }

                } else {
                    $(this).val(data[name].value);

                }

            });
            $('#modal_sub_data').find('button[data-action="save"]').attr('data-id', id);
        } else {
            $('#modal_sub_data').find('button[data-action="save"]').removeAttr('data-id');
        }


    };

    this.render = function () {
        var form_name = this.form;
        var html = $('<div/>');
        for (var i in this.data) {
            var row = $('<div class="row thumbnail" style="margin-bottom:20px;"></div>');
            for (var j in this.data[i]) {
                //console.log(this.data[i][j])
                var value = this.data[i][j].value;
                var label = this.data[i][j].label;
                var name = this.data[i][j].name;

                if ( name!=undefined ){
                    var hidden = $('<input type="hidden" name="' + form_name + '[' + name + '][' + i + ']" value="' + value + '">');
                    row.append(hidden);
                }
                if (!this.cols[name]) {

                    continue;
                }

                row.append('<div class="col-md-5"><strong>' + label + '</strong></div>');
                row.append('<div class="col-md-5">' + value + '</div>');

            }
            var btn = $('<div class="col-md-10" style="margin-top:10px"></div>').
                append(
                    $('<div class="btn-group"></div>').append(
                            $('<button type="button" class="btn btn-danger" data-id="' + i + '" data-action="delete">Удалить</button>').data('data-object', this)
                        ).append(
                            $('<button type="button" class="btn btn-danger" data-id="' + i + '" data-action="edit">Изменить</button>').data('data-object', this)
                        )
                );
            row.append(btn);
            html.append(row);
        }

        $('#container_' + this.form).html(html);
    }

}

