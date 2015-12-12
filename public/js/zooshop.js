
/* Показ флэш-сообщений */
var setFlash = function(type, message) {
	$('<div class="alert alert-'+type+'" role="alert">'+message+
		'<button type="button" class="close" data-dismiss="alert" aria-label="Закрыть">'+
		'<span aria-hidden="true">&times;</span></button></div>').appendTo('.alerts');
	if (type !== 'danger') {
		$(".alert").fadeOut(7000);
		$().alert('close');
	}
};

/* Взаимодействие с сервером */
var getData = function(type, url, data, noasync) {
	var answer = {};
	$.ajax({
		type: type,
		url: url,
		data: (data === 'undefined') ? '' : data,
		success: function(data) {
			answer.status = data.status;
			answer.description = data.description;
		},
		error: function(request, status, error) {
			answer.status = 'danger';
			answer.description = 'Не удалось соединиться с сервером: ' + error;
		},
		async: (noasync === 'undefined')
	});
	return answer;
};

/* Основной функционал */
$(document).ready(function() {

    /* Простейший скороллинг для удобства просмотра таблицы с результатами поиска */
    $.fn.scrollTo = function( target, options, callback ){
        if(typeof options == 'function' && arguments.length == 2){ callback = options; options = target; }
        var settings = $.extend({
            scrollTarget  : target,
            offsetTop     : 50,
            duration      : 1000,
            easing        : 'swing'
        }, options);
        return this.each(function(){
            var scrollPane = $(this);
            var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
            var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
            scrollPane.animate({scrollTop : scrollY }, parseInt(settings.duration), settings.easing, function(){
                if (typeof callback == 'function') { callback.call(this); }
            });
        });
    };

    /* Подсветка меню */
    $('a[href="' + this.location.pathname + '"]').parent().addClass('active');

	/* Обработка клика на кнопках "Добавить" и "Посмотреть" */
	$('.store').click(function(e) {
		e.preventDefault();
		$('.store').parent().removeClass('active');
		$(this).parent().addClass('active');
		var action = $(this).attr('id'),
            category = $('#category');
		if (action === 'show') {
			category.attr('multiple', 'true');
			$('input[name="name"]').removeAttr('required');
            category.attr('size', 3);
			$(".submit-button").html('Запросить');
		} else if (action === 'add') {
			$('input[name="name"]').attr('required', 'true');
            category.removeAttr('multiple');
            category.removeAttr('size');
			$(".submit-button").html('Сохранить');
		}
	});

	/* Обработка клика на чекбоксы */
	$("input:checkbox.check").click(function(){
		var animals = "животное";
		if ($("#category").val() === animals) {
			$("input:checkbox.check").not($(this)).removeAttr("checked");
			$(this).attr("checked");
		}
	});

	/* Обработка кнопки отправки формы */
	$("#store").submit(function(e) {
        e.preventDefault();
		var type = $('li[role=presentation].active').text(),
			url = "", data = "",
			formData = $(this).serialize(); // the script where you handle the form input.

		if (type === "Добавить") {

			if ($("input:checkbox.check:checked").length !== 0) {
				url = '/add';
				data = getData('POST', url, formData, noasync = true);
				if (data.status === 'success') {
					setFlash(data.status, data.description);
					$('#store')[0].reset();
				} else if (data.status === 'danger') {
					setFlash(data.status, data.description);
				}
			} else {
				setFlash('danger', 'Не выбрана подкатегория');
			}

		} else if (type === "Посмотреть") {
			url = '/select';
			data = getData('POST', url, formData, noasync = true);

			if (data.status === 'success') {
				setFlash(data.status, 'Запрос выполнен успешно');
				$('.results').empty();
				console.log(data);
				$('.container').append(data.description);
                $('body').scrollTo('.results');
			} else {
				setFlash(data.status, data.description);
			}

		}
	});
});
