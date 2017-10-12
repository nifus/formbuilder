Validator = function (id) {
    this.formId = id;
    this.checkArray = new Array();
    this.errorArray = new Array();
    $('#' + id).data('formCheck', this);
    $('#' + id).submit(function (event) {
        return $(this).data('formCheck').check();
    })

    /**
     * регистрируем проверку контрола с именем name и функцией check
     *
     */
    Validator.prototype.registerCheck = function (name, check) {
        if (!check.event || check.event == 'submit') {
            this.checkArray[ this.checkArray.length ] = {'name': name, 'check': check};
        }
    }


    Validator.prototype.registerCheck4From = function () {
        var validator = this;
        $('#' + id).find('*[data-required]').each(function(){
            var name = $(this).attr('name');
            var error = $(this).attr('data-error-msg');
            var rules_string = $(this).attr('data-required');
            var rules = rules_string.split('|');
            for( i in rules ){
                if ( rules[i]=='email' ){
                    validator.registerCheck(name,{func:'checkEmail',onlyTrue:false,msg:error});
                }
                if ( rules[i]=='required' ){
                    validator.registerCheck(name,{func:'checkEmpty',onlyTrue:false,msg:error});
                }
            }
            /*
            if ( $(this).is('select') ){
                validator.registerCheck(name,'checkZero');
            }
            if ( $(this).is('checkbox') ){
                validator.registerCheck(name,'checkChecked');
            }
            if ( $(this).is('radio') ){
                validator.registerCheck(name,'checkChecked');
            }*/
        });
    }

    Validator.prototype.check = function () {
        var count_error = 0;

        this.registerCheck4From();
        //  перебераем список контролов, которые нужно проверить
        //  массив типа {name - имя контрола, check - {link,onlyTrue,func,error - функция проверки значения} }

        for ( var i in this.checkArray) {

            var o = this.checkArray[i];

            // не очень понятно зачем
            if ( o.check.link && o.check.link === true) {
                var link = $(o.name)
            } else {
                var link = $('#' + this.formId + ' *[name=' + o.name + ']');
            }
            this.__clearError(link);
            //  если контрол текстовое поле однострочное/многострочное
            if ( link.is('input') || link.is('textarea')) {
                count_error+=this.__checkTextFields(link,o);
            }
            //  селекты
            if ($('#' + this.formId + ' *[name=' + o.name + ']').is('select')) {
                if ($('#' + this.formId + ' *[name=' + o.name + ']').length == 0) {
                    alert('Элемент #' + this.formId + ' *[name=' + o.name + '] не найден');
                    return false;
                }
                o.control = $('#' + this.formId + ' *[name=' + o.name + ']');
                if (o.control.length == 1) {
                    //  поле проверяется только в том случае, если все предыдущие правила были выполнены корректно
                    if ((o.check.onlyTrue == true && count_error == 0) || !o.check.onlyTrue) {
                        result = o.check.func(o.control.val(), o.check);
                        if (typeof(o.check.error) == 'function') {
                            result = o.check.error(result, o.control);
                        }
                        if (false == result) {
                            count_error++;
                        }
                        if (typeof(o.check.error) == 'string' && false == result) {
                            if (o.check.error != '') {
                                error = o.check.error;
                            }
                            else {
                                error = 'Ошибка: поле "' + o.control.attr('name') + '" не заполнено!';
                            }

                            this.errorArray[ this.errorArray.length ] = error;
                        }
                    }
                    continue;
                }
                if (o.control.length > 1) {
                    //  режим, при котром контрол проверяется, только если предыдущие были заполнены корректно
                    if ((o.check.onlyTrue == true && count_error == 0) || !o.check.onlyTrue) {
                        //  начинаем перебирать группу
                        o.control.each(function (j) {
                            cntr = o.control[j]

                            result = o.check.func($(cntr).val(), o.check, cntr);

                            if (typeof(o.check.error) == 'function') {
                                result = o.check.error(result, $(cntr));
                            }
                            if (false == result) {
                                count_error++;
                            }
                            if (typeof(o.check.error) == 'string' && false == result) {
                                if (o.check.error != '') {
                                    error = o.check.error;
                                }
                                else {
                                    error = 'Ошибка: поле "' + cntr.attr('name') + '" не заполнено!';
                                }
                                this.errorArray[ this.errorArray.length ] = error;
                            }
                        });
                    }
                }
            }
        }
        //console.log(count_error);

        if (count_error > 0) {
            return false;
        }
        return true;
    };


    Validator.prototype.setError = function (msg) {
        this.errorArray[ this.errorArray.length ] = {'msg': msg};
    }

    this.checkEmpty = function (value, par) {
        if (value == '') {
            return false;
        }
        return true;
    }

    this.checkCheckedOn = function (value, par) {
        return value;
    }

    this.checkDigit = function (value, par) {
        if (value.match(/^\d+$/) === null) {
            return false;
        }
        return true;
    }
    this.checkMore = function (value, par, control) {
        if (!value) {
            return false;
        }

        if ($(control).attr('max') < value) {
            return false;
        }
        return true;
    }

    this.checkChecked = function (value, par) {
        if (value == '') {
            return false;
        }
        return true;
    }

    this.checkUrl = function (value, par) {

        if (value == '' || value.match(/^http(s?):\/\/[-\w\.]{3,}\.[A-Za-z]{2,8}$/) == null) {
            return false;
        }
        return true;
    }

    this.checkZero = function (value, par) {
        if (value == 0) {
            return false;
        }
        return true;
    }

    this.checkEmail = function (email) {
        return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z0-9]{2,5}$/i).test(email);
    }

    Validator.prototype.__checkTextFields = function (link,o) {
        var count_error = 0;
        //  получаем на него короткую ссылку
        if (link.length == 0) {
            alert('Элемент #' + this.formId + ' *[name=' + o.name + '] не найден');
            return false;
        }

        o.control = link;

        //  поле проверяется только в том случае, если все предыдущие правила были выполнены корректно
        //  бывает нужно находить только 1 ошибку и выдавать её, как раз для этого
        if ((o.check.onlyTrue == true && count_error == 0) || !o.check.onlyTrue) {
            //  контрол 1 ( бывает, что контролов целый массив )
            if (o.control.length == 1) {
                var value = o.control.val();
                var func = this[o.check.func];
                var result = func(value, o.check, o.control);
                //  полученный результат обработаем пользовательской функцией

                if (typeof(o.check.error) == 'function') {
                    result = o.check.error(result, o.control);
                }
                if (false == result) {
                    count_error++;
                        if ( !o.check.msg ){
                            msg = false;
                        }else{
                            msg = o.check.msg;
                        }
                        this.__fireError(o.control,msg);

                }

                //  если
                if (typeof(o.check.error) == 'string' && false == result) {

                    if (o.check.error != '') {
                        var error = o.check.error;
                    }
                    else {
                        var error = 'Ошибка: поле "' + o.control.attr('name') + '" не заполнено!';
                    }
                    this.errorArray[ this.errorArray.length ] = error;
                }

            }

            if (o.control.length > 1) {
                o.control.each(function (j) {
                    var cntr = o.control[j]
                    var value = cntr.val()

                    result = o.check.func(value, o.check, cntr);
                    if (typeof(o.check.error) == 'function') {
                        result = o.check.error(result, cntr);
                    }
                    if (false == result) {
                        count_error++;
                    }
                    if (typeof(o.check.error) == 'string' && false == result) {
                        if (o.check.error != '') {
                            var error = o.check.error;
                        }
                        else {
                            var error = 'Ошибка: поле "' + cntr.attr('name') + '" не заполнено!';
                        }
                        this.errorArray[ this.errorArray.length ] = error;
                    }
                });
            }
        }
        return count_error;
    }

    Validator.prototype.__fireError = function(element,msg){
        if ( false!==msg ){
            element.attr('data-toggle','tooltip').attr('data-placement',"right").attr('data-original-title',msg).attr('data-trigger','focus')
        }
        element.tooltip('show')
        element.parent('div.control-group').addClass('has-error');
        //console.log(msg);
    }

    Validator.prototype.__clearError = function(element){
        element.removeAttr('data-toggle').removeAttr('data-placement').removeAttr('data-original-title').removeAttr('data-trigger').parent('div.control-group').removeClass('has-error')
    }
}


