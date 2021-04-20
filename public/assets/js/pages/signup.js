$(document).ready(function() {

  $('#inputCep').on('keypress', function(event) {
    var str = $('#inputCep').val();
    var regex = new RegExp("^[0-9]+$");
    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
    if (!regex.test(key) || str.length > 7) {
       event.preventDefault();
       return false;
    }
  });

  $('#btnCep').on('click', function() {
    var inputCidade = $('#inputCidade');
    var inputEstado = $('#inputEstado');
    var inputBairro = $('#inputBairro');
    var inputCep    = $('#inputCep');
    var cep         = inputCep.val();
    var inputLogradouro  = $('#inputLogradouro');

    $.ajax({
      method: 'get',
      url: `http://viacep.com.br/ws/${cep}/json/`,
    }).done(function(data) {
      inputCidade.val(data.localidade);
      inputEstado.val(data.uf);
      inputBairro.val(data.bairro);
      inputLogradouro.val(data.logradouro);
    });
  });

  $('#btnSignup').on('click', function() {
    var alrt   = $('#alert');
    var user   = $('#inputUser').val();
    var pass   = $('#inputPass').val();
    var name   = $('#inputName').val();
    var email  = $('#inputEmail').val();
    var cep    = $('#inputCep').val();
    var cidade = $('#inputCidade').val();
    var estado = $('#inputEstado').val();
    var bairro = $('#inputBairro').val();
    var numero = $('#inputNum').val();
    var complemento = $('#inputComplemento').val();
    var logradouro  = $('#inputLogradouro').val();

    if (validateAll()) {
      $.ajax({
        method: 'post',
        url: 'users/create',
        data: {
          user, pass, name, email,
          cep, cidade, estado, bairro,
          numero, complemento, logradouro,
        }
      }).done(function(data) {
        $data = JSON.parse(data);
        if (data.status) {
          window.location.href = basePath + '/';
        } else {
          alrt.html(helper.alert('danger', data.message));
        }
      });
    } else {
      alrt.html(helper.alert('danger', 'Um ou mais campos são inválidos'));
    }

  });

  function validateAll() {
    var inputUser   = $('#inputUser');
    var inputPass   = $('#inputPass');
    var inputPass2  = $('#inputPass2');
    var inputName   = $('#inputName');
    var inputEmail  = $('#inputEmail');
    var inputCep    = $('#inputCep');
    var inputCidade = $('#inputCidade');
    var inputEstado = $('#inputEstado');
    var inputBairro = $('#inputBairro');
    var inputNumero = $('#inputNum');
    var inputComplemento = $('#inputComplemento');
    var inputLogradouro  = $('#inputLogradouro');
    var bool    = true;
    var arrFail = [];

    helper.clearFieldValidation([
      inputUser, inputPass, inputPass2, inputName, inputEmail, inputCidade, inputCep,
      inputEstado, inputBairro, inputNumero, inputComplemento, inputLogradouro
    ]);

    if (!helper.validate(inputUser.val(), ['required', 'text'])) {
      arrFail.push(inputUser);
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

    if (inputPass2.val() !== inputPass.val()) {
      arrFail.push(inputPass);
      arrFail.push(inputPass2);
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

    if (!helper.validate(inputCep.val(), ['required'])) {
      arrFail.push(inputCep);
      bool = false;
    }

    if (!helper.validate(inputCidade.val(), ['required'])) {
      arrFail.push(inputCidade);
      bool = false;
    }

    if (!helper.validate(inputEstado.val(), ['required'])) {
      arrFail.push(inputEstado);
      bool = false;
    }

    if (!helper.validate(inputBairro.val(), ['required'])) {
      arrFail.push(inputBairro);
      bool = false;
    }

    if (!helper.validate(inputLogradouro.val(), ['required'])) {
      arrFail.push(inputLogradouro);
      bool = false;
    }

    if (!helper.validate(inputNumero.val(), ['required', 'number'])) {
      arrFail.push(inputNumero);
      bool = false;
    }

    if (!helper.validate(inputComplemento.val(), ['required', 'textnumber'])) {
      arrFail.push(inputComplemento);
      bool = false;
    }

    if (!bool) {
      helper.addFieldValidation(arrFail, false);
    }

    return bool;
  }

});