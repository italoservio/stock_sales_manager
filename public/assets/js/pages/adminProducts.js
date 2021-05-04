$(document).ready(function () {
  let category = 0;
  var arrProducts = [];
  var id = 0;
  $.ajax({
    method: 'get',
    url: basePath + '/products',
    data: {category}
  }).done(function (p_data) {
    p_data = JSON.parse(p_data);
    if (p_data.status) {
      arrProducts = p_data.products;
      reloadTable(p_data.products);
    }
  });

  $.ajax({
    method: 'get',
    url: basePath + '/categories'
  }).done(function (p_data) {
    p_data = JSON.parse(p_data);
    if (p_data.status) {
      let categories = $('#categories');
      p_data.categories.map(e => {
        categories.append(`        
            <option value="${e.id}">${e.name}</option>`);
      });
    }
  });

  $(document).on('click', 'button.actEdit', function () {
    $('#inputNameCheck').show();
    $('.spanContext').html('Editar');
    $('#inputId').attr('disabled', 'disabled');
    id = $(this).attr("id");
    let index = id - 1;
    let element = arrProducts[index];

    $('#inputId').val(element.name);
    $('#createModal').modal('show');
  });

  $(document).on('click', 'button#btnCreate', function () {
    let data = new FormData();

    data.append('fileName', $('#fileimagem')[0].files[0]["name"]);
    data.append('fileimagem', $('#fileimagem')[0].files[0]);
    data.append('fileType', $('#fileimagem').prop('files')[0]["type"]);
    data.append('inputName', $('#inputName').val());
    data.append('inputQtd', $('#inputQtd').val());
    data.append('inputPrice', $('#inputPrice').val());
    data.append('categories', $('#categories').val());
    data.append('des', $('#inputDes').val());
    console.log($('#categories').val());

    if (true) {
      $.ajax({
        method: 'post',
        url: basePath + '/products/create',
        data: data,
        processData: false,
        contentType: false
      }).done(function (p_data) {
      });
    }
  });

  $(document).on('click', 'button.actCreate', function () {
    $('#inputNameCheck').hide();
    $('#inputId').val('');
    $('.spanContext').html('Criar');
    $('#createModal').modal('show');

  });

  function reloadTable(p_data) {
    let products = $('#products');
    products.html('');
    p_data.map(e => {
        let imgPath = `${assetsPath}/img/sys/${e.imagePath}`;
        products.append(`
      <tr id="${e.id}">
        <th scope="row">${e.id}</th>
        <td> <img src="${imgPath}" class="img-fluid w-25 p-3" alt="..."></td>
        <td>${e.name}</td>
        <td>${e.desc}</td>
        <td>${e.price}</td>
        <td align="end">
          <button id="${e.id}" class="btn btn-secondary mx-1 actEdit">Ver Produtos</button>
          <button id="${e.id}" class="btn btn-danger mx-1 actRemove">Remover</button>
        </td>
      </tr>`)
      }
    );
  }

  function validateAll() {
    let inputName = $('#inputName');
    let inputQtd = $('#inputQtd');
    let inputPrice = $('#inputDes');
    let inputDes = $('#inputDes');
    var bool = true;
    var arrFail = [];

    helper.clearFieldValidation([inputName, inputQtd, inputDes, inputPrice]);

    if (!helper.validate(inputName.val(), ['textnumber'])) {
      arrFail.push(inputName);
      bool = false;
    }

    if (!helper.validate(inputQtd.val(), ['number'])) {
      arrFail.push(inputQtd);
      bool = false;
    }

    if (!helper.validate(inputDes.val(), ['textnumber'])) {
      arrFail.push(inputDes);
      bool = false;
    }

    if (!helper.validate(inputPrice.val(), ['number'])) {
      arrFail.push(inputPrice);
      bool = false;
    }

    if (!bool) {
      helper.addFieldValidation(arrFail, false);
    }

    return bool;
  }

});