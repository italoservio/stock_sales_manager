$(document).ready(function() {

  listProducts();

  function listProducts() {
    let cart = $('#cart');
    c = getCart();
    if (c !== null && c.length > 0) {
      c.forEach((e, i) => {
        cart.append(`
        <li class="list-group-item">
          <div class="row">
            <div class="col-lg-6">
              <div class="d-flex align-items-center">
                <div class="img-cart-container rounded-circle">
                  <img src="${assetsPath}/img/sys/${e.imagePath}" alt="${e.name}">
                </div>
                <p class="ml-3 mb-0">
                  <span class="h4">${e.name}</span> <br>
                  <i>Há ${e.available} unidade(s)</i>
                </p>
              </div>
            </div>

            <div class="col-lg-6 d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center">
                  <button class="btn btn-secondary btn-sm mr-3">
                    <div onclick="removeItem(${i})" class="d-flex align-items-center py-1">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-lg" viewBox="0 0 16 16">
                        <path d="M0 8a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H1a1 1 0 0 1-1-1z"/>
                      </svg>
                    </div>
                  </button>
                  <span><b class="h4">${e.qtd} unidade(s)</b></span>
                  <button class="btn btn-primary btn-sm ml-3">
                    <div onclick="addItem(${i})" class="d-flex align-items-center py-1">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path d="M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z"/>
                      </svg>
                    </div>
                  </button>
                </div>

                <div>
                  <button onclick="removeFromCart(${i})" class="btn btn-outline-danger">
                    <div class="d-flex align-items-center py-1">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                      </svg>
                    </div>
                  </button>
                </div>

            </div>
          </div>
        </li>
        `);
      });
    } else {
      cart.append(`
      <li class="list-group-item">
        <div class="d-flex justify-content-center">
          <div>
            <h4 class="text-center mb-0">Não há items para exibir</h4>
            <p class="mb-0">Vá as compras e volte aqui para finalizar seu pedido!</p>
          </div>
        </div>
      </li>
      `);
    }
  }

});

function getCart() {
  c = localStorage.getItem('c');
  if (c !== null) {
    c = JSON.parse(c);
    return c;
  } else {
    Swal.fire(
      'Erro',
      'Ocorreu uma falha ao carregar o carrinho',
      'error'
    );
    return null;
  }
}

function addItem(p_index) {
  let c = getCart();
  if (c !== null) {
    let item = c[p_index];
    item.qtd += 1;
    localStorage.setItem('c', JSON.stringify(c));
    location.reload();
  }
}

function removeItem(p_index) {
  let c = getCart();
  if (c !== null) {
    let item = c[p_index];
    if ((item.qtd - 1) === 0) {
      removeFromCart();
    } else {
      item.qtd -= 1;
      localStorage.setItem('c', JSON.stringify(c));
      location.reload();
    }
  }
}

function removeFromCart(p_index) {
  let c = getCart();
  if (c !== null) {
    Swal.fire({
      title: 'Tem certeza que deseja remover este produto?',
      text: "Você não será capaz de reverter isto",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#325d88',
      cancelButtonColor: '#d9534f',
      confirmButtonText: 'Sim, remover!',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.isConfirmed) {
        c.splice(p_index, 1);
        localStorage.setItem('c', JSON.stringify(c));
        location.reload();
        Swal.fire(
          'Item removido',
          'Este item foi removido de seu carrinho.',
          'success'
        );
      }
    });
  }
}