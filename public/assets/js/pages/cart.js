$(document).ready(function () {
    var total = 0;
    var listProduct = [];
    var listQtd = [];
    listProducts();

    function listProducts() {
        let cart = $('#cart');
        c = getCart();
        if (c !== null && c.length > 0) {
            c.forEach((e, i) => {
                listProduct.push(e.id);
                listQtd.push(e.qtd);
                total += parseFloat(e.price * e.qtd);

                cart.append(`
        <li class="list-group-item">
          <div class="row">
            <div class="col-lg-3">
              <div class="d-flex align-items-center">
                <div class="img-cart-container rounded-circle">
                  <img src="${assetsPath}/img/sys/${e.imagePath}" alt="${e.name}">
                </div>
                <p class="ml-3 mb-0">
                  <span class="h4">${e.name}</span> <br>
                </p>
              </div>
            </div>

            <div class="col-lg-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                  <span class="text-primary h4">R$ ${e.price}</span>
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
        viewPrice(total);
    }

    $('#btnCalculate').on('click', function () {
        let inputCep = $('#inputCEP');
        let cep = inputCep.val().replace(/[^\w\s]/gi, '');
        let price = 0;

        if (cep.length < 9 && helper.validate(cep, ['required', 'number'])) {
            $.ajax({
                method: 'get',
                url: `https://viacep.com.br/ws/${cep}/json/`
            }).done(function (data) {
                if (data.erro !== undefined && data.erro === true) {
                    Swal.fire(
                        'Ocorreu um falha',
                        'Não foi possível econtrar o endereço deste CEP',
                        'error'
                    );
                }
                switch (data.uf) {
                    case 'RO':
                        price = '65,34';
                    case 'AC':
                        price = '53,12';
                    case 'AM':
                        price = '44,42';
                    case 'RR':
                        price = '62,66';
                    case 'PA':
                        price = '49,97';
                    case 'AP':
                        price = '47,37';
                    case 'TO':
                        price = '42,11';
                    case 'MA':
                        price = '43,80';
                    case 'PI':
                        price = '38,92';
                    case 'CE':
                        price = '39,10';
                    case 'RN':
                        price = '45,39';
                    case 'PB':
                        price = '46,56';
                    case 'PE':
                        price = '47,74';
                    case 'AL':
                        price = '38,33';
                    case 'SE':
                        price = '37,65';
                    case 'BA':
                        price = '34,78';
                    case 'MG':
                        price = '12,40';
                    case 'ES':
                        price = '32,33';
                    case 'RJ':
                        price = '26,80';
                    case 'SP':
                        price = '22,30';
                    case 'PR':
                        price = '32,43';
                    case 'SC':
                        price = '35,11';
                    case 'RS':
                        price = '37,45';
                    case 'MS':
                        price = '38,14';
                    case 'MT':
                        price = '40,30';
                    case 'GO':
                        price = '32,30';
                    case 'DF':
                        price = '18,10';
                }
                $('#cepPrice').html(`<div class="btn btn-outline-warning mb-0 mt-1">Frete: <b>R$ ${price}</b></div>`);
                total = parseFloat(total) + parseFloat(price);
                $('.spanContext').html(total);

            }).fail(function () {
                Swal.fire(
                    'Ocorreu um falha',
                    'Não foi possível econtrar o endereço deste CEP',
                    'error'
                );
            })
        } else {
            helper.addFieldValidation([inputCep], false);
        }

    });

    $('#btnBuy').on('click', function () {
        $.ajax({
            method: 'post',
            url: basePath + `/orders/set`,
            data: {listProduct,listQtd}
        }).done(function (data) {

        });
    });
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

function viewPrice(total) {
    let price = $('#price');
    $('.spanContext').html(total);
    price.append(`        
  <div class="col-lg-6">
      <h1 class="h2 text-center">Total produtos: R$ ${total}</h1>
  </div>`);
}