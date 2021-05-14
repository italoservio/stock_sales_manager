$(document).ready(function() {

  $('#inputLogin').val(userLogin);
  $('#inputName').val(userName);;
  $('#inputEmail').val(userEmail);
  if (hasClient) {
    $('#inputCep').val(clientCep);
    $('#inputCidade').val(clientCidade);
    $('#inputEstado').val(clientEstado);
    $('#inputBairro').val(clientBairro);
    $('#inputNum').val(clientNumero);
    $('#inputComplemento').val(clientComplemento);
    $('#inputLogradouro').val(clientLogradouro);
  }

  var imgChanged = false;

  $('#btnSaveImage').on('click', function() {
    let inputFile = $('#inputFile');
    let formData = new FormData();
    let imgFile = inputFile.prop('files')[0];

    if (imgFile !== undefined) {
      formData.append('imgFile', imgFile);
      $.ajax({
        method: 'post',
        url: basePath + `/users/${userId}/image`,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
      }).done(function (data) {
        if (data.status) {
          Swal.fire(
            'Ação confirmada',
            'Sua imagem será carregada na próxima vez que entrar na aplicação',
            'success'
          );
        }
      });
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

  $('#btnEdit').on('click', function() {
    let form = {};
    let alrt   = $('#alert');
    let inputPass   = $('#inputPass');
    let inputPass2  = $('#inputPass2');
    let name   = $('#inputName').val();
    let email  = $('#inputEmail').val();
    let pass   = $('#inputPass').val();
    let pass2  = $('#inputPass2').val();
    if (hasClient) {
      var cep    = $('#inputCep').val();
      var cidade = $('#inputCidade').val();
      var estado = $('#inputEstado').val();
      var bairro = $('#inputBairro').val();
      var numero = $('#inputNum').val();
      var complemento = $('#inputComplemento').val();
      var logradouro  = $('#inputLogradouro').val();
    }

    if (validateAll()) {
      if (pass === pass2) {

        if (pass !== '' && MD5(pass) !== userOldPass) pass = MD5(pass);
        else pass = userOldPass;

        userAdmin = (userAdmin === '1');

        if (hasClient) {
          form = {
            id: userId, login: userLogin, name, email,
            cep, cidade, estado, bairro, numero, complemento, logradouro,
            pass, admin: userAdmin, clientId: clientId, hasClient: hasClient
          }
        } else {
          form = {
            id: userId, login: userLogin, name, email,
            pass, admin: userAdmin, hasClient: hasClient
          }
        }

        $.ajax({
          method: 'post',
          url: basePath + '/users/create',
          data: form
        }).done(function(data) {
          data = JSON.parse(data);
          if (data.status) {
            window.location.href = `${basePath}/`;
          } else {
            alrt.html(helper.alert('danger', data.message));
          }
        });

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

    if (hasClient) {
      var inputCep    = $('#inputCep');
      var inputCidade = $('#inputCidade');
      var inputEstado = $('#inputEstado');
      var inputBairro = $('#inputBairro');
      var inputNumero = $('#inputNum');
      var inputComplemento = $('#inputComplemento');
      var inputLogradouro  = $('#inputLogradouro');

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
    }

    if (!bool) {
      helper.addFieldValidation(arrFail, false);
    }

    return bool;
  }

});