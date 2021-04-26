$(document).ready(function () {
  var select = document.querySelector('#categories');
  getAllProduts();
  getAllCategories();

  select.addEventListener('change', function () {
    console.log($('#categories').val());
    displayProducts(getAllProduts($('#categories').val()));
  });

});


function getAllProduts(p_category = null) {
  let category = (p_category == null || p_category == '') ? -1 : $('#categories').val();
  $.ajax({
    method: 'get',
    url: 'products',
    data: { category }
  }).done(function (data) {
    data = JSON.parse(data);
    displayProducts(data);
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
  p_data.products.map(e => {
    let imgPath = assetsPath + "/img/sys/" + e.imagePath;
    let price = "R$ " + e.price;
    product.append(`
    <div class="col-lg-3">
      <div id="teste" class="card">
        <img src="${imgPath}" class="card-img-top" alt="...">
        <div class="card-body">
          <h5 class="card-title">${e.name}</h5>
          <span>${price}</span>
        </div>
      </div>
    </div>`)
  });
}