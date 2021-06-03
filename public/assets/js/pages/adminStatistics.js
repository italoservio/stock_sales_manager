$(document).ready(function () {

  lineChart();
  pieChart();
  barChart();

  function lineChart() {
    $.ajax({
      method: 'get',
      url: basePath + `/statistics/linechart`
    }).done(function (p_data) {
      p_data = JSON.parse(p_data);
      if (p_data.status) {
        const data = p_data.data;
        const config = {
          type: 'line',
          data: {
            labels: [
              'Janeiro', 'Fevereiro',
              'Março', 'Abril',
              'Maio', 'Junho',
              'Julho', 'Agosto',
              'Setembro', 'Outrubro',
              'Novembro', 'Dezembro'
            ],
            datasets: [{
              label: 'Vendas por mês',
              backgroundColor: '#325d88',
              borderColor: '#325d88',
              data,
            }]
          },
          options: {}
        };
        new Chart(
          document.getElementById('lineChart'),
          config
        );
      }
    });
  }

  function pieChart() {
    $.ajax({
      method: 'get',
      url: basePath + `/statistics/piechart`
    }).done(function (p_data) {
      p_data = JSON.parse(p_data);
      if (p_data.status) {
        const data = p_data.data;
        const config = {
          type: 'pie',
          data: {
            labels: [
              'Vendas Confirmadas',
              'Vendas Pendentes',
            ],
            datasets: [{
              data,
              backgroundColor: [ '#93c54b', '#325d88' ],
              hoverOffset: 4
            }]
          },
        };
        new Chart(
          document.getElementById('pieChart'),
          config
        );
      }
    });
  }

  function barChart() {
    $.ajax({
      method: 'get',
      url: basePath + `/statistics/barchart`
    }).done(function (p_data) {
      p_data = JSON.parse(p_data);
      if (p_data.status) {
        const data = p_data.data;
        const config = {
          type: 'bar',
          data: {
            labels: [
              'Janeiro', 'Fevereiro',
              'Março', 'Abril',
              'Maio', 'Junho',
              'Julho', 'Agosto',
              'Setembro', 'Outrubro',
              'Novembro', 'Dezembro'
            ],
            datasets: [{
              label: 'Lucro por mês',
              data,
              backgroundColor: [ '#325d88', '#93c54b' ],
              borderColor: [ '#325d88', '#93c54b' ],
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          },
        };
        new Chart(
          document.getElementById('barChart'),
          config
        );
      }
    });
  }

});