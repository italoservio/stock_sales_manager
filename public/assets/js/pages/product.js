$(document).ready(function () {
  var category;
  var idProduct;
  $.ajax({
    method: "get",
    url: basePath + `/products/${productId}`
  }).done(function (p_data) {
    p_data = JSON.parse(p_data);
    if (p_data.status) {
      category = p_data.product[0]['category']['id'];
      idProduct = p_data.product[0]['id'];
      let image = $('#image');
      let name = $('#name');
      let desc = $('#desc');
      let price = $('#price');
      let qtd = $('#qtd');
      let imgPath = `${assetsPath}/img/sys/${p_data.product[0]['imagePath']}`;

      image.html(`<img src="${imgPath}" class="img-fluid w-100 p-3" alt="...">`); // coloca no final

      name.html(`<h1>${p_data.product[0]['name']} </h1>`); // apaga tudo e coloca do 0
      desc.html(`<h2>${p_data.product[0]['desc']}</h2>`);
      price.html(`<h2>R$:  ${p_data.product[0]['price']}</h2>`);
      qtd.html(`<h2>stock: ${p_data.product[0]['qtd']}</h2>`);
    }
    $.ajax({
      method: "get",
      url: basePath + '/products',
      data: { category }
    }).done(function (p_data) {
      p_data = JSON.parse(p_data);
      if (p_data.status) {
        let columns = $('#columns');
        p_data.products.map(e => {
          if (e.id !== idProduct) {
            let imgPath = `${assetsPath}/img/sys/${e.imagePath}`;
            let price = `R$ ${e.price}`;
            columns.append(`
            <div class="col-3">
                <div class="card card-product">
                    <img src="${imgPath}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">${e.name}</h5>
                        <span>R$ ${price}</span>
                        <a href= "${basePath}/products/${e.id}/details" class="stretched-link"></a>
                    </div>
                  </div>
            </div>`);
          }
        });
      }
    });
  });

});