$(document).ready(function () {

  $('#inputUser').keypress(function (e) {
    if (e.which == 13) { $('#inputPass').focus(); return false; }
  });

  $('#inputPass').keypress(function (e) {
    if (e.which == 13) { $('#btnLogin').trigger('click'); return false; }
  });

  $('#btnLogin').on('click', function () {
    var inputUser = $('#inputUser');
    var inputPass = $('#inputPass');
    var alrt = $('#alert');
    var user = inputUser.val();
    var pass = inputPass.val();

    helper.clearFieldValidation([inputUser, inputPass]);

    if (helper.validate(user, ['required', 'text']) && helper.validate(pass, ['required'])) {
      pass = MD5(pass);
      $.ajax({
        method: 'get',
        url: `${basePath}/users/auth`,
        data: { user, pass }
      }).done(function (data) {
        data = JSON.parse(data);
        if (data.status) {
          if (data.admin === 1) {
            window.location.href = `${basePath}/admin`;
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