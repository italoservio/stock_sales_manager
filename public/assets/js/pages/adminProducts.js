$(document).ready(function () {
  let category = 0;
  var arrProducts = [];
  var id = 0;

  reloadTable();
  getCategory();

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
    let fileData = $('#inputImg').prop('files')[0];
    let formData = new FormData();

    inputName = $('#inputName');
    inputQtd = $('#inputQtd');
    inputPrice = $('#inputPrice');
    inputCategory = $('#inputCategory');
    inputDesc = $('#inputDesc');
    formData.append('id', id);
    formData.append('file', fileData);
    formData.append('name', inputName.val());
    formData.append('qtd', inputQtd.val());
    formData.append('price', inputPrice.val());
    formData.append('category', inputCategory.val());
    formData.append('desc', inputDesc.val());
    $.ajax({
      method: 'post',
      url: basePath + '/products/create',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
    }).done(function (data) {
      console.log(data);
      reloadTable();
    });
  });

  $(document).on('click', 'button.actCreate', function () {
    $('#inputNameCheck').hide();
    $('#inputId').val('');
    $('.spanContext').html('Criar');
    $('#createModal').modal('show');
  });

  function reloadTable() {
    $.ajax({
      method: 'get',
      url: basePath + '/products',
      data: {category}
    }).done(function (p_data) {
      p_data = JSON.parse(p_data);
      if (p_data.status) {
        arrProducts = p_data.products;
        let products = $('#products');
        products.html('');
        p_data.products.map(e => {
            let imgPath = `${assetsPath}/img/sys/${e.imagePath}`;
            products.append(`
      <tr id="${e.id}">
        <td class="w-25"> <img src="${imgPath}" class="img-fluid w-50 p-3" alt="..."></td>
        <td>${e.name}</td>
        <td>${e.price}</td>
        <td>${e.qtd}</td>
        <td>${e.desc}</td>
        <td>${e.category['name']}</td>
        
        <td align="end">
          <button id="${e.id}" class="btn btn-secondary mx-1 actEdit">Ver Produtos</button>
          <button id="${e.id}" class="btn btn-danger mx-1 actRemove">Remover</button>
        </td>
      </tr>`)
          }
        );
      }
    });
  }

  function getCategory() {
    $.ajax({
      method: 'get',
      url: basePath + '/categories'
    }).done(function (p_data) {
      p_data = JSON.parse(p_data);
      if (p_data.status) {
        let categories = $('#inputCategory');
        p_data.categories.map(e => {
          categories.append(`<option value="${e.id}">${e.name}</option>`);
        });
      }
    });
  }

  function validateAll() {
    let inputName = $('#inputName');
    let inputQtd = $('#inputQtd');
    let inputPrice = $('#inputDesc');
    let inputDesc = $('#inputDesc');
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