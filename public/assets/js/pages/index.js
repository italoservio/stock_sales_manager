$(document).ready(function () {

  $.ajax({
    method: 'get',
    url: 'products'
  }).done(function (data) {
    data = JSON.parse(data);
    let products = $('#products');
    data.map(x => {
      products.append(`
      <div class="col-lg-3">
        <div class="card">
          <img src=".." class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">${x.name}</h5>
            <p class="card-text">${x.desc}</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
          </div>
        </div>
      </div>`)
    })
  })

});
