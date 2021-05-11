$(document).ready(function () {

  var arrCategories = [];

  $.ajax({
    method: 'get',
    url: basePath + '/categories'
  }).done(function (data) {
    let categories = $('#categories');
    data = JSON.parse(data);

    if (data.status) {
      arrCategories = data.categories;
      reloadTable();
    }

  });

  $(document).on('click', 'button#createCategory', function () {
    $('.spanContext').html('Criar');
  });

  $(document).on('click', 'button.actEdit', function () {
    $('.spanContext').html('Editar');
    let id = $(this).attr("id");
    $('#inputCategory').val(arrCategories[id]["name"]);
  });

  $(document).on('click', 'button#btnCreate', function () {
    let inputCategory = $('#inputCategory');
    let alrt = $('#alert');
    let name = inputCategory.val();

    helper.clearFieldValidation([inputCategory]);

    if (helper.validate(name, ['required', 'text'])) {

      $.ajax({
        method: 'post',
        url: basePath + '/categories/create',
        data: {name}
      }).done(function (data) {
        data = JSON.parse(data);
        if (data.status) {
          arrCategories.splice(arrCategories.length, 0, data.category);
          reloadTable();
          closeCreateModal();
        } else {
          alrt.html(helper.alert('danger', data.message));
        }
      });

    } else {
      alrt.html(helper.alert('danger', 'Nome da categoria é inválido'));
      helper.addFieldValidation([inputCategory], false);
    }
  });

  $(document).on('click', 'button.actProduct', function () {
    let category = $(this).attr("id");
    category = arrCategories[category]["id"];
    $.ajax({
      method: 'get',
      url: basePath + `/products`,
      data: {category}
    }).done(function (p_data) {
      p_data = JSON.parse(p_data);
      if (p_data.status) {
        let products = $('#products');
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
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sim, delete!',
      cancelButtonText: 'Cancelar!',
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
          'Sua Categoria foi excluído.',
          'success'
        )
      }
    });

  });

  function reloadTable() {
    let categories = $('#categories');
    categories.html('');
    arrCategories.forEach((e, index) => {
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

  function closeCreateModal() {
    $('#inputCategory').val('');
    $('#createModal').modal('hide');
  }

});

