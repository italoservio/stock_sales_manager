$(document).ready(function() {

	$('#btnLogin').on('click', function() {
		var inputUser = $('#inputUser');
		var inputPass = $('#inputPass');
		var alrt = $('#alert');
		var user = inputUser.val();
		var pass = inputPass.val();

		helper.clearFieldValidation([inputUser, inputPass]);

		if (helper.validate(user, ['required', 'text']) && helper.validate(pass, ['required'])) {
			$.ajax({
			  method: 'get',
			  url: 'users/auth',
			  data: { user, pass }
			}).done(function(data) {
		    data = JSON.parse(data);
		  	if (data.status) {
		  		if (data.admin === 1) {
		  			window.location.href = 'admin';
		  		} else {
		  			window.location.href = basePath + '/';
		  		}
		  	} else {
		  		alrt.html(helper.alert('danger', data.message));
		  	}
		  });
		} else {
			alrt.html(helper.alert('danger', 'Um ou mais campos são inválidos'));
			helper.addFieldValidation([inputUser, inputPass], false);
		}
	});

});