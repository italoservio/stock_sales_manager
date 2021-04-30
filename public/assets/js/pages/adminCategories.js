$(document).ready(function() {

  var arrCategories = [];

  $.ajax({
    method: 'get',
    url: basePath + '/categories'
  }).done(function(data) {
    let categories = $('#categories');
    data = JSON.parse(data);

    if (data.status) {
      arrCategories = data.categories;
      reloadTable();
    }

  });


  $(document).on('click', 'button#btnCreate', function() {
    let categories    = $('#categories');
    let inputCategory = $('#inputCategory');
    let alrt  = $('#alert');
    let name  = inputCategory.val();

    helper.clearFieldValidation([inputCategory]);

    if (helper.validate(name, ['required', 'text'])) {

      $.ajax({
			  method: 'post',
			  url: basePath + '/categories/create',
			  data: { name }
			}).done(function(data) {
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


  $(document).on('click', 'button.actRemove', function() {
    let index   = $(this).attr("id");
    let element = arrCategories[index];

    $.ajax({
      method: 'delete',
      url: basePath + `/categories/delete/${element.id}`,
    }).done(function(data) {
      data = JSON.parse(data);
      if (data.status) {
        arrCategories.splice(index, 1);
        reloadTable();
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
          <button id="${index}" class="btn btn-secondary mx-1">Ver Produtos</button>
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

