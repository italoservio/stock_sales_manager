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

  $('#btnCalculate').on('click', function() {
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
        switch(data.uf) {
          case 'RO': price = '65,34';
          case 'AC': price = '53,12';
          case 'AM': price = '44,42';
          case 'RR': price = '62,66';
          case 'PA': price = '49,97';
          case 'AP': price = '47,37';
          case 'TO': price = '42,11';
          case 'MA': price = '43,80';
          case 'PI': price = '38,92';
          case 'CE': price = '39,10';
          case 'RN': price = '45,39';
          case 'PB': price = '46,56';
          case 'PE': price = '47,74';
          case 'AL': price = '38,33';
          case 'SE': price = '37,65';
          case 'BA': price = '34,78';
          case 'MG': price = '12,40';
          case 'ES': price = '32,33';
          case 'RJ': price = '26,80';
          case 'SP': price = '22,30';
          case 'PR': price = '32,43';
          case 'SC': price = '35,11';
          case 'RS': price = '37,45';
          case 'MS': price = '38,14';
          case 'MT': price = '40,30';
          case 'GO': price = '32,30';
          case 'DF': price = '18,10';
        }
        $('#cepPrice').html(`<div class="btn btn-outline-warning mb-0 mt-1">Frete: <b>R$ ${price}</b></div>`);
      }).fail(function() {
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

  $('#btnBuy').on('click', function() {
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
          available: productQtd,
          qtd: 1
        });
      }
      localStorage.setItem('c', JSON.stringify(c));
    } else {
      c = [{
        id: productId,
        name: productName,
        price: productPrice,
        imagePath: productImagePath,
        available: productQtd,
        qtd: 1
      }];
      localStorage.setItem('c', JSON.stringify(c));
    }
    Swal.fire(
      'Produto adicionado',
      'Uma unidade do produto selecionado foi adicionada ao carrinho.',
      'success'
    );
  });

});