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
    }).done(function (p_data) {
      p_data = JSON.parse(p_data);
      if (p_data.status && p_data.products.length > 0) {
        displayProducts(p_data.products);
      }
      else {
        // ERROR
      }
    });
  }

  function getAllCategories() {
    $.ajax({
      method: 'get',
      url: 'categories/byproduct'
    }).done(function (p_data) {
      p_data = JSON.parse(p_data);
      if (p_data.status) {
        let categories = $('#categories');
        p_data.categories.map(e => {
          categories.append(`        
            <option value="${e.id}">${e.name}</option>`);
        });
      }
    });
  }

  function displayProducts(p_data) {
    let product = $('#products');
    product.html('');
    if (p_data !== undefined) {
      p_data.map(e => {
        let imgPath = `${assetsPath}/img/sys/${e.imagePath}`;
        let price = `R$ ${e.price}`;
        product.append(`
      <div class="col-lg-3 mb-3">
        <div  class="card card-product">
          <img src="${imgPath}" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">${e.name}</h5>
            <span>${price}</span>
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

