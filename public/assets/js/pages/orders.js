$(document).ready(function() {

  $.ajax({
    method: 'get',
    url: `${basePath}/users/orders/all`,
  }).done(function (data) {
    data = JSON.parse(data);
    if (data.status) {

      data.orders.forEach((e, i) => {
        let htmlProducts = '';
        e.products.forEach(p => {
          htmlProducts += `
          <li class="list-group-item">
            <div class="row justify-content-between align-items-center">
              <div class="d-flex align-items-center">
                <div class="img-cart-container rounded-circle">
                  <img src="${assetsPath + '/img/sys/' + p.imagePath}" alt="A">
                </div>
                <p class="ml-3 mb-0">
                  <span class="h4">${p.name}</span> <br>
                </p>
              </div>

              <div class="d-flex align-items-center">
                <span class="text-muted h4">R$ ${p.price}</span>
              </div>
            </div>
          </li>
          \r\n
          `;
        });

        $('#orders').append(`
          <div class="card px-4 py-3 mb-2">
            <div class="d-flex justify-content-between align-items-center">
              <h2 class="h4 mb-0">Pedido <span class="title">#${e.id}</span></h2>
              <p class="text-muted mb-0">${e.createdAt}</p>
              <div>
                <a class="btn btn-outline-primary btn-sm" data-toggle="collapse" href="#collapse${i}" role="button" aria-expanded="false" aria-controls="collapse${i}">Ver mais</a>
              </div>
            </div>
            <div class="collapse" id="collapse${i}">
              <div class="pt-4">
                <p class="text-muted mb-2"><i>Produtos:</i></p>
                <ul class="list-group list-group-flush">
                  ${ htmlProducts }
                </ul>
              </div>
            </div>
          </div>
        `);
      });
    } else {
      Swal.fire('Ocorreu um erro', data.message, 'error');
    }
  });

});