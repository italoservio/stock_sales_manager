$(document).ready(function() {

  console.log(orderId);

  // Getting products and listing:
  $.ajax({
    method: 'get',
    url: basePath + `/orders/${orderId}/all`,
  }).done(function (data) {
    data = JSON.parse(data);
    if (data.status) {
      let products = data.products;
      let productsPrice = 0;
      products.forEach((e, i) => {
        productsPrice += parseFloat(e.price * e.qtd);
        $('#cart').append(`
        <li class="list-group-item">
          <div class="row justify-content-between align-items-center">

            <div class="d-flex align-items-center">
              <div class="img-cart-container rounded-circle">
                <img src="${assetsPath}/img/sys/${e.imagePath}" alt="${e.name}">
              </div>
              <p class="ml-3 mb-0">
                <span class="h4">${e.name}</span> <br>
              </p>
            </div>

            <div class="d-flex align-items-center">
              <span class="text-muted h4">R$ ${e.price}</span>
            </div>

          </div>
        </li>
        `);
      });
      $('#totalPrice').html('R$ ' + productsPrice.toFixed(2));
    } else if (data.redirect) {
      location.href = `${basePath}/`;
    } else {
      Swal.fire('Erro', data.message, 'error');
    }
  });

});