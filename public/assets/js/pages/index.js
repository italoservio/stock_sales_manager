$(document).ready(function () {
  getAllProducts();
  getAllCategories();

  $('#categories').on('change', function () {
    displayProducts(getAllProducts($('#categories').val()));
  });

  function getAllProducts(p_category = null) {
    let category = (p_category == null || p_category == '') ? -1 : $('#categories').val();
    $.ajax({
      method: 'get',
      url: 'products',
      data: { category }
    }).done(function (data) {
      data = JSON.parse(data);
      if (data.status && data.products.length > 0) {
        displayProducts(data.products);
      }
    });
  
  }
  
  function getAllCategories() {
    $.ajax({
      method: 'get',
      url: 'categories'
    }).done(function (data) {
      data = JSON.parse(data);
      let categories = $('#categories');
      data.categories.map(e => {
        categories.append(`        
            <option value="${e.id}">${e.name}</option>`);
      });
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
      <div class="col-lg-3">
        <div id="teste" class="card">
          <img src="${imgPath}" class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">${e.name}</h5>
            <span>${price}</span>
            <a href="products/${e.id}" class="stretched-link"></a>
          </div>
        </div>
      </div>`)
      });
    }
  }
});


