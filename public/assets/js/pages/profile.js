$(document).ready(function() {

  $('#inputLogin').val(userLogin);
  $('#inputName').val(userName);;
  $('#inputEmail').val(userEmail);

  var imgChanged = false;

  $('#btnEdit').on('click', function() {
    let alrt   = $('#alert');
    let inputPass   = $('#inputPass');
    let inputPass2  = $('#inputPass2');
    let login  = $('#inputLogin').val();
    let name   = $('#inputName').val();
    let email  = $('#inputEmail').val();
    let pass   = $('#inputPass').val();
    let pass2  = $('#inputPass2').val();

    if (validateAll()) {
      if (pass === pass2) {

        alert('passou');

      } else {
        helper.clearFieldValidation([inputPass, inputPass2]);
        helper.addFieldValidation([inputPass, inputPass2], false);
        alrt.html(helper.alert('danger', 'Os dois campos de senha devem ser iguais'));
      }
    } else {
      alrt.html(helper.alert('danger', 'Por favor preencha todos os campos para continuar'));
    }
  });

  function validateAll() {
    let inputLogin  = $('#inputLogin');
    let inputName   = $('#inputName');
    let inputEmail  = $('#inputEmail');
    let inputPass   = $('#inputPass');
    let inputPass2   = $('#inputPass2');
    var bool    = true;
    var arrFail = [];

    helper.clearFieldValidation([inputPass, inputPass2, inputLogin, inputName, inputEmail]);

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

    if (!helper.validate(inputPass2.val(), ['required'])) {
      arrFail.push(inputPass2);
      bool = false;
    }

    if (!bool) {
      helper.addFieldValidation(arrFail, false);
    }

    return bool;
  }

});