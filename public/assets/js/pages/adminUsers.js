$(document).ready(function() {

  // Getting all users:
  $.ajax({
    method: 'get',
    url: basePath + '/users'
  }).done(function(data) {
    let users = $('#users');
    data = JSON.parse(data);
    if (data.status) {
      data.users.map(e => {
        users.append(`
        <tr>
          <th scope="row">${e.id}</th>
          <td>${e.name}</td>
          <td>${e.email}</td>
          <td class="d-flex">
            <button id="${e.id}" class="btn btn-primary mx-1">Ver</button>
            <button id="${e.id}" class="btn btn-danger mx-1">Remover</button>
          </td>
        </tr>
        `);
      });
    }
  });

});