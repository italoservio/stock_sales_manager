$(document).ready(function () {

  lineChart();
  pieChart();
  barChar();

  function lineChart() {
    const config = {
      type: 'line',
      data: {
        labels: [
          'January',
          'February',
          'March',
          'April',
          'May',
          'June',
        ],
        datasets: [{
          label: 'Vendas por mês',
          backgroundColor: '#325d88',
          borderColor: '#325d88',
          data: [5, 2, 0, 0, 0, 0, 0],
        }]
      },
      options: {}
    };
    new Chart(
      document.getElementById('lineChart'),
      config
    );
  }

  function pieChart() {
    const config = {
      type: 'pie',
      data: {
        labels: [
          'Vendas Confirmadas',
          'Vendas Pendentes',
        ],
        datasets: [{
          data: [300, 50],
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

  function barChar() {
    const labels = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho'];
    const config = {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          label: 'Lucro por mês',
          data: [65, 59, 80, 81, 56, 55, 40],
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