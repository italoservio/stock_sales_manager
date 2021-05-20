$(document).ready(function () {
  let category = 0;
  var id = 0;

  reloadTable();
  getCategory();

  $(document).on('click', 'button.actEdit', function () {
    let image = $('#image');
    let imgPath;

    id = $(this).attr("id");
    $.ajax({
      method: 'get',
      url: basePath + `/products/${id}`
    }).done(function (data) {
      data = JSON.parse(data);
      if (data.status) {
        imgPath = `${assetsPath}/img/sys/${data.product["imagePath"]}`;
        $('#inputId').val(data.product["name"]);
        $('#inputName').val(data.product["name"]);
        $('#inputQtd').val(data.product["qtd"]);
        $('#inputPrice').val(data.product["price"]);
        $('#inputCategory').val(data.product["category"]["id"]);
        $('#inputDesc').val(data.product["desc"]);

        image.html(`
          <div class="my-3">
            <div class="d-flex justify-content-center">
              <div class="img-container rounded-circle">
                <img id="imageModal" src="${imgPath}" alt="Imagem do produto" class="rounded-circle" width="120" height="120">
              </div>
            </div>
            <label class="label-file mt-2" for="inputImg">Editar imagem</label>
            <input class="input-file" id="inputImg" type="file" onchange="document.getElementById('imageModal').src = window.URL.createObjectURL(this.files[0])">
          </div>
        `);
      }
    });

    $('#inputNameCheck').show();
    $('.spanContext').html('Editar');
    $('#inputId').attr('disabled', 'disabled');
    $('#createModal').modal('show');
  });

  $(document).on('click', 'button.actCreate', function () {
    let image = $('#image');
    $('#inputId').val("");
    $('#inputName').val("");
    $('#inputQtd').val("");
    $('#inputPrice').val("");
    $('#inputCategory').val("");
    $('#inputDesc').val("");
    image.html(`
      <div class="my-3">
        <div class="d-flex justify-content-center">
          <div class="img-container rounded-circle">
            <img id="imageModal" src="${assetsPath}/img/sys/icons/default.png" alt="Imagem do produto" class="rounded-circle" width="120" height="120">
          </div>
        </div>
        <label class="label-file mt-2" for="inputImg">Adicionar imagem</label>
        <input class="input-file" id="inputImg" type="file" onchange="document.getElementById('imageModal').src = window.URL.createObjectURL(this.files[0])">
      </div>
    `);
    $('#inputNameCheck').hide();
    $('#inputId').val('');
    $('.spanContext').html('Criar');
    $('#createModal').modal('show');
  });

  $(document).on('click', 'button#btnCreate', function () {
    let imageUpdate;
    let fileData;
    let formData = new FormData();

    if ($('#inputImg').prop('files')[0] !== undefined) {
      fileData = $('#inputImg').prop('files')[0];
      imageUpdate = 0;
    } else {
      imageUpdate = 1;
    }

    inputName = $('#inputName');
    inputQtd = $('#inputQtd');
    inputPrice = $('#inputPrice');
    inputCategory = $('#inputCategory');
    inputDesc = $('#inputDesc');
    formData.append('id', id);
    formData.append('file', fileData);
    formData.append('imageUpdate', imageUpdate);
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
      Swal.fire(
        'Produto criado',
        '',
        'success'
      )
      reloadTable();
    });
  });

  $(document).on('click', 'button.actRemove', function () {
    let index = $(this).attr("id");
    Swal.fire({
      title: 'Tem certeza?',
      text: "Você não será capaz de reverter isso!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#325d88',
      cancelButtonColor: '#d9534f',
      confirmButtonText: 'Sim, remover!',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          method: 'delete',
          url: basePath + `/products/delete/${index}`,
        }).done(function (data) {
          data = JSON.parse(data);
          if (data.status) {
            reloadTable();
          }
        });
        Swal.fire(
          'Produto excluído',
          '',
          'success'
        )
      }
    })
  });

  function reloadTable() {
    $.ajax({
      method: 'get',
      url: basePath + '/products',
      data: {category}
    }).done(function (data) {
      data = JSON.parse(data);
      if (data.status) {
        let products = $('#products');
        products.html('');
        data.products.map(e => {
            let imgPath = `${assetsPath}/img/sys/${e.imagePath}`;
            products.append(`
            <tr>
              <td>
                <div class="img-container rounded-circle">
                  <img src="${imgPath}" alt="${e.name}">
                </div>
              </td>
              <td>${e.name}</td>
              <td>${e.price}</td>
              <td>${e.qtd}</td>
              <td class="p-0">
                <div class="px-3 py-1 scroll" style="height: 140px; overflow: auto;">
                  ${e.desc}
                </div>
              </td>
              <td>${e.category['name']}</td>
              <td align="end">
                <button id="${e.id}" class="btn btn-secondary mx-1 actEdit">Ver Produtos</button>
                <button id="${e.id}" class="btn btn-danger mx-1 actRemove">Remover</button>
              </td>
            </tr>`
            );
          }
        );
      }
    });
  }

  function getCategory() {
    $.ajax({
      method: 'get',
      url: basePath + '/categories'
    }).done(function (data) {
      data = JSON.parse(data);
      if (data.status) {
        let categories = $('#inputCategory');
        data.categories.map(e => {
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

})
;