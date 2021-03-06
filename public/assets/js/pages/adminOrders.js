$(document).ready(function () {

  reloadTable();

  $(document).on('click', 'button.confirmPayed', function () {
    id = $(this).attr("id");
    $.ajax({
      method: 'POST',
      url: `${basePath}/orders/payed`,
      data: { id }
    }).done(function (data) {
      data = JSON.parse(data);
      if (data.status) {
        $('#orders').html("");
        reloadTable();
        Swal.fire(
          'Pagamento do pedido: ' + id + ' confirmado com sucesso',
          '',
          'success'
        )
      }
    });
  });

  function reloadTable() {
    $.ajax({
      method: 'get',
      url: `${basePath}/orders/all`
    }).done(function (data) {
      data = JSON.parse(data);
      if (data.status) {
        data.orders.forEach((e, i) => {
          let htmlProducts = '';
          let totalProdutct = 0;
          let totalProdutctEnd = 0;
          let statusProduct = (e.payed === 1) ? "Pagamento confirmado" : "Aguardando pagamento";

          e.products.forEach(p => {
            totalProdutct = (p.price * p.qtd).toFixed(2);
            totalProdutctEnd = parseFloat(totalProdutct) + parseFloat(totalProdutctEnd);
            htmlProducts += `
            <li class="list-group-item">
              <div class="row justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <div class="img-cart-container rounded-circle">
                    <img src="${assetsPath + '/img/sys/' + p.imagePath}" alt="A">
                  </div>
                  <p class="ml-3 mb-0">
                    <span class="h4 px-md-2">${p.name}</span> <br>
                  </p>
                  <p class="ml-3 mb-0">
                    <span class="h4 px-md-5"> Quantidade: ${p.qtd}</span> <br>
                  </p>
                </div>

                <div class="d-flex align-items-center">
                  <span class="text-muted h4">R$ ${totalProdutct}</span>
              </div>
            </li>
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
                    ${htmlProducts}
                  </ul>
                  <div class="d-flex align-items-center justify-content-between my-4">
                      <span class="h4">Status do pedido: <i class="${((statusProduct === 'Aguardando pagamento') ? 'text-warning' : 'text-success')}">${statusProduct}</i></span>
                      <span class="h4 align-items-md-end">Total do pedido: <span class="text-dark title">${(totalProdutctEnd).toFixed(2)}</span></span>
                  </div>
                  <div class="d-flex justify-content-center">
                    <button id="${e.id}" type="submit" class="btn btn-primary align-items-md-end confirmPayed ${((statusProduct === 'Pagamento confirmado') ? 'd-none' : '')}">Confirmar pagamento</button>
                  </div>
                </div>
              </div>
            </div>
          `);
        });
      }
    });
  }
});