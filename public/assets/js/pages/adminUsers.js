$(document).ready(function() {

  var arrUsers = [];

  // Getting all users:
  $.ajax({
    method: 'get',
    url: basePath + '/users'
  }).done(function(data) {
    let users = $('#users');
    data = JSON.parse(data);
    if (data.status) {
      arrUsers = data.users;
      data.users.forEach((e, index) => {
        users.append(`
        <tr id="${index}">
          <th scope="row">${e.id}</th>
          <td>${e.name}</td>
          <td>${e.email}</td>
          <td align="end" class="d-flex">
            <button id="${index}" class="btn btn-primary mx-1">Ver</button>
            <button id="${e.id}" class="btn btn-danger mx-1">Remover</button>
          </td>
        </tr>
        `);
      });
    }
  });

  $(document).on('click', 'button#btnCreate', function() {
    let alrt   = $('#alert');
    let users  = $('#users');
    let login  = $('#inputLogin').val();
    let name   = $('#inputName').val();
    let email  = $('#inputEmail').val();
    let pass   = $('#inputPass').val();
    let admin  = $('#inputAdmin').is(':checked');

    if (validateAll()) {
      $.ajax({
			  method: 'post',
			  url: basePath + '/users/create',
			  data: { login, name, email, 
          pass, admin, hasClient: false }
			}).done(function(data) {
		    data = JSON.parse(data);
		  	if (data.status) {
          arrUsers.splice(arrUsers.length, 0, data.user);
          users.append(`
          <tr id="${arrUsers.length}">
            <th scope="row">${data.user.id}</th>
            <td>${data.user.name}</td>
            <td>${data.user.email}</td>
            <td align="end" class="d-flex">
              <button id="${arrUsers.length}" class="btn btn-primary mx-1">Ver</button>
              <button id="${data.user.id}" class="btn btn-danger mx-1">Remover</button>
            </td>
          </tr>
          `);
          closeCreateModal();
		  	} else {
		  		alrt.html(helper.alert('danger', data.message));
		  	}
		  });
    }
  });

  function closeCreateModal() {
    let inputLogin  = $('#inputLogin');
    let inputName   = $('#inputName');
    let inputEmail  = $('#inputEmail');
    let inputPass   = $('#inputPass');
    let inputAdmin  = $('#inputAdmin');
    helper.clearFieldValidation([inputPass, inputLogin, inputName, inputEmail, inputAdmin]);    
    inputLogin.val('');
    inputName.val('');
    inputEmail.val('');
    inputPass.val('');
    inputAdmin.val('');
    $('#createModal').modal('hide');
  }

  function validateAll() {

    let inputLogin  = $('#inputLogin');
    let inputName   = $('#inputName');
    let inputEmail  = $('#inputEmail');
    let inputPass   = $('#inputPass');
    let inputAdmin  = $('#inputAdmin');
    var bool    = true;
    var arrFail = [];

    helper.clearFieldValidation([inputPass, inputLogin, inputName, inputEmail, inputAdmin]);

    if (!helper.validate(inputLogin.val(), ['required', 'text'])) {
      arrFail.push(inputLogin);
      bool = false;
    }

    if (!helper.validate(inputName.val(), ['required', 'text'])) {
      arrFail.push(inputName);
      bool = false;
    }

    if (!helper.validate(inputEmail.val(), ['required', 'email'])) {
      arrFail.push(inputEmail);
      bool = false;
    }

    if (!helper.validate(inputPass.val(), ['required'])) {
      arrFail.push(inputPass);
      bool = false;
    }

    if (!bool) {
      helper.addFieldValidation(arrFail, false);
    }

    return bool;
  }

});