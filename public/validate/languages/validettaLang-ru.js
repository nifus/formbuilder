(function($){
	$.fn.validettaLanguage = function(){	
	}
	$.validettaLanguage = {
		init : function(){
			$.validettaLanguage.messages = {
				empty		: 'Заполните пожалуйста поле',
				email		: 'Введите корректный E-Mail',
				number		: 'Поле может содержать только цифровые значения',
				maxLength	: 'Максимальная длинна {count} символов',
				minLength	: 'Минимальная длинна {count} символов! ',
				checkbox	: 'Bu alanı işaretmeleniz gerekli. Lütfen kontrol ediniz.',
				maxChecked	: 'En fazla {count} seçim yapabilirsiniz. Lütfen kontrol ediniz.',
				minChecked	: 'En az {count} seçim yapmalısınız. Lütfen kontrol ediniz.',
				selectbox	: 'Выберите пожалуйста',
				maxSelected : 'En fazla {count} seçim yapabilirsiniz. Lütfen kontrol ediniz.',
				minSelected : 'En az {count} seçim yapmalısınız. Lütfen kontrol ediniz.',
				notEqual	: 'Alanlar birbiriyle oyuşmuyor. Lütfen kontrol ediniz',
				creditCard	: 'Kredi kartı numarası geçersiz. Lütfen kontrol ediniz.'
			};
		}
	}
	$.validettaLanguage.init();
})(jQuery);