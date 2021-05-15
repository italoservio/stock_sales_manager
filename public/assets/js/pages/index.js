$(document).ready(function () {
  getAllProducts();
  getAllCategories();

  $('#categories').on('change', function () {
    displayProducts(getAllProducts($('#categories').val()));
  });

  function getAllProducts(p_category = null) {
    let category = $('#categories').val();
    $.ajax({
      method: 'get',
      url: 'products',
      data: { category }
    }).done(function (data) {
      data = JSON.parse(data);
      if (data.status && data.products.length > 0) {
        displayProducts(data.products);
      }
      else {
        // ERROR
      }
    });
  }

  function getAllCategories() {
    $.ajax({
      method: 'get',
      url: 'categories/'
    }).done(function (data) {
      data = JSON.parse(data);
      if (data.status) {
        let categories = $('#categories');
        data.categories.map(e => {
          categories.append(`
            <option value="${e.id}">${e.name}</option>`);
        });
      }
    });
  }

  function displayProducts(data) {
    let product = $('#products');
    product.html('');
    if (data !== undefined) {
      data.map(e => {
        let imgPath = `${assetsPath}/img/sys/${e.imagePath}`;
        let price = `R$ ${e.price}`;
        product.append(`
        <div class="col-lg-3 mb-3">
          <div  class="card card-product">
            <div class="img-products-container rounded">
              <img src="${imgPath}" class="card-img-top" alt="${e.name}">
            </div>
            <div class="card-body">
              <h5 class="card-title h4 mb-0" style="font-weight: 600; height: 55px">${e.name}</h5>
              <span class="text-primary h4">${price}</span>
              <a href="products/${e.id}/details" class="stretched-link"></a>
            </div>
          </div>
        </div>`)
      });
    }
    else {

    }
  }
});


