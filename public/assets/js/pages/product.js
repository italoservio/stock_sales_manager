$(document).ready(function () {

  $.ajax({
    method: "get",
    url: basePath + '/products',
    data: { category: categoryId }
  }).done(function (data) {
    data = JSON.parse(data);

    if (data.status && data.products.length > 1) {
      let similarProducts = $('#similarProducts');

      similarProducts.append(`
        <div class="col-lg-12">
          <hr class="my-4">
          <h3 clas="mb-5">Produtos semelhantes:</h3>
        </div>
      `);

      data.products.map(e => {
        if (e.id !== parseInt(productId)) {
          similarProducts.append(`
            <div class="col-lg-3 my-3">
              <div  class="card card-product">
                <div class="img-products-container rounded">
                  <img src="${assetsPath}/img/sys/${e.imagePath}" class="card-img-top" alt="${e.name}">
                </div>
                <div class="card-body">
                  <h5 class="card-title h4 mb-0" style="font-weight: 600; height: 55px">${e.name}</h5>
                  <span class="text-primary h4">R$ ${e.price}</span>
                  <a href="${basePath}/products/${e.id}/details" class="stretched-link"></a>
                </div>
              </div>
            </div>
          `);
        }
      });
    }
  });

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
            break;
          case 'AC':
            price = '53,12';
            break;
          case 'AM':
            price = '44,42';
            break;
          case 'RR':
            price = '62,66';
            break;
          case 'PA':
            price = '49,97';
            break;
          case 'AP':
            price = '47,37';
            break;
          case 'TO':
            price = '42,11';
            break;
          case 'MA':
            price = '43,80';
            break;
          case 'PI':
            price = '38,92';
            break;
          case 'CE':
            price = '39,10';
            break;
          case 'RN':
            price = '45,39';
            break;
          case 'PB':
            price = '46,56';
            break;
          case 'PE':
            price = '47,74';
            break;
          case 'AL':
            price = '38,33';
            break;
          case 'SE':
            price = '37,65';
            break;
          case 'BA':
            price = '34,78';
            break;
          case 'MG':
            price = '12,40';
            break;
          case 'ES':
            price = '32,33';
            break;
          case 'RJ':
            price = '26,80';
            break;
          case 'SP':
            price = '22,30';
            break;
          case 'PR':
            price = '32,43';
            break;
          case 'SC':
            price = '35,11';
            break;
          case 'RS':
            price = '37,45';
            break;
          case 'MS':
            price = '38,14';
            break;
          case 'MT':
            price = '40,30';
            break;
          case 'GO':
            price = '32,30';
            break;
          case 'DF':
            price = '18,10';
            break;
        }
        $('#cepPrice').html(`<div class="btn btn-outline-warning mb-0 mt-1">Frete: <b>R$ ${price}</b></div>`);
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
    let c = localStorage.getItem('c');
    let finded = false;
    if (c !== null) {
      c = JSON.parse(c);
      for (const i of c) {
        if (i.id === productId) {
          i.qtd += 1;
          finded = true;
        }
      }
      if (!finded) {
        c.push({
          id: productId,
          name: productName,
          price: productPrice,
          imagePath: productImagePath,
          category: categoryId,
          qtd: 1
        });
      }
    } else {
      c = [{
        id: productId,
        name: productName,
        price: productPrice,
        imagePath: productImagePath,
        category: categoryId,
        qtd: 1
      }];
    }
    localStorage.setItem('c', JSON.stringify(c));
    helper.increaseBag();
    Swal.fire(
      'Produto adicionado',
      'Uma unidade do produto selecionado foi adicionada ao carrinho.',
      'success'
    );
  });

});