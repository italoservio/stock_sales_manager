$(document).ready(function () {

  var arrCategories = [];
  var id = -1;

  reloadTable();

  $(document).on('click', 'button#createCategory', function () {
    $('#inputCategory').val("");
    $('.spanContext').html('Criar');
  });

  $(document).on('click', 'button.actEdit', function () {
    $('.spanContext').html('Editar');
    id = $(this).attr("id");
    $('#inputCategory').val(arrCategories[id]["name"]);
    id = arrCategories[id]["id"];
  });

  $(document).on('click', 'button#btnCreate', function () {
    let inputCategory = $('#inputCategory');
    let alrt = $('#alert');
    let name = inputCategory.val();
    id = -1;

    helper.clearFieldValidation([inputCategory]);

    if (helper.validate(name, ['required', 'text'])) {

      $.ajax({
        method: 'post',
        url: basePath + '/categories/create',
        data: {name, id}
      }).done(function (data) {
        data = JSON.parse(data);
        if (data.status) {
          arrCategories.splice(arrCategories.length, 0, data.category);
          reloadTable();
          closeCreateModal();
          if (id == -1) {
            Swal.fire(
              'Sucesso!',
              'Sua categoria foi criada!',
              'success'
            )
          } else {
            Swal.fire(
              'Sucesso!',
              'Sua categoria foi editada!',
              'success'
            )
          }
          id = -1;
        } else {
          Swal.fire(
            'Falha!',
            data.message,
            'error'
          )
        }
      });

    } else {
      alrt.html(helper.alert('danger', 'Nome da categoria é inválido'));
      helper.addFieldValidation([inputCategory], false);
    }
  });

  $(document).on('click', 'button.actProduct', function () {
    let category = $(this).attr("id");
    let products = $('#products');
    products.html("");
    category = arrCategories[category]["id"];
    $.ajax({
      method: 'get',
      url: basePath + `/products`,
      data: {category}
    }).done(function (p_data) {
      p_data = JSON.parse(p_data);
      if (p_data.status) {
        products.html('');
        p_data.products.map(e => {
          let imgPath = `${assetsPath}/img/sys/${e.imagePath}`;
          products.append(`<tr id="${e.id}">
             <td class="w-25"> <img src="${imgPath}" class="img-fluid w-100 p-3" alt="..."></td>
             <td>${e.name}</td>`)
        });
      }
    });
  });

  $(document).on('click', 'button.actRemove', function () {
    let index = $(this).attr("id");
    let element = arrCategories[index];
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
          url: basePath + `/categories/delete/${element.id}`,
        }).done(function (data) {
          data = JSON.parse(data);
          if (data.status) {
            arrCategories.splice(index, 1);
            reloadTable();
          }
        });
        Swal.fire(
          'Deletado!',
          'Sua Categoria foi excluída.',
          'success'
        )
      }
    });

  });

  function reloadTable() {
    let categories = $('#categories');
    categories.html('');
    $.ajax({
      method: 'get',
      url: basePath + '/categories/'
    }).done(function (data) {
      data = JSON.parse(data);
      arrCategories = data.categories;
      if (data.status) {
        data.categories.forEach((e, index) => {
          categories.append(`
              <tr id="${index}">
                <th scope="row">${e.id}</th>
                <td>${e.name}</td>
                <td align="end">
                  <button id="${index}" class="btn btn-secondary mx-1 actEdit" data-toggle="modal" data-target="#createModal">Editar Categoria</button>
                  <button id="${index}" class="btn btn-secondary mx-1 actProduct" class="btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">Ver Produtos</button>
                  <button id="${index}" class="btn btn-danger mx-1 actRemove">Remover</button>
                </td>
              </tr>
      `);
        });
      }
    });
  }


  function closeCreateModal() {
    $('#inputCategory').val('');
    $('#createModal').modal('hide');
  }

});

