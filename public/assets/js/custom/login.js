$( document ).ready( function () {
	$('.ajaxFormInit').ajaxForm( {
		success: function (data, textStatus, jqXHR) {
			if(!data.success) {
				Lobibox.notify('error',
					{ msg: data.msg }
				)
			} else {
				Lobibox.notify('success',
					{ msg: data.msg }
				)

				if(data.redirect) {
					setTimeout(function() {
						window.location.replace(data.redirect);
					}, 2500);
				}
			}
		},
		error: function (jqXHR, textStatus, errorText) {
			Lobibox.notify('error', { msg: errorText });
		}
	})
});
