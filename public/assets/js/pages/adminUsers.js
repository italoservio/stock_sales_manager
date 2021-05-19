$(document).ready(function () {

  var arrUsers = [];
  var id = 0;
  var editingIndex = -1;

  // Getting all users:
  $.ajax({
    method: 'get',
    url: basePath + '/users'
  }).done(function (data) {
    let users = $('#users');
    data = JSON.parse(data);
    if (data.status) {
      arrUsers = data.users;
      reloadTable();
    }
  });

  // Open modal to creation:
  $(document).on('click', 'button.actCreate', function () {
    $('.spanContext').html('Criar');
    $('#createModal').modal('show');
  });

  // Open modal to edition:
  $(document).on('click', 'button.actEdit', function () {
    $('.spanContext').html('Editar');
    $('#inputLogin').attr('disabled', 'disabled');
    let index = $(this).attr("id");
    let element = arrUsers[index];

    id = element.id;
    editingIndex = index;
    $('#inputLogin').val(element.login);
    $('#inputName').val(element.name);
    $('#inputEmail').val(element.email);
    if (element.admin === 1) $("#inputAdmin").prop("checked", true);

    $('#createModal').modal('show');
  });

  // Remove user:
  $(document).on('click', 'button.actRemove', function () {
    let index = $(this).attr("id");
    let element = arrUsers[index];
    $.ajax({
      method: 'delete',
      url: basePath + `/users/delete/${element.id}`,
    }).done(function (data) {
      data = JSON.parse(data);
      if (data.status) {
        arrUsers.splice(index, 1);
        reloadTable();
      }
    });
  });

  // Action to button create/edit user:
  $(document).on('click', 'button#btnCreate', function () {
    let alrt = $('#alert');
    let login = $('#inputLogin').val();
    let name = $('#inputName').val();
    let email = $('#inputEmail').val();
    let pass = $('#inputPass').val();
    let admin = $('#inputAdmin').is(':checked');

    if (pass !== '' && arrUsers[editingIndex].pass !== MD5(pass)) pass = MD5(pass);
    else pass = arrUsers[editingIndex].pass;

    if (validateAll()) {
      $.ajax({
        method: 'post',
        url: basePath + '/users/create',
        data: {
          login, name, email,
          pass, admin, id, hasClient: false
        }
      }).done(function (data) {
        data = JSON.parse(data);
        if (data.status) {
          if (id !== 0) {
            // If editing, is necessary to remove from the array "users" the old user:
            arrUsers.splice(editingIndex, 1);
          }
          // Add returned user to array "users":
          arrUsers.splice(arrUsers.length, 0, data.user);
          reloadTable();
          closeCreateModal();
        } else {
          alrt.html(helper.alert('danger', data.message));
        }
      });
    }
  });

  function reloadTable() {
    let users = $('#users');
    users.html('');
    arrUsers.forEach((e, index) => {
      users.append(`
      <tr id="${index}">
        <th scope="row">${e.id}</th>
        <td>${e.name}</td>
        <td>${e.email}</td>
        <td align="center">
          <button id="${index}" class="btn btn-primary mx-1 actEdit">Editar</button>
          <button id="${index}" class="btn btn-danger mx-1 actRemove">Remover</button>
        </td>
      </tr>
      `);
    });
  }

  $(document).on('click', 'button#btnCancel', function () {
    closeCreateModal();
  });

  function closeCreateModal() {
    id = 0;
    editingIndex = -1;
    $('input').removeAttr('disabled');
    $("#inputAdmin").prop("checked", false);
    let inputLogin = $('#inputLogin');
    let inputName = $('#inputName');
    let inputEmail = $('#inputEmail');
    let inputPass = $('#inputPass');
    let inputAdmin = $('#inputAdmin');
    helper.clearFieldValidation([inputPass, inputLogin, inputName, inputEmail, inputAdmin]);
    inputLogin.val('');
    inputName.val('');
    inputEmail.val('');
    inputPass.val('');
    inputAdmin.val('');
    $('#createModal').modal('hide');
  }

  function validateAll() {
    let inputLogin = $('#inputLogin');
    let inputName = $('#inputName');
    let inputEmail = $('#inputEmail');
    let inputPass = $('#inputPass');
    let inputAdmin = $('#inputAdmin');
    var bool = true;
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

    if (!bool) {
      helper.addFieldValidation(arrFail, false);
    }

    return bool;
  }

});